<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;
use App\Http\Requests\{StoreAppointmentRequest, UpdateAppointmentRequest, BookAppointmentRequest};
use App\Services\AppointmentConflictService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['book']);
        $this->middleware('clinic.access')->except(['book']);
    }
    public function index()
    {
        $user = Auth::user();
        
        if (!$user->clinic_id) {
            return redirect()->route('dashboard')->with('error', 'Access denied. No clinic assigned.');
        }

        $appointments = Appointment::where('clinic_id', $user->clinic_id)
            ->with(['patient:id,first_name,last_name,patient_id', 'dentist:id,name'])
            ->select('id', 'patient_id', 'dentist_id', 'appointment_date', 'appointment_time', 'type', 'status', 'clinic_id')
            ->latest('appointment_date')
            ->paginate(15);
            
        $statusColors = [
            'scheduled' => 'from-blue-500 to-blue-600',
            'confirmed' => 'from-green-500 to-green-600',
            'in_progress' => 'from-yellow-500 to-yellow-600',
            'completed' => 'from-emerald-500 to-emerald-600',
            'cancelled' => 'from-red-500 to-red-600',
            'no_show' => 'from-gray-500 to-gray-600',
            'pending' => 'from-orange-500 to-orange-600'
        ];
        
        $statusIcons = [
            'scheduled' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
            'confirmed' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
            'in_progress' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
            'completed' => 'M5 13l4 4L19 7',
            'cancelled' => 'M6 18L18 6M6 6l12 12',
            'no_show' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.268 16.5c-.77.833.192 2.5 1.732 2.5z',
            'pending' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'
        ];
            
        return view('appointments.index', compact('appointments', 'statusColors', 'statusIcons'));
    }

    public function create()
    {
        $user = Auth::user();
        
        if (!$user->clinic_id) {
            return redirect()->route('dashboard')->with('error', 'Access denied. No clinic assigned.');
        }

        $patients = Patient::where('clinic_id', $user->clinic_id)->get();
        return view('appointments.create', compact('patients'));
    }

    public function store(StoreAppointmentRequest $request)
    {
        $user = Auth::user();
        
        if (!$user->clinic_id) {
            return redirect()->route('dashboard')->with('error', 'Access denied. No clinic assigned.');
        }

        $validated = $request->validated();

        // Set default dentist_id to current user if not provided
        if (empty($validated['dentist_id'])) {
            $validated['dentist_id'] = $user->id;
        }

        // Check for conflicts
        $conflictService = new AppointmentConflictService();
        $conflicts = $conflictService->checkConflicts(
            $user->clinic_id,
            $validated['dentist_id'],
            $validated['appointment_date'],
            $validated['appointment_time']
        );

        if (!empty($conflicts)) {
            return back()->withErrors(['conflict' => $conflicts[0]['message']])->withInput();
        }

        $validated['clinic_id'] = $user->clinic_id;
        $validated['status'] = 'scheduled';

        $appointment = Appointment::create($validated);
        
        // --- High-Performance Alerting Suite ---
        
        // 1. Patient Notification (Unified)
        if ($appointment->patient) {
            \App\Services\CommunicationService::notify(
                $appointment->patient,
                'Appointment Scheduled',
                "Your clinical session for " . ucfirst($appointment->type) . " is scheduled for " . $appointment->appointment_date->format('M d, Y') . " at " . $appointment->appointment_time,
                'appointment',
                ['appointment_id' => $appointment->id]
            );

            if ($appointment->patient->email) {
                \App\Services\CommunicationService::email(
                    $appointment->patient,
                    'Appointment Confirmation - ' . $user->clinic->name,
                    'emails.appointment-confirmation',
                    ['appointment' => $appointment]
                );
            }
        }
        
        // 2. Staff Internal Alert (Internal Mesh)
        $receptionists = \App\Models\User::role('receptionist')
            ->where('clinic_id', $user->clinic_id)
            ->get();
            
        foreach ($receptionists as $receptionist) {
            \App\Services\CommunicationService::notify(
                $receptionist,
                'New Appointment Logged',
                "Patient " . $appointment->patient->full_name . " has been scheduled for " . $appointment->appointment_date->format('M d'),
                'system',
                ['appointment_id' => $appointment->id]
            );
        }

        return redirect()->route('clinic.appointments.index')->with('success', 'Appointment scheduled successfully. Confirmation email sent.');
    }

    public function show(Appointment $appointment)
    {
        // Ensure user can only access appointments from their clinic
        if ($appointment->clinic_id !== Auth::user()->clinic_id) {
            abort(403, 'Unauthorized access to appointment.');
        }
        
        return view('appointments.show', compact('appointment'));
    }

    public function edit(Appointment $appointment)
    {
        // Ensure user can only edit appointments from their clinic
        if ($appointment->clinic_id !== Auth::user()->clinic_id) {
            abort(403, 'Unauthorized access to appointment.');
        }
        
        $patients = Auth::user()->clinic->patients;
        $dentists = Auth::user()->clinic->users()->role('dentist')->get();
        return view('appointments.edit', compact('appointment', 'patients', 'dentists'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        // Ensure user can only update appointments from their clinic
        if ($appointment->clinic_id !== Auth::user()->clinic_id) {
            abort(403, 'Unauthorized access to appointment.');
        }
        
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'dentist_id' => 'required|exists:users,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required|date_format:H:i',
            'type' => 'required|string|max:100',
            'status' => 'required|in:scheduled,confirmed,in_progress,completed,cancelled,no_show',
            'notes' => 'nullable|string|max:1000',
        ]);
        
        // Verify patient belongs to this clinic using secure Eloquent queries
        $patient = Patient::where('clinic_id', Auth::user()->clinic_id)
            ->find($validated['patient_id']);
        if (!$patient) {
            return back()->withErrors(['patient_id' => 'Invalid patient selection.']);
        }
        
        // Verify dentist belongs to this clinic using secure Eloquent queries
        $dentist = User::where('clinic_id', Auth::user()->clinic_id)
            ->find($validated['dentist_id']);
        if (!$dentist) {
            return back()->withErrors(['dentist_id' => 'Invalid dentist selection.']);
        }

        $appointment->update($validated);

        return redirect()->route('clinic.appointments.index')->with('success', 'Appointment updated successfully.');
    }

    public function destroy(Appointment $appointment, \App\Services\WaitlistService $waitlistService)
    {
        // Ensure user can only delete appointments from their clinic
        if ($appointment->clinic_id !== Auth::user()->clinic_id) {
            abort(403, 'Unauthorized access to appointment.');
        }
        
        $backup = $appointment;
        $appointment->delete();

        // Trigger Waitlist Engine
        $waitlistService->notifyNextInLine($backup);

        return redirect()->route('clinic.appointments.index')->with('success', 'Appointment cancelled and waitlist notified.');
    }

    public function book(Request $request, $clinic = null)
    {
        if ($request->isMethod('get')) {
            $clinic = \App\Models\Clinic::where('slug', $clinic)->firstOrFail();
            $dentists = $clinic->users()->role('dentist')->get();
            return view('appointments.book', compact('clinic', 'dentists'));
        }

        $clinic = \App\Models\Clinic::where('slug', $clinic)->firstOrFail();
        
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'appointment_date' => 'required|date|after:today',
            'appointment_time' => 'required|date_format:H:i',
            'dentist_id' => 'required|exists:users,id',
            'type' => 'required|string|max:100',
        ]);
        
        // Verify dentist belongs to this clinic using secure Eloquent queries
        $dentist = \App\Models\User::where('clinic_id', $clinic->id)
            ->find($validated['dentist_id']);
            
        if (!$dentist) {
            return back()->withErrors(['dentist_id' => 'Invalid dentist selection.']);
        }

        // Create or find patient
        $patient = \App\Models\Patient::firstOrCreate(
            [
                'email' => $validated['email'], 
                'clinic_id' => $clinic->id
            ],
            [
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'phone' => $validated['phone'],
                'patient_id' => 'P' . str_pad(\App\Models\Patient::where('clinic_id', $clinic->id)->count() + 1, 6, '0', STR_PAD_LEFT),
                'clinic_id' => $clinic->id,
            ]
        );

        // Create appointment
        $appointment = \App\Models\Appointment::create([
            'clinic_id' => $clinic->id,
            'patient_id' => $patient->id,
            'dentist_id' => $validated['dentist_id'],
            'appointment_date' => $validated['appointment_date'],
            'appointment_time' => $validated['appointment_time'],
            'type' => $validated['type'],
            'status' => 'scheduled',
        ]);
        
        // Send welcome email if new patient
        if ($patient->wasRecentlyCreated && $patient->email) {
            $patient->notify(new \App\Notifications\WelcomePatient($patient));
        }
        
        // Send appointment confirmation
        if ($patient->email) {
            $patient->notify(new \App\Notifications\AppointmentConfirmation($appointment));
        }

        return redirect()->route('clinic.landing', $clinic->slug)->with('success', 'Appointment booked successfully! Check your email for confirmation.');
    }

    public function publicBook($slug)
    {
        $clinic = \App\Models\Clinic::where('slug', $slug)->firstOrFail();
        $dentists = $clinic->users()->role('dentist')->get();
        return view('appointments.book', compact('clinic', 'dentists'));
    }

    public function storePublicBook(Request $request, $slug)
    {
        $clinic = \App\Models\Clinic::where('slug', $slug)->firstOrFail();
        
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'appointment_date' => 'required|date|after:today',
            'appointment_time' => 'required|date_format:H:i',
            'dentist_id' => 'nullable|exists:users,id',
            'type' => 'required|string|max:100',
        ]);
        
        // Create or find patient
        $patient = \App\Models\Patient::firstOrCreate(
            ['email' => $validated['email'], 'clinic_id' => $clinic->id],
            [
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'phone' => $validated['phone'],
                'patient_id' => 'P' . str_pad(\App\Models\Patient::where('clinic_id', $clinic->id)->count() + 1, 6, '0', STR_PAD_LEFT),
                'clinic_id' => $clinic->id,
            ]
        );

        // Create appointment
        $appointment = \App\Models\Appointment::create([
            'clinic_id' => $clinic->id,
            'patient_id' => $patient->id,
            'dentist_id' => $validated['dentist_id'] ?? $clinic->users()->role('dentist')->first()?->id,
            'appointment_date' => $validated['appointment_date'],
            'appointment_time' => $validated['appointment_time'],
            'type' => $validated['type'],
            'status' => 'scheduled',
        ]);
        
        // Send welcome email if new patient
        if ($patient->wasRecentlyCreated && $patient->email) {
            $patient->notify(new \App\Notifications\WelcomePatient($patient));
        }
        
        // Send appointment confirmation
        if ($patient->email) {
            $patient->notify(new \App\Notifications\AppointmentConfirmation($appointment));
        }

        return redirect()->route('clinic.landing', $clinic->slug)->with('success', 'Appointment booked successfully! Check your email for confirmation.');
    }
}