@extends('patient-portal.layout')

@section('title', 'My Bills')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">My Bills</h1>
        <p class="text-gray-600">View and pay your outstanding bills</p>
    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <ul class="divide-y divide-gray-200">
            @forelse($invoices as $invoice)
            <li class="px-4 py-4 sm:px-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="h-12 w-12 rounded-full bg-gray-100 flex items-center justify-center">
                                <svg class="h-6 w-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <div class="text-lg font-medium text-gray-900">{{ $invoice->invoice_number }}</div>
                            <div class="text-sm text-gray-500">{{ $invoice->clinic->name }}</div>
                            @if($invoice->description)
                                <div class="text-sm text-gray-600">{{ $invoice->description }}</div>
                            @endif
                            <div class="text-sm text-gray-500">
                                Due: {{ $invoice->due_date->format('M j, Y') }}
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="text-right">
                            <div class="text-lg font-medium text-gray-900">NPR {{ number_format($invoice->total_amount, 2) }}</div>
                            @if($invoice->total_paid > 0)
                                <div class="text-sm text-green-600">Paid: NPR {{ number_format($invoice->total_paid, 2) }}</div>
                                @if($invoice->remaining_amount > 0)
                                    <div class="text-sm text-red-600">Due: NPR {{ number_format($invoice->remaining_amount, 2) }}</div>
                                @endif
                            @endif
                        </div>
                        <div class="flex flex-col space-y-2">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($invoice->status === 'paid') bg-green-100 text-green-800
                                @elseif($invoice->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($invoice->status === 'overdue') bg-red-100 text-red-800
                                @elseif($invoice->status === 'cancelled') bg-gray-100 text-gray-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($invoice->status) }}
                            </span>
                            @if($invoice->status === 'pending' && $invoice->remaining_amount > 0)
                                <a href="{{ route('patient.payment', $invoice) }}" 
                                   class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded text-white bg-blue-600 hover:bg-blue-700">
                                    Pay Now
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </li>
            @empty
            <li class="px-4 py-8 text-center">
                <div class="text-gray-400">
                    <svg class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No bills</h3>
                    <p class="mt-1 text-sm text-gray-500">You have no outstanding bills.</p>
                </div>
            </li>
            @endforelse
        </ul>
    </div>

    @if($invoices->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $invoices->links() }}
        </div>
    @endif
</div>
@endsection