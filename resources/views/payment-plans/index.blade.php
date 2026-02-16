@extends('layouts.app')

@section('title', 'Payment Plans')
@section('page-title', 'Payment Plans')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Payment Plans</h1>
            <p class="text-sm text-gray-500">Manage patient installment plans and financial agreements.</p>
        </div>
        <a href="{{ route('clinic.payment-plans.create') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white font-bold rounded-xl shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition-all duration-200">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            New Payment Plan
        </a>
    </div>

    <div class="card overflow-hidden border border-gray-100 shadow-sm rounded-2xl bg-white">
        @if($paymentPlans->isEmpty())
            <div class="text-center py-20">
                <div class="w-24 h-24 bg-green-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="h-12 w-12 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900">No payment plans found</h3>
                <p class="mt-2 text-gray-500 max-w-sm mx-auto">Create a payment plan to help patients manage large treatment costs with installments.</p>
                <div class="mt-8">
                    <a href="{{ route('clinic.payment-plans.create') }}" class="btn-primary">
                        Create First Plan
                    </a>
                </div>
            </div>
        @else
            <div class="overflow-x-auto text-left">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50/50">
                        <tr>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Patient</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Invoice</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Total Amount</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Installments</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($paymentPlans as $plan)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center text-left">
                                    <div class="h-10 w-10 flex-shrink-0 bg-green-50 text-green-700 rounded-full flex items-center justify-center font-bold">
                                        {{ substr($plan->patient->first_name, 0, 1) }}
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-semibold text-gray-900">{{ $plan->patient->first_name }} {{ $plan->patient->last_name }}</div>
                                        <div class="text-xs text-gray-500">{{ $plan->patient->phone }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 font-medium">{{ $plan->invoice->invoice_number }}</div>
                                <div class="text-xs text-gray-500">Total: Rs. {{ number_format($plan->invoice->total_amount, 2) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-gray-900">Rs. {{ number_format($plan->total_amount, 2) }}</div>
                                <div class="text-xs text-gray-500">Down Payment: Rs. {{ number_format($plan->down_payment, 2) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $plan->installments }} Months</div>
                                <div class="text-xs text-gray-500">Rs. {{ number_format($plan->installment_amount, 2) }} / mo</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $colors = [
                                        'active' => 'bg-green-100 text-green-800',
                                        'completed' => 'bg-blue-100 text-blue-800',
                                        'defaulted' => 'bg-red-100 text-red-800',
                                    ];
                                @endphp
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $colors[$plan->status] ?? 'bg-gray-100' }}">
                                    {{ ucfirst($plan->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('clinic.payment-plans.show', $plan->id) }}" class="inline-flex items-center px-4 py-2 bg-indigo-50 text-indigo-700 rounded-lg font-semibold hover:bg-indigo-100 transition-colors">
                                    View Details
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 bg-gray-50/30 border-t border-gray-100">
                {{ $paymentPlans->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
