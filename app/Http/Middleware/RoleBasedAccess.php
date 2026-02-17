<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleBasedAccess
{
    public function handle(Request $request, Closure $next, ...$allowedRoles)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Check if user has any of the allowed roles using Spatie permission
        if (!$user->hasAnyRole($allowedRoles)) {
            abort(403, 'Access denied. Insufficient permissions.');
        }

        return $next($request);
    }
}