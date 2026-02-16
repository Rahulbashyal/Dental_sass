@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">Payment Plan Details</h2>
            <p class="text-sm text-slate-500">Contract #{{ $paymentPlan->id }} | Active Schedule</p>
        </div>
        <div class="flex items-center space-x-3">
            <span class="px-3 py-1 bg-green-50 text-green-700 ring-1 ring-green-100 rounded-full text-xs font-bold uppercase tracking-widest">{{ $paymentPlan->status }}</span>
        </div>
    </div>

    <!-- Summary Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Liability</p>
            <h4 class="text-xl font-black text-slate-900">{{ tenant()->clinic->currency ?? '$' }}{{ number_format($paymentPlan->total_amount, 2) }}</h4>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Down Payment</p>
            <h4 class="text-xl font-black text-slate-900">{{ tenant()->clinic->currency ?? '$' }}{{ number_format($paymentPlan->down_payment, 2) }}</h4>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            @php $paid = $paymentPlan->paymentInstallments()->where('status', 'paid')->sum('amount'); @endphp
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Amount Paid</p>
            <h4 class="text-xl font-black text-green-600">{{ tenant()->clinic->currency ?? '$' }}{{ number_format($paid, 2) }}</h4>
        </div>
        <div class="bg-indigo-900 p-6 rounded-2xl shadow-xl shadow-indigo-100">
            @php $remaining = $paymentPlan->total_amount - $paymentPlan->down_payment - $paid; @endphp
            <p class="text-[10px] font-bold text-indigo-300 uppercase tracking-widest mb-1">Remaining</p>
            <h4 class="text-xl font-black text-white">{{ tenant()->clinic->currency ?? '$' }}{{ number_format($remaining, 2) }}</h4>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Installment Schedule -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-widest">Installment Schedule</h3>
                </div>
                <table class="min-w-full divide-y divide-slate-200">
                    <thead>
                        <tr class="bg-slate-50/10">
                            <th class="px-6 py-3 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">#</th>
                            <th class="px-6 py-3 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Due Date</th>
                            <th class="px-6 py-3 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Amount</th>
                            <th class="px-6 py-3 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</th>
                            <th class="px-6 py-3 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($paymentPlan->paymentInstallments as $installment)
                        <tr class="hover:bg-slate-50/30 transition-colors">
                            <td class="px-6 py-4 text-xs font-bold text-slate-400">{{ $installment->installment_number }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-slate-900">{{ $installment->due_date->format('M d, Y') }} @if($installment->due_date < now() && $installment->status == 'pending') <span class="text-red-500 font-black ml-1">(! Overdue)</span> @endif</td>
                            <td class="px-6 py-4 text-sm font-black text-slate-900">{{ tenant()->clinic->currency ?? '$' }}{{ number_format($installment->amount, 2) }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-0.5 rounded text-[9px] font-black uppercase tracking-tighter {{ $installment->status == 'paid' ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-500' }}">
                                    {{ $installment->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if($installment->status == 'pending')
                                <form action="{{ route('installments.pay', $installment) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-xs font-bold text-indigo-600 hover:text-indigo-800">Mark as Paid</button>
                                </form>
                                @else
                                <span class="text-[10px] text-slate-400 font-medium italic">Paid on {{ $installment->paid_date->format('M d') }}</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Sidebar Details -->
        <div class="space-y-6">
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6">Patient Overview</h4>
                <div class="flex items-center mb-6">
                    <div class="h-10 w-10 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-400 font-black">
                        {{ substr($paymentPlan->patient->full_name, 0, 2) }}
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-bold text-slate-900">{{ $paymentPlan->patient->full_name }}</p>
                        <p class="text-[10px] text-slate-500 font-medium">Record ID: {{ $paymentPlan->patient->patient_id }}</p>
                    </div>
                </div>
                <div class="space-y-4 pt-6 border-t border-slate-50">
                    <div class="flex justify-between items-center text-xs">
                        <span class="text-slate-400 font-bold">Plan Start:</span>
                        <span class="text-slate-900 font-black">{{ $paymentPlan->start_date->format('M d, Y') }}</span>
                    </div>
                    <div class="flex justify-between items-center text-xs">
                        <span class="text-slate-400 font-bold">Installments:</span>
                        <span class="text-slate-900 font-black">{{ $paymentPlan->installments }} Months</span>
                    </div>
                    <div class="flex justify-between items-center text-xs">
                        <span class="text-slate-400 font-bold">Monthly Rate:</span>
                        <span class="text-indigo-600 font-black">{{ tenant()->clinic->currency ?? '$' }}{{ number_format($paymentPlan->installment_amount, 2) }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-indigo-50 p-6 rounded-2xl border border-indigo-100 no-print">
                 <h4 class="text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-4">Contract Actions</h4>
                 <div class="space-y-3">
                    <button onclick="window.print()" class="w-full py-2.5 bg-white border border-indigo-100 text-indigo-600 text-xs font-bold rounded-xl shadow-sm hover:shadow-md transition-all">Print Installment Receipt</button>
                    <a href="{{ route('clinic.invoices.show', $paymentPlan->invoice_id) }}" class="block text-center w-full py-2.5 bg-indigo-600 text-white text-xs font-bold rounded-xl shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition-all">View Original Invoice</a>
                 </div>
            </div>
        </div>
    </div>
</div>
@endsection
