<?php

namespace App\Http\Controllers;

use App\Models\Medication;
use Illuminate\Http\Request;

class MedicationController extends Controller
{
    /**
     * Display a listing of medications (for admin)
     */
    public function index()
    {
        $medications = Medication::orderBy('category')
            ->orderBy('name')
            ->paginate(20);

        return view('medications.index', compact('medications'));
    }

    /**
     * AJAX search for medications (for prescription creation)
     */
    public function search(Request $request)
    {
        $term = $request->get('term', '');
        
        $medications = Medication::active()
            ->search($term)
            ->orderBy('name')
            ->limit(20)
            ->get(['id', 'name', 'generic_name', 'category', 'common_dosages']);

        return response()->json($medications);
    }

    /**
     * Store a custom medication (admin only)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'generic_name' => 'nullable|string|max:255',
            'category' => 'required|string',
            'description' => 'nullable|string',
            'common_dosages_input' => 'nullable|string',
            'side_effects' => 'nullable|string',
            'contraindications' => 'nullable|string',
            'manufacturer' => 'nullable|string',
        ]);

        // Process common dosages from comma-separated input
        $commonDosages = [];
        if (!empty($validated['common_dosages_input'])) {
            $commonDosages = array_map('trim', explode(',', $validated['common_dosages_input']));
        }

        $medication = Medication::create([
            'name' => $validated['name'],
            'generic_name' => $validated['generic_name'],
            'category' => $validated['category'],
            'description' => $validated['description'],
            'common_dosages' => $commonDosages,
            'side_effects' => $validated['side_effects'],
            'contraindications' => $validated['contraindications'],
            'manufacturer' => $validated['manufacturer'],
            'is_active' => true,
        ]);

        return redirect()->route('medications.index')->with('success', 'Medication added successfully!');
    }

    /**
     * Toggle medication active status
     */
    public function toggle(Medication $medication)
    {
        $medication->update(['is_active' => !$medication->is_active]);
        
        $status = $medication->is_active ? 'activated' : 'deactivated';
        return redirect()->back()->with('success', "Medication {$status} successfully!");
    }
}
