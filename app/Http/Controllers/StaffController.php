<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;

class StaffController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if (!$user->clinic_id) {
            return redirect()->route('dashboard')->with('error', 'Access denied. No clinic assigned.');
        }

        $staff = User::where('clinic_id', $user->clinic_id)
            ->with('roles')
            ->get();

        return view('staff.index', compact('staff'));
    }

    public function create()
    {
        $roles = Role::whereIn('name', ['clinic_admin', 'dentist', 'receptionist', 'accountant'])->get();
        return view('staff.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->clinic_id) {
            return redirect()->route('dashboard')->with('error', 'Access denied. No clinic assigned.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'role' => 'required|exists:roles,name'
        ]);

        $staff = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => \Illuminate\Support\Facades\Hash::make($validated['password']),
            'clinic_id' => $user->clinic_id,
            'is_active' => true
        ]);

        $staff->assignRole($validated['role']);

        return redirect()->route('staff.index')->with('success', 'Staff member added successfully!');
    }

    public function edit(User $staff)
    {
        if ($staff->clinic_id !== Auth::user()->clinic_id) {
            abort(403);
        }

        $roles = Role::whereIn('name', ['clinic_admin', 'dentist', 'receptionist', 'accountant'])->get();
        return view('staff.edit', compact('staff', 'roles'));
    }

    public function update(Request $request, User $staff)
    {
        if ($staff->clinic_id !== Auth::user()->clinic_id) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $staff->id,
            'role' => 'required|exists:roles,name',
            'is_active' => 'boolean'
        ]);

        $staff->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'is_active' => $validated['is_active'] ?? true
        ]);

        $staff->syncRoles([$validated['role']]);

        return redirect()->route('staff.index')->with('success', 'Staff member updated successfully!');
    }

    public function destroy(User $staff)
    {
        if ($staff->clinic_id !== Auth::user()->clinic_id) {
            abort(403);
        }

        $staff->delete();
        return redirect()->route('staff.index')->with('success', 'Staff member removed successfully!');
    }
}