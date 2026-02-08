<?php

namespace App\Notifications;

use App\Models\User;
use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewAppointmentAlert extends Notification implements ShouldQueue
{
    use Queueable;

    protected $appointment;

    /**
     * Create a new notification instance.
     */
    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        $clinic = $this->appointment->clinic;
        
        return (new MailMessage)
            ->subject('New Appointment Alert - ' . $clinic->name)
            ->view('emails.staff.new-appointment-alert', [
                'receptionist' => $notifiable,
                'appointment' => $this->appointment,
                'clinic' => $clinic
            ]);
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        return [
            'appointment_id' => $this->appointment->id,
            'patient_name' => $this->appointment->patient->first_name . ' ' . $this->appointment->patient->last_name,
            'appointment_date' => $this->appointment->appointment_date,
            'appointment_time' => $this->appointment->appointment_time,
        ];
    }
}
