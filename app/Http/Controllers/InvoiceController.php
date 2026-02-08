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
            'amount' => 'required|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'due_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $validated['clinic_id'] = Auth::user()->clinic_id;
        $validated['invoice_number'] = 'INV' . date('Ymd') . str_pad(Invoice::where('clinic_id', Auth::user()->clinic_id)->count() + 1, 4, '0', STR_PAD_LEFT);
        $validated['invoice_date'] = $validated['invoice_date'] ?? now();
        $validated['due_date'] = $validated['due_date'] ?? now()->addDays(30);

        $invoice = Invoice::create($validated);
        
        // Send invoice email to patient
        if ($invoice->patient && $invoice->patient->email) {
            $invoice->patient->notify(new \App\Notifications\InvoiceGenerated($invoice));
        }

        return redirect()->route('invoices.show', $invoice)->with('success', 'Invoice created successfully! Email sent to patient.');
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

        return redirect()->route('invoices.index')->with('success', 'Invoice marked as paid.');
    }
}