<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckModuleEnabled
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $module): Response
    {
        // Central context check (optional - usually modules are tenant-only)
        if (!function_exists('tenant') || !tenant()) {
            return $next($request);
        }

        // Check if tenant has a clinic relationship and if the module is enabled
        $clinic = tenant()->clinic;

        if (!$clinic || !$clinic->hasModule($module)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => "The '$module' module is not enabled for this clinic."
                ], 403);
            }

            abort(403, "The '$module' module is not enabled for this clinic.");
        }

        return $next($request);
    }
}
