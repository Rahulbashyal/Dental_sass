<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payment;
use App\Services\EsewaService;
use App\Services\KhaltiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Notifications\PaymentReceived;
use Illuminate\Support\Facades\Notification;

class PaymentController extends Controller
{
    /**
     * Display the payment form for an invoice.
     */
    public function show(Invoice $invoice)
    {
        // Use the patient auth guard for authorization
        $patient = Auth::guard('patient')->user();
        if (!$patient || $invoice->patient_id !== $patient->id) {
            abort(403);
        }

        if ($invoice->status === 'paid') {
            return redirect()->route('patient.invoices')->with('error', 'Invoice already paid.');
        }

        return view('patient-portal.payment', compact('invoice'));
    }

    /**
     * Process the payment: route to the correct gateway.
     */
    public function process(Request $request, Invoice $invoice)
    {
        // Use the patient auth guard for authorization
        $patient = Auth::guard('patient')->user();
        if (!$patient || $invoice->patient_id !== $patient->id) {
            abort(403);
        }

        if ($invoice->status === 'paid') {
            return redirect()->route('patient.invoices')->with('error', 'Invoice already paid.');
        }

        $validated = $request->validate([
            'payment_method' => 'required|in:cash,card,esewa,khalti',
            'amount' => 'required|numeric|min:1|max:' . ($invoice->remaining_amount ?? $invoice->total_amount),
        ]);

        $amount = (float) $validated['amount'];
        $method = $validated['payment_method'];

        // Store payment intent in session for callback verification
        session([
            'payment_intent' => [
                'invoice_id' => $invoice->id,
                'amount' => $amount,
                'method' => $method,
                'patient_id' => $patient->id,
                'clinic_id' => $invoice->clinic_id,
            ],
        ]);

        switch ($method) {
            case 'esewa':
                return $this->initiateEsewa($invoice, $amount);
            case 'khalti':
                return $this->initiateKhalti($invoice, $amount);
            case 'cash':
                return $this->processCash($invoice, $amount);
            case 'card':
                return $this->processCard($invoice, $amount);
            default:
                return back()->with('error', 'Invalid payment method selected.');
        }
    }

    /**
     * Initiate eSewa payment: renders a hidden auto-submit form.
     */
    private function initiateEsewa(Invoice $invoice, float $amount)
    {
        $esewa = new EsewaService();
        $transactionUuid = $invoice->id . '-' . now()->format('ymd-His');

        // Store UUID in session for verification
        session(['esewa_txn_uuid' => $transactionUuid]);

        $successUrl = route('patient.payment.esewa.callback');
        $failureUrl = route('patient.payment.esewa.failed');

        $paymentData = $esewa->preparePayment($amount, $transactionUuid, $successUrl, $failureUrl);

        return view('patient-portal.payment-redirect', [
            'gateway' => 'eSewa',
            'url' => $paymentData['url'],
            'fields' => $paymentData['fields'],
        ]);
    }

    /**
     * Handle eSewa success callback.
     * eSewa redirects to success_url with Base64-encoded response data.
     */
    public function esewaCallback(Request $request)
    {
        $intent = session('payment_intent');
        $storedUuid = session('esewa_txn_uuid');

        if (!$intent || !$storedUuid) {
            return redirect()->route('patient.invoices')->with('error', 'Payment session expired.');
        }

        // eSewa sends a Base64-encoded JSON in the 'data' query parameter
        $encodedData = $request->query('data');
        if (!$encodedData) {
            return redirect()->route('patient.invoices')->with('error', 'No payment data received from eSewa.');
        }

        $responseData = json_decode(base64_decode($encodedData), true);

        if (!$responseData || !isset($responseData['status'])) {
            return redirect()->route('patient.invoices')->with('error', 'Invalid eSewa response.');
        }

        // Verify signature integrity
        $esewa = new EsewaService();
        if (!$esewa->verifySignature($responseData)) {
            Log::warning('eSewa signature verification failed', $responseData);
            return redirect()->route('patient.invoices')->with('error', 'Payment verification failed. Signature mismatch.');
        }

        if ($responseData['status'] !== 'COMPLETE') {
            return redirect()->route('patient.invoices')->with('error', 'Payment was not completed. Status: ' . $responseData['status']);
        }

        // Double-verify via Status Check API
        $verification = $esewa->verifyTransaction($storedUuid, $intent['amount']);

        if (($verification['status'] ?? '') !== 'COMPLETE') {
            Log::warning('eSewa status check verification failed', $verification);
            return redirect()->route('patient.invoices')->with('error', 'Payment status verification failed.');
        }

        // Record the payment
        $this->recordPayment($intent, $responseData['transaction_code'] ?? $storedUuid);

        // Clear session
        session()->forget(['payment_intent', 'esewa_txn_uuid']);

        return redirect()->route('patient.invoices')->with('success', 'Payment via eSewa completed successfully!');
    }

    /**
     * Handle eSewa failure callback.
     */
    public function esewaFailed(Request $request)
    {
        session()->forget(['payment_intent', 'esewa_txn_uuid']);
        return redirect()->route('patient.invoices')->with('error', 'eSewa payment was cancelled or failed.');
    }

    /**
     * Initiate Khalti payment: server-side POST then redirect.
     */
    private function initiateKhalti(Invoice $invoice, float $amount)
    {
        $khalti = new KhaltiService();
        $purchaseOrderId = 'INV-' . $invoice->id . '-' . time();

        session(['khalti_order_id' => $purchaseOrderId]);

        $patient = \App\Models\Patient::find($invoice->patient_id);
        $customerInfo = [];
        if ($patient) {
            $customerInfo = [
                'name' => $patient->first_name . ' ' . $patient->last_name,
                'email' => $patient->email ?? '',
                'phone' => $patient->phone ?? '9800000000',
            ];
        }

        $response = $khalti->initiatePayment(
            $amount,
            $purchaseOrderId,
            'Invoice #' . $invoice->invoice_number,
            $customerInfo
        );

        if (isset($response['error']) && $response['error']) {
            Log::error('Khalti initiation failed', $response);
            return back()->with('error', 'Khalti payment initiation failed: ' . ($response['message'] ?? 'Unknown error'));
        }

        if (!isset($response['payment_url']) || !isset($response['pidx'])) {
            Log::error('Khalti missing payment_url/pidx', $response);
            return back()->with('error', 'Khalti returned an unexpected response.');
        }

        session(['khalti_pidx' => $response['pidx']]);

        return redirect()->away($response['payment_url']);
    }

    /**
     * Handle Khalti callback (return_url).
     * Khalti redirects with query params: pidx, status, transaction_id, tidx, amount, etc.
     */
    public function khaltiCallback(Request $request)
    {
        $intent = session('payment_intent');
        $storedPidx = session('khalti_pidx');

        if (!$intent || !$storedPidx) {
            return redirect()->route('patient.invoices')->with('error', 'Payment session expired.');
        }

        $pidx = $request->query('pidx');
        $status = $request->query('status');
        $transactionId = $request->query('transaction_id');

        if ($pidx !== $storedPidx) {
            return redirect()->route('patient.invoices')->with('error', 'Payment reference mismatch.');
        }

        if ($status === 'User canceled') {
            session()->forget(['payment_intent', 'khalti_pidx', 'khalti_order_id']);
            return redirect()->route('patient.invoices')->with('error', 'Khalti payment was cancelled.');
        }

        // Verify via Lookup API
        $khalti = new KhaltiService();
        $verification = $khalti->verifyPayment($pidx);

        if (($verification['status'] ?? '') !== 'Completed') {
            Log::warning('Khalti verification failed', $verification);
            return redirect()->route('patient.invoices')->with('error', 'Khalti payment verification failed. Status: ' . ($verification['status'] ?? 'unknown'));
        }

        // Verify amount matches (Khalti returns amount in paisa)
        $paidAmountNpr = ($verification['total_amount'] ?? 0) / 100;
        if (abs($paidAmountNpr - $intent['amount']) > 1) {
            Log::warning('Khalti amount mismatch', [
                'expected' => $intent['amount'],
                'received' => $paidAmountNpr,
            ]);
            return redirect()->route('patient.invoices')->with('error', 'Payment amount mismatch detected.');
        }

        // Record the payment
        $this->recordPayment($intent, $transactionId ?? $pidx);

        // Clear session
        session()->forget(['payment_intent', 'khalti_pidx', 'khalti_order_id']);

        return redirect()->route('patient.invoices')->with('success', 'Payment via Khalti completed successfully!');
    }

    /**
     * Process Cash payment (recorded as pending — needs front-desk confirmation).
     */
    private function processCash(Invoice $invoice, float $amount)
    {
        $intent = session('payment_intent');

        $payment = Payment::create([
            'invoice_id' => $invoice->id,
            'patient_id' => $invoice->patient_id,
            'clinic_id' => $invoice->clinic_id,
            'amount' => $amount,
            'payment_method' => 'cash',
            'status' => 'pending',
            'transaction_id' => 'CASH_' . $invoice->id . '_' . time(),
            'paid_at' => null,
        ]);

        session()->forget('payment_intent');

        return redirect()->route('patient.invoices')->with('success', 'Cash payment reference generated. Please present reference #' . $payment->transaction_id . ' at the clinic front-desk.');
    }

    /**
     * Process Card payment (placeholder — would integrate with a card processor).
     */
    private function processCard(Invoice $invoice, float $amount)
    {
        // For now, simulate card processing
        $intent = session('payment_intent');
        $txnId = 'CARD_' . $invoice->id . '_' . bin2hex(random_bytes(4));

        $payment = Payment::create([
            'invoice_id' => $invoice->id,
            'patient_id' => $invoice->patient_id,
            'clinic_id' => $invoice->clinic_id,
            'amount' => $amount,
            'payment_method' => 'card',
            'status' => 'completed',
            'transaction_id' => $txnId,
            'paid_at' => now(),
        ]);

        $this->updateInvoiceStatus($invoice, $amount);
        session()->forget('payment_intent');

        // Notify clinic admins
        $clinic = $payment->clinic;
        if ($clinic) {
            Notification::send($clinic->admins, new PaymentReceived($payment));
        }

        return redirect()->route('patient.invoices')->with('success', 'Card payment processed successfully! Transaction: ' . $txnId);
    }

    /**
     * Record a verified payment from an external gateway (eSewa/Khalti).
     */
    private function recordPayment(array $intent, string $transactionId): void
    {
        \Illuminate\Support\Facades\DB::transaction(function () use ($intent, $transactionId) {
            $payment = Payment::create([
                'invoice_id' => $intent['invoice_id'],
                'patient_id' => $intent['patient_id'],
                'clinic_id' => $intent['clinic_id'],
                'amount' => $intent['amount'],
                'payment_method' => $intent['method'],
                'status' => 'completed',
                'transaction_id' => $transactionId,
                'paid_at' => now(),
            ]);

            $invoice = Invoice::find($intent['invoice_id']);
            if ($invoice) {
                $this->updateInvoiceStatus($invoice, $intent['amount']);

                // Notify clinic admins
                $clinic = $invoice->clinic;
                if ($clinic) {
                    Notification::send($clinic->admins, new PaymentReceived($payment));
                }
            }
        });
    }

    /**
     * Update invoice status based on total payments received.
     */
    private function updateInvoiceStatus(Invoice $invoice, float $newPaymentAmount): void
    {
        $totalPaid = Payment::where('invoice_id', $invoice->id)
            ->where('status', 'completed')
            ->sum('amount');

        if ($totalPaid >= $invoice->total_amount) {
            $invoice->update([
                'status' => 'paid',
                'paid_at' => now(),
            ]);
        } elseif ($totalPaid > 0) {
            $invoice->update([
                'status' => 'partial',
            ]);
        }
    }
}