@extends('layouts.app')

@section('page-title', 'Invoice Details')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Invoice {{ $invoice->invoice_number }}</h1>
            <p class="text-gray-600">Patient: {{ $invoice->patient->name }}</p>
        </div>
        <div class="flex space-x-4">
            @if($invoice->status !== 'paid')
                <form action="{{ route('invoices.mark-paid', $invoice) }}" method="POST" class="inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                        Mark as Paid
                    </button>
                </form>
            @endif
            <a href="{{ route('invoices.index') }}" class="border border-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-50">
                Back to Invoices
            </a>
        </div>
    </div>

    <div class="bg-white shadow-md rounded-lg p-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Invoice Information</h3>
                <div class="space-y-3">
                    <div>
                        <span class="text-sm font-medium text-gray-600">Invoice Number:</span>
                        <span class="ml-2 text-gray-900 font-mono">{{ $invoice->invoice_number }}</span>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-600">Issue Date:</span>
                        <span class="ml-2 text-gray-900">{{ $invoice->issued_date->format('M d, Y') }}</span>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-600">Due Date:</span>
                        <span class="ml-2 text-gray-900">{{ $invoice->due_date->format('M d, Y') }}</span>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-600">Status:</span>
                        <span class="ml-2 px-2 py-1 text-xs font-semibold rounded-full 
                            {{ $invoice->status === 'paid' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $invoice->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $invoice->status === 'overdue' ? 'bg-red-100 text-red-800' : '' }}">
                            {{ ucfirst($invoice->status) }}
                        </span>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Patient Information</h3>
                <div class="space-y-3">
                    <div>
                        <span class="text-sm font-medium text-gray-600">Name:</span>
                        <span class="ml-2 text-gray-900">{{ $invoice->patient->name }}</span>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-600">Email:</span>
                        <span class="ml-2 text-gray-900">{{ $invoice->patient->email }}</span>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-600">Phone:</span>
                        <span class="ml-2 text-gray-900">{{ $invoice->patient->phone }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 border-t pt-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Service Details</h3>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-gray-700">{{ $invoice->description }}</p>
            </div>
        </div>

        <div class="mt-8 border-t pt-8">
            <div class="flex justify-end">
                <div class="w-64">
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal:</span>
                            <span class="text-gray-900">NPR {{ number_format($invoice->amount) }}</span>
                        </div>
                        @if($invoice->tax_amount > 0)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Tax:</span>
                                <span class="text-gray-900">NPR {{ number_format($invoice->tax_amount) }}</span>
                            </div>
                        @endif
                        @if($invoice->discount_amount > 0)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Discount:</span>
                                <span class="text-red-600">-NPR {{ number_format($invoice->discount_amount) }}</span>
                            </div>
                        @endif
                        <div class="border-t pt-2">
                            <div class="flex justify-between text-lg font-bold">
                                <span class="text-gray-900">Total:</span>
                                <span class="text-gray-900">NPR {{ number_format($invoice->total_amount) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($invoice->status === 'paid' && $invoice->paid_date)
            <div class="mt-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span class="text-green-800 font-medium">
                        Paid on {{ $invoice->paid_date->format('M d, Y') }}
                    </span>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection