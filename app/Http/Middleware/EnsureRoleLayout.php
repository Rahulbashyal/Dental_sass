<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EnsureRoleLayout
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }
        
        // Set the layout based on user role
        $layout = 'app'; // default layout
        
        if ($user->hasRole('superadmin')) {
            $layout = 'superadmin';
        } elseif ($user->hasRole('receptionist')) {
            $layout = 'receptionist';
        } elseif ($user->hasRole('accountant')) {
            $layout = 'accountant';
        } elseif ($user->hasRole('dentist')) {
            $layout = 'dentist';
        }
        
        // Share the layout with all views
        view()->share('roleLayout', $layout);
        
        return $next($request);
    }
}