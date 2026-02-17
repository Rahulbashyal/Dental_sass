<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DailyFinancialSummary extends Notification implements ShouldQueue
{
    use Queueable;

    protected $accountant;
    protected $summary;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $accountant, array $summary)
    {
        $this->accountant = $accountant;
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
        $clinic = $this->accountant->clinic;
        
        return (new MailMessage)
            ->subject('Daily Financial Summary - ' . now()->format('F j, Y') . ' - ' . $clinic->name)
            ->view('emails.staff.financial-summary', [
                'accountant' => $this->accountant,
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
            'date' => now()->format('Y-m-d'),
            'total_revenue' => $this->summary['total_revenue'] ?? 0,
            'invoices_generated' => $this->summary['invoices_generated'] ?? 0,
        ];
    }
}
