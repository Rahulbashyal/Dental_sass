<?php

namespace App\Notifications;

use App\Models\Clinic;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ClinicProvisioned extends Notification
{
    use Queueable;

    protected $clinic;
    protected $password;

    public function __construct(Clinic $clinic, string $password)
    {
        $this->clinic = $clinic;
        $this->password = $password;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Your Clinic Control Center is Ready!')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Congratulations! Your clinic, **' . $this->clinic->name . '**, has been successfully provisioned and is ready for use.')
            ->line('You can now log in to manage your appointments, patients, and clinical operations.')
            ->action('Login to Control Center', route('login'))
            ->line('**Your Credentials:**')
            ->line('Email: ' . $notifiable->email)
            ->line('Initial Password: ' . $this->password)
            ->line('For security reasons, we recommend changing your password after your first login.')
            ->line('Thank you for choosing our platform!');
    }

    public function toArray($notifiable)
    {
        return [
            'clinic_id' => $this->clinic->id,
            'clinic_name' => $this->clinic->name,
            'message' => 'Your clinic has been successfully provisioned.',
            'type' => 'success',
            'action_url' => route('dashboard'),
        ];
    }
}
