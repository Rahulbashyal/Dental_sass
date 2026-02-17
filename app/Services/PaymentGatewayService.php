<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Http;

class PaymentGatewayService
{
    public function processKhaltiPayment($amount, $productId, $productName)
    {
        $response = Http::post('https://khalti.com/api/v2/payment/verify/', [
            'token' => request('token'),
            'amount' => $amount * 100, // Convert to paisa
        ], [
            'Authorization' => 'Key ' . config('services.khalti.secret_key')
        ]);

        $this->logPaymentAttempt('khalti', $amount, $response->json());
        
        return $response->successful();
    }

    public function processEsewaPayment($amount, $productId)
    {
        $signature = $this->generateEsewaSignature($amount, $productId);
        
        $response = Http::post('https://uat.esewa.com.np/epay/transrec', [
            'amt' => $amount,
            'rid' => request('refId'),
            'pid' => $productId,
            'scd' => config('services.esewa.merchant_code')
        ]);

        $this->logPaymentAttempt('esewa', $amount, $response->body());
        
        return str_contains($response->body(), 'Success');
    }

    private function generateEsewaSignature($amount, $productId): string
    {
        $message = "total_amount={$amount},transaction_uuid={$productId},product_code=" . config('services.esewa.merchant_code');
        return base64_encode(hash_hmac('sha256', $message, config('services.esewa.secret_key'), true));
    }

    private function logPaymentAttempt($gateway, $amount, $response)
    {
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'payment_attempt',
            'model_type' => 'Payment',
            'new_values' => [
                'gateway' => $gateway,
                'amount' => $amount,
                'response' => $response
            ],
            'ip_address' => request()->ip(),
        ]);
    }
}