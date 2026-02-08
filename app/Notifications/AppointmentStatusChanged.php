<?php

namespace App\Notifications;

use App\Models\Appointment;
use App\Helpers\NepaliDateHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentStatusChanged extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Appointment $appointment, public string $oldStatus)
    {
        //
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $nepaliDate = NepaliDateHelper::convertToNepaliDate($this->appointment->appointment_date);
        $formattedNepaliDate = $nepaliDate['formatted'] ?? $this->appointment->appointment_date->format('M d, Y');
        
        return (new MailMessage)
            ->subject('Appointment Status Updated')
            ->line("Your appointment status has been changed from {$this->oldStatus} to {$this->appointment->status}.")
            ->line("Appointment Date: {$this->appointment->appointment_date->format('M d, Y')} ({$formattedNepaliDate})")
            ->line("Time: {$this->appointment->appointment_time}")
            ->action('View Appointment', url('/patient/appointments'))
            ->line('Thank you for choosing our dental care services!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'appointment_id' => $this->appointment->id,
            'old_status' => $this->oldStatus,
            'new_status' => $this->appointment->status,
            'message' => "Appointment status changed from {$this->oldStatus} to {$this->appointment->status}"
        ];
    }
}
