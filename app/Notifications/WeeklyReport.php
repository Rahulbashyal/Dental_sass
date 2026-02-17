<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WeeklyReport extends Notification implements ShouldQueue
{
    use Queueable;

    protected $admin;
    protected $summary;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $admin, array $summary)
    {
        $this->admin = $admin;
        $this->summary = $summary;
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
        $clinic = $this->admin->clinic;
        
        return (new MailMessage)
            ->subject('Weekly Clinic Report - Week Ending ' . now()->format('F j, Y') . ' - ' . $clinic->name)
            ->view('emails.staff.weekly-report', [
                'admin' => $this->admin,
                'summary' => $this->summary,
                'clinic' => $clinic
            ]);
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        return [
            'week_ending' => now()->format('Y-m-d'),
            'total_appointments' => $this->summary['total_appointments'] ?? 0,
            'total_revenue' => $this->summary['total_revenue'] ?? 0,
        ];
    }
}
