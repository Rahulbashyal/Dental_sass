<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentApiController extends Controller
{
    public function index(Request $request)
    {
        $appointments = Appointment::where('clinic_id', $request->user()->clinic_id)
            ->with(['patient:id,first_name,last_name', 'dentist:id,name'])
            ->when($request->date, fn($q) => $q->whereDate('appointment_date', $request->date))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->paginate(20);
            
        return response()->json($appointments);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'dentist_id' => 'required|exists:users,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required|date_format:H:i',
            'type' => 'required|string|max:100',
        ]);

        $validated['clinic_id'] = $request->user()->clinic_id;
        $validated['status'] = 'scheduled';
        
        $appointment = Appointment::create($validated);
        
        return response()->json($appointment->load(['patient', 'dentist']), 201);
    }

    public function show(Appointment $appointment)
    {
        return response()->json($appointment->load(['patient', 'dentist', 'clinic']));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'status' => 'sometimes|in:scheduled,confirmed,in_progress,completed,cancelled,no_show',
            'notes' => 'sometimes|string|max:1000',
        ]);
        
        $appointment->update($validated);
        
        return response()->json($appointment->load(['patient', 'dentist']));
    }
}
