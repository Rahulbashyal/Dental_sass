@extends('patient-portal.layout')

@section('title', 'Billing & Payments')

@section('content')
<div class="max-w-6xl mx-auto space-y-10 page-fade-in pb-20">
    @php
        $patient = Auth::guard('patient')->user();
        $clinicColor = ($patient && $patient->clinic) ? $patient->clinic->primary_color : '#0ea5e9';
        $outstandingTotal = $invoices->sum('remaining_amount');
    @endphp

    <!-- Page Header & Stats -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
        <div class="space-y-1">
            <h1 class="text-3xl font-black text-slate-900 tracking-tight Outfit">Financial Ledger</h1>
            <p class="text-slate-500 font-medium">Manage your clinical fees, payment history, and outstanding balances.</p>
        </div>

        <div class="grid grid-cols-2 gap-4 w-full md:w-auto">
            <div class="bg-white px-6 py-4 rounded-[1.5rem] border border-slate-200 shadow-sm">
                <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Total Outstanding</p>
                <p class="text-xl font-black text-slate-900 Outfit">NPR {{ number_format($outstandingTotal, 2) }}</p>
            </div>
            <div class="bg-white px-6 py-4 rounded-[1.5rem] border border-slate-200 shadow-sm">
                <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Invoice Count</p>
                <p class="text-xl font-black text-slate-900 Outfit">{{ $invoices->total() }}</p>
            </div>
        </div>
    </div>

    <!-- Main Invoices Table -->
    <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-sm overflow-hidden backdrop-blur-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-10 py-5 text-xs font-black text-slate-400 uppercase tracking-widest">Document</th>
                        <th class="px-10 py-5 text-xs font-black text-slate-400 uppercase tracking-widest">Service Description</th>
                        <th class="px-10 py-5 text-xs font-black text-slate-400 uppercase tracking-widest">Status</th>
                        <th class="px-10 py-5 text-right text-xs font-black text-slate-400 uppercase tracking-widest">Amount</th>
                        <th class="px-10 py-5 text-right text-xs font-black text-slate-400 uppercase tracking-widest">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($invoices as $invoice)
                    <tr class="group hover:bg-slate-50/30 transition-all duration-200">
                        <!-- Document Info -->
                        <td class="px-10 py-6">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-400 group-hover:bg-white group-hover:shadow-md transition-all duration-300">
                                    <i class="fas fa-file-invoice text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-black text-slate-900 tracking-tight">{{ $invoice->invoice_number }}</p>
                                    <p class="text-xs font-bold text-slate-400 uppercase">{{ $invoice->created_at->format('M d, Y') }}</p>
                                </div>
                            </div>
                        </td>

                        <!-- Description -->
                        <td class="px-10 py-6">
                            <p class="text-sm text-slate-600 font-medium max-w-xs truncate">
                                {{ $invoice->description ?? 'General Dental Services' }}
                            </p>
                        </td>

                        <!-- Status Badge -->
                        <td class="px-10 py-6">
                            @if($invoice->status === 'paid')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black uppercase tracking-widest bg-emerald-50 text-emerald-600 border border-emerald-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-600 animate-pulse"></span>
                                    Fulfilled
                                </span>
                            @elseif($invoice->status === 'partially_paid')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black uppercase tracking-widest bg-sky-50 text-sky-600 border border-sky-100">
                                    Partial
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black uppercase tracking-widest bg-amber-50 text-amber-600 border border-amber-100">
                                    Pending
                                </span>
                            @endif
                        </td>

                        <!-- Amount Details -->
                        <td class="px-10 py-6 text-right">
                            <p class="text-base font-black text-slate-900 Outfit whitespace-nowrap">NPR {{ number_format($invoice->total_amount, 2) }}</p>
                            @if($invoice->status !== 'paid')
                                <p class="text-xs font-bold text-rose-500 uppercase tracking-tighter whitespace-nowrap">Due: NPR {{ number_format($invoice->remaining_amount, 2) }}</p>
                            @endif
                        </td>

                        <!-- Actions -->
                        <td class="px-10 py-6 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @if($invoice->status !== 'paid')
                                    <a href="{{ route('patient.payment', $invoice) }}" 
                                       class="px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest text-white transition-all transform hover:scale-105 active:scale-95 shadow-lg shadow-sky-100"
                                       style="background-color: {{ $clinicColor }}">
                                        Pay Now
                                    </a>
                                @endif
                                <button onclick="window.print()" class="w-10 h-10 rounded-xl bg-slate-50 text-slate-400 flex items-center justify-center hover:bg-slate-900 hover:text-white transition-all duration-300">
                                    <i class="fas fa-print text-xs"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-32 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-20 h-20 bg-slate-50 rounded-[2rem] flex items-center justify-center mb-6 text-slate-200">
                                    <i class="fas fa-wallet text-3xl"></i>
                                </div>
                                <h3 class="text-xl font-black text-slate-900 Outfit">No Invoices Found</h3>
                                <p class="text-slate-500 max-w-xs mx-auto mt-2 font-medium">Your procedural billing history will appear here once treatments are initiated.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($invoices->hasPages())
    <div class="mt-8">
        {{ $invoices->links() }}
    </div>
    @endif

    <!-- Trust Footer -->
    <div class="flex items-center justify-center gap-4 text-slate-400 pt-8 opacity-60">
        <div class="flex items-center gap-1.5">
            <i class="fas fa-lock text-xs"></i>
            <span class="text-xs font-black uppercase tracking-widest">Secure 256-bit Encryption</span>
        </div>
        <div class="w-1 h-1 rounded-full bg-slate-300"></div>
        <div class="flex items-center gap-1.5">
            <i class="fas fa-shield-alt text-xs"></i>
            <span class="text-xs font-black uppercase tracking-widest">PCI-DSS Compliant</span>
        </div>
    </div>
</div>
@endsection
