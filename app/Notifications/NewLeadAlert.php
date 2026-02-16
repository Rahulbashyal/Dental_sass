<?php

namespace App\Notifications;

use App\Models\Lead;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewLeadAlert extends Notification
{
    use Queueable;

    protected $lead;

    public function __construct(Lead $lead)
    {
        $this->lead = $lead;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Patient Lead Received!')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('You have received a new lead from your clinic\'s landing page.')
            ->line('**Lead Details:**')
            ->line('Name: ' . $this->lead->name)
            ->line('Email: ' . $this->lead->email)
            ->line('Phone: ' . ($this->lead->phone ?? 'Not provided'))
            ->line('Message: ' . ($this->lead->message ?? 'No message provided'))
            ->action('View All Leads', route('clinic.crm.leads.index'))
            ->line('Prompt follow-up increases conversion rates!');
    }

    public function toArray($notifiable)
    {
        return [
            'lead_id' => $this->lead->id,
            'lead_name' => $this->lead->name,
            'message' => 'New lead received from landing page: ' . $this->lead->name,
            'type' => 'info',
            'action_url' => route('clinic.crm.leads.index'),
        ];
    }
}
