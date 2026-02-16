<?php

namespace App\Services;

class KhaltiService
{
    private string $secretKey;
    private string $baseUrl;
    private string $returnUrl;

    public function __construct()
    {
        $this->secretKey = config('services.khalti.secret_key');
        $this->baseUrl = config('services.khalti.base_url');
        $this->returnUrl = config('services.khalti.return_url');
    }

    /**
     * Initiate a payment request to Khalti.
     * Amount must be in "paisa" (NPR * 100).
     * Returns the pidx and payment_url for redirect.
     */
    public function initiatePayment(float $amountNpr, string $purchaseOrderId, string $purchaseOrderName, array $customerInfo = []): array
    {
        $amountPaisa = (int) ($amountNpr * 100);

        $payload = [
            'return_url' => $this->returnUrl,
            'website_url' => config('app.url', 'http://127.0.0.1:8000'),
            'amount' => $amountPaisa,
            'purchase_order_id' => $purchaseOrderId,
            'purchase_order_name' => $purchaseOrderName,
        ];

        if (!empty($customerInfo)) {
            $payload['customer_info'] = $customerInfo;
        }

        $response = $this->makeRequest('/epayment/initiate/', $payload);

        return $response;
    }

    /**
     * Verify/Lookup a payment using pidx.
     */
    public function verifyPayment(string $pidx): array
    {
        $response = $this->makeRequest('/epayment/lookup/', ['pidx' => $pidx]);
        return $response;
    }

    /**
     * Make an authenticated POST request to Khalti API.
     */
    private function makeRequest(string $endpoint, array $data): array
    {
        $url = rtrim($this->baseUrl, '/') . $endpoint;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Key ' . $this->secretKey,
            'Content-Type: application/json',
        ]);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            return ['error' => true, 'message' => 'cURL Error: ' . $error];
        }

        $decoded = json_decode($response, true);

        if ($httpCode >= 400) {
            return [
                'error' => true,
                'message' => $decoded['detail'] ?? ($decoded['message'] ?? 'Khalti API error'),
                'http_code' => $httpCode,
                'response' => $decoded,
            ];
        }

        return $decoded ?? ['error' => true, 'message' => 'Empty response'];
    }
}
