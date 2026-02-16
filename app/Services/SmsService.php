<?php

namespace App\Services;

use App\Models\Message; // Actually I should use a model for SMS logs or just log it
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class SmsService
{
    /**
     * Send an SMS via the configured provider.
     */
    public function send($recipient, $message, $provider = 'log')
    {
        // Sanitize recipient (ensure it starts with country code if needed)
        $recipient = preg_replace('/[^0-9]/', '', $recipient);

        if ($provider === 'sparrow') {
            return $this->sendViaSparrow($recipient, $message);
        }

        // Default to Log
        Log::info("SMS SENT TO {$recipient}: {$message}");
        
        return [
            'success' => true,
            'provider' => 'log',
            'provider_id' => 'LOG-' . uniqid()
        ];
    }

    /**
     * Sparrow SMS (Nepal) Driver
     */
    private function sendViaSparrow($recipient, $message)
    {
        $token = config('services.sparrow.token');
        $from = config('services.sparrow.from', 'DentalCare');

        if (!$token) {
            Log::error("Sparrow SMS Token missing.");
            return ['success' => false, 'error' => 'Config missing'];
        }

        try {
            $response = Http::get('http://api.sparrowsms.com/v2/sms/', [
                'token' => $token,
                'from'  => $from,
                'to'    => $recipient,
                'text'  => $message
            ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'provider' => 'sparrow',
                    'provider_id' => $response->json('response_code')
                ];
            }

            return ['success' => false, 'error' => 'API Error', 'response' => $response->json()];
        } catch (\Exception $e) {
            Log::error("SMS Sending Failed: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
