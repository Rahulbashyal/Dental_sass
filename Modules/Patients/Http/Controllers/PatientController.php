<?php

namespace Modules\Patients\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index()
    {
        $patients = Patient::orderBy('created_at', 'desc')->paginate(15);
        return view('patients::index', compact('patients'));
    }

    public function create()
    {
        return view('patients::create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|string|in:male,female,other',
            'address' => 'nullable|string',
            'medical_history' => 'nullable|string',
            'allergies' => 'nullable|string',
        ]);

        $patient = Patient::create(array_merge($validated, [
            'patient_id' => 'P' . strtoupper(uniqid()),
            'is_active' => true,
        ]));

        return redirect()->route('clinic.patients.index')
            ->with('status', "Patient '{$patient->full_name}' registered successfully.");
    }

    public function show(Patient $patient)
    {
        return view('patients::show', compact('patient'));
    }

    public function edit(Patient $patient)
    {
        return view('patients::edit', compact('patient'));
    }

    public function update(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|string|in:male,female,other',
            'address' => 'nullable|string',
            'medical_history' => 'nullable|string',
            'allergies' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $patient->update($validated);

        return redirect()->route('clinic.patients.index')
            ->with('status', "Patient '{$patient->full_name}' updated successfully.");
    }
}
