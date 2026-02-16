@extends('layouts.app')

@section('title', 'Payment Plan Details')
@section('page-title', 'Payment Plan Details')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('clinic.payment-plans.index') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-indigo-600">
            <svg class="mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Plans
        </a>
        <div class="flex space-x-3">
            <button class="px-4 py-2 border border-gray-200 text-gray-600 rounded-lg hover:bg-gray-50 font-semibold transition">
                Print Agreement
            </button>
            <button class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-semibold shadow-md transition">
                Record Payment
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Plan Overview -->
        <div class="lg:col-span-1 space-y-6">
            <div class="card p-6 bg-white border border-gray-100 shadow-sm rounded-2xl">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Plan Overview</h3>
                <div class="space-y-4">
                    <div class="flex justify-between py-2 border-b border-gray-50">
                        <span class="text-gray-500">Patient</span>
                        <span class="font-semibold">{{ $paymentPlan->patient->first_name }} {{ $paymentPlan->patient->last_name }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-50">
                        <span class="text-gray-500">Invoice</span>
                        <span class="font-semibold text-indigo-600">{{ $paymentPlan->invoice->invoice_number }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-50">
                        <span class="text-gray-500">Total Amount</span>
                        <span class="font-bold">Rs. {{ number_format($paymentPlan->total_amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-50">
                        <span class="text-gray-500">Status</span>
                        <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-700">Active</span>
                    </div>
                </div>
            </div>

            <div class="card p-6 bg-indigo-600 text-white rounded-2xl shadow-xl shadow-indigo-100">
                <p class="text-indigo-200 text-sm font-medium mb-1">Monthly Installment</p>
                <p class="text-3xl font-black">Rs. {{ number_format($paymentPlan->installment_amount, 2) }}</p>
                <div class="mt-4 pt-4 border-t border-indigo-500 text-sm">
                    <div class="flex justify-between mb-1">
                        <span>Paid Progress</span>
                        <span>{{ round(($paymentPlan->paymentInstallments->where('status', 'paid')->count() / $paymentPlan->installments) * 100) }}%</span>
                    </div>
                    <div class="w-full bg-indigo-700 rounded-full h-2">
                        <div class="bg-white h-2 rounded-full" style="width: {{ ($paymentPlan->paymentInstallments->where('status', 'paid')->count() / $paymentPlan->installments) * 100 }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Installment Schedule -->
        <div class="lg:col-span-2">
            <div class="card overflow-hidden border border-gray-100 shadow-sm rounded-2xl bg-white text-left">
                <div class="p-6 border-b border-gray-50">
                    <h3 class="text-lg font-bold text-gray-900">Installment Schedule</h3>
                </div>
                <div class="overflow-x-auto text-left">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50/50">
                            <tr>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">#</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Due Date</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($paymentPlan->paymentInstallments as $installment)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $installment->installment_number }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $installment->due_date->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                    Rs. {{ number_format($installment->amount, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-yellow-50 text-yellow-700 border border-yellow-100',
                                            'paid' => 'bg-green-50 text-green-700 border border-green-100',
                                            'overdue' => 'bg-red-50 text-red-700 border border-red-100',
                                        ];
                                    @endphp
                                    <span class="px-3 py-1 text-xs font-bold rounded-lg {{ $statusColors[$installment->status] ?? 'bg-gray-100' }}">
                                        {{ ucfirst($installment->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    @if($installment->status !== 'paid')
                                        <button class="text-indigo-600 hover:text-indigo-900 font-bold">Mark Paid</button>
                                    @else
                                        <span class="text-gray-400 italic">Paid on {{ $installment->paid_date?->format('M d, Y') }}</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
