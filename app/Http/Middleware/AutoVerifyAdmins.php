<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AutoVerifyAdmins
{
    /**
     * Handle an incoming request.
     *
     * Auto-verify email for superadmin and clinic_admin roles.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        
        if ($user && is_null($user->email_verified_at)) {
            // Auto-verify superadmin and clinic_admin
            if ($user->hasAnyRole(['superadmin', 'clinic_admin'])) {
                $user->email_verified_at = now();
                $user->save();
            }
        }

        return $next($request);
    }
}
