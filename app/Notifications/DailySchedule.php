<?php

namespace App\Notifications;

use App\Models\User;
use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;

class DailySchedule extends Notification implements ShouldQueue
{
    use Queueable;

    protected $dentist;
    protected $appointments;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $dentist, Collection $appointments)
    {
        $this->dentist = $dentist;
        $this->appointments = $appointments;
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
        $clinic = $this->dentist->clinic;
        
        return (new MailMessage)
            ->subject('Your Schedule for ' . now()->format('F j, Y') . ' - ' . $clinic->name)
            ->view('emails.staff.dentist-schedule', [
                'dentist' => $this->dentist,
                'appointments' => $this->appointments,
                'clinic' => $clinic
            ]);
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        return [
            'dentist_id' => $this->dentist->id,
            'appointments_count' => $this->appointments->count(),
            'date' => now()->format('Y-m-d'),
        ];
    }
}
