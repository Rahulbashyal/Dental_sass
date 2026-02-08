<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnforceClinicConfiguration
{
    public function handle(Request $request, Closure $next, $module = null, $role = null)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $clinic = $user->clinic;

        if (!$clinic) {
            abort(403, 'No clinic associated with user.');
        }

        // Check if the required module is enabled
        if ($module && !in_array($module, $clinic->enabled_modules ?? [])) {
            abort(403, "The {$module} module is not enabled for this clinic.");
        }

        // Check if the user's role is enabled for this clinic
        if ($role && !in_array($role, $clinic->enabled_roles ?? [])) {
            abort(403, "The {$role} role is not enabled for this clinic.");
        }

        // Check user limits
        if ($clinic->max_users && $clinic->users()->count() > $clinic->max_users) {
            abort(403, 'User limit exceeded for this clinic.');
        }

        return $next($request);
    }
}