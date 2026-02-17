<?php

namespace App\Http\Controllers;

use App\Models\Waitlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WaitlistController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'clinic.access']);
    }

    public function index()
    {
        $waitlists = Waitlist::where('clinic_id', Auth::user()->clinic_id)
            ->with(['patient', 'dentist'])
            ->orderBy('preferred_date')
            ->paginate(15);
            
        return view('waitlist.index', compact('waitlists'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'dentist_id' => 'required|exists:users,id',
            'preferred_date' => 'required|date|after_or_equal:today',
            'preferred_time' => 'nullable|date_format:H:i',
            'appointment_type' => 'required|string|max:100',
            'notes' => 'nullable|string|max:500'
        ]);

        $validated['clinic_id'] = Auth::user()->clinic_id;
        $validated['status'] = 'waiting';
        
        Waitlist::create($validated);

        return back()->with('success', 'Patient added to waitlist successfully.');
    }

    public function updateStatus(Waitlist $waitlist, Request $request)
    {
        $request->validate([
            'status' => 'required|in:waiting,contacted,scheduled,cancelled'
        ]);

        $waitlist->update(['status' => $request->status]);

        return back()->with('success', 'Waitlist status updated.');
    }
}
