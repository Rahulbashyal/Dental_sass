<?php

namespace Modules\Financials\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Patient;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status');
        $query = Invoice::with(['patient', 'appointment'])->orderBy('created_at', 'desc');

        if ($status) {
            $query->where('status', $status);
        }

        $invoices = $query->paginate(15);
        
        $stats = [
            'total' => Invoice::sum('total_amount'),
            'paid' => Invoice::where('status', 'paid')->sum('total_amount'),
            'pending' => Invoice::where('status', 'pending')->sum('total_amount'),
            'overdue' => Invoice::where('status', 'overdue')->sum('total_amount'),
        ];

        return view('financials::invoices.index', compact('invoices', 'stats', 'status'));
    }

    public function create(Request $request)
    {
        $patients = Patient::where('is_active', true)->get();
        $appointments = [];
        
        if ($request->has('patient_id')) {
            $appointments = Appointment::where('patient_id', $request->patient_id)
                ->where('status', '!=', 'cancelled')
                ->latest()
                ->get();
        }

        return view('financials::invoices.create', compact('patients', 'appointments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'appointment_id' => 'nullable|exists:appointments,id',
            'amount' => 'required|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'due_date' => 'required|date',
            'description' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $treatment_type = $validated['description'];
        if ($validated['appointment_id']) {
            $appointment = Appointment::find($validated['appointment_id']);
            $treatment_type = $appointment->type;
        }

        $total_amount = $validated['amount'] + ($validated['tax_amount'] ?? 0) - ($validated['discount_amount'] ?? 0);

        $invoice = Invoice::create(array_merge($validated, [
            'clinic_id' => tenant()->clinic->id,
            'invoice_number' => 'INV-' . strtoupper(uniqid()),
            'total_amount' => $total_amount,
            'status' => 'pending',
            'issued_date' => Carbon::today(),
            'treatment_type' => $treatment_type,
        ]));

        return redirect()->route('clinic.invoices.index')
            ->with('status', "Invoice #{$invoice->invoice_number} generated successfully.");
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['patient', 'appointment', 'payments']);
        return view('financials::invoices.show', compact('invoice'));
    }

    public function markAsPaid(Invoice $invoice)
    {
        $invoice->update([
            'status' => 'paid',
            'paid_at' => Carbon::now(),
            'paid_date' => Carbon::today(),
        ]);

        // Create a payment record for the audit trail
        \App\Models\Payment::create([
            'invoice_id' => $invoice->id,
            'patient_id' => $invoice->patient_id,
            'clinic_id' => $invoice->clinic_id,
            'amount' => $invoice->total_amount,
            'payment_method' => 'Cash', // Default for manual marking
            'status' => 'completed',
            'transaction_id' => 'MANual-' . strtoupper(uniqid()),
            'paid_at' => Carbon::now(),
        ]);

        return back()->with('status', "Invoice #{$invoice->invoice_number} marked as paid.");
    }
}
