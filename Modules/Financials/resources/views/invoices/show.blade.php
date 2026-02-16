@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="mb-8 flex items-center justify-between no-print">
        <h2 class="text-2xl font-bold text-slate-900 line-clamp-1">Invoice Details</h2>
        <div class="flex items-center space-x-3">
            <button onclick="window.print()" class="inline-flex items-center px-4 py-2 bg-white border border-slate-200 text-slate-700 text-sm font-semibold rounded-lg hover:bg-slate-50 transition-colors shadow-sm">
                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                Print Invoice
            </button>
            @if($invoice->status != 'paid')
            <form action="{{ route('clinic.invoices.pay', $invoice) }}" method="POST">
                @csrf
                @method('PATCH')
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-bold rounded-lg hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-100">
                    Mark as Paid
                </button>
            </form>
            @endif
        </div>
    </div>

    <!-- Professional Invoice Design -->
    <div class="bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden print:shadow-none print:border-none">
        <!-- Header / Branding -->
        <div class="px-10 py-12 border-b border-slate-100 bg-slate-50/50 flex flex-col md:flex-row justify-between items-start">
            <div>
                @if(tenant()->clinic->logo)
                    <img src="{{ Storage::url(tenant()->clinic->logo) }}" alt="Logo" class="h-16 w-auto mb-6">
                @else
                    <h1 class="text-2xl font-black text-indigo-600 mb-2">{{ tenant()->clinic->name }}</h1>
                @endif
                <p class="text-xs text-slate-500 font-medium max-w-xs leading-relaxed">
                    {{ tenant()->clinic->address }}<br>
                    {{ tenant()->clinic->phone }} | {{ tenant()->clinic->email }}
                </p>
            </div>
            <div class="mt-8 md:mt-0 text-right">
                <h2 class="text-4xl font-black text-slate-900 tracking-tight uppercase">Invoice</h2>
                <div class="mt-4 space-y-1">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Invoice Number</p>
                    <p class="text-sm font-mono font-bold text-indigo-600">#{{ $invoice->invoice_number }}</p>
                </div>
            </div>
        </div>

        <!-- Bill To / Info -->
        <div class="px-10 py-10 grid grid-cols-2 md:grid-cols-3 gap-8">
            <div>
                <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3">Bill To:</h4>
                <p class="text-base font-bold text-slate-900">{{ $invoice->patient->full_name }}</p>
                <p class="text-xs text-slate-500 font-medium mt-1">
                    Patient ID: {{ $invoice->patient->patient_id }}<br>
                    {{ $invoice->patient->email }}<br>
                    {{ $invoice->patient->phone }}
                </p>
            </div>
            <div>
                <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3">Invoice Details:</h4>
                <div class="space-y-2">
                    <div class="flex justify-between md:justify-start md:space-x-8">
                        <span class="text-[10px] font-bold text-slate-500">Issued On</span>
                        <span class="text-xs text-slate-900 font-medium">{{ $invoice->issued_date->format('M d, Y') }}</span>
                    </div>
                    <div class="flex justify-between md:justify-start md:space-x-8">
                        <span class="text-[10px] font-bold text-slate-500">Due Date</span>
                        <span class="text-xs text-slate-900 font-medium">{{ $invoice->due_date->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>
            <div class="col-span-2 md:col-span-1 flex flex-col items-start md:items-end">
                <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3">Payment Status:</h4>
                @if($invoice->status == 'paid')
                    <div class="px-4 py-2 bg-green-50 text-green-700 rounded-xl border border-green-200 flex flex-col items-center">
                        <span class="font-black text-sm uppercase tracking-wider">PAID</span>
                        <span class="text-[10px] font-bold mt-0.5 opacity-60">{{ $invoice->paid_at?->format('M d, Y') }}</span>
                    </div>
                @else
                    <div class="px-4 py-2 bg-amber-50 text-amber-700 rounded-xl border border-amber-200 flex flex-col items-center">
                        <span class="font-black text-sm uppercase tracking-wider">PENDING</span>
                        <span class="text-[10px] font-bold mt-0.5 opacity-60">Pay by {{ $invoice->due_date->format('M d, Y') }}</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Table -->
        <div class="px-10 pb-12">
            <table class="w-full">
                <thead>
                    <tr class="border-b-2 border-slate-100">
                        <th class="py-4 text-left text-[10px] font-bold text-slate-400 uppercase tracking-widest">Service / Treatment Description</th>
                        <th class="py-4 text-right text-[10px] font-bold text-slate-400 uppercase tracking-widest">Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <tr>
                        <td class="py-6">
                            <span class="text-sm font-bold text-slate-900">{{ $invoice->description }}</span>
                            @if($invoice->appointment)
                                <p class="text-[10px] text-slate-400 font-medium mt-1">Clinical Procedure: {{ $invoice->appointment->type }} (Dr. {{ $invoice->appointment->dentist->name }})</p>
                            @endif
                        </td>
                        <td class="py-6 text-right text-sm font-bold text-slate-900">
                            {{ tenant()->clinic->currency ?? '$' }}{{ number_format($invoice->amount, 2) }}
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Footers / Totals -->
            <div class="mt-8 flex flex-col md:flex-row justify-between items-start">
                <div class="max-w-xs">
                    <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Note:</h4>
                    <p class="text-xs text-slate-500 leading-relaxed italic">{{ $invoice->notes ?? 'Thank you for choosing our clinic for your dental care.' }}</p>
                </div>
                <div class="w-full md:w-64 space-y-3 mt-8 md:mt-0 bg-slate-50 p-6 rounded-2xl border border-slate-100">
                    <div class="flex justify-between items-center text-xs font-bold text-slate-500">
                        <span>Subtotal</span>
                        <span class="text-slate-900">{{ tenant()->clinic->currency ?? '$' }}{{ number_format($invoice->amount, 2) }}</span>
                    </div>
                    @if($invoice->tax_amount > 0)
                    <div class="flex justify-between items-center text-xs font-bold text-slate-500">
                        <span>Tax</span>
                        <span class="text-slate-900">{{ tenant()->clinic->currency ?? '$' }}{{ number_format($invoice->tax_amount, 2) }}</span>
                    </div>
                    @endif
                    @if($invoice->discount_amount > 0)
                    <div class="flex justify-between items-center text-xs font-bold text-red-500">
                        <span>Discount</span>
                        <span>-{{ tenant()->clinic->currency ?? '$' }}{{ number_format($invoice->discount_amount, 2) }}</span>
                    </div>
                    @endif
                    <div class="pt-3 border-t border-slate-200 flex justify-between items-center">
                        <span class="text-sm font-black text-slate-900 uppercase">Total Due</span>
                        <span class="text-lg font-black text-indigo-600">{{ tenant()->clinic->currency ?? '$' }}{{ number_format($invoice->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Branding -->
        <div class="px-10 py-6 bg-indigo-900 flex justify-between items-center">
            <span class="text-[10px] font-bold text-indigo-300 uppercase tracking-widest">Powered by NexDMD Solutions</span>
            <span class="text-[10px] font-bold text-white uppercase tracking-widest">Page 1 of 1</span>
        </div>
    </div>
</div>

<style>
@media print {
    body { background: white !important; }
    .no-print { display: none !important; }
}
</style>
@endsection
