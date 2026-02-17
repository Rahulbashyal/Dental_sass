<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class SecureRateLimit
{
    public function handle(Request $request, Closure $next, string $key = 'global', int $maxAttempts = 60, int $decayMinutes = 1): Response
    {
        $throttleKey = $this->resolveRequestSignature($request, $key);
        
        if (RateLimiter::tooManyAttempts($throttleKey, $maxAttempts)) {
            $retryAfter = RateLimiter::availableIn($throttleKey);
            
            return response()->json([
                'message' => 'Too many requests. Please try again later.',
                'retry_after' => $retryAfter
            ], 429)->header('Retry-After', $retryAfter);
        }
        
        RateLimiter::hit($throttleKey, $decayMinutes * 60);
        
        $response = $next($request);
        
        return $response->withHeaders([
            'X-RateLimit-Limit' => $maxAttempts,
            'X-RateLimit-Remaining' => RateLimiter::remaining($throttleKey, $maxAttempts),
        ]);
    }
    
    protected function resolveRequestSignature(Request $request, string $key): string
    {
        $signature = $key . ':' . $request->ip();
        
        if ($request->user()) {
            $signature .= ':' . $request->user()->id;
        }
        
        if ($key === 'login') {
            $signature .= ':' . Str::lower($request->input('email', ''));
        }
        
        return hash('sha256', $signature);
    }
}