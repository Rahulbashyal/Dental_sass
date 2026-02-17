<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Campaign;
use App\Models\EmailLog;
use Illuminate\Http\Request;

class CrmController extends Controller
{
    public function leads()
    {
        $leads = Lead::with('assignedTo')->latest()->paginate(15);
        $stats = [
            'total_leads' => Lead::count(),
            'new_leads' => Lead::where('status', 'new')->count(),
            'qualified_leads' => Lead::where('status', 'qualified')->count(),
            'converted_leads' => Lead::where('status', 'converted')->count(),
        ];
        
        return view('superadmin.crm.leads', compact('leads', 'stats'));
    }

    public function createLead()
    {
        return view('superadmin.crm.create-lead');
    }

    public function storeLead(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:leads',
            'phone' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:255',
            'source' => 'required|in:website,referral,social_media,advertisement,other',
            'potential_value' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string'
        ]);

        Lead::create($validated);

        return redirect()->route('superadmin.crm.leads')->with('success', 'Lead created successfully!');
    }

    public function campaigns()
    {
        $campaigns = Campaign::latest()->paginate(15);
        $stats = [
            'total_campaigns' => Campaign::count(),
            'active_campaigns' => Campaign::where('status', 'active')->count(),
            'total_sent' => Campaign::sum('total_sent'),
            'total_opened' => Campaign::sum('total_opened'),
        ];
        
        return view('superadmin.crm.campaigns', compact('campaigns', 'stats'));
    }

    public function createCampaign()
    {
        return view('superadmin.crm.create-campaign');
    }

    public function storeCampaign(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:email,sms,social_media,advertisement',
            'budget' => 'nullable|numeric|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date'
        ]);

        Campaign::create($validated);

        return redirect()->route('superadmin.crm.campaigns')->with('success', 'Campaign created successfully!');
    }

    public function emailLogs()
    {
        $emailLogs = EmailLog::with(['campaign', 'lead'])->latest()->paginate(15);
        $stats = [
            'total_sent' => EmailLog::count(),
            'total_opened' => EmailLog::where('status', 'opened')->count(),
            'total_clicked' => EmailLog::where('status', 'clicked')->count(),
            'bounce_rate' => EmailLog::where('status', 'bounced')->count(),
        ];
        
        return view('superadmin.crm.email-logs', compact('emailLogs', 'stats'));
    }
}