<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Medication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class PrescriptionController extends Controller
{
    /**
     * Display a listing of prescriptions
     */
    public function index(Request $request)
    {
        $query = Prescription::with(['patient', 'dentist', 'items'])
            ->where('clinic_id', Auth::user()->clinic_id)
            ->latest();

        // Filter by patient if provided
        if ($request->filled('patient_id')) {
            $query->where('patient_id', $request->patient_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search by prescription number
        if ($request->filled('search')) {
            $query->where('prescription_number', 'like', '%' . $request->search . '%');
        }

        $prescriptions = $query->paginate(15);
        $patients = Patient::where('clinic_id', Auth::user()->clinic_id)->get();

        return view('prescriptions.index', compact('prescriptions', 'patients'));
    }

    /**
     * Show the form for creating a new prescription
     */
    public function create(Request $request)
    {
        $patients = Patient::where('clinic_id', Auth::user()->clinic_id)
            ->orderBy('first_name')
            ->get();

        $appointments = Appointment::with('patient')
            ->where('clinic_id', Auth::user()->clinic_id)
            ->where('status', 'completed')
            ->latest()
            ->take(50)
            ->get();

        $medications = Medication::active()->orderBy('name')->get();

        // Pre-select patient if provided
        $selectedPatient = null;
        if ($request->filled('patient_id')) {
            $selectedPatient = Patient::find($request->patient_id);
        }

        return view('prescriptions.create', compact('patients', 'appointments', 'medications', 'selectedPatient'));
    }

    /**
     * Store a newly created prescription
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'appointment_id' => 'nullable|exists:appointments,id',
            'chief_complaint' => 'nullable|string',
            'diagnosis' => 'required|string',
            'treatment_provided' => 'nullable|string',
            'dental_notes' => 'nullable|string',
            'known_allergies' => 'nullable|string',
            'current_medications' => 'nullable|string',
            'medical_conditions' => 'nullable|string',
            'general_instructions' => 'nullable|string',
            'prescribed_date' => 'required|date',
            'valid_until' => 'nullable|date|after:prescribed_date',
            
            // Prescription items
            'medications' => 'required|array|min:1',
            'medications.*.medication_id' => 'required|exists:medications,id',
            'medications.*.dosage' => 'required|string',
            'medications.*.frequency' => 'required|string',
            'medications.*.route' => 'required|string',
            'medications.*.duration_days' => 'required|integer|min:1',
            'medications.*.quantity' => 'required|integer|min:1',
            'medications.*.instructions' => 'nullable|string',
            'medications.*.precautions' => 'nullable|string',
        ]);

        // Create prescription
        $prescription = Prescription::create([
            'clinic_id' => Auth::user()->clinic_id,
            'patient_id' => $validated['patient_id'],
            'dentist_id' => Auth::id(),
            'appointment_id' => $validated['appointment_id'] ?? null,
            'chief_complaint' => $validated['chief_complaint'],
            'diagnosis' => $validated['diagnosis'],
            'treatment_provided' => $validated['treatment_provided'] ?? null,
            'dental_notes' => $validated['dental_notes'] ?? null,
            'known_allergies' => $validated['known_allergies'] ?? null,
            'current_medications' => $validated['current_medications'] ?? null,
            'medical_conditions' => $validated['medical_conditions'] ?? null,
            'general_instructions' => $validated['general_instructions'] ?? null,
            'prescribed_date' => $validated['prescribed_date'],
            'valid_until' => $validated['prescribed_date'] ? Carbon::parse($validated['prescribed_date'])->addDays(30) : null,
            'status' => 'active',
        ]);

        // Add prescription items
        foreach ($validated['medications'] as $medData) {
            $medication = Medication::find($medData['medication_id']);
            
            $prescription->items()->create([
                'medication_id' => $medication->id,
                'medication_name' => $medication->name,
                'generic_name' => $medication->generic_name,
                'dosage' => $medData['dosage'],
                'frequency' => $medData['frequency'],
                'route' => $medData['route'],
                'duration_days' => $medData['duration_days'],
                'quantity' => $medData['quantity'],
                'instructions' => $medData['instructions'] ?? null,
                'precautions' => $medData['precautions'] ?? null,
            ]);
        }

        return redirect()->route('prescriptions.show', $prescription)
            ->with('success', 'Prescription created successfully! Email sent to patient.')
            ->after(function () use ($prescription) {
                // Send email notification to patient
                $prescription->patient->notify(new \App\Notifications\PrescriptionIssued($prescription));
            });
    }

    /**
     * Display the specified prescription
     */
    public function show(Prescription $prescription)
    {
        $prescription->load(['patient', 'dentist', 'appointment', 'clinic', 'items.medication']);

        // Authorization check
        if ($prescription->clinic_id !== Auth::user()->clinic_id) {
            abort(403);
        }

        return view('prescriptions.show', compact('prescription'));
    }

    /**
     * Show the form for editing the prescription
     */
    public function edit(Prescription $prescription)
    {
        // Authorization check
        if ($prescription->clinic_id !== Auth::user()->clinic_id) {
            abort(403);
        }

        $prescription->load('items');
        $patients = Patient::where('clinic_id', Auth::user()->clinic_id)->orderBy('first_name')->get();
        $appointments = Appointment::with('patient')->where('clinic_id', Auth::user()->clinic_id)->latest()->take(50)->get();
        $medications = Medication::active()->orderBy('name')->get();

        return view('prescriptions.edit', compact('prescription', 'patients', 'appointments', 'medications'));
    }

    /**
     * Update the specified prescription
     */
    public function update(Request $request, Prescription $prescription)
    {
        // Authorization check
        if ($prescription->clinic_id !== Auth::user()->clinic_id) {
            abort(403);
        }

        $validated = $request->validate([
            'diagnosis' => 'required|string',
            'treatment_provided' => 'nullable|string',
            'dental_notes' => 'nullable|string',
            'general_instructions' => 'nullable|string',
            'status' => 'required|in:active,completed,cancelled',
        ]);

        $prescription->update($validated);

        return redirect()->route('prescriptions.show', $prescription)
            ->with('success', 'Prescription updated successfully!');
    }

    /**
     * Remove the specified prescription
     */
    public function destroy(Prescription $prescription)
    {
        // Authorization check
        if ($prescription->clinic_id !== Auth::user()->clinic_id) {
            abort(403);
        }

        $prescription->delete();

        return redirect()->route('prescriptions.index')
            ->with('success', 'Prescription deleted successfully!');
    }

    /**
     * Generate PDF for prescription
     */
    public function pdf(Prescription $prescription)
    {
        // Authorization check
        if ($prescription->clinic_id !== Auth::user()->clinic_id) {
            abort(403);
        }

        $prescription->load(['patient', 'dentist', 'clinic', 'items.medication']);

        $pdf = Pdf::loadView('prescriptions.pdf', compact('prescription'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('prescription-' . $prescription->prescription_number . '.pdf');
    }

    /**
     * Print view for prescription
     */
    public function print(Prescription $prescription)
    {
        // Authorization check
        if ($prescription->clinic_id !== Auth::user()->clinic_id) {
            abort(403);
        }

        $prescription->load(['patient', 'dentist', 'clinic', 'items.medication']);

        return view('prescriptions.print', compact('prescription'));
    }
}
