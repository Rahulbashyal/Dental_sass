<?php

namespace Modules\Financials\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PaymentPlan;
use App\Models\PaymentInstallment;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PaymentPlanController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status');
        $query = PaymentPlan::with(['patient', 'invoice'])->orderBy('created_at', 'desc');

        if ($status) {
            $query->where('status', $status);
        }

        $paymentPlans = $query->paginate(15);
        
        $stats = [
            'active' => PaymentPlan::where('status', 'active')->count(),
            'total_value' => PaymentPlan::sum('total_amount'),
            'outstanding' => PaymentInstallment::where('status', 'pending')->sum('amount'),
        ];

        return view('financials::payment_plans.index', compact('paymentPlans', 'stats', 'status'));
    }

    public function create(Request $request)
    {
        $invoiceId = $request->get('invoice_id');
        $invoice = null;
        if ($invoiceId) {
            $invoice = Invoice::with('patient')->findOrFail($invoiceId);
            
            // Check if already has a plan
            if (PaymentPlan::where('invoice_id', $invoiceId)->exists()) {
                return redirect()->route('payment-plans.index')->with('error', 'A payment plan already exists for this invoice.');
            }
        }

        return view('financials::payment_plans.create', compact('invoice'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'down_payment' => 'required|numeric|min:0',
            'installments' => 'required|integer|min:2|max:24',
            'start_date' => 'required|date|after_or_equal:today',
        ]);

        $invoice = Invoice::findOrFail($validated['invoice_id']);
        $totalAmount = $invoice->total_amount;
        $remainingAmount = $totalAmount - $validated['down_payment'];
        $installmentAmount = round($remainingAmount / $validated['installments'], 2);

        return DB::transaction(function () use ($validated, $invoice, $totalAmount, $remainingAmount, $installmentAmount) {
            $paymentPlan = PaymentPlan::create([
                'clinic_id' => tenant()->clinic->id,
                'patient_id' => $invoice->patient_id,
                'invoice_id' => $invoice->id,
                'total_amount' => $totalAmount,
                'down_payment' => $validated['down_payment'],
                'installments' => $validated['installments'],
                'installment_amount' => $installmentAmount,
                'start_date' => $validated['start_date'],
                'status' => 'active',
            ]);

            // Create installments
            $startDate = Carbon::parse($validated['start_date']);
            for ($i = 1; $i <= $validated['installments']; $i++) {
                PaymentInstallment::create([
                    'payment_plan_id' => $paymentPlan->id,
                    'installment_number' => $i,
                    'amount' => ($i == $validated['installments']) ? ($remainingAmount - ($installmentAmount * ($i - 1))) : $installmentAmount,
                    'due_date' => (clone $startDate)->addMonths($i - 1),
                    'status' => 'pending',
                ]);
            }

            return redirect()->route('payment-plans.show', $paymentPlan)
                ->with('status', 'Payment plan established successfully.');
        });
    }

    public function show(PaymentPlan $paymentPlan)
    {
        $paymentPlan->load(['patient', 'invoice', 'paymentInstallments']);
        return view('financials::payment_plans.show', compact('paymentPlan'));
    }

    public function payInstallment(PaymentInstallment $installment)
    {
        $installment->update([
            'status' => 'paid',
            'paid_date' => Carbon::today(),
        ]);

        // Audit log in payments table
        \App\Models\Payment::create([
            'invoice_id' => $installment->paymentPlan->invoice_id,
            'patient_id' => $installment->paymentPlan->patient_id,
            'clinic_id' => $installment->paymentPlan->clinic_id,
            'amount' => $installment->amount,
            'payment_method' => 'Cash',
            'status' => 'completed',
            'transaction_id' => 'INST-' . $installment->id . '-' . strtoupper(uniqid()),
            'paid_at' => Carbon::now(),
        ]);

        // Check if all paid
        $remaining = $installment->paymentPlan->paymentInstallments()->where('status', '!=', 'paid')->count();
        if ($remaining === 0) {
            $installment->paymentPlan->update(['status' => 'completed']);
            $installment->paymentPlan->invoice->update(['status' => 'paid']);
        }

        return back()->with('status', 'Installment payment recorded.');
    }
}
