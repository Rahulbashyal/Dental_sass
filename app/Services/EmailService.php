<?php

namespace App\Services;

use App\Models\Email;
use App\Models\User;
use App\Models\Patient;
use App\Models\Notification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EmailService
{
    public function sendEmail($data)
    {
        try {
            $email = Email::create([
                'sender_id' => $data['sender_id'],
                'recipients' => $data['recipients'],
                'subject' => $data['subject'],
                'body' => $data['body'],
                'clinic_id' => $data['clinic_id'] ?? null,
                'status' => 'pending',
                'attachments' => $data['attachments'] ?? []
            ]);

            // Send actual email
            $this->sendActualEmail($email);
            
            // Create notifications
            $this->createNotifications($email);
            
            return $email;
        } catch (\Exception $e) {
            Log::error('Email sending failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function sendAppointmentReminder($appointment)
    {
        $patient = $appointment->patient;
        $clinic = $appointment->clinic;
        
        $nepaliDate = NepaliCalendarService::englishToNepali($appointment->appointment_date);
        
        $subject = "Appointment Reminder - {$clinic->name}";
        
        try {
            Mail::send('emails.appointment-reminder', [
                'appointment' => $appointment,
                'patient' => $patient,
                'clinic' => $clinic,
                'nepaliDate' => $nepaliDate
            ], function ($message) use ($patient, $subject) {
                $message->to($patient->email, $patient->name)
                       ->subject($subject);
            });
            
            // Log the email
            $email = Email::create([
                'sender_id' => $appointment->dentist_id,
                'recipients' => [$patient->email],
                'subject' => $subject,
                'body' => 'Appointment reminder email sent',
                'clinic_id' => $clinic->id,
                'status' => 'sent',
                'sent_at' => now()
            ]);
            
            $this->createNotifications($email);
            return $email;
        } catch (\Exception $e) {
            Log::error('Appointment reminder email failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function sendAppointmentConfirmation($appointment)
    {
        $patient = $appointment->patient;
        $clinic = $appointment->clinic;
        
        $nepaliDate = NepaliCalendarService::englishToNepali($appointment->appointment_date);
        
        $subject = "Appointment Confirmed - {$clinic->name}";
        
        try {
            Mail::send('emails.appointment-confirmation', [
                'appointment' => $appointment,
                'patient' => $patient,
                'clinic' => $clinic,
                'nepaliDate' => $nepaliDate
            ], function ($message) use ($patient, $subject) {
                $message->to($patient->email, $patient->name)
                       ->subject($subject);
            });
            
            // Log the email
            $email = Email::create([
                'sender_id' => $appointment->dentist_id,
                'recipients' => [$patient->email],
                'subject' => $subject,
                'body' => 'Appointment confirmation email sent',
                'clinic_id' => $clinic->id,
                'status' => 'sent',
                'sent_at' => now()
            ]);
            
            $this->createNotifications($email);
            return $email;
        } catch (\Exception $e) {
            Log::error('Appointment confirmation email failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function sendInvoiceEmail($invoice)
    {
        $patient = $invoice->patient;
        $clinic = $invoice->clinic;
        
        $subject = "Invoice #{$invoice->invoice_number} - {$clinic->name}";
        
        try {
            Mail::send('emails.invoice', [
                'invoice' => $invoice,
                'patient' => $patient,
                'clinic' => $clinic
            ], function ($message) use ($patient, $subject) {
                $message->to($patient->email, $patient->name)
                       ->subject($subject);
            });
            
            // Log the email
            $email = Email::create([
                'sender_id' => auth()->id(),
                'recipients' => [$patient->email],
                'subject' => $subject,
                'body' => 'Invoice email sent',
                'clinic_id' => $clinic->id,
                'status' => 'sent',
                'sent_at' => now()
            ]);
            
            $this->createNotifications($email);
            return $email;
        } catch (\Exception $e) {
            Log::error('Invoice email failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function sendBulkEmail($recipients, $subject, $body, $clinicId = null)
    {
        $chunks = array_chunk($recipients, 50); // Send in batches
        $results = [];

        foreach ($chunks as $chunk) {
            $results[] = $this->sendEmail([
                'sender_id' => auth()->id(),
                'recipients' => $chunk,
                'subject' => $subject,
                'body' => $body,
                'clinic_id' => $clinicId
            ]);
        }

        return $results;
    }

    private function sendActualEmail($email)
    {
        try {
            foreach ($email->recipients as $recipient) {
                Mail::raw($email->body, function ($message) use ($email, $recipient) {
                    $message->to($recipient)
                           ->subject($email->subject)
                           ->from(config('mail.from.address'), config('mail.from.name'));
                    
                    // Add attachments if any
                    if (!empty($email->attachments)) {
                        foreach ($email->attachments as $attachment) {
                            if (file_exists($attachment)) {
                                $message->attach($attachment);
                            }
                        }
                    }
                });
            }

            $email->update([
                'status' => 'sent',
                'sent_at' => now()
            ]);
            
            Log::info('Email sent successfully', [
                'id' => $email->id,
                'subject' => $email->subject,
                'recipients' => $email->recipients
            ]);
        } catch (\Exception $e) {
            $email->update(['status' => 'failed']);
            Log::error('Email sending failed', [
                'id' => $email->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    private function createNotifications($email)
    {
        foreach ($email->recipients as $recipient) {
            if (is_numeric($recipient)) {
                $user = User::find($recipient);
            } else {
                $user = User::where('email', $recipient)->first();
            }

            if ($user) {
                Notification::create([
                    'user_id' => $user->id,
                    'title' => 'New Email: ' . $email->subject,
                    'message' => 'You have received a new email from ' . $email->sender->name,
                    'type' => 'email',
                    'data' => ['email_id' => $email->id]
                ]);
            }
        }
    }

    public function getEmailTemplates()
    {
        return [
            'appointment_reminder' => [
                'name' => 'Appointment Reminder',
                'subject' => 'Appointment Reminder - {{clinic_name}}',
                'body' => 'Dear {{patient_name}},\n\nThis is a reminder for your upcoming appointment on {{appointment_date}} at {{appointment_time}}.\n\nBest regards,\n{{clinic_name}}'
            ],
            'appointment_confirmation' => [
                'name' => 'Appointment Confirmation',
                'subject' => 'Appointment Confirmed - {{clinic_name}}',
                'body' => 'Dear {{patient_name}},\n\nYour appointment has been confirmed for {{appointment_date}} at {{appointment_time}}.\n\nThank you,\n{{clinic_name}}'
            ],
            'invoice_notification' => [
                'name' => 'Invoice Notification',
                'subject' => 'Invoice #{{invoice_number}} - {{clinic_name}}',
                'body' => 'Dear {{patient_name}},\n\nYour invoice #{{invoice_number}} for NPR {{amount}} is ready.\n\nDue date: {{due_date}}\n\nBest regards,\n{{clinic_name}}'
            ],
            'welcome_patient' => [
                'name' => 'Welcome New Patient',
                'subject' => 'Welcome to {{clinic_name}}',
                'body' => 'Dear {{patient_name}},\n\nWelcome to {{clinic_name}}! We are excited to serve you.\n\nBest regards,\n{{clinic_name}}'
            ]
        ];
    }
}