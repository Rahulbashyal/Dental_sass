<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Email;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class CommunicationService
{
    /**
     * Dispatch a notification to a notifiable entity (User or Patient).
     */
    public static function notify($notifiable, $title, $message, $type = 'system', $data = [])
    {
        try {
            return Notification::create([
                'notifiable_id' => $notifiable->id,
                'notifiable_type' => get_class($notifiable),
                'user_id' => $notifiable instanceof \App\Models\User ? $notifiable->id : null,
                'title' => $title,
                'message' => $message,
                'type' => $type,
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            Log::error('Notification dispatch failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Send an email and log it.
     */
    public static function email($notifiable, $subject, $view, $viewData = [])
    {
        $clinic = $notifiable->clinic ?? null;
        
        // Inject Nepali date if appointment is present
        if (isset($viewData['appointment']) && !isset($viewData['nepaliDate'])) {
            $viewData['nepaliDate'] = \App\Services\NepaliCalendarService::englishToNepali($viewData['appointment']->appointment_date);
        }

        try {
            Mail::send($view, array_merge($viewData, [
                'notifiable' => $notifiable,
                'clinic' => $clinic
            ]), function ($message) use ($notifiable, $subject) {
                $message->to($notifiable->email, $notifiable->name ?? ($notifiable->full_name ?? 'Subject'))
                       ->subject($subject);
            });

            // Log the email in our system
            return Email::create([
                'sender_id' => auth()->id(),
                'recipients' => [$notifiable->email],
                'subject' => $subject,
                'body' => 'Email sent via ' . $view,
                'clinic_id' => $clinic->id ?? null,
                'status' => 'sent',
                'sent_at' => now(),
            ]);
        } catch (\Exception $e) {
            Log::error('Email delivery failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Send an SMS and log it.
     */
    public static function sms($notifiable, $message)
    {
        $smsService = app(SmsService::class);
        $phone = $notifiable->phone;

        if (!$phone) {
            Log::info("No phone number for SMS for {$notifiable->name}");
            return null;
        }

        try {
            $result = $smsService->send($phone, $message);
            
            // Log SMS if needed (using the same Message model but with type 'sms' or a dedicated model)
            // For now, we'll use the log.
            
            return $result;
        } catch (\Exception $e) {
            Log::error('SMS delivery failed: ' . $e->getMessage());
            return null;
        }
    }
}
