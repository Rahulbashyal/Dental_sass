<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupplierController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'role:clinic_admin|accountant']);
    }

    public function index()
    {
        $suppliers = Supplier::where('clinic_id', Auth::user()->clinic_id)
            ->withCount('purchaseOrders')
            ->paginate(15);

        return view('suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $validated['clinic_id'] = Auth::user()->clinic_id;

        Supplier::create($validated);

        return redirect()->route('clinic.suppliers.index')->with('success', 'Supplier created successfully.');
    }

    public function edit(Supplier $supplier)
    {
        $this->authorizeAccess($supplier);
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $this->authorizeAccess($supplier);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $supplier->update($validated);

        return redirect()->route('clinic.suppliers.index')->with('success', 'Supplier updated successfully.');
    }

    public function destroy(Supplier $supplier)
    {
        $this->authorizeAccess($supplier);
        
        if ($supplier->purchaseOrders()->count() > 0) {
            return back()->with('error', 'Cannot delete supplier with existing purchase orders.');
        }

        $supplier->delete();

        return redirect()->route('clinic.suppliers.index')->with('success', 'Supplier deleted successfully.');
    }

    protected function authorizeAccess(Supplier $supplier)
    {
        if ($supplier->clinic_id !== Auth::user()->clinic_id) {
            abort(403);
        }
    }
}
