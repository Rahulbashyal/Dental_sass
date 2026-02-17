<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityValidation
{
    public function handle(Request $request, Closure $next): Response
    {
        // Sanitize all input data
        $this->sanitizeInput($request);
        
        // Check for suspicious patterns
        $this->checkSuspiciousPatterns($request);
        
        return $next($request);
    }
    
    private function sanitizeInput(Request $request): void
    {
        $input = $request->all();
        
        foreach ($input as $key => $value) {
            if (is_string($value)) {
                // Remove potential XSS and SQL injection patterns
                $sanitized = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
                $sanitized = strip_tags($sanitized);
                $request->merge([$key => $sanitized]);
            }
        }
    }
    
    private function checkSuspiciousPatterns(Request $request): void
    {
        $suspiciousPatterns = [
            '/union\s+select/i',
            '/drop\s+table/i',
            '/delete\s+from/i',
            '/<script/i',
            '/javascript:/i',
            '/on\w+\s*=/i'
        ];
        
        $input = json_encode($request->all());
        
        foreach ($suspiciousPatterns as $pattern) {
            if (preg_match($pattern, $input)) {
                abort(403, 'Suspicious input detected');
            }
        }
    }
}