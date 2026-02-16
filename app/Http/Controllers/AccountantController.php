<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AccountantController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:accountant|clinic_admin|receptionist']);
    }

    public function paymentTracking()
    {
        $clinic = Auth::user()->clinic;
        
        $stats = [
            'total_outstanding' => $clinic->invoices()->where('status', 'pending')->sum('total_amount'),
            'overdue_amount' => $clinic->invoices()->where('status', 'pending')->where('due_date', '<', now())->sum('total_amount'),
            'this_month_collected' => $clinic->payments()->whereMonth('created_at', now()->month)->where('status', 'completed')->sum('amount'),
            'avg_collection_time' => $this->getAverageCollectionTime(),
            'total_transactions' => $clinic->payments()->whereMonth('created_at', now()->month)->count(),
        ];

        // Gateway breakdown for this month
        $gatewayBreakdown = $clinic->payments()
            ->whereMonth('created_at', now()->month)
            ->where('status', 'completed')
            ->selectRaw('payment_method, COUNT(*) as count, SUM(amount) as total')
            ->groupBy('payment_method')
            ->get()
            ->keyBy('payment_method');

        // Recent payments (last 30)
        $recentPayments = $clinic->payments()
            ->with(['patient', 'invoice'])
            ->orderByDesc('created_at')
            ->limit(30)
            ->get();

        // Pending cash payments (need front-desk confirmation)
        $pendingCash = $clinic->payments()
            ->where('payment_method', 'cash')
            ->where('status', 'pending')
            ->with(['patient', 'invoice'])
            ->orderByDesc('created_at')
            ->get();

        $overdueInvoices = $clinic->invoices()
            ->where('status', 'pending')
            ->where('due_date', '<', now())
            ->with('patient')
            ->orderBy('due_date')
            ->get();

        return view('accountant.payment-tracking', compact('stats', 'overdueInvoices', 'recentPayments', 'pendingCash', 'gatewayBreakdown'));
    }

    public function expenseTracking()
    {
        $expenses = Auth::user()->clinic->expenses()
            ->whereMonth('expense_date', now()->month)
            ->orderBy('expense_date', 'desc')
            ->paginate(15);

        $monthlyTotal = Auth::user()->clinic->expenses()
            ->whereMonth('expense_date', now()->month)
            ->sum('amount');

        return view('accountant.expenses', compact('expenses', 'monthlyTotal'));
    }

    public function profitLossReport(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth());
        $endDate = $request->get('end_date', now()->endOfMonth());
        
        $clinic = Auth::user()->clinic;
        
        $revenue = $clinic->payments()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount');
            
        $expenses = $clinic->expenses()
            ->whereBetween('expense_date', [$startDate, $endDate])
            ->sum('amount');
            
        $profit = $revenue - $expenses;
        
        $revenueByCategory = $clinic->invoices()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'paid')
            ->selectRaw('treatment_type, SUM(total_amount) as total')
            ->groupBy('treatment_type')
            ->get();

        return view('accountant.profit-loss', compact('revenue', 'expenses', 'profit', 'revenueByCategory', 'startDate', 'endDate'));
    }

    public function patientBalances()
    {
        $patients = Patient::where('clinic_id', Auth::user()->clinic_id)
            ->withSum(['invoices as outstanding_balance' => function($query) {
                $query->where('status', 'pending');
            }], 'total_amount')
            ->having('outstanding_balance', '>', 0)
            ->orderBy('outstanding_balance', 'desc')
            ->paginate(20);

        return view('accountant.patient-balances', compact('patients'));
    }

    public function sendPaymentReminder(Invoice $invoice)
    {
        // Logic to send payment reminder
        $invoice->update(['reminder_sent_at' => now()]);
        
        return back()->with('success', 'Payment reminder sent successfully.');
    }

    public function bulkInvoiceActions(Request $request)
    {
        $validated = $request->validate([
            'invoice_ids' => 'required|array',
            'action' => 'required|in:mark_paid,send_reminder,mark_overdue'
        ]);

        $invoices = Invoice::whereIn('id', $validated['invoice_ids'])
            ->where('clinic_id', Auth::user()->clinic_id)
            ->get();

        foreach ($invoices as $invoice) {
            switch ($validated['action']) {
                case 'mark_paid':
                    $invoice->update(['status' => 'paid', 'paid_at' => now()]);
                    break;
                case 'send_reminder':
                    $invoice->update(['reminder_sent_at' => now()]);
                    break;
                case 'mark_overdue':
                    $invoice->update(['status' => 'overdue']);
                    break;
            }
        }

        return back()->with('success', 'Bulk action completed successfully.');
    }

    public function taxReport(Request $request)
    {
        $year = $request->get('year', now()->year);
        
        $quarterlyData = [];
        for ($quarter = 1; $quarter <= 4; $quarter++) {
            $startMonth = ($quarter - 1) * 3 + 1;
            $endMonth = $quarter * 3;
            
            $revenue = Auth::user()->clinic->payments()
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', '>=', $startMonth)
                ->whereMonth('created_at', '<=', $endMonth)
                ->sum('amount');
                
            $quarterlyData[$quarter] = [
                'revenue' => $revenue,
                'tax_due' => $revenue * 0.13 // Assuming 13% tax rate
            ];
        }

        return view('accountant.tax-report', compact('quarterlyData', 'year'));
    }

    public function branchComparison(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth());
        $endDate = $request->get('end_date', now()->endOfMonth());
        $clinic = Auth::user()->clinic;
        
        $branches = \App\Models\Branch::all(); // In tenant system, branches are for this clinic
        
        $comparison = $branches->map(function($branch) use ($startDate, $endDate) {
            return [
                'name' => $branch->name,
                'revenue' => \App\Models\Payment::where('branch_id', $branch->id)
                    ->whereBetween('paid_at', [$startDate, $endDate])
                    ->where('status', 'completed')
                    ->where('is_refund', false)
                    ->sum('amount'),
                'expenses' => \App\Models\Expense::where('branch_id', $branch->id)
                    ->whereBetween('expense_date', [$startDate, $endDate])
                    ->sum('amount'),
                'appointments' => \App\Models\Appointment::where('branch_id', $branch->id)
                    ->whereBetween('appointment_date', [$startDate, $endDate])
                    ->count()
            ];
        });

        return view('accountant.branch-comparison', compact('comparison', 'startDate', 'endDate'));
    }

    public function serviceProfitability(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth());
        $endDate = $request->get('end_date', now()->endOfMonth());
        $clinic = Auth::user()->clinic;

        $profitability = \App\Models\Invoice::where('clinic_id', $clinic->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'paid')
            ->selectRaw('treatment_type, SUM(total_amount) as total_revenue, COUNT(*) as usage_count')
            ->groupBy('treatment_type')
            ->orderByDesc('total_revenue')
            ->get();

        return view('accountant.service-profitability', compact('profitability', 'startDate', 'endDate'));
    }

    public function creditNotes()
    {
        $creditNotes = \App\Models\CreditNote::where('clinic_id', Auth::user()->clinic_id)
            ->with(['patient', 'invoice'])
            ->latest()
            ->paginate(15);

        return view('accountant.credit-notes', compact('creditNotes'));
    }

    public function storeCreditNote(Request $request)
    {
        $validated = $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'amount' => 'required|numeric|min:0.01',
            'reason' => 'required|string|max:500',
        ]);

        $invoice = \App\Models\Invoice::findOrFail($validated['invoice_id']);
        
        if ($validated['amount'] > $invoice->total_amount) {
            return back()->with('error', 'Credit note amount cannot exceed invoice total.');
        }

        \App\Models\CreditNote::create([
            'clinic_id' => Auth::user()->clinic_id,
            'invoice_id' => $invoice->id,
            'patient_id' => $invoice->patient_id,
            'credit_note_number' => 'CN-' . strtoupper(uniqid()),
            'amount' => $validated['amount'],
            'reason' => $validated['reason'],
        ]);

        return redirect()->route('clinic.credit-notes.index')->with('success', 'Credit note issued successfully.');
    }

    public function vendors()
    {
        $vendors = \App\Models\Vendor::where('clinic_id', Auth::user()->clinic_id)
            ->withCount('expenses')
            ->paginate(15);

        return view('accountant.vendors', compact('vendors'));
    }

    public function storeVendor(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'category' => 'nullable|string|max:255',
        ]);

        $validated['clinic_id'] = Auth::user()->clinic_id;
        \App\Models\Vendor::create($validated);

        return back()->with('success', 'Vendor added successfully.');
    }

    /**
     * Confirm a pending cash payment (front-desk confirmation).
     */
    public function confirmCashPayment(Payment $payment)
    {
        if ($payment->clinic_id !== Auth::user()->clinic_id) {
            abort(403);
        }

        if ($payment->status !== 'pending' || $payment->payment_method !== 'cash') {
            return back()->with('error', 'This payment cannot be confirmed.');
        }

        $payment->update([
            'status' => 'completed',
            'paid_at' => now(),
        ]);

        // Update invoice status
        $invoice = $payment->invoice;
        if ($invoice) {
            $totalPaid = Payment::where('invoice_id', $invoice->id)
                ->where('status', 'completed')
                ->sum('amount');

            if ($totalPaid >= $invoice->total_amount) {
                $invoice->update(['status' => 'paid', 'paid_at' => now()]);
            } elseif ($totalPaid > 0) {
                $invoice->update(['status' => 'partial']);
            }
        }

        return back()->with('success', 'Cash payment confirmed. Transaction: ' . $payment->transaction_id);
    }

    private function getAverageCollectionTime()
    {
        $paidInvoices = Invoice::where('clinic_id', Auth::user()->clinic_id)
            ->where('status', 'paid')
            ->whereNotNull('paid_at')
            ->get();

        if ($paidInvoices->isEmpty()) {
            return 0;
        }

        $totalDays = $paidInvoices->sum(function ($invoice) {
            return Carbon::parse($invoice->created_at)->diffInDays(Carbon::parse($invoice->paid_at));
        });

        return round($totalDays / $paidInvoices->count(), 1);
    }
    
    public function journalEntries()
    {
        // Mock journal entries for now - can be expanded to real DB table later
        $entries = collect([
            ['date' => '2024-01-15', 'description' => 'Patient Payment Received', 'debit_account' => 'Cash', 'credit_account' => 'Revenue', 'amount' => 5000],
            ['date' => '2024-01-14', 'description' => 'Equipment Purchase', 'debit_account' => 'Equipment', 'credit_account' => 'Cash', 'amount' => 25000],
            ['date' => '2024-01-13', 'description' => 'Salary Payment', 'debit_account' => 'Salary Expense', 'credit_account' => 'Cash', 'amount' => 15000]
        ]);
        
        return view('accountant.journal-entries', compact('entries'));
    }
    
    public function createJournalEntry()
    {
        $accounts = ['Cash', 'Revenue', 'Equipment', 'Salary Expense', 'Rent Expense', 'Accounts Receivable', 'Accounts Payable'];
        return view('accountant.create-journal-entry', compact('accounts'));
    }
    
    public function storeJournalEntry(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'description' => 'required|string|max:255',
            'debit_account' => 'required|string',
            'credit_account' => 'required|string',
            'amount' => 'required|numeric|min:0'
        ]);
        
        // Store journal entry logic here
        return redirect()->route('clinic.journal-entries')->with('success', 'Journal entry created successfully.');
    }
    
    public function ledger()
    {
        // Mock ledger data
        $accounts = [
            'Cash' => ['balance' => 150000, 'entries' => 25],
            'Revenue' => ['balance' => 300000, 'entries' => 18],
            'Equipment' => ['balance' => 500000, 'entries' => 8],
            'Accounts Receivable' => ['balance' => 75000, 'entries' => 12]
        ];
        
        return view('accountant.ledger', compact('accounts'));
    }
    
    public function chartOfAccounts()
    {
        $accounts = [
            'Assets' => ['Cash', 'Accounts Receivable', 'Equipment', 'Inventory'],
            'Liabilities' => ['Accounts Payable', 'Loans Payable', 'Accrued Expenses'],
            'Equity' => ['Owner Equity', 'Retained Earnings'],
            'Revenue' => ['Service Revenue', 'Treatment Revenue'],
            'Expenses' => ['Salary Expense', 'Rent Expense', 'Utilities', 'Supplies']
        ];
        
        return view('accountant.chart-of-accounts', compact('accounts'));
    }
}