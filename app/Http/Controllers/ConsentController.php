<?php

namespace App\Http\Controllers;

use App\Models\ConsentTemplate;
use App\Models\PatientConsent;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConsentController extends Controller
{
    use \App\Traits\SanitizesHtml;

    public function index()
    {
        $templates = ConsentTemplate::where('clinic_id', Auth::user()->clinic_id)->get();
        return view('clinic.consents.templates', compact('templates'));
    }

    public function storeTemplate(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        ConsentTemplate::create([
            'clinic_id' => Auth::user()->clinic_id,
            'title' => $validated['title'],
            'content' => $this->sanitizeHtml($validated['content'])
        ]);

        return back()->with('success', 'Consent template created.');
    }

    public function sign(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'template_id' => 'required|exists:consent_templates,id',
            'signature_data' => 'nullable|string', // Base64 signature
        ]);

        PatientConsent::create([
            'clinic_id' => Auth::user()->clinic_id,
            'patient_id' => $patient->id,
            'template_id' => $validated['template_id'],
            'signed_at' => now(),
            'ip_address' => $request->ip(),
            'status' => 'signed'
        ]);

        return back()->with('success', 'Consent signed successfully.');
    }
}
