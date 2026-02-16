@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">Billing & Invoices</h2>
            <p class="text-sm text-slate-500">Manage patient payments, pending dues, and clinic revenue.</p>
        </div>
        <a href="{{ route('clinic.invoices.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow-sm">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Create Invoice
        </a>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Revenue</p>
            <h4 class="text-2xl font-black text-slate-900">{{ tenant()->clinic->currency ?? '$' }}{{ number_format($stats['total'], 2) }}</h4>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <p class="text-[10px] font-bold text-green-500 uppercase tracking-widest mb-1">Collected</p>
            <h4 class="text-2xl font-black text-slate-900">{{ tenant()->clinic->currency ?? '$' }}{{ number_format($stats['paid'], 2) }}</h4>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <p class="text-[10px] font-bold text-amber-500 uppercase tracking-widest mb-1">Pending</p>
            <h4 class="text-2xl font-black text-slate-900">{{ tenant()->clinic->currency ?? '$' }}{{ number_format($stats['pending'], 2) }}</h4>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <p class="text-[10px] font-bold text-red-500 uppercase tracking-widest mb-1">Overdue</p>
            <h4 class="text-2xl font-black text-slate-900">{{ tenant()->clinic->currency ?? '$' }}{{ number_format($stats['overdue'], 2) }}</h4>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm mb-6 flex items-center justify-between">
        <div class="flex items-center space-x-2">
            <a href="{{ route('clinic.invoices.index') }}" class="px-3 py-1.5 rounded-lg text-xs font-bold {{ !$status ? 'bg-indigo-600 text-white' : 'text-slate-500 hover:bg-slate-50' }}">All</a>
            <a href="{{ route('clinic.invoices.index', ['status' => 'paid']) }}" class="px-3 py-1.5 rounded-lg text-xs font-bold {{ $status == 'paid' ? 'bg-green-600 text-white' : 'text-slate-500 hover:bg-slate-50' }}">Paid</a>
            <a href="{{ route('clinic.invoices.index', ['status' => 'pending']) }}" class="px-3 py-1.5 rounded-lg text-xs font-bold {{ $status == 'pending' ? 'bg-amber-600 text-white' : 'text-slate-500 hover:bg-slate-50' }}">Pending</a>
            <a href="{{ route('clinic.invoices.index', ['status' => 'overdue']) }}" class="px-3 py-1.5 rounded-lg text-xs font-bold {{ $status == 'overdue' ? 'bg-red-600 text-white' : 'text-slate-500 hover:bg-slate-50' }}">Overdue</a>
        </div>
        <div class="relative w-64">
            <input type="text" placeholder="Search invoice or patient..." class="w-full pl-10 pr-4 py-2 rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead>
                    <tr class="bg-slate-50">
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Invoice #</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Patient</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Due Date</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-200">
                @forelse($invoices as $invoice)
                    <tr class="hover:bg-slate-50/80 transition-colors group">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-xs font-mono font-bold text-indigo-600">{{ $invoice->invoice_number }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="text-sm font-bold text-slate-900">{{ $invoice->patient->full_name }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-black text-slate-900">{{ tenant()->clinic->currency ?? '$' }}{{ number_format($invoice->total_amount, 2) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-xs text-slate-500">{{ $invoice->due_date->format('M d, Y') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($invoice->status == 'paid')
                                <span class="px-2 py-1 rounded bg-green-50 text-green-700 text-[10px] font-bold uppercase tracking-wider ring-1 ring-green-200">Paid</span>
                            @elseif($invoice->status == 'pending')
                                <span class="px-2 py-1 rounded bg-amber-50 text-amber-700 text-[10px] font-bold uppercase tracking-wider ring-1 ring-amber-200">Pending</span>
                            @elseif($invoice->status == 'overdue')
                                <span class="px-2 py-1 rounded bg-red-50 text-red-700 text-[10px] font-bold uppercase tracking-wider ring-1 ring-red-200">Overdue</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                            <div class="flex items-center justify-end space-x-2">
                                <a href="{{ route('clinic.invoices.show', $invoice) }}" class="p-1.5 text-slate-400 hover:text-indigo-600 transition-colors">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                                @if($invoice->status != 'paid')
                                <a href="{{ route('payment-plans.create', ['invoice_id' => $invoice->id]) }}" class="p-1.5 text-slate-400 hover:text-indigo-600 transition-colors" title="Establish Payment Plan">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </a>
                                <form action="{{ route('clinic.invoices.pay', $invoice) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="p-1.5 text-slate-400 hover:text-green-600 transition-colors" title="Mark as Paid">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </button>
                                </form>
                                @endif
                                <button class="p-1.5 text-slate-400 hover:text-slate-600 transition-colors">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                            <div class="flex flex-col items-center">
                                <svg class="h-12 w-12 text-slate-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="text-sm font-medium">No invoices found</p>
                                <p class="text-xs text-slate-400 mt-1">Generate your first patient invoice by clicking "Create Invoice" above.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if($invoices->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/30">
                {{ $invoices->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
