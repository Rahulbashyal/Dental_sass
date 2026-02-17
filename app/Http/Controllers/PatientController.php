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
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->clinic_id) {
                abort(403, 'No clinic assigned to your account.');
            }
            return $next($request);
        });
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

        return redirect()->route('patients.index')->with('success', 'Patient registered successfully! Welcome email sent.');
    }

    public function show(Patient $patient)
    {
        if ($patient->clinic_id !== Auth::user()->clinic_id) {
            abort(403, 'Unauthorized access to patient record.');
        }
        
        return view('patients.show', compact('patient'));
    }

    public function edit(Patient $patient)
    {
        if ($patient->clinic_id !== Auth::user()->clinic_id) {
            abort(403, 'Unauthorized access to patient record.');
        }
        
        return view('patients.edit', compact('patient'));
    }

    public function update(StorePatientRequest $request, Patient $patient)
    {
        if ($patient->clinic_id !== Auth::user()->clinic_id) {
            abort(403, 'Unauthorized access to patient record.');
        }
        
        $validated = $request->validated();
        unset($validated['clinic_id']); // Don't allow clinic_id changes
        
        $patient->update($validated);

        return redirect()->route('patients.index')->with('success', 'Patient updated successfully.');
    }

    public function destroy(Patient $patient)
    {
        if ($patient->clinic_id !== Auth::user()->clinic_id) {
            abort(403, 'Unauthorized access to patient record.');
        }
        
        // Check if patient has appointments before deletion
        if ($patient->appointments()->count() > 0) {
            return redirect()->route('patients.index')
                ->with('error', 'Cannot delete patient with existing appointments.');
        }
        
        $patient->delete();
        return redirect()->route('patients.index')->with('success', 'Patient deleted successfully.');
    }
}