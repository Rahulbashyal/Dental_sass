@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">Patient Payment Plans</h2>
            <p class="text-sm text-slate-500">Manage 0% interest clinical installment pathways.</p>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm transition-all hover:shadow-md">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Active Plans</p>
            <h4 class="text-2xl font-black text-slate-900">{{ $stats['active'] }}</h4>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm transition-all hover:shadow-md">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Contract Value</p>
            <h4 class="text-2xl font-black text-slate-900">{{ tenant()->clinic->currency ?? '$' }}{{ number_format($stats['total_value'], 2) }}</h4>
        </div>
        <div class="bg-indigo-900 p-6 rounded-2xl shadow-xl shadow-indigo-100">
            <p class="text-[10px] font-bold text-indigo-300 uppercase tracking-widest mb-1">Outstanding Liability</p>
            <h4 class="text-2xl font-black text-white">{{ tenant()->clinic->currency ?? '$' }}{{ number_format($stats['outstanding'], 2) }}</h4>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead>
                    <tr class="bg-slate-50">
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Patient</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Invoice</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Installments</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-200">
                @forelse($paymentPlans as $plan)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-slate-900">{{ $plan->patient->full_name }}</div>
                            <div class="text-[10px] text-slate-500 font-medium">{{ $plan->patient->patient_id }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-xs font-mono font-bold text-indigo-600">{{ $plan->invoice->invoice_number }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-black text-slate-900">{{ tenant()->clinic->currency ?? '$' }}{{ number_format($plan->total_amount, 2) }}</div>
                            <div class="text-[10px] text-slate-400">Down: {{ number_format($plan->down_payment, 2) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-slate-700">{{ $plan->installments }} Months</div>
                            <div class="text-[10px] text-slate-400">{{ tenant()->clinic->currency ?? '$' }}{{ number_format($plan->installment_amount, 2) }} / mo</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusClass = match($plan->status) {
                                    'active' => 'bg-green-50 text-green-700 ring-green-100',
                                    'completed' => 'bg-blue-50 text-blue-700 ring-blue-100',
                                    'defaulted' => 'bg-red-50 text-red-700 ring-red-100',
                                };
                            @endphp
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider ring-1 {{ $statusClass }}">{{ $plan->status }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <a href="{{ route('payment-plans.show', $plan) }}" class="inline-flex items-center px-3 py-1 bg-slate-50 hover:bg-slate-100 text-slate-600 text-[10px] font-bold rounded shadow-sm border border-slate-100 transition-all">
                                Manage Plan
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-500 italic text-sm">No payment plans active.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
