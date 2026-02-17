<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;

class EnhancedCsrfProtection extends ValidateCsrfToken
{
    protected $except = [
        'api/*', // API routes typically use different auth
    ];

    public function handle(Request $request, Closure $next)
    {
        // Enhanced CSRF validation for sensitive operations
        if ($this->isSensitiveOperation($request)) {
            $this->validateCsrfToken($request);
        }

        return parent::handle($request, $next);
    }

    private function isSensitiveOperation(Request $request): bool
    {
        $sensitiveRoutes = [
            'patients.store', 'patients.update', 'patients.destroy',
            'appointments.store', 'appointments.update', 'appointments.destroy',
            'users.store', 'users.update', 'users.destroy',
            'clinics.update', 'settings.update'
        ];

        return in_array($request->route()?->getName(), $sensitiveRoutes) ||
               $request->isMethod('POST') ||
               $request->isMethod('PUT') ||
               $request->isMethod('DELETE');
    }

    private function validateCsrfToken(Request $request)
    {
        if (!$request->session()->token() || 
            !hash_equals($request->session()->token(), $request->input('_token'))) {
            abort(419, 'CSRF token mismatch');
        }
    }
}