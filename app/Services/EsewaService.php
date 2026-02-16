<?php

namespace App\Services;

class EsewaService
{
    private string $merchantCode;
    private string $secretKey;
    private string $gatewayUrl;
    private string $verificationUrl;

    public function __construct()
    {
        $this->merchantCode = config('services.esewa.merchant_code');
        $this->secretKey = config('services.esewa.secret_key');
        $this->gatewayUrl = config('services.esewa.gateway_url');
        $this->verificationUrl = config('services.esewa.verification_url');
    }

    /**
     * Generate HMAC-SHA256 signature for eSewa ePay v2.
     * Input format: "total_amount={amount},transaction_uuid={uuid},product_code={code}"
     */
    public function generateSignature(float $totalAmount, string $transactionUuid): string
    {
        $message = "total_amount={$totalAmount},transaction_uuid={$transactionUuid},product_code={$this->merchantCode}";
        $hash = hash_hmac('sha256', $message, $this->secretKey, true);
        return base64_encode($hash);
    }

    /**
     * Prepare form data for eSewa redirect.
     * Returns an array of hidden form fields + the gateway URL.
     */
    public function preparePayment(float $amount, string $transactionUuid, string $successUrl, string $failureUrl): array
    {
        $totalAmount = $amount;
        $signature = $this->generateSignature($totalAmount, $transactionUuid);

        return [
            'url' => $this->gatewayUrl,
            'fields' => [
                'amount' => $amount,
                'tax_amount' => 0,
                'total_amount' => $totalAmount,
                'transaction_uuid' => $transactionUuid,
                'product_code' => $this->merchantCode,
                'product_service_charge' => 0,
                'product_delivery_charge' => 0,
                'success_url' => $successUrl,
                'failure_url' => $failureUrl,
                'signed_field_names' => 'total_amount,transaction_uuid,product_code',
                'signature' => $signature,
            ],
        ];
    }

    /**
     * Verify transaction status via eSewa Status Check API.
     */
    public function verifyTransaction(string $transactionUuid, float $totalAmount): array
    {
        $url = $this->verificationUrl . '?' . http_build_query([
            'product_code' => $this->merchantCode,
            'total_amount' => $totalAmount,
            'transaction_uuid' => $transactionUuid,
        ]);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        // Enable SSL verification except in local development
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, !app()->environment('local'));
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, !app()->environment('local') ? 2 : 0);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            return ['status' => 'FAILED', 'message' => 'Verification request failed'];
        }

        return json_decode($response, true) ?? ['status' => 'FAILED'];
    }

    /**
     * Verify the HMAC signature from eSewa's callback response.
     */
    public function verifySignature(array $responseData): bool
    {
        if (!isset($responseData['signed_field_names']) || !isset($responseData['signature'])) {
            return false;
        }

        $fieldNames = explode(',', $responseData['signed_field_names']);
        $parts = [];
        foreach ($fieldNames as $field) {
            if (!isset($responseData[$field])) {
                return false;
            }
            $parts[] = "{$field}={$responseData[$field]}";
        }

        $message = implode(',', $parts);
        $expectedSignature = base64_encode(hash_hmac('sha256', $message, $this->secretKey, true));

        return hash_equals($expectedSignature, $responseData['signature']);
    }
}
