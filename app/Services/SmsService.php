<?php

namespace App\Services;

use App\Models\Message;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class SmsService
{
    /**
     * Send an SMS via the configured provider.
     */
    public function send($recipient, $message, $provider = null)
    {
        // Sanitize recipient (ensure it starts with country code if needed)
        $recipient = preg_replace('/[^0-9]/', '', $recipient);
        
        // Use configured default provider if not specified
        $provider = $provider ?? config('services.sms.default', 'log');

        switch ($provider) {
            case 'twilio':
                return $this->sendViaTwilio($recipient, $message);
            case 'sparrow':
                return $this->sendViaSparrow($recipient, $message);
            case 'akash':
                return $this->sendViaAkash($recipient, $message);
            case 'log':
            default:
                return $this->sendViaLog($recipient, $message);
        }
    }

    /**
     * Log SMS (default/development)
     */
    private function sendViaLog($recipient, $message)
    {
        Log::info("SMS SENT TO {$recipient}: {$message}");
        
        return [
            'success' => true,
            'provider' => 'log',
            'provider_id' => 'LOG-' . uniqid()
        ];
    }

    /**
     * Twilio SMS Driver (International)
     */
    private function sendViaTwilio($recipient, $message)
    {
        $sid = config('services.twilio.sid');
        $token = config('services.twilio.token');
        $from = config('services.twilio.from');

        if (!$sid || !$token || !$from) {
            Log::error("Twilio SMS credentials missing.");
            return ['success' => false, 'error' => 'Twilio configuration missing'];
        }

        try {
            $response = Http::withBasicAuth($sid, $token)
                ->post("https://api.twilio.com/2010-04-01/Accounts/{$sid}/Messages.json", [
                    'To' => $recipient,
                    'From' => $from,
                    'Body' => $message
                ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => true,
                    'provider' => 'twilio',
                    'provider_id' => $data['sid'] ?? null
                ];
            }

            Log::error("Twilio SMS failed: " . $response->body());
            return ['success' => false, 'error' => 'Twilio API error', 'details' => $response->json()];
        } catch (\Exception $e) {
            Log::error("Twilio SMS exception: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Sparrow SMS (Nepal)
     */
    private function sendViaSparrow($recipient, $message)
    {
        $token = config('services.sparrow.token');
        $from = config('services.sparrow.from', 'DentalCare');

        if (!$token) {
            Log::error("Sparrow SMS Token missing.");
            return ['success' => false, 'error' => 'Sparrow configuration missing'];
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
            Log::error("Sparrow SMS failed: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Akash SMS (Nepal)
     */
    private function sendViaAkash($recipient, $message)
    {
        $apiKey = config('services.akash.api_key');
        $senderId = config('services.akash.sender_id', 'DentalCare');

        if (!$apiKey) {
            Log::error("Akash SMS API key missing.");
            return ['success' => false, 'error' => 'Akash configuration missing'];
        }

        try {
            $response = Http::post('https://sms.akashit.com/api/send/single', [
                'api_key' => $apiKey,
                'sender_id' => $senderId,
                'phone' => $recipient,
                'message' => $message
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => true,
                    'provider' => 'akash',
                    'provider_id' => $data['id'] ?? null
                ];
            }

            Log::error("Akash SMS failed: " . $response->body());
            return ['success' => false, 'error' => 'Akash API error', 'details' => $response->json()];
        } catch (\Exception $e) {
            Log::error("Akash SMS exception: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
