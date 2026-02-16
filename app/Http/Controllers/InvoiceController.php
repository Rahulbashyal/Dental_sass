<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Patient;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'clinic.access']);
    }
    
    public function index()
    {
        $user = Auth::user();
        $query = $user->clinic->invoices()->with(['patient', 'appointment']);
        
        // If user is dentist, only show invoices for their appointments
        if ($user->hasRole('dentist')) {
            $query->whereHas('appointment', function($q) use ($user) {
                $q->where('dentist_id', $user->id);
            });
        }
        
        $invoices = $query->latest()->paginate(10);
        $canEdit = $user->hasAnyRole(['accountant', 'clinic_admin']);
        
        return view('invoices.index', compact('invoices', 'canEdit'));
    }

    public function create()
    {
        $patients = Auth::user()->clinic->patients;
        $appointments = Auth::user()->clinic->appointments()->where('status', 'completed')->get();
        return view('invoices.create', compact('patients', 'appointments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'appointment_id' => 'nullable|exists:appointments,id',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'due_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $itemTotal = collect($validated['items'])->sum(function($item) {
            return $item['quantity'] * $item['unit_price'];
        });

        $tax = $validated['tax_amount'] ?? 0;
        $discount = $validated['discount_amount'] ?? 0;
        $total = $itemTotal + $tax - $discount;

        $invoiceData = [
            'clinic_id' => Auth::user()->clinic_id,
            'patient_id' => $validated['patient_id'],
            'appointment_id' => $validated['appointment_id'],
            'invoice_number' => 'INV' . date('Ymd') . str_pad(\App\Models\Invoice::where('clinic_id', Auth::user()->clinic_id)->count() + 1, 4, '0', STR_PAD_LEFT),
            'amount' => $itemTotal,
            'tax_amount' => $tax,
            'discount_amount' => $discount,
            'total_amount' => $total,
            'status' => 'pending',
            'due_date' => $validated['due_date'],
            'issued_date' => now(),
            'notes' => $validated['notes'],
        ];

        $invoice = \App\Models\Invoice::create($invoiceData);

        foreach ($validated['items'] as $item) {
            $invoice->items()->create([
                'description' => $item['description'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'amount' => $item['quantity'] * $item['unit_price'],
            ]);
        }
        
        // Send invoice email to patient
        if ($invoice->patient && $invoice->patient->email) {
            $invoice->patient->notify(new \App\Notifications\InvoiceGenerated($invoice));
        }

        return redirect()->route('clinic.invoices.show', $invoice)->with('success', 'Invoice created successfully! Itemized billing applied.');
    }

    public function show(Invoice $invoice)
    {
        // Authorization: Check clinic ownership
        if ($invoice->clinic_id !== Auth::user()->clinic_id) {
            abort(403, 'Unauthorized access to invoice.');
        }
        
        return view('invoices.show', compact('invoice'));
    }

    public function markPaid(Invoice $invoice)
    {
        // Authorization: Check clinic ownership
        if ($invoice->clinic_id !== Auth::user()->clinic_id) {
            abort(403, 'Unauthorized access to invoice.');
        }
        
        $invoice->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        return redirect()->route('clinic.invoices.index')->with('success', 'Invoice marked as paid.');
    }
}