@extends(request()->has('iframe') ? 'layouts.iframe' : 'layouts.app')

@section('title', 'Create Payment Plan')
@section('page-title', 'Create Payment Plan')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="card bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
        <div class="p-6 border-b border-gray-50 bg-gray-50/50">
            <h2 class="text-xl font-bold text-gray-900">New Financial Agreement</h2>
            <p class="text-sm text-gray-500 mt-1">Split large patient invoices into manageable monthly installments.</p>
        </div>
        
        <form action="{{ route('clinic.payment-plans.store') }}" method="POST" class="p-8 space-y-8" id="paymentPlanForm">
    @if(request()->has('iframe'))
        <input type="hidden" name="iframe" value="1">
    @endif

            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-2">
                    <label class="form-label text-gray-700 font-semibold">Patient</label>
                    <select name="patient_id" id="patient_id" class="form-input block w-full h-12 rounded-xl border-gray-200" required>
                        <option value="">Select Patient</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}">{{ $patient->first_name }} {{ $patient->last_name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="space-y-2">
                    <label class="form-label text-gray-700 font-semibold">Invoice to Link</label>
                    <select name="invoice_id" id="invoice_id" class="form-input block w-full h-12 rounded-xl border-gray-200" required>
                        <option value="">Select Invoice</option>
                        @foreach($invoices as $invoice)
                            <option value="{{ $invoice->id }}" data-amount="{{ $invoice->total_amount }}" {{ $selectedInvoice && $selectedInvoice->id == $invoice->id ? 'selected' : '' }}>
                                {{ $invoice->invoice_number }} (Rs. {{ number_format($invoice->total_amount, 2) }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="bg-indigo-50/30 p-6 rounded-2xl border border-indigo-100/50 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-left">
                    <div class="space-y-2">
                        <label class="form-label text-gray-700 font-semibold">Total Amount</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">Rs.</span>
                            <input type="number" name="total_amount" id="total_amount" class="form-input block w-full pl-10 h-12 rounded-xl border-gray-200" required readonly>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="form-label text-gray-700 font-semibold">Down Payment</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">Rs.</span>
                            <input type="number" name="down_payment" id="down_payment" step="0.01" class="form-input block w-full pl-10 h-12 rounded-xl border-gray-200" required value="0">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="form-label text-gray-700 font-semibold">Installments (Months)</label>
                        <input type="number" name="installments" id="installments" min="1" max="24" class="form-input block w-full h-12 rounded-xl border-gray-200" required value="6">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-left">
                    <div class="space-y-2">
                        <label class="form-label text-gray-700 font-semibold">Start Date</label>
                        <input type="date" name="start_date" class="form-input block w-full h-12 rounded-xl border-gray-200" required value="{{ date('Y-m-d') }}">
                    </div>
                    
                    <div class="flex items-center justify-center bg-white p-4 rounded-xl border border-indigo-100 shadow-sm">
                        <div class="text-center">
                            <p class="text-xs text-gray-500 uppercase font-bold tracking-wider">Estimated Monthly</p>
                            <p class="text-2xl font-black text-indigo-600" id="monthly_estimate">Rs. 0.00</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="pt-6 border-t border-gray-100 flex items-center justify-between">
                <p class="text-xs text-gray-400 italic">Financial agreement will be generated for the patient to sign.</p>
                <div class="flex space-x-4">
                    <a href="{{ route('clinic.payment-plans.index') }}" class="px-6 py-3 rounded-xl border border-gray-200 text-gray-600 font-semibold hover:bg-gray-50 transition duration-150">
                        Cancel
                    </a>
                    <button type="submit" class="px-8 py-3 bg-indigo-600 text-white rounded-xl font-bold shadow-lg shadow-indigo-200 hover:bg-indigo-700 transform hover:-translate-y-0.5 transition duration-150">
                        Create Plan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    const invoiceSelect = document.getElementById('invoice_id');
    const totalInput = document.getElementById('total_amount');
    const downPaymentInput = document.getElementById('down_payment');
    const installmentsInput = document.getElementById('installments');
    const estimateLabel = document.getElementById('monthly_estimate');

    function calculateEstimate() {
        const total = parseFloat(totalInput.value) || 0;
        const down = parseFloat(downPaymentInput.value) || 0;
        const count = parseInt(installmentsInput.value) || 1;
        
        const remaining = Math.max(0, total - down);
        const monthly = remaining / count;
        
        estimateLabel.textContent = 'Rs. ' + monthly.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
    }

    invoiceSelect.addEventListener('change', function() {
        const selected = this.options[this.selectedIndex];
        const amount = selected.getAttribute('data-amount');
        totalInput.value = amount || 0;
        calculateEstimate();
    });

    downPaymentInput.addEventListener('input', calculateEstimate);
    installmentsInput.addEventListener('input', calculateEstimate);

    // Initial calculation if invoice is pre-selected
    if (invoiceSelect.value) {
        const selected = invoiceSelect.options[invoiceSelect.selectedIndex];
        totalInput.value = selected.getAttribute('data-amount');
        calculateEstimate();
    }
</script>
@endpush
@endsection


{{-- Auto-close modal script on success --}}
@if(session('success') && request()->has('iframe'))
    <script>
        setTimeout(() => {
            window.parent.location.reload();
        }, 1500);
    </script>
@endif
