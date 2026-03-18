<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ModuleController extends Controller
{
    /**
     * Create a new controller instance.
     * Ensure only superadmins can access these methods.
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!Auth::user() || !Auth::user()->hasRole('superadmin')) {
                abort(403, 'Access denied. Superadmin privileges required.');
            }
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $modules = Module::orderBy('name')->get();
        return view('superadmin.modules.index', compact('modules'));
    }

    /**
     * Update the specified resource in storage.
     * Only updates display_name and description - NOT is_active.
     * Use toggle() for enabling/disabling modules.
     */
    public function update(Request $request, Module $module)
    {
        $validated = $request->validate([
            'display_name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $module->update([
            'display_name' => $validated['display_name'],
            'description' => $validated['description'] ?? $module->description,
        ]);

        return redirect()->route('superadmin.modules.index')
            ->with('status', "Module '{$module->name}' updated successfully.");
    }

    /**
     * Toggle module enabled/disabled status.
     * Core modules cannot be disabled.
     */
    public function toggle(Module $module)
    {
        // Core modules cannot be toggled - security safeguard
        if ($module->is_core) {
            return back()->with('error', 'Cannot disable a core system module. Core modules are required for platform operation.');
        }

        $newStatus = !$module->is_active;
        $module->update(['is_active' => $newStatus]);

        $statusText = $newStatus ? 'enabled' : 'disabled';
        
        \Log::info("Module '{$module->name}' has been {$statusText} by superadmin.", [
            'user_id' => Auth::id(),
            'module' => $module->name,
            'new_status' => $newStatus,
        ]);

        return back()->with('status', "Module '{$module->name}' has been {$statusText} globally.");
    }

    /**
     * Manually set module status (for admin use, separate from toggle).
     */
    public function setStatus(Request $request, Module $module)
    {
        // Core modules cannot be changed
        if ($module->is_core) {
            return back()->with('error', 'Cannot modify a core system module.');
        }

        $validated = $request->validate([
            'is_active' => ['required', 'boolean'],
        ]);

        $module->update(['is_active' => $validated['is_active']]);

        $statusText = $validated['is_active'] ? 'enabled' : 'disabled';

        \Log::info("Module '{$module->name}' status changed to {$statusText} by superadmin.", [
            'user_id' => Auth::id(),
            'module' => $module->name,
            'new_status' => $validated['is_active'],
        ]);

        return back()->with('status', "Module '{$module->name}' has been {$statusText}.");
    }
}
