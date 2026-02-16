<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EnsureClinicAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        
        // Superadmin can access everything
        if ($user && $user->hasRole('superadmin')) {
            return $next($request);
        }
        
        // Regular users must have a clinic assigned
        if (!$user || !$user->clinic_id) {
            abort(403, 'No clinic assigned to your account. Contact administrator.');
        }
        
        // Check if accessing a model that belongs to a clinic
        $route = $request->route();
        if ($route) {
            $parameters = $route->parameters();
            
            foreach ($parameters as $parameter) {
                if ($parameter instanceof \Illuminate\Database\Eloquent\Model) {
                    // Check if model has clinic_id attribute and verify access
                    $modelClinicId = $parameter->getAttribute('clinic_id');
                    if ($modelClinicId !== null && $modelClinicId !== $user->clinic_id) {
                        abort(403, 'Unauthorized access to resource.');
                    }
                }
            }
        }
        
        return $next($request);
    }
}