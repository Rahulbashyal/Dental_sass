<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DailyFinancialSummary extends Notification implements ShouldQueue
{
    use Queueable;

    protected $summary;

    /**
     * Create a new notification instance.
     */
    public function __construct(array $summary)
    {
        $this->summary = $summary;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        $clinic = $notifiable->clinic;
        
        return (new MailMessage)
            ->subject('Daily Clinic Summary - ' . ($this->summary['date'] ?? now()->format('F j, Y')))
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Here is the daily overview for **' . ($clinic->name ?? 'your clinic') . '**.')
            ->line('**Key Performance Indicators:**')
            ->line('- Total Revenue Collected: ' . ($this->summary['revenue'] ?? 0) . ' ' . ($clinic->currency ?? 'NPR'))
            ->line('- New Patients Registered: ' . ($this->summary['new_patients'] ?? 0))
            ->line('- New Appointments Scheduled: ' . ($this->summary['new_appointments'] ?? 0))
            ->line('- Appointments Completed: ' . ($this->summary['completed_appointments'] ?? 0))
            ->line('- New Leads Generated: ' . ($this->summary['total_leads'] ?? 0))
            ->action('View Full Analytics', route('clinic.analytics.dashboard'))
            ->line('Keep up the great work!');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        return [
            'date' => $this->summary['date'] ?? now()->format('Y-m-d'),
            'revenue' => $this->summary['revenue'] ?? 0,
            'message' => 'Daily summary for ' . ($this->summary['date'] ?? 'today') . ' is ready.',
            'type' => 'info',
            'action_url' => route('clinic.analytics.dashboard'),
        ];
    }
}
