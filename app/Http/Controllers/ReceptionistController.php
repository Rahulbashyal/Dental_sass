<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ReceptionistController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:receptionist|clinic_admin']);
    }

    public function checkIn(Request $request, Appointment $appointment)
    {
        $appointment->update([
            'status' => 'checked_in',
            'checked_in_at' => now()
        ]);

        return back()->with('success', 'Patient checked in successfully.');
    }

    public function noShow(Request $request, Appointment $appointment)
    {
        $appointment->update(['status' => 'no_show']);
        return back()->with('success', 'Appointment marked as no-show.');
    }

    public function createEmergencyAppointment()
    {
        return view('receptionist.emergency-appointment');
    }

    public function emergencyAppointment(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'dentist_id' => 'required|exists:users,id',
            'notes' => 'required|string|max:500'
        ]);

        $appointment = Appointment::create([
            'clinic_id' => Auth::user()->clinic_id,
            'patient_id' => $validated['patient_id'],
            'dentist_id' => $validated['dentist_id'],
            'appointment_date' => today(),
            'appointment_time' => now()->format('H:i'),
            'type' => 'emergency',
            'status' => 'confirmed',
            'notes' => $validated['notes']
        ]);

        return redirect()->route('dashboard')->with('success', 'Emergency appointment created successfully.');
    }

    public function todaySchedule()
    {
        $appointments = Appointment::where('clinic_id', Auth::user()->clinic_id)
            ->whereDate('appointment_date', today())
            ->with(['patient', 'dentist'])
            ->orderBy('appointment_time')
            ->get();

        return view('receptionist.today-schedule', compact('appointments'));
    }

    public function patientHistory(Patient $patient)
    {
        $appointments = $patient->appointments()
            ->with('dentist')
            ->orderBy('appointment_date', 'desc')
            ->paginate(10);

        return view('receptionist.patient-history', compact('patient', 'appointments'));
    }

    public function quickSearch(Request $request)
    {
        $request->validate([
            'q' => 'required|string|max:100'
        ]);
        
        $query = $request->get('q');
        
        $patients = Patient::where('clinic_id', Auth::user()->clinic_id)
            ->where(function($q) use ($query) {
                $q->where('first_name', 'like', '%' . $query . '%')
                  ->orWhere('last_name', 'like', '%' . $query . '%')
                  ->orWhere('phone', 'like', '%' . $query . '%')
                  ->orWhere('patient_id', 'like', '%' . $query . '%');
            })
            ->limit(10)
            ->get(['id', 'first_name', 'last_name', 'phone', 'patient_id']);

        return response()->json($patients);
    }

    public function printSchedule()
    {
        $appointments = Appointment::where('clinic_id', Auth::user()->clinic_id)
            ->whereDate('appointment_date', today())
            ->with(['patient', 'dentist'])
            ->orderBy('appointment_time')
            ->get();

        return view('receptionist.print-schedule', compact('appointments'));
    }
}