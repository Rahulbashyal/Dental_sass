<?php

namespace App\Services;

use App\Models\Waitlist;
use App\Models\Appointment;
use Illuminate\Support\Facades\Log;

class WaitlistService
{
    /**
     * Check waitlist for potential candidates when an appointment is cancelled.
     */
    public function notifyNextInLine(Appointment $cancelledAppointment)
    {
        // Find suitable candidates
        $candidates = Waitlist::where('clinic_id', $cancelledAppointment->clinic_id)
            ->where('status', 'waiting')
            ->where(function($q) use ($cancelledAppointment) {
                $q->where('preferred_date', $cancelledAppointment->appointment_date)
                  ->orWhereNull('preferred_date');
            })
            ->orderBy('priority', 'desc')
            ->orderBy('created_at', 'asc')
            ->take(3)
            ->get();

        if ($candidates->isEmpty()) {
            return;
        }

        foreach ($candidates as $candidate) {
            // Send Notification / SMS
            $message = "Greetings from {$cancelledAppointment->clinic->name}. A slot is now open on {$cancelledAppointment->appointment_date->format('M d')} at {$cancelledAppointment->appointment_time->format('H:i')}. Please call us to secure it.";
            
            CommunicationService::notify($candidate->patient, 'Appointment Slot Available', $message, 'appointment_alert');
            CommunicationService::sms($candidate->patient, $message);
            
            $candidate->update(['status' => 'contacted']);
        }

        Log::info("Waitlist notified for cancelled appointment ID: {$cancelledAppointment->id}");
    }
}
