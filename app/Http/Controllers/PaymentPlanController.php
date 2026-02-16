<?php

namespace App\Http\Controllers;

use App\Models\PaymentPlan;
use App\Models\PaymentInstallment;
use App\Models\Invoice;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PaymentPlanController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function index()
    {
        $paymentPlans = PaymentPlan::where('clinic_id', Auth::user()->clinic_id)
            ->with(['patient', 'invoice'])
            ->latest()
            ->paginate(10);

        return view('payment-plans.index', compact('paymentPlans'));
    }

    public function create(Request $request)
    {
        $patients = Patient::where('clinic_id', Auth::user()->clinic_id)->orderBy('first_name')->get();
        $invoices = Invoice::where('clinic_id', Auth::user()->clinic_id)
            ->where('status', '!=', 'paid')
            ->orderBy('invoice_number')
            ->get();

        $selectedInvoice = null;
        if ($request->has('invoice_id')) {
            $selectedInvoice = Invoice::find($request->invoice_id);
        }

        return view('payment-plans.create', compact('patients', 'invoices', 'selectedInvoice'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'invoice_id' => 'required|exists:invoices,id',
            'total_amount' => 'required|numeric|min:0',
            'down_payment' => 'required|numeric|min:0',
            'installments' => 'required|integer|min:1|max:24',
            'start_date' => 'required|date|after_or_equal:today',
        ]);

        $validated['clinic_id'] = Auth::user()->clinic_id;
        $validated['installment_amount'] = ($validated['total_amount'] - $validated['down_payment']) / $validated['installments'];
        $validated['status'] = 'active';

        $paymentPlan = PaymentPlan::create($validated);

        // Generate Installments
        $this->generateInstallments($paymentPlan);

        return redirect()->route('clinic.payment-plans.index')->with('success', 'Payment plan created and installments generated.');
    }

    public function show(PaymentPlan $paymentPlan)
    {
        $this->authorizeAccess($paymentPlan);
        $paymentPlan->load(['patient', 'invoice', 'paymentInstallments']);
        
        return view('payment-plans.show', compact('paymentPlan'));
    }

    protected function generateInstallments(PaymentPlan $plan)
    {
        $dueDate = Carbon::parse($plan->start_date);
        
        for ($i = 1; $i <= $plan->installments; $i++) {
            PaymentInstallment::create([
                'payment_plan_id' => $plan->id,
                'installment_number' => $i,
                'amount' => $plan->installment_amount,
                'due_date' => $dueDate->copy()->addMonths($i - 1),
                'status' => 'pending'
            ]);
        }
    }

    protected function authorizeAccess(PaymentPlan $plan)
    {
        if ($plan->clinic_id !== Auth::user()->clinic_id) {
            abort(403);
        }
    }
}
