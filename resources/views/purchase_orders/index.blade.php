@extends('layouts.app')

@section('page-title', 'Operations: Procurement Hub')

@section('content')
<div class="page-fade-in space-y-8">
    <!-- Premium Operations Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="stagger-in">
            <div class="flex items-center space-x-3 text-slate-500 text-sm mb-2">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-600 transition-colors">Operations Hub</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                <span class="text-slate-900 font-medium">Procurement Hub</span>
            </div>
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-100">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Procurement Hub</h1>
                    <p class="text-slate-500 font-medium italic">Inventory Replenishment & Transmission Ledger</p>
                </div>
            </div>
        </div>
        
        <div class="flex items-center space-x-3 stagger-in" style="--delay: 1">
            <a href="{{ route('clinic.purchase-orders.create') }}" class="group relative px-6 py-3 bg-blue-600 text-white rounded-2xl font-bold flex items-center space-x-2 hover:bg-blue-700 transition-all shadow-lg shadow-blue-100 active:scale-95">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                <span>Initialize New Order</span>
            </a>
        </div>
    </div>

    <!-- Smart Filter Hub -->
    <div class="stagger-in bg-white rounded-3xl border border-slate-100 shadow-sm p-6" style="--delay: 2">
        <form method="GET" action="{{ route('clinic.purchase-orders.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Transmission State</label>
                <select name="status" class="w-full px-4 py-3 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 transition-all text-slate-700 font-medium">
                    <option value="">All Streams</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft Protocols</option>
                    <option value="ordered" {{ request('status') == 'ordered' ? 'selected' : '' }}>Active Transmissions</option>
                    <option value="received" {{ request('status') == 'received' ? 'selected' : '' }}>Fulfilled Logs</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Voided Sessions</option>
                </select>
            </div>

            <div>
                 <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Source Partner</label>
                <select name="supplier_id" class="w-full px-4 py-3 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 transition-all text-slate-700 font-medium">
                    <option value="">All Partners</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-2 flex items-end">
                <button type="submit" class="w-full py-3 bg-slate-900 text-white rounded-2xl font-bold hover:bg-black transition-all active:scale-95 flex items-center justify-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    <span>Refine Transmission Stream</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Procurement Directory -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($purchaseOrders as $index => $order)
            <div class="stagger-in group" style="--delay: {{ 3 + $index }}">
                <div class="bg-white rounded-[2.5rem] p-6 border border-slate-100 shadow-sm hover:shadow-xl transition-all duration-500 relative overflow-hidden h-full flex flex-col">
                    <!-- Status Badge -->
                    <div class="absolute top-6 right-6">
                        @php
                            $statusColors = [
                                'draft' => 'bg-slate-50 text-slate-500 border-slate-100',
                                'ordered' => 'bg-blue-50 text-blue-600 border-blue-100',
                                'received' => 'bg-blue-50 text-blue-600 border-blue-100',
                                'cancelled' => 'bg-slate-50 text-slate-600 border-slate-200',
                            ];
                        @endphp
                        <span class="flex items-center space-x-1.5 {{ $statusColors[$order->status] ?? 'bg-slate-50 text-slate-500' }} px-3 py-1.5 rounded-full text-xs font-bold tracking-tight border">
                            @if($order->status == 'ordered') <span class="w-1.5 h-1.5 bg-blue-500 rounded-full animate-pulse"></span> @endif
                            <span>{{ strtoupper($order->status) }}</span>
                        </span>
                    </div>

                    <!-- Order Header -->
                    <div class="flex items-center space-x-4 mb-6">
                        <div class="w-16 h-16 rounded-[1.5rem] bg-indigo-50 flex items-center justify-center group-hover:bg-indigo-600 transition-colors duration-500">
                             <svg class="w-8 h-8 text-indigo-600 group-hover:text-white transition-colors duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-slate-900 group-hover:text-indigo-600 transition-colors leading-tight">
                                {{ $order->order_number }}
                            </h3>
                            <p class="text-slate-500 text-sm font-medium">{{ $order->supplier->name }}</p>
                        </div>
                    </div>

                    <!-- Financial Data Grid -->
                    <div class="grid grid-cols-2 gap-3 mb-6">
                        <div class="bg-slate-50 rounded-2xl p-3">
                            <span class="block text-[10px] font-bold text-slate-400 uppercase mb-0.5 tracking-wider">Transmission Value</span>
                            <span class="text-slate-900 font-black text-sm tracking-tight">NPR {{ number_format($order->total_amount, 2) }}</span>
                        </div>
                        <div class="bg-slate-50 rounded-2xl p-3">
                            <span class="block text-[10px] font-bold text-slate-400 uppercase mb-0.5 tracking-wider">Log Date</span>
                            <span class="text-slate-700 font-bold block text-xs">{{ $order->created_at->format('M d, Y') }}</span>
                        </div>
                    </div>

                    <!-- Action Hub -->
                    <div class="flex items-center space-x-3 pt-6 border-t border-slate-50 mt-auto">
                        <a href="{{ route('clinic.purchase-orders.show', $order) }}" class="flex-1 px-4 py-2.5 bg-blue-50 text-blue-700 rounded-xl font-bold text-sm hover:bg-blue-100 transition-colors flex items-center justify-center space-x-2">
                             <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            <span>Open Analysis</span>
                        </a>
                        @if($order->status == 'draft')
                        <a href="{{ route('clinic.purchase-orders.edit', $order) }}" class="w-11 h-11 bg-slate-50 text-slate-600 rounded-xl flex items-center justify-center hover:bg-slate-100 hover:text-indigo-600 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="md:col-span-3 card text-center py-20 bg-slate-50 border-dashed border-2 border-slate-200 rounded-[3rem]">
                <div class="w-24 h-24 bg-white rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-sm">
                    <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                </div>
                <h3 class="text-2xl font-bold text-slate-900 mb-2">Protocol Ledger Empty</h3>
                <p class="text-slate-500 mb-8 max-w-sm mx-auto font-medium leading-relaxed">No procurement sessions were found in the operations lake. Begin by initializing your first resource order.</p>
                <a href="{{ route('clinic.purchase-orders.create') }}" class="inline-flex items-center space-x-2 px-8 py-4 bg-blue-600 text-white rounded-2xl font-bold hover:bg-blue-700 transition-all shadow-lg shadow-blue-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    <span>Initialize Procurement Flow</span>
                </a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($purchaseOrders->hasPages())
        <div class="flex justify-center pt-8">
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-4 px-6 scale-90 md:scale-100">
                {{ $purchaseOrders->links() }}
            </div>
        </div>
    @endif
</div>
@endsection
