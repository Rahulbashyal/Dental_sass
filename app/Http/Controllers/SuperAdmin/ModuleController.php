<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Module;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
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
     */
    public function update(Request $request, Module $module)
    {
        $validated = $request->validate([
            'display_name' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);

        $module->update([
            'display_name' => $validated['display_name'],
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('superadmin.modules.index')
            ->with('status', "Module '{$module->name}' updated successfully.");
    }

    /**
     * Toggle module status.
     */
    public function toggle(Module $module)
    {
        if ($module->is_core) {
            return back()->with('error', 'Cannot disable a core system module.');
        }

        $module->update(['is_active' => !$module->is_active]);

        $status = $module->is_active ? 'enabled' : 'disabled';
        return back()->with('status', "Module '{$module->name}' has been {$status} globally.");
    }
}
