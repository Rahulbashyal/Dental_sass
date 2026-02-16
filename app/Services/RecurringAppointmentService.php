<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\RecurringAppointment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RecurringAppointmentService
{
    /**
     * Generate appointments for a recurring schedule.
     *
     * @param RecurringAppointment $recurringAppointment
     * @param int $monthsAhead How many months ahead to generate
     * @return void
     */
    public function generateAppointments(RecurringAppointment $recurringAppointment, int $monthsAhead = 3)
    {
        $startDate = Carbon::parse($recurringAppointment->start_date);
        $endDate = $recurringAppointment->end_date 
            ? Carbon::parse($recurringAppointment->end_date) 
            : $startDate->copy()->addMonths($monthsAhead);

        // Ensure we don't generate too far into the future if no end date
        $maxDate = Carbon::now()->addMonths($monthsAhead);
        if ($endDate->gt($maxDate)) {
            $endDate = $maxDate;
        }

        $currentDate = $startDate->copy();
        
        DB::transaction(function () use ($recurringAppointment, $currentDate, $endDate) {
            while ($currentDate->lte($endDate)) {
                if ($this->shouldCreateAppointment($recurringAppointment, $currentDate)) {
                    $this->createAppointment($recurringAppointment, $currentDate);
                }
                $currentDate->addDay();
            }
        });
    }

    protected function shouldCreateAppointment(RecurringAppointment $recurring, Carbon $date): bool
    {
        // Frequency check
        switch ($recurring->frequency) {
            case 'daily':
                return true;
            case 'weekly':
                return in_array($date->dayOfWeek, $recurring->days_of_week ?? []);
            case 'monthly':
                // For simplicity, same day of month as start date
                $startDay = Carbon::parse($recurring->start_date)->day;
                return $date->day === $startDay;
            case 'quarterly':
                $startDate = Carbon::parse($recurring->start_date);
                return $date->day === $startDate->day && $date->diffInMonths($startDate) % 3 === 0;
            default:
                return false;
        }
    }

    protected function createAppointment(RecurringAppointment $recurring, Carbon $date)
    {
        // Don't create if it already exists for this recurring ID and date
        $exists = Appointment::where('recurring_appointment_id', $recurring->id)
            ->whereDate('appointment_date', $date)
            ->exists();

        if ($exists) {
            return;
        }

        Appointment::create([
            'clinic_id' => $recurring->clinic_id,
            'patient_id' => $recurring->patient_id,
            'dentist_id' => $recurring->dentist_id,
            'recurring_appointment_id' => $recurring->id,
            'appointment_date' => $date->toDateString(),
            'appointment_time' => $recurring->appointment_time,
            'type' => $recurring->type,
            'status' => 'scheduled',
            'notes' => 'Auto-generated from recurring schedule #' . $recurring->id
        ]);
    }
}
