<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class ScalableRateLimiting
{
    public function handle(Request $request, Closure $next, string $type = 'general'): Response
    {
        $limits = config('scalability.rate_limiting');
        
        switch ($type) {
            case 'login':
                $this->checkLoginLimits($request, $limits);
                break;
            case 'booking':
                $this->checkBookingLimits($request, $limits);
                break;
            case 'api':
                $this->checkApiLimits($request, $limits);
                break;
        }
        
        return $next($request);
    }
    
    protected function checkLoginLimits(Request $request, array $limits): void
    {
        $ip = $request->ip();
        $email = $request->input('email', '');
        
        // Per-IP rate limiting (more generous for legitimate traffic)
        $ipKey = "login_ip:{$ip}";
        if (RateLimiter::tooManyAttempts($ipKey, $limits['login_per_ip']['attempts'])) {
            abort(429, 'Too many login attempts from this IP. Try again later.');
        }
        
        // Per-user rate limiting (strict for account protection)
        if ($email) {
            $userKey = "login_user:" . hash('sha256', $email);
            if (RateLimiter::tooManyAttempts($userKey, $limits['login_per_user']['attempts'])) {
                abort(429, 'Too many attempts for this account. Try again in 15 minutes.');
            }
        }
    }
    
    protected function checkBookingLimits(Request $request, array $limits): void
    {
        $ip = $request->ip();
        $clinic = $request->route('clinic');
        
        // Global booking rate limit
        $globalKey = 'global_bookings';
        $currentBookings = Cache::get($globalKey, 0);
        
        if ($currentBookings >= $limits['max_concurrent_bookings']) {
            abort(429, 'System is busy. Please try again in a few minutes.');
        }
        
        // Per-clinic booking limit
        if ($clinic) {
            $clinicKey = "clinic_bookings:{$clinic->id}";
            $clinicBookings = Cache::get($clinicKey, 0);
            
            if ($clinicBookings >= 10) { // Max 10 concurrent bookings per clinic
                abort(429, 'This clinic is receiving high booking volume. Please try again shortly.');
            }
        }
        
        // Increment counters (expire in 1 minute)
        Cache::put($globalKey, $currentBookings + 1, 60);
        if ($clinic) {
            Cache::put("clinic_bookings:{$clinic->id}", Cache::get("clinic_bookings:{$clinic->id}", 0) + 1, 60);
        }
    }
    
    protected function checkApiLimits(Request $request, array $limits): void
    {
        $key = 'api_requests:' . $request->ip();
        
        if (RateLimiter::tooManyAttempts($key, $limits['global_requests_per_minute'])) {
            abort(429, 'API rate limit exceeded.');
        }
        
        RateLimiter::hit($key, 60); // 1 minute window
    }
}