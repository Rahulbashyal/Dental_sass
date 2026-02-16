<?php

namespace App\Notifications;

use App\Models\PaymentInstallment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InstallmentReminder extends Notification implements ShouldQueue
{
    use Queueable;

    protected $installment;

    /**
     * Create a new notification instance.
     */
    public function __construct(PaymentInstallment $installment)
    {
        $this->installment = $installment;
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
        $plan = $this->installment->paymentPlan;
        $clinic = $plan->clinic;
        
        return (new MailMessage)
            ->subject('Payment Reminder: Installment #' . $this->installment->installment_number . ' - ' . $clinic->name)
            ->greeting('Hello ' . $notifiable->full_name . ',')
            ->line('This is a reminder that you have an upcoming or overdue payment installment for your treatment plan.')
            ->line('Installment #: ' . $this->installment->installment_number)
            ->line('Amount Due: ' . ($clinic->currency ?? '$') . number_format($this->installment->amount, 2))
            ->line('Due Date: ' . $this->installment->due_date->format('M d, Y'))
            ->action('View Payment Plan', route('payment-plans.show', $plan))
            ->line('Thank you for choosing ' . $clinic->name . ' for your dental care!');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        return [
            'type' => 'installment_reminder',
            'installment_id' => $this->installment->id,
            'plan_id' => $this->installment->payment_plan_id,
            'amount' => $this->installment->amount,
            'due_date' => $this->installment->due_date->toDateTimeString(),
        ];
    }
}
