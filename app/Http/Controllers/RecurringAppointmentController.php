<?php

namespace App\Http\Controllers;

use App\Models\RecurringAppointment;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecurringAppointmentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function index()
    {
        $recurringAppointments = RecurringAppointment::where('clinic_id', Auth::user()->clinic_id)
            ->with(['patient', 'dentist'])
            ->latest()
            ->paginate(10);

        return view('recurring-appointments.index', compact('recurringAppointments'));
    }

    public function create()
    {
        $patients = Patient::where('clinic_id', Auth::user()->clinic_id)->orderBy('first_name')->get();
        $dentists = User::role('dentist')->where('clinic_id', Auth::user()->clinic_id)->orderBy('name')->get();

        return view('recurring-appointments.create', compact('patients', 'dentists'));
    }

    public function store(Request $request, \App\Services\RecurringAppointmentService $service)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'dentist_id' => 'required|exists:users,id',
            'frequency' => 'required|in:daily,weekly,monthly,quarterly',
            'interval_count' => 'nullable|integer|min:1',
            'days_of_week' => 'nullable|array',
            'appointment_time' => 'required',
            'type' => 'required|string|max:100',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'nullable|date|after:start_date',
        ]);

        $validated['clinic_id'] = Auth::user()->clinic_id;
        $validated['is_active'] = true;

        $recurring = RecurringAppointment::create($validated);

        // Generate appointments for the next 3 months
        $service->generateAppointments($recurring);

        return redirect()->route('clinic.recurring-appointments.index')->with('success', 'Recurring schedule created and appointments generated.');
    }

    public function show(RecurringAppointment $recurringAppointment)
    {
        $this->authorizeAccess($recurringAppointment);
        $recurringAppointment->load(['patient', 'dentist']);
        return view('recurring-appointments.show', compact('recurringAppointment'));
    }

    public function edit(RecurringAppointment $recurringAppointment)
    {
        $this->authorizeAccess($recurringAppointment);
        $patients = Patient::where('clinic_id', Auth::user()->clinic_id)->orderBy('first_name')->get();
        $dentists = User::role('dentist')->where('clinic_id', Auth::user()->clinic_id)->orderBy('name')->get();

        return view('recurring-appointments.edit', compact('recurringAppointment', 'patients', 'dentists'));
    }

    public function update(Request $request, RecurringAppointment $recurringAppointment)
    {
        $this->authorizeAccess($recurringAppointment);
        
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'dentist_id' => 'required|exists:users,id',
            'frequency' => 'required|in:daily,weekly,monthly,quarterly',
            'interval_count' => 'nullable|integer|min:1',
            'days_of_week' => 'nullable|array',
            'appointment_time' => 'required|date_format:H:i',
            'type' => 'required|string|max:100',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'is_active' => 'boolean'
        ]);

        $recurringAppointment->update($validated);

        return redirect()->route('clinic.recurring-appointments.index')->with('success', 'Recurring appointment updated successfully.');
    }

    public function destroy(RecurringAppointment $recurringAppointment)
    {
        $this->authorizeAccess($recurringAppointment);
        $recurringAppointment->delete();

        return redirect()->route('clinic.recurring-appointments.index')->with('success', 'Recurring appointment deleted successfully.');
    }

    protected function authorizeAccess(RecurringAppointment $recurringAppointment)
    {
        if ($recurringAppointment->clinic_id !== Auth::user()->clinic_id) {
            abort(403);
        }
    }
}