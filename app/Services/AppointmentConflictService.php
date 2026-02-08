<?php

namespace App\Services;

use App\Models\Appointment;
use Carbon\Carbon;

class AppointmentConflictService
{
    public function checkConflicts($clinicId, $dentistId, $appointmentDate, $appointmentTime, $excludeId = null)
    {
        $conflicts = [];
        $dateTime = Carbon::parse($appointmentDate . ' ' . $appointmentTime);
        
        // Check for time overlaps (assuming 30-minute appointments)
        $startTime = $dateTime->copy()->subMinutes(29);
        $endTime = $dateTime->copy()->addMinutes(29);
        
        $existingAppointments = Appointment::where('clinic_id', $clinicId)
            ->where('dentist_id', $dentistId)
            ->whereDate('appointment_date', $appointmentDate)
            ->whereBetween('appointment_time', [$startTime->format('H:i'), $endTime->format('H:i')])
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->with('patient')
            ->get();

        foreach ($existingAppointments as $appointment) {
            $conflicts[] = [
                'type' => 'time_overlap',
                'message' => "Conflicts with {$appointment->patient->first_name} {$appointment->patient->last_name} at {$appointment->appointment_time}",
                'appointment_id' => $appointment->id
            ];
        }

        return $conflicts;
    }

    public function getDentistAvailability($clinicId, $dentistId, $date)
    {
        $appointments = Appointment::where('clinic_id', $clinicId)
            ->where('dentist_id', $dentistId)
            ->whereDate('appointment_date', $date)
            ->orderBy('appointment_time')
            ->get(['appointment_time']);

        $bookedSlots = $appointments->pluck('appointment_time')->map(function($time) {
            return Carbon::parse($time)->format('H:i');
        })->toArray();

        // Generate available slots (9 AM to 5 PM, 30-minute intervals)
        $availableSlots = [];
        $start = Carbon::parse('09:00');
        $end = Carbon::parse('17:00');

        while ($start < $end) {
            $timeSlot = $start->format('H:i');
            if (!in_array($timeSlot, $bookedSlots)) {
                $availableSlots[] = $timeSlot;
            }
            $start->addMinutes(30);
        }

        return $availableSlots;
    }
}