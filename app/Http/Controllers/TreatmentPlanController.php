<?php

namespace App\Http\Controllers;

use App\Models\TreatmentPlan;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TreatmentPlanController extends Controller
{
    public function index(Request $request, Patient $patient)
    {
        $treatmentPlans = TreatmentPlan::where('patient_id', $patient->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('treatment-plans.index', compact('treatmentPlans', 'patient'));
    }

    public function create()
    {
        $patients = Patient::where('clinic_id', Auth::user()->clinic_id)
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();
        
        return view('treatment-plans.create', compact('patients'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'estimated_cost' => 'nullable|numeric',
            'priority' => 'required|in:low,medium,high',
        ]);

        $plan = new TreatmentPlan($validated);
        $plan->clinic_id = Auth::user()->clinic_id;
        $plan->dentist_id = Auth::id();
        $plan->status = 'proposed';
        $plan->save();

        return redirect()->route('clinic.treatment-plans.index', ['patient' => $validated['patient_id']])
            ->with('success', 'Treatment plan proposed.');
    }

    public function show(TreatmentPlan $treatmentPlan)
    {
        $patient = $treatmentPlan->patient;
        return view('treatment-plans.show', compact('treatmentPlan', 'patient'));
    }

    public function edit(TreatmentPlan $treatmentPlan)
    {
        $patients = Patient::where('clinic_id', Auth::user()->clinic_id)
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();
        
        return view('treatment-plans.edit', compact('treatmentPlan', 'patients'));
    }

    public function update(Request $request, TreatmentPlan $treatmentPlan)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'estimated_cost' => 'nullable|numeric',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:proposed,approved,in_progress,completed',
        ]);

        $treatmentPlan->update($validated);

        return redirect()->route('clinic.treatment-plans.show', $treatmentPlan->id)
            ->with('success', 'Treatment plan updated.');
    }

    public function destroy(TreatmentPlan $treatmentPlan)
    {
        $patientId = $treatmentPlan->patient_id;
        $treatmentPlan->delete();

        return redirect()->route('clinic.treatment-plans.index', ['patient' => $patientId])
            ->with('success', 'Treatment plan deleted.');
    }

    public function updateStatus(Request $request, TreatmentPlan $plan)
    {
        $validated = $request->validate([
            'status' => 'required|in:proposed,approved,in_progress,completed',
        ]);

        if ($plan->clinic_id !== Auth::user()->clinic_id) {
            abort(403);
        }

        $plan->update(['status' => $validated['status']]);
        return back()->with('success', 'Plan status updated.');
    }
}