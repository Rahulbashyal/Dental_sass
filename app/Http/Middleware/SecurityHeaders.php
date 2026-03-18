<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
       

        $response = $next($request);
        
        // Security headers
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN'); // SAMEORIGIN allows the slide-modal iframe for same-origin pages
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=(), payment=(), usb=()');
        $response->headers->set('X-Permitted-Cross-Domain-Policies', 'none');
        // $response->headers->set('Cross-Origin-Embedder-Policy', 'require-corp');
        // $response->headers->set('Cross-Origin-Opener-Policy', 'same-origin');
        // $response->headers->set('Cross-Origin-Resource-Policy', 'same-origin');
        
        // Content Security Policy (CSP) - Allow necessary external resources
        if (app()->environment('local')) {
            $csp = "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com http://127.0.0.1:5173 http://localhost:5173 http://127.0.0.1:5174 http://localhost:5174 http://127.0.0.1:5175 http://localhost:5175 http://127.0.0.1:5176 http://localhost:5176; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.jsdelivr.net https://cdnjs.cloudflare.com http://127.0.0.1:5173 http://localhost:5173 http://127.0.0.1:5174 http://localhost:5174 http://127.0.0.1:5175 http://localhost:5175 http://127.0.0.1:5176 http://localhost:5176; font-src 'self' https://fonts.gstatic.com https://cdnjs.cloudflare.com; img-src 'self' data: blob: https://images.pexels.com https://i.pravatar.cc https://ui-avatars.com https://placehold.co; frame-src 'self'; connect-src 'self' ws://127.0.0.1:5173 ws://localhost:5173 ws://127.0.0.1:5174 ws://localhost:5174 ws://127.0.0.1:5175 ws://localhost:5175 ws://127.0.0.1:5176 ws://localhost:5176 http://127.0.0.1:5173 http://localhost:5173 http://127.0.0.1:5174 http://localhost:5174 http://127.0.0.1:5175 http://localhost:5175 http://127.0.0.1:5176 http://localhost:5176;";
        } else {
            $csp = "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; font-src 'self' https://fonts.gstatic.com https://cdnjs.cloudflare.com; img-src 'self' data: blob: https://images.pexels.com https://i.pravatar.cc https://ui-avatars.com https://placehold.co; frame-src 'self'; connect-src 'self';";
        }
        $response->headers->set('Content-Security-Policy', $csp);
        
        // HSTS (only in production with HTTPS)
        if (app()->environment('production') && $request->isSecure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        }
        
        return $response;   
    }
}