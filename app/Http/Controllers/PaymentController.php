<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function show(Invoice $invoice)
    {
        // Verify patient access
        $patientId = session('patient_id');
        if (!$patientId || $invoice->patient_id !== $patientId) {
            abort(403);
        }

        if ($invoice->status === 'paid') {
            return redirect()->route('patient.invoices')->with('error', 'Invoice already paid.');
        }

        return view('patient-portal.payment', compact('invoice'));
    }

    public function process(Request $request, Invoice $invoice)
    {
        // Verify patient access
        $patientId = session('patient_id');
        if (!$patientId || $invoice->patient_id !== $patientId) {
            abort(403);
        }

        if ($invoice->status === 'paid') {
            return redirect()->route('patient.invoices')->with('error', 'Invoice already paid.');
        }

        $validated = $request->validate([
            'payment_method' => 'required|in:cash,card,esewa,khalti',
            'amount' => 'required|numeric|min:1|max:' . $invoice->total_amount,
        ]);

        // For now, we'll simulate payment processing
        // In production, integrate with actual payment gateways
        
        $payment = Payment::create([
            'invoice_id' => $invoice->id,
            'patient_id' => $invoice->patient_id,
            'clinic_id' => $invoice->clinic_id,
            'amount' => $validated['amount'],
            'payment_method' => $validated['payment_method'],
            'status' => 'completed',
            'transaction_id' => 'TXN_' . time() . '_' . bin2hex(random_bytes(4)),
            'paid_at' => now(),
        ]);

        // Update invoice status if fully paid
        if ($payment->amount >= $invoice->total_amount) {
            $invoice->update([
                'status' => 'paid',
                'paid_at' => now(),
            ]);
        }

        return redirect()->route('patient.invoices')->with('success', 'Payment processed successfully!');
    }
}