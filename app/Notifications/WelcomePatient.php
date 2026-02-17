<?php

namespace App\Notifications;

use App\Models\Patient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomePatient extends Notification implements ShouldQueue
{
    use Queueable;

    protected $patient;

    /**
     * Create a new notification instance.
     */
    public function __construct(Patient $patient)
    {
        $this->patient = $patient;
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
        $clinic = $this->patient->clinic;
        
        return (new MailMessage)
            ->subject('Welcome to ' . $clinic->name)
            ->view('emails.auth.welcome', [
                'patient' => $this->patient,
                'clinic' => $clinic
            ]);
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        return [
            'patient_id' => $this->patient->id,
            'patient_name' => $this->patient->first_name . ' ' . $this->patient->last_name,
        ];
    }
}
