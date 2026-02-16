<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use Illuminate\Http\Request;
use App\Notifications\NewLeadAlert;
use Illuminate\Support\Facades\Notification;

class ClinicCrmController extends Controller
{
    public function index()
    {
        $clinicId = auth()->user()->clinic_id;
        $leads = Lead::where('clinic_id', $clinicId)->with('assignedTo')->latest()->paginate(15);
        
        $stats = [
            'total_leads' => Lead::where('clinic_id', $clinicId)->count(),
            'new_leads' => Lead::where('clinic_id', $clinicId)->where('status', 'new')->count(),
            'qualified_leads' => Lead::where('clinic_id', $clinicId)->where('status', 'qualified')->count(),
            'converted_leads' => Lead::where('clinic_id', $clinicId)->where('status', 'converted')->count(),
        ];
        
        return view('clinic.crm.leads.index', compact('leads', 'stats'));
    }

    public function create()
    {
        return view('clinic.crm.leads.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:255',
            'source' => 'required|in:website,referral,social_media,advertisement,other',
            'potential_value' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string'
        ]);

        $validated['clinic_id'] = auth()->user()->clinic_id;
        $validated['status'] = 'new';

        $lead = Lead::create($validated);

        // Notify clinic admins
        $clinic = auth()->user()->clinic;
        if ($clinic) {
            Notification::send($clinic->admins, new NewLeadAlert($lead));
        }

        return redirect()->route('clinic.crm.leads.index')->with('success', 'Lead created successfully!');
    }
}
