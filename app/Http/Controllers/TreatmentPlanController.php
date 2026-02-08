<?php

namespace App\Http\Controllers;

use App\Models\TreatmentPlan;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TreatmentPlanController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'clinic.access']);
    }
    
    public function index()
    {
        $user = Auth::user();
        
        if (!$user->clinic_id) {
            return redirect()->route('dashboard')->with('error', 'Access denied. No clinic assigned.');
        }

        $query = TreatmentPlan::where('clinic_id', $user->clinic_id)->with('patient');
        
        // If user is dentist, only show their treatment plans
        if ($user->hasRole('dentist')) {
            $query->where('dentist_id', $user->id);
        }
        
        $treatmentPlans = $query->latest()->paginate(15);

        return view('treatment-plans.index', compact('treatmentPlans'));
    }

    public function create()
    {
        $user = Auth::user();
        
        if (!$user->clinic_id) {
            return redirect()->route('dashboard')->with('error', 'Access denied. No clinic assigned.');
        }

        $patients = Patient::where('clinic_id', $user->clinic_id)->get();
        return view('treatment-plans.create', compact('patients'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->clinic_id) {
            return redirect()->route('dashboard')->with('error', 'Access denied. No clinic assigned.');
        }

        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'estimated_cost' => 'required|numeric|min:0',
            'estimated_duration' => 'required|string',
            'priority' => 'required|in:low,medium,high,urgent'
        ]);

        TreatmentPlan::create([
            'clinic_id' => $user->clinic_id,
            'patient_id' => $validated['patient_id'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'estimated_cost' => $validated['estimated_cost'],
            'estimated_duration' => $validated['estimated_duration'],
            'priority' => $validated['priority'],
            'status' => 'draft'
        ]);

        return redirect()->route('treatment-plans.index')->with('success', 'Treatment plan created successfully!');
    }

    public function show(TreatmentPlan $treatmentPlan)
    {
        if ($treatmentPlan->clinic_id !== Auth::user()->clinic_id) {
            abort(403);
        }

        return view('treatment-plans.show', compact('treatmentPlan'));
    }

    public function edit(TreatmentPlan $treatmentPlan)
    {
        if ($treatmentPlan->clinic_id !== Auth::user()->clinic_id) {
            abort(403);
        }

        $patients = Patient::where('clinic_id', Auth::user()->clinic_id)->get();
        return view('treatment-plans.edit', compact('treatmentPlan', 'patients'));
    }

    public function update(Request $request, TreatmentPlan $treatmentPlan)
    {
        if ($treatmentPlan->clinic_id !== Auth::user()->clinic_id) {
            abort(403);
        }

        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'estimated_cost' => 'required|numeric|min:0',
            'estimated_duration' => 'required|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:draft,approved,in_progress,completed,cancelled'
        ]);

        $treatmentPlan->update($validated);

        return redirect()->route('treatment-plans.index')->with('success', 'Treatment plan updated successfully!');
    }

    public function destroy(TreatmentPlan $treatmentPlan)
    {
        if ($treatmentPlan->clinic_id !== Auth::user()->clinic_id) {
            abort(403);
        }

        $treatmentPlan->delete();
        return redirect()->route('treatment-plans.index')->with('success', 'Treatment plan deleted successfully!');
    }
}