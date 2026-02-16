<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Http\Requests\StorePatientRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'clinic.access', 'resource.owner']);
    }
    public function index()
    {
        $patients = Auth::user()->clinic->patients()->latest()->paginate(10);
        return view('patients.index', compact('patients'));
    }

    public function create()
    {
        return view('patients.create');
    }

    public function store(StorePatientRequest $request)
    {
        $validated = $request->validated();
        $validated['clinic_id'] = Auth::user()->clinic_id;
        $validated['patient_id'] = 'P' . str_pad(
            Patient::where('clinic_id', Auth::user()->clinic_id)->count() + 1, 
            6, '0', STR_PAD_LEFT
        );

        $patient = Patient::create($validated);
        
        // Send welcome email to new patient
        if ($patient->email) {
            $patient->notify(new \App\Notifications\WelcomePatient($patient));
        }

        return redirect()->route('clinic.patients.index')->with('success', 'Patient registered successfully! Welcome email sent.');
    }

    public function show(Patient $patient)
    {
        $patient->load(['clinicalNotes.dentist', 'appointments', 'treatmentPlans', 'consents.template']);
        
        // Group notes by tooth number for the chart
        $toothNotes = $patient->clinicalNotes->groupBy('tooth_number')->map(function ($notes) {
            return $notes->map(function ($note) {
                return [
                    'id' => $note->id,
                    'condition' => $note->condition,
                    'note' => $note->note,
                    'date' => $note->created_at->format('M d, Y'),
                    'dentist' => $note->dentist->name
                ];
            });
        });

        return view('patients.show', compact('patient', 'toothNotes'));
    }

    public function edit(Patient $patient)
    {
        return view('patients.edit', compact('patient'));
    }

    public function update(StorePatientRequest $request, Patient $patient)
    {
        
        $validated = $request->validated();
        unset($validated['clinic_id']); // Don't allow clinic_id changes
        
        $patient->update($validated);

        return redirect()->route('clinic.patients.index')->with('success', 'Patient updated successfully.');
    }

    public function destroy(Patient $patient)
    {
        
        // Check if patient has appointments before deletion
        if ($patient->appointments()->count() > 0) {
            return redirect()->route('clinic.patients.index')
                ->with('error', 'Cannot delete patient with existing appointments.');
        }
        
        $patient->delete();
        return redirect()->route('clinic.patients.index')->with('success', 'Patient deleted successfully.');
    }
}