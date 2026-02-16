<?php

namespace Modules\Treatments\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TreatmentPlan;
use App\Models\Patient;
use Illuminate\Http\Request;

class TreatmentController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status');
        $query = TreatmentPlan::with('patient')->orderBy('created_at', 'desc');

        if ($status) {
            $query->where('status', $status);
        }

        $treatmentPlans = $query->paginate(15);

        return view('treatments::index', compact('treatmentPlans', 'status'));
    }

    public function create(Request $request)
    {
        $patients = Patient::where('is_active', true)->get();
        $selectedPatientId = $request->get('patient_id');

        return view('treatments::create', compact('patients', 'selectedPatientId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'estimated_cost' => 'required|numeric|min:0',
            'estimated_duration' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
        ]);

        $treatmentPlan = TreatmentPlan::create(array_merge($validated, [
            'clinic_id' => tenant()->clinic->id,
            'status' => 'proposed',
        ]));

        return redirect()->route('clinic.treatment-plans.index')
            ->with('status', "Treatment plan '{$treatmentPlan->title}' created successfully.");
    }

    public function show(TreatmentPlan $treatmentPlan)
    {
        $treatmentPlan->load('patient');
        return view('treatments::show', compact('treatmentPlan'));
    }

    public function updateStatus(Request $request, TreatmentPlan $treatmentPlan)
    {
        $validated = $request->validate([
            'status' => 'required|in:proposed,accepted,rejected,in_progress,completed',
        ]);

        $treatmentPlan->update(['status' => $validated['status']]);

        return back()->with('status', 'Treatment plan status updated.');
    }
}
