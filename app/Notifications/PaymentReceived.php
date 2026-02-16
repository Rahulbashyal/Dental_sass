<?php

namespace App\Notifications;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentReceived extends Notification
{
    use Queueable;

    protected $payment;

    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $invoice = $this->payment->invoice;
        $patient = $this->payment->patient;

        return (new MailMessage)
            ->subject('Payment Received: Invoice #' . ($invoice->invoice_number ?? $invoice->id))
            ->greeting('Hello!')
            ->line('A new payment has been received for your clinic.')
            ->line('**Payment Details:**')
            ->line('Patient: ' . ($patient ? $patient->full_name : 'Guest'))
            ->line('Amount: ' . $this->payment->amount . ' ' . ($this->payment->clinic->currency ?? 'NPR'))
            ->line('Method: ' . strtoupper($this->payment->payment_method))
            ->line('Transaction ID: ' . $this->payment->transaction_id)
            ->action('View Invoice', route('clinic.invoices.show', $invoice->id))
            ->line('The invoice status has been updated accordingly.');
    }

    public function toArray($notifiable)
    {
        return [
            'payment_id' => $this->payment->id,
            'invoice_id' => $this->payment->invoice_id,
            'amount' => $this->payment->amount,
            'message' => 'New ' . $this->payment->payment_method . ' payment of ' . $this->payment->amount . ' received.',
            'type' => 'success',
            'action_url' => route('clinic.invoices.show', $this->payment->invoice_id),
        ];
    }
}
