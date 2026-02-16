<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BranchController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user->clinic_id) {
            return redirect()->route('dashboard')->with('error', 'No clinic assigned.');
        }

        $branches = Branch::where('clinic_id', $user->clinic_id)
            ->orderBy('is_main_branch', 'desc')
            ->get();

        return view('branches.index', compact('branches'));
    }

    public function create()
    {
        return view('branches.create');
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user->clinic_id) {
            return redirect()->route('dashboard')->with('error', 'No clinic assigned.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'is_main_branch' => 'boolean',
        ]);

        if ($request->has('is_main_branch')) {
            Branch::where('clinic_id', $user->clinic_id)
                ->where('is_main_branch', true)
                ->update(['is_main_branch' => false]);
        }

        Branch::create(array_merge($validated, [
            'clinic_id' => $user->clinic_id
        ]));

        return redirect()->route('clinic.branches.index')
            ->with('status', 'Branch created successfully.');
    }

    public function edit(Branch $branch)
    {
        if ($branch->clinic_id !== Auth::user()->clinic_id) {
            abort(403);
        }
        return view('branches.edit', compact('branch'));
    }

    public function update(Request $request, Branch $branch)
    {
        if ($branch->clinic_id !== Auth::user()->clinic_id) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'is_main_branch' => 'boolean',
            'is_active' => 'boolean',
        ]);

        if ($request->has('is_main_branch') && !$branch->is_main_branch) {
            Branch::where('clinic_id', Auth::user()->clinic_id)
                ->where('is_main_branch', true)
                ->update(['is_main_branch' => false]);
        }

        $branch->update($validated);

        return redirect()->route('clinic.branches.index')
            ->with('status', 'Branch updated successfully.');
    }

    public function destroy(Branch $branch)
    {
        if ($branch->clinic_id !== Auth::user()->clinic_id) {
            abort(403);
        }

        if ($branch->is_main_branch) {
            return back()->with('error', 'The main branch cannot be deleted.');
        }

        $branch->delete();

        return redirect()->route('clinic.branches.index')
            ->with('status', 'Branch deleted successfully.');
    }
}
