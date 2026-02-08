@extends('layouts.app')

@section('page-title', 'Invoices')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Invoices</h1>
        <a href="{{ route('invoices.create') }}" class="btn-primary">Create Invoice</a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <ul class="divide-y divide-gray-200">
            @forelse($invoices as $invoice)
                <li>
                    <div class="px-4 py-4 flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $invoice->status === 'paid' ? 'bg-green-100 text-green-800' : 
                                       ($invoice->status === 'overdue' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    {{ ucfirst($invoice->status) }}
                                </span>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $invoice->invoice_number }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $invoice->patient->full_name }} • Rs. {{ number_format($invoice->total_amount, 2) }}
                                </div>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <a href="{{ route('invoices.show', $invoice) }}" class="text-blue-600 hover:text-blue-900">View</a>
                            @if($invoice->status !== 'paid')
                                <form method="POST" action="{{ route('invoices.mark-paid', $invoice) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-green-600 hover:text-green-900">Mark Paid</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </li>
            @empty
                <li class="px-4 py-8 text-center text-gray-500">
                    No invoices found. <a href="{{ route('invoices.create') }}" class="text-blue-600">Create your first invoice</a>
                </li>
            @endforelse
        </ul>
    </div>

    <div class="mt-6">
        {{ $invoices->links() }}
    </div>
</div>
@endsection