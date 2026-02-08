<?php

namespace App\Notifications;

use App\Models\Prescription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Barryvdh\DomPDF\Facade\Pdf;

class PrescriptionIssued extends Notification implements ShouldQueue
{
    use Queueable;

    protected $prescription;

    /**
     * Create a new notification instance.
     */
    public function __construct(Prescription $prescription)
    {
        $this->prescription = $prescription;
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
        $clinic = $this->prescription->clinic;
        
        // Generate PDF
        $pdf = Pdf::loadView('prescriptions.pdf', [
            'prescription' => $this->prescription
        ]);
        
        $pdfContent = $pdf->output();
        $pdfFilename = 'prescription_' . $this->prescription->id . '.pdf';
        
        return (new MailMessage)
            ->subject('New Prescription Issued - ' . $clinic->name)
            ->view('emails.prescriptions.issued', [
                'prescription' => $this->prescription,
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
            'prescription_id' => $this->prescription->id,
            'prescribed_date' => $this->prescription->prescribed_date,
            'dentist_name' => $this->prescription->dentist->name,
        ];
    }
}
