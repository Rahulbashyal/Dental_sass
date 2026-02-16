<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $branches = Branch::orderBy('is_main_branch', 'desc')->get();
        return view('tenant.branches.index', compact('branches'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'is_main_branch' => 'boolean',
        ]);

        if ($request->has('is_main_branch')) {
            Branch::where('is_main_branch', true)->update(['is_main_branch' => false]);
        }

        Branch::create($validated);

        return redirect()->route('tenant.branches.index')
            ->with('status', 'Branch created successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Branch $branch)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'is_main_branch' => 'boolean',
            'is_active' => 'boolean',
        ]);

        if ($request->has('is_main_branch') && !$branch->is_main_branch) {
            Branch::where('is_main_branch', true)->update(['is_main_branch' => false]);
        }

        $branch->update($validated);

        return redirect()->route('tenant.branches.index')
            ->with('status', 'Branch updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Branch $branch)
    {
        if ($branch->is_main_branch) {
            return back()->with('error', 'The main branch cannot be deleted.');
        }

        $branch->delete();

        return redirect()->route('tenant.branches.index')
            ->with('status', 'Branch deleted successfully.');
    }
}
