<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;

class ApiRateLimit
{
    public function handle(Request $request, Closure $next, $maxAttempts = 60, $decayMinutes = 1)
    {
        $key = $this->resolveRequestSignature($request);
        
        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $this->logSuspiciousActivity($request);
            
            return response()->json([
                'error' => 'Too many requests',
                'retry_after' => RateLimiter::availableIn($key)
            ], 429);
        }

        RateLimiter::hit($key, $decayMinutes * 60);

        $response = $next($request);

        return $response->header(
            'X-RateLimit-Remaining',
            RateLimiter::remaining($key, $maxAttempts)
        );
    }

    protected function resolveRequestSignature(Request $request): string
    {
        if ($user = $request->user()) {
            return 'api_rate_limit:' . $user->id;
        }

        return 'api_rate_limit:' . $request->ip();
    }

    private function logSuspiciousActivity(Request $request)
    {
        $key = "suspicious_activity:" . $request->ip();
        $count = Cache::increment($key, 1);
        Cache::expire($key, 3600); // 1 hour

        if ($count > 5) {
            // Log security event
            logger()->warning('Potential API abuse detected', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'endpoint' => $request->path(),
                'count' => $count
            ]);
        }
    }
}