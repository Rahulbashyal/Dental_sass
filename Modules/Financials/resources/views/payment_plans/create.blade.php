@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">Establish Payment Plan</h2>
            <p class="text-sm text-slate-500">Divide financial liability into manageable installments.</p>
        </div>
        <a href="{{ route('payment-plans.index') }}" class="text-sm font-medium text-slate-500 hover:text-slate-700">Back to List</a>
    </div>

    @if($invoice)
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-8 bg-slate-50/50 border-b border-slate-100 flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Invoice Details</p>
                <h3 class="text-lg font-black text-slate-900">{{ $invoice->invoice_number }}</h3>
                <p class="text-sm text-slate-500">{{ $invoice->patient->full_name }}</p>
            </div>
            <div class="text-right">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Outstanding Balance</p>
                <h3 class="text-2xl font-black text-indigo-600">{{ tenant()->clinic->currency ?? '$' }}{{ number_format($invoice->total_amount, 2) }}</h3>
            </div>
        </div>

        <form action="{{ route('payment-plans.store') }}" method="POST" class="p-8">
            @csrf
            <input type="hidden" name="invoice_id" value="{{ $invoice->id }}">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <div>
                    <label for="down_payment" class="block text-sm font-bold text-slate-700 mb-1">Initial Down Payment ({{ tenant()->clinic->currency ?? '$' }})</label>
                    <input type="number" name="down_payment" id="down_payment" step="0.01" value="0.00" class="block w-full rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm font-bold h-11">
                </div>

                <div>
                    <label for="installments" class="block text-sm font-bold text-slate-700 mb-1">Number of Installments (Months)</label>
                    <select name="installments" id="installments" class="block w-full rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm h-11">
                        @foreach([2,3,4,6,12,18,24] as $n)
                            <option value="{{ $n }}">{{ $n }} Monthly Payments</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="start_date" class="block text-sm font-bold text-slate-700 mb-1">Installment Start Date</label>
                    <input type="date" name="start_date" id="start_date" value="{{ date('Y-m-d') }}" class="block w-full rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm h-11">
                </div>
            </div>

            <div class="bg-indigo-50 rounded-xl p-6 border border-indigo-100 flex items-center mb-8">
                <div class="flex-shrink-0 bg-indigo-100 p-2 rounded-lg">
                    <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-[10px] font-bold text-indigo-400 uppercase tracking-widest">Plan Projection</p>
                    <p class="text-sm text-indigo-700 mt-1">Estimating installment amounts based on input...</p>
                    <div id="projection-results" class="hidden mt-2 flex items-center space-x-4">
                         <div>
                            <span class="text-[10px] text-indigo-400 uppercase font-bold">Per Installment:</span>
                             <span id="projected-amount" class="text-sm font-black text-indigo-900 ml-1"></span>
                         </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-8 py-3 bg-indigo-600 shadow-xl shadow-indigo-100 text-white text-sm font-bold rounded-xl hover:bg-indigo-700 transition-all flex items-center">
                    Establish Contract
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </button>
            </div>
        </form>
    </div>

    <script>
        const total = {{ $invoice->total_amount }};
        const downInput = document.getElementById('down_payment');
        const instSelect = document.getElementById('installments');
        const projection = document.getElementById('projection-results');
        const amountDisplay = document.getElementById('projected-amount');

        function updateProjection() {
            const down = parseFloat(downInput.value) || 0;
            const count = parseInt(instSelect.value);
            const remaining = total - down;
            if (remaining > 0) {
                const perMonth = (remaining / count).toFixed(2);
                amountDisplay.textContent = `{{ tenant()->clinic->currency ?? '$' }}${perMonth}`;
                projection.classList.remove('hidden');
            } else {
                projection.classList.add('hidden');
            }
        }

        downInput.addEventListener('input', updateProjection);
        instSelect.addEventListener('change', updateProjection);
        updateProjection();
    </script>
    @else
    <div class="bg-white p-12 text-center rounded-2xl border border-slate-200">
        <p class="text-slate-500">Please initiate a payment plan directly from an Invoice view.</p>
        <a href="{{ route('clinic.invoices.index') }}" class="mt-4 inline-block text-indigo-600 font-bold hover:underline">Go to Invoices</a>
    </div>
    @endif
</div>
@endsection
