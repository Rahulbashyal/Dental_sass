<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Module;

class CheckModuleEnabled
{
    /**
     * Handle an incoming request.
     *
     * Checks if a module is globally enabled before allowing access.
     * This middleware should be applied to routes that belong to specific modules.
     *
     * Usage in routes:
     *   Route::get('/appointments', [AppointmentController::class, 'index'])->middleware('module:Appointments');
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $module): Response
    {
        // Find the module by name (case-insensitive for flexibility)
        $moduleModel = Module::where('name', $module)->orWhere('name', strtolower($module))->first();

        // If module doesn't exist in database, log warning and allow (fail-open for development)
        // In production, you might want to fail-closed: abort(404)
        if (!$moduleModel) {
            \Log::warning("CheckModuleEnabled: Module '{$module}' not found in database. Allowing access. Consider seeding modules.");
            return $next($request);
        }

        // Check if the module is globally enabled
        if (!$moduleModel->is_active) {
            // Log the blocked access attempt
            \Log::warning("CheckModuleEnabled: Access blocked to '{$module}' module. Module is disabled.", [
                'user_id' => $request->user()?->id,
                'ip' => $request->ip(),
                'module' => $module,
            ]);

            // Return appropriate response based on request type
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'message' => "The '{$module}' module is currently disabled.",
                    'error' => 'module_disabled'
                ], 403);
            }

            // For web requests, redirect with error message
            return redirect()->route('dashboard')->with('error', "The '{$module}' module is currently disabled. Contact your administrator.");
        }

        return $next($request);
    }
}
