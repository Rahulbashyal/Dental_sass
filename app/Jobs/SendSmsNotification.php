<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendSmsNotification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $phoneNumber,
        public string $message
    ) {}

    public function handle(): void
    {
        // Mock SMS service - replace with actual SMS provider
        $response = Http::post('https://api.sms-provider.com/send', [
            'to' => $this->phoneNumber,
            'message' => $this->message,
            'api_key' => config('services.sms.api_key')
        ]);

        if ($response->successful()) {
            Log::info("SMS sent to {$this->phoneNumber}: {$this->message}");
        } else {
            Log::error("Failed to send SMS to {$this->phoneNumber}");
            throw new \Exception('SMS sending failed');
        }
    }
}
