@extends('layouts.app')

@section('title', 'Payment Tracking')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Payment Tracking</h1>
            <p class="text-sm text-gray-500 mt-1">Real-time gateway analytics and transaction monitoring</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="inline-flex items-center gap-2 px-4 py-2 bg-green-50 text-green-700 text-xs font-bold rounded-full border border-green-200">
                <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                Live Tracking Active
            </span>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Monthly Collected</span>
                <div class="w-9 h-9 bg-green-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-arrow-down text-green-600 text-sm"></i>
                </div>
            </div>
            <p class="text-2xl font-black text-gray-900">NPR {{ number_format($stats['this_month_collected'], 0) }}</p>
            <p class="text-xs text-green-600 mt-1 font-medium">This month</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Outstanding</span>
                <div class="w-9 h-9 bg-yellow-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-clock text-yellow-600 text-sm"></i>
                </div>
            </div>
            <p class="text-2xl font-black text-gray-900">NPR {{ number_format($stats['total_outstanding'], 0) }}</p>
            <p class="text-xs text-yellow-600 mt-1 font-medium">Pending collection</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Overdue</span>
                <div class="w-9 h-9 bg-red-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-red-500 text-sm"></i>
                </div>
            </div>
            <p class="text-2xl font-black text-red-600">NPR {{ number_format($stats['overdue_amount'], 0) }}</p>
            <p class="text-xs text-red-500 mt-1 font-medium">Past due date</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Transactions</span>
                <div class="w-9 h-9 bg-blue-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-exchange-alt text-blue-600 text-sm"></i>
                </div>
            </div>
            <p class="text-2xl font-black text-gray-900">{{ $stats['total_transactions'] }}</p>
            <p class="text-xs text-blue-600 mt-1 font-medium">This month</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Avg Collection</span>
                <div class="w-9 h-9 bg-purple-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-hourglass-half text-purple-600 text-sm"></i>
                </div>
            </div>
            <p class="text-2xl font-black text-gray-900">{{ $stats['avg_collection_time'] }} <span class="text-sm font-medium text-gray-400">days</span></p>
            <p class="text-xs text-purple-600 mt-1 font-medium">Invoice → Payment</p>
        </div>
    </div>

    <!-- Gateway Breakdown -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-sm font-bold text-gray-900 mb-5">Gateway Performance — {{ now()->format('F Y') }}</h3>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-5">
            @php
                $gateways = [
                    'cash'   => ['label' => 'Cash',   'icon' => 'fa-money-bill-wave', 'color' => '#2563eb', 'bg' => 'bg-blue-50'],
                    'card'   => ['label' => 'Card',   'icon' => 'fa-credit-card',     'color' => '#eab308', 'bg' => 'bg-yellow-50'],
                    'esewa'  => ['label' => 'eSewa',  'icon' => 'fa-wallet',          'color' => '#16a34a', 'bg' => 'bg-green-50'],
                    'khalti' => ['label' => 'Khalti', 'icon' => 'fa-mobile-alt',      'color' => '#7c3aed', 'bg' => 'bg-violet-50'],
                ];
            @endphp
            @foreach($gateways as $key => $gw)
                @php
                    $data = $gatewayBreakdown[$key] ?? null;
                    $count = $data ? $data->count : 0;
                    $total = $data ? $data->total : 0;
                @endphp
                <div class="rounded-xl border border-gray-100 p-5 hover:shadow-md transition-all {{ $gw['bg'] }}" style="border-left: 4px solid {{ $gw['color'] }}">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: {{ $gw['color'] }}20;">
                            <i class="fas {{ $gw['icon'] }} text-lg" style="color: {{ $gw['color'] }}"></i>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-900">{{ $gw['label'] }}</p>
                            <p class="text-xs text-gray-400">{{ $count }} transactions</p>
                        </div>
                    </div>
                    <p class="text-xl font-black text-gray-900">NPR {{ number_format($total, 0) }}</p>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Pending Cash Confirmations -->
    @if($pendingCash->count() > 0)
    <div class="bg-white rounded-2xl shadow-sm border-2 border-amber-200 p-6">
        <div class="flex items-center gap-3 mb-5">
            <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-hand-holding-usd text-amber-600 text-lg"></i>
            </div>
            <div>
                <h3 class="text-sm font-bold text-gray-900">Pending Cash Confirmations</h3>
                <p class="text-xs text-gray-500">These patients have generated a cash reference from the portal. Confirm when payment is received.</p>
            </div>
            <span class="ml-auto bg-amber-500 text-white text-xs font-black px-3 py-1 rounded-full">{{ $pendingCash->count() }} PENDING</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="text-left text-xs font-bold text-gray-500 uppercase tracking-wider pb-3 px-4">Reference</th>
                        <th class="text-left text-xs font-bold text-gray-500 uppercase tracking-wider pb-3 px-4">Patient</th>
                        <th class="text-left text-xs font-bold text-gray-500 uppercase tracking-wider pb-3 px-4">Invoice</th>
                        <th class="text-right text-xs font-bold text-gray-500 uppercase tracking-wider pb-3 px-4">Amount</th>
                        <th class="text-left text-xs font-bold text-gray-500 uppercase tracking-wider pb-3 px-4">Requested</th>
                        <th class="text-center text-xs font-bold text-gray-500 uppercase tracking-wider pb-3 px-4">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingCash as $cp)
                    <tr class="border-b border-gray-50 hover:bg-amber-50/50 transition-colors">
                        <td class="py-4 px-4">
                            <span class="text-xs font-mono font-bold text-gray-800 bg-gray-100 px-2 py-1 rounded">{{ $cp->transaction_id }}</span>
                        </td>
                        <td class="py-4 px-4 text-sm text-gray-700 font-medium">{{ $cp->patient->first_name ?? '' }} {{ $cp->patient->last_name ?? '' }}</td>
                        <td class="py-4 px-4 text-sm text-gray-500">{{ $cp->invoice->invoice_number ?? 'N/A' }}</td>
                        <td class="py-4 px-4 text-right text-sm font-bold text-gray-900">NPR {{ number_format($cp->amount, 2) }}</td>
                        <td class="py-4 px-4 text-xs text-gray-400">{{ $cp->created_at->diffForHumans() }}</td>
                        <td class="py-4 px-4 text-center">
                            <form action="{{ route('clinic.payments.confirm-cash', $cp) }}" method="POST" onsubmit="return confirm('Confirm cash payment of NPR {{ number_format($cp->amount, 2) }}?')">
                                @csrf
                                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white text-xs font-bold rounded-lg hover:bg-green-700 transition-colors shadow-sm">
                                    <i class="fas fa-check"></i> Confirm
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Recent Transactions -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-5">
            <h3 class="text-sm font-bold text-gray-900">Recent Transactions</h3>
            <span class="text-xs text-gray-400 font-medium">Last 30 transactions</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="text-left text-xs font-bold text-gray-500 uppercase tracking-wider pb-3 px-4">Transaction</th>
                        <th class="text-left text-xs font-bold text-gray-500 uppercase tracking-wider pb-3 px-4">Patient</th>
                        <th class="text-left text-xs font-bold text-gray-500 uppercase tracking-wider pb-3 px-4">Gateway</th>
                        <th class="text-right text-xs font-bold text-gray-500 uppercase tracking-wider pb-3 px-4">Amount</th>
                        <th class="text-left text-xs font-bold text-gray-500 uppercase tracking-wider pb-3 px-4">Status</th>
                        <th class="text-left text-xs font-bold text-gray-500 uppercase tracking-wider pb-3 px-4">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentPayments as $payment)
                    <tr class="border-b border-gray-50 hover:bg-gray-50 transition-colors">
                        <td class="py-4 px-4">
                            <span class="text-xs font-mono text-gray-600">{{ Str::limit($payment->transaction_id, 20) }}</span>
                        </td>
                        <td class="py-4 px-4">
                            <p class="text-sm font-medium text-gray-800">{{ $payment->patient->first_name ?? '' }} {{ $payment->patient->last_name ?? '' }}</p>
                            <p class="text-xs text-gray-400">{{ $payment->invoice->invoice_number ?? 'N/A' }}</p>
                        </td>
                        <td class="py-4 px-4">
                            @php
                                $methodColors = [
                                    'cash' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-700', 'border' => 'border-blue-200'],
                                    'card' => ['bg' => 'bg-yellow-50', 'text' => 'text-yellow-700', 'border' => 'border-yellow-200'],
                                    'esewa' => ['bg' => 'bg-green-50', 'text' => 'text-green-700', 'border' => 'border-green-200'],
                                    'khalti' => ['bg' => 'bg-violet-50', 'text' => 'text-violet-700', 'border' => 'border-violet-200'],
                                ];
                                $mc = $methodColors[$payment->payment_method] ?? ['bg' => 'bg-gray-50', 'text' => 'text-gray-700', 'border' => 'border-gray-200'];
                            @endphp
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-bold rounded-full border {{ $mc['bg'] }} {{ $mc['text'] }} {{ $mc['border'] }}">
                                {{ ucfirst($payment->payment_method) }}
                            </span>
                        </td>
                        <td class="py-4 px-4 text-right text-sm font-bold text-gray-900">NPR {{ number_format($payment->amount, 2) }}</td>
                        <td class="py-4 px-4">
                            @if($payment->status === 'completed')
                                <span class="inline-flex items-center gap-1 text-xs font-bold text-green-700">
                                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span> Completed
                                </span>
                            @elseif($payment->status === 'pending')
                                <span class="inline-flex items-center gap-1 text-xs font-bold text-amber-600">
                                    <span class="w-1.5 h-1.5 bg-amber-500 rounded-full animate-pulse"></span> Pending
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 text-xs font-bold text-red-600">
                                    <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span> {{ ucfirst($payment->status) }}
                                </span>
                            @endif
                        </td>
                        <td class="py-4 px-4 text-xs text-gray-400">{{ $payment->created_at->format('M d, H:i') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-gray-400">
                            <i class="fas fa-receipt text-3xl mb-3 block"></i>
                            <p class="text-sm font-medium">No transactions recorded yet</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Overdue Invoices -->
    @if($overdueInvoices->count() > 0)
    <div class="bg-white rounded-2xl shadow-sm border border-red-100 p-6">
        <div class="flex items-center gap-3 mb-5">
            <div class="w-10 h-10 bg-red-50 rounded-xl flex items-center justify-center">
                <i class="fas fa-exclamation-circle text-red-500 text-lg"></i>
            </div>
            <div>
                <h3 class="text-sm font-bold text-gray-900">Overdue Invoices</h3>
                <p class="text-xs text-gray-500">{{ $overdueInvoices->count() }} invoices past their due date</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="text-left text-xs font-bold text-gray-500 uppercase tracking-wider pb-3 px-4">Invoice</th>
                        <th class="text-left text-xs font-bold text-gray-500 uppercase tracking-wider pb-3 px-4">Patient</th>
                        <th class="text-right text-xs font-bold text-gray-500 uppercase tracking-wider pb-3 px-4">Amount</th>
                        <th class="text-left text-xs font-bold text-gray-500 uppercase tracking-wider pb-3 px-4">Due Date</th>
                        <th class="text-left text-xs font-bold text-gray-500 uppercase tracking-wider pb-3 px-4">Days Overdue</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($overdueInvoices as $inv)
                    <tr class="border-b border-gray-50 hover:bg-red-50/30 transition-colors">
                        <td class="py-4 px-4 text-sm font-bold text-gray-800">{{ $inv->invoice_number }}</td>
                        <td class="py-4 px-4 text-sm text-gray-700">{{ $inv->patient->first_name ?? '' }} {{ $inv->patient->last_name ?? '' }}</td>
                        <td class="py-4 px-4 text-right text-sm font-bold text-red-600">NPR {{ number_format($inv->total_amount, 2) }}</td>
                        <td class="py-4 px-4 text-xs text-gray-500">{{ $inv->due_date ? \Carbon\Carbon::parse($inv->due_date)->format('M d, Y') : 'N/A' }}</td>
                        <td class="py-4 px-4">
                            @if($inv->due_date)
                                <span class="text-xs font-bold text-red-600 bg-red-50 px-2 py-1 rounded">{{ \Carbon\Carbon::parse($inv->due_date)->diffInDays(now()) }} days</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection
