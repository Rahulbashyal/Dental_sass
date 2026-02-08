<?php

namespace App\Jobs;

use App\Models\Appointment;
use App\Services\NepaliCalendarService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SendAppointmentReminder implements ShouldQueue
{
    use Queueable;

    public function __construct()
    {
        //
    }

    public function handle(): void
    {
        // Send reminders for appointments tomorrow
        $tomorrow = Carbon::tomorrow();
        
        $appointments = Appointment::with(['patient', 'clinic'])
            ->whereDate('appointment_date', $tomorrow)
            ->where('status', 'scheduled')
            ->get();

        foreach ($appointments as $appointment) {
            // Get Nepali date for the appointment
            $nepaliDate = NepaliCalendarService::englishToNepali($appointment->appointment_date);
            
            // Email reminder using template
            Mail::send('emails.appointment-reminder', [
                'appointment' => $appointment,
                'patient' => $appointment->patient,
                'clinic' => $appointment->clinic,
                'nepaliDate' => $nepaliDate
            ], function ($message) use ($appointment) {
                $message->to($appointment->patient->email)
                       ->subject('Appointment Reminder - ' . $appointment->clinic->name);
            });
            
            // Log the reminder
            Log::info("Reminder sent to {$appointment->patient->email} for appointment {$appointment->id}");
        }
    }
}
