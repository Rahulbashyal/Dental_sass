<?php

namespace App\Notifications;

use App\Models\Clinic;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubscriptionExpiryAlert extends Notification
{
    use Queueable;

    protected $clinic;
    protected $daysLeft;

    public function __construct(Clinic $clinic, int $daysLeft)
    {
        $this->clinic = $clinic;
        $this->daysLeft = $daysLeft;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $subject = $this->daysLeft <= 0 
            ? 'URGENT: Your Subscription has Expired' 
            : 'Subscription Expiry Notice: ' . $this->daysLeft . ' Days Remaining';

        $message = (new MailMessage)
            ->subject($subject)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('This is an automated notice regarding your clinic\'s subscription plan for **' . $this->clinic->name . '**.');

        if ($this->daysLeft <= 0) {
            $message->line('Your subscription has **EXPIRED**. Access to some clinical features may be restricted until you renew.')
                   ->error();
        } else {
            $message->line('Your subscription will expire in **' . $this->daysLeft . ' days** (' . $this->clinic->subscription_expires_at->format('F j, Y') . ').');
        }

        return $message
            ->line('To ensure uninterrupted service, please renew your plan now.')
            ->action('Renew Subscription', route('subscriptions.plans'))
            ->line('Thank you for being with us!');
    }

    public function toArray($notifiable)
    {
        return [
            'clinic_id' => $this->clinic->id,
            'days_left' => $this->daysLeft,
            'message' => $this->daysLeft <= 0 
                ? 'Your subscription has expired. Please renew.' 
                : 'Subscription expires in ' . $this->daysLeft . ' days.',
            'type' => $this->daysLeft <= 3 ? 'danger' : 'warning',
            'action_url' => route('subscriptions.plans'),
        ];
    }
}
