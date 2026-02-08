<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:superadmin|clinic_admin']);
    }

    public function index()
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all()->groupBy(function($permission) {
            return $permission->category ?? 'general';
        });
        
        // Get users for role assignment dropdown
        $user = auth()->user();
        if ($user->hasRole('superadmin')) {
            // Superadmin can assign roles to all users
            $users = User::with('roles')->orderBy('name')->get();
        } else {
            // Clinic admin can only assign roles to users in their clinic
            $users = User::where('clinic_id', $user->clinic_id)
                ->with('roles')
                ->orderBy('name')
                ->get();
        }
        
        return view('admin.roles.index', compact('roles', 'permissions', 'users'));
    }

    public function create()
    {
        $permissions = Permission::all()->groupBy(function($permission) {
            return $permission->category ?? 'general';
        });
        return view('admin.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        $role = Role::create([
            'name' => $validated['name'],
            'display_name' => $validated['display_name'],
            'description' => $validated['description'] ?? '',
        ]);

        // Convert permission IDs to permission objects
        if (!empty($validated['permissions'])) {
            $permissions = Permission::whereIn('id', $validated['permissions'])->get();
            $role->syncPermissions($permissions);
        }

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role created successfully.');
    }

    public function edit(Role $role)
    {
        $permissions = Permission::all()->groupBy(function($permission) {
            return $permission->category ?? 'general';
        });
        $rolePermissions = $role->permissions->pluck('id')->toArray();
        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        $role->update([
            'display_name' => $validated['display_name'],
            'description' => $validated['description'] ?? '',
        ]);

        // Convert permission IDs to permission objects
        if (!empty($validated['permissions'])) {
            $permissions = Permission::whereIn('id', $validated['permissions'])->get();
            $role->syncPermissions($permissions);
        } else {
            $role->syncPermissions([]);
        }

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        if (in_array($role->name, ['superadmin', 'clinic_admin'])) {
            return back()->with('error', 'Cannot delete system roles.');
        }

        $role->delete();
        return redirect()->route('admin.roles.index')
            ->with('success', 'Role deleted successfully.');
    }

    public function assignRole(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'role_id' => 'required|exists:roles,id'
        ]);

        $user = User::findOrFail($validated['user_id']);
        $role = Role::findOrFail($validated['role_id']);

        $user->syncRoles([$role->name]);

        return back()->with('success', "Role '{$role->display_name}' assigned to {$user->name}.");
    }
}