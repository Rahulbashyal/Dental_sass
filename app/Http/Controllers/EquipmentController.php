<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EquipmentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'role:clinic_admin']);
    }

    public function index(Request $request)
    {
        $query = Equipment::where('clinic_id', Auth::user()->clinic_id);

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('serial_number', 'like', '%' . $request->search . '%');
        }

        $equipment = $query->latest()->paginate(15);

        return view('equipment.index', compact('equipment'));
    }

    public function create()
    {
        return view('equipment.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'model' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'purchase_date' => 'nullable|date',
            'warranty_expiry' => 'nullable|date',
            'status' => 'required|in:operational,under_maintenance,retired',
        ]);

        $validated['clinic_id'] = Auth::user()->clinic_id;

        Equipment::create($validated);

        return redirect()->route('clinic.equipment.index')->with('success', 'Equipment added successfully.');
    }

    public function edit(Equipment $equipment)
    {
        $this->authorizeAccess($equipment);
        return view('equipment.edit', compact('equipment'));
    }

    public function update(Request $request, Equipment $equipment)
    {
        $this->authorizeAccess($equipment);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'model' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'purchase_date' => 'nullable|date',
            'warranty_expiry' => 'nullable|date',
            'status' => 'required|in:operational,under_maintenance,retired',
            'last_maintenance_at' => 'nullable|date',
        ]);

        $equipment->update($validated);

        return redirect()->route('clinic.equipment.index')->with('success', 'Equipment updated successfully.');
    }

    public function destroy(Equipment $equipment)
    {
        $this->authorizeAccess($equipment);
        $equipment->delete();

        return redirect()->route('clinic.equipment.index')->with('success', 'Equipment removed successfully.');
    }

    protected function authorizeAccess(Equipment $equipment)
    {
        if ($equipment->clinic_id !== Auth::user()->clinic_id) {
            abort(403);
        }
    }
}
