<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class ValidateResourceOwnership
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        
        // Skip for superadmin
        if ($user && $user->hasRole('superadmin')) {
            return $next($request);
        }
        
        // Get route parameters
        $route = $request->route();
        if ($route) {
            $parameters = $route->parameters();
            
            foreach ($parameters as $key => $parameter) {
                if (is_object($parameter)) {
                    // Check if model has clinic_id and validate ownership
                    if (method_exists($parameter, 'getAttribute') && $parameter->getAttribute('clinic_id')) {
                        if ($parameter->clinic_id !== $user->clinic_id) {
                            abort(403, 'Unauthorized access to resource.');
                        }
                    }
                    
                    // Check if model belongs to user directly
                    if (method_exists($parameter, 'getAttribute') && $parameter->getAttribute('user_id')) {
                        if ($parameter->user_id !== $user->id && !$user->hasRole(['clinic_admin', 'superadmin'])) {
                            abort(403, 'Unauthorized access to resource.');
                        }
                    }
                }
            }
        }
        
        return $next($request);
    }
}
