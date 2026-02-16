<?php

namespace Modules\Appointments\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;
use App\Models\Branch;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->get('date', Carbon::today()->toDateString());
        $branchId = $request->get('branch_id');

        $query = Appointment::with(['patient', 'dentist', 'branch'])
            ->whereDate('appointment_date', $date);

        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        $appointments = $query->orderBy('appointment_time')->get();
        $branches = Branch::where('is_active', true)->get();

        return view('appointments::index', compact('appointments', 'date', 'branches', 'branchId'));
    }

    /**
     * API: Fetch events for FullCalendar
     */
    public function events(Request $request)
    {
        $start = $request->get('start');
        $end = $request->get('end');
        $branchId = $request->get('branch_id');

        $query = Appointment::with(['patient', 'dentist'])
            ->whereBetween('appointment_date', [
                Carbon::parse($start)->toDateString(),
                Carbon::parse($end)->toDateString()
            ]);

        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        $appointments = $query->get();

        $events = $appointments->map(function ($app) {
            $startDateTime = Carbon::parse($app->appointment_date->format('Y-m-d') . ' ' . $app->appointment_time->format('H:i:s'));
            $endDateTime = (clone $startDateTime)->addMinutes($app->duration);

            $color = match($app->status) {
                'scheduled' => '#3b82f6', // blue
                'confirmed' => '#6366f1', // indigo
                'arrived' => '#f59e0b',   // amber
                'in_progress' => '#8b5cf6', // violet
                'completed' => '#10b981',  // emerald
                'cancelled' => '#ef4444',  // red
                default => '#64748b'       // slate
            };

            return [
                'id' => $app->id,
                'title' => $app->patient->full_name . ' (' . $app->type . ')',
                'start' => $startDateTime->toIso8601String(),
                'end' => $endDateTime->toIso8601String(),
                'backgroundColor' => $color,
                'borderColor' => $color,
                'extendedProps' => [
                    'dentist' => $app->dentist->name,
                    'status' => $app->status
                ]
            ];
        });

        return response()->json($events);
    }

    public function calendar()
    {
        $branches = Branch::where('is_active', true)->get();
        return view('appointments::calendar', compact('branches'));
    }

    public function create(Request $request)
    {
        $patients = Patient::where('is_active', true)->get();
        $dentists = User::role('dentist')->where('is_active', true)->get();
        $branches = Branch::where('is_active', true)->get();
        
        $selectedPatient = null;
        if ($request->has('patient_id')) {
            $selectedPatient = Patient::find($request->patient_id);
        }

        return view('appointments::create', compact('patients', 'dentists', 'branches', 'selectedPatient'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'dentist_id' => 'required|exists:users,id',
            'branch_id' => 'required|exists:branches,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required',
            'duration' => 'required|integer|min:15',
            'type' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        // Check for conflicts
        $startTime = Carbon::parse($validated['appointment_date'] . ' ' . $validated['appointment_time']);
        $endTime = (clone $startTime)->addMinutes($validated['duration']);

        $conflict = Appointment::where('dentist_id', $validated['dentist_id'])
            ->where('appointment_date', $validated['appointment_date'])
            ->whereIn('status', ['scheduled', 'confirmed', 'arrived', 'in_progress'])
            ->where(function ($query) use ($startTime, $endTime) {
                $query->where(function ($q) use ($startTime, $endTime) {
                    // Check if new appointment starts during existing one
                    $q->whereRaw("CONCAT(appointment_date, ' ', appointment_time) < ?", [$endTime->toDateTimeString()])
                      ->whereRaw("DATE_ADD(CONCAT(appointment_date, ' ', appointment_time), INTERVAL duration MINUTE) > ?", [$startTime->toDateTimeString()]);
                });
            })->exists();
            
        if ($conflict) {
            return back()->withInput()->withErrors(['appointment_time' => 'This dentist already has an overlapping appointment at this time.']);
        }
        
        $appointment = Appointment::create(array_merge($validated, [
            'status' => 'scheduled',
            'clinic_id' => tenant()->clinic->id
        ]));

        return redirect()->route('clinic.appointments.index', ['date' => $validated['appointment_date']])
            ->with('status', 'Appointment scheduled successfully.');
    }

    public function updateStatus(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:scheduled,confirmed,arrived,in_progress,completed,cancelled,no_show'
        ]);

        $appointment->update(['status' => $validated['status']]);

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return back()->with('status', "Appointment status updated to {$validated['status']}.");
    }
}
