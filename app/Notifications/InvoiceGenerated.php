<?php

namespace App\Notifications;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceGenerated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $invoice;

    /**
     * Create a new notification instance.
     */
    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
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
        $clinic = $this->invoice->clinic;
        
        // Generate PDF (assuming you have an invoice PDF view)
        $pdf = Pdf::loadView('invoices.pdf', [
            'invoice' => $this->invoice
        ]);
        
        $pdfContent = $pdf->output();
        $pdfFilename = 'invoice_' . $this->invoice->invoice_number . '.pdf';
        
        return (new MailMessage)
            ->subject('Invoice #' . $this->invoice->invoice_number . ' - ' . $clinic->name)
            ->view('emails.invoices.generated', [
                'invoice' => $this->invoice,
                'clinic' => $clinic,
                'patient' => $notifiable
            ])
            ->attachData($pdfContent, $pdfFilename, [
                'mime' => 'application/pdf',
            ]);
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        return [
            'invoice_id' => $this->invoice->id,
            'invoice_number' => $this->invoice->invoice_number,
            'total_amount' => $this->invoice->total_amount,
            'status' => $this->invoice->status,
        ];
    }
}
