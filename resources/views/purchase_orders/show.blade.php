@extends('layouts.app')

@section('page-title', 'Operations: Procurement Analysis')

@section('content')
<div class="page-fade-in max-w-5xl mx-auto space-y-8 pb-12">
    <!-- Premium Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="stagger-in">
            <div class="flex items-center space-x-3 text-slate-500 text-sm mb-2">
                <a href="{{ route('clinic.purchase-orders.index') }}" class="hover:text-blue-600 transition-colors">Procurement Hub</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                <span class="text-slate-900 font-medium">Order Analysis</span>
            </div>
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-100 font-bold text-white text-xl">
                    {{ substr($purchaseOrder->order_number, 0, 2) }}
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-slate-900 tracking-tight">{{ $purchaseOrder->order_number }}</h1>
                    <p class="text-slate-500 font-medium italic">Partner: {{ $purchaseOrder->supplier->name }}</p>
                </div>
            </div>
        </div>
        
        <div class="flex items-center space-x-3 stagger-in" style="--delay: 1">
            @if($purchaseOrder->status == 'draft')
                <a href="{{ route('clinic.purchase-orders.edit', $purchaseOrder) }}" class="px-6 py-3 bg-white text-slate-700 border border-slate-200 rounded-2xl font-bold hover:bg-slate-50 transition-all flex items-center space-x-2">
                     <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    <span>Edit Metadata</span>
                </a>
                <form action="{{ route('clinic.purchase-orders.update-status', $purchaseOrder) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="ordered">
                    <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-2xl font-bold hover:bg-blue-700 transition-all shadow-lg shadow-blue-100 flex items-center space-x-2 active:scale-95">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span>Dispatch Transmission</span>
                    </button>
                </form>
            @endif

            @if($purchaseOrder->status == 'ordered')
                <form action="{{ route('clinic.purchase-orders.update-status', $purchaseOrder) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="received">
                    <button type="submit" class="px-6 py-3 bg-emerald-600 text-white rounded-2xl font-bold hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-100 flex items-center space-x-2 active:scale-95" onclick="return confirm('Inventory Injection: This will update stock levels. Continue?')">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        <span>Fulfill & Inject stock</span>
                    </button>
                </form>
                <form action="{{ route('clinic.purchase-orders.update-status', $purchaseOrder) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="cancelled">
                    <button type="submit" class="w-12 h-12 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center hover:bg-rose-100 transition-all active:scale-95 shadow-sm" onclick="return confirm('Void Session: Are you sure?')">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Transmission Analysis Cards -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Metadata Overview -->
        <div class="space-y-6 lg:col-span-1">
            <div class="stagger-in bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm" style="--delay: 1">
                <div class="flex items-center space-x-3 mb-8">
                    <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h2 class="text-xl font-bold text-slate-900 tracking-tight">Transmission Details</h2>
                </div>
                
                <div class="space-y-6">
                    <div>
                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">State Protocol</span>
                        @php
                            $statusColors = [
                                'draft' => 'bg-slate-50 text-slate-500 border-slate-100',
                                'ordered' => 'bg-blue-50 text-blue-600 border-blue-100',
                                'received' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                'cancelled' => 'bg-rose-50 text-rose-600 border-rose-100',
                            ];
                        @endphp
                         <span class="inline-flex items-center space-x-1.5 {{ $statusColors[$purchaseOrder->status] ?? 'bg-slate-50 text-slate-500 px-3 py-1.5 rounded-full' }} px-4 py-2 rounded-2xl text-xs font-bold tracking-tight border">
                            @if($purchaseOrder->status == 'ordered') <span class="w-1.5 h-1.5 bg-blue-500 rounded-full animate-pulse"></span> @endif
                            <span>{{ strtoupper($purchaseOrder->status) }}</span>
                        </span>
                    </div>

                    <div class="grid grid-cols-1 gap-4">
                        <div class="bg-slate-50 rounded-2xl p-4">
                            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Valuation</span>
                            <span class="text-xl font-black text-slate-900 tracking-tighter">NPR {{ number_format($purchaseOrder->total_amount, 2) }}</span>
                        </div>
                        
                        <div class="bg-slate-50 rounded-2xl p-4">
                            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Log Date</span>
                            <span class="text-sm font-bold text-slate-700">{{ $purchaseOrder->created_at->format('M d, Y H:i') }}</span>
                        </div>

                        @if($purchaseOrder->ordered_at)
                        <div class="bg-blue-50/50 rounded-2xl p-4 border border-blue-50">
                            <span class="block text-[10px] font-bold text-blue-400 uppercase tracking-widest mb-1">Transmission Timestamp</span>
                            <span class="text-sm font-bold text-blue-700">{{ $purchaseOrder->ordered_at->format('M d, Y H:i') }}</span>
                        </div>
                        @endif

                        @if($purchaseOrder->received_at)
                        <div class="bg-emerald-50/50 rounded-2xl p-4 border border-emerald-50">
                            <span class="block text-[10px] font-bold text-emerald-400 uppercase tracking-widest mb-1">Fulfillment Timestamp</span>
                            <span class="text-sm font-bold text-emerald-700">{{ $purchaseOrder->received_at->format('M d, Y H:i') }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <a href="{{ route('clinic.purchase-orders.index') }}" class="flex items-center justify-center space-x-2 text-slate-400 hover:text-blue-600 transition-colors font-bold text-sm stagger-in" style="--delay: 2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span>Return to Hub</span>
            </a>
        </div>

        <!-- Matrix List -->
        <div class="lg:col-span-2 space-y-6">
            <div class="stagger-in bg-white rounded-[2.5rem] overflow-hidden border border-slate-100 shadow-sm" style="--delay: 3">
                 <div class="p-8 border-b border-slate-50 flex items-center space-x-3">
                    <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                    <h2 class="text-xl font-bold text-slate-900 tracking-tight">Resource Matrix Analysis</h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50/50">
                            <tr>
                                <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Description</th>
                                <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Volume</th>
                                <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Unit Value</th>
                                <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Aggregate</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($purchaseOrder->items as $item)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-8 py-5">
                                    <div class="font-bold text-slate-900">{{ $item->inventoryItem->name }}</div>
                                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $item->inventoryItem->sku }}</div>
                                </td>
                                <td class="px-8 py-5 text-center">
                                    <span class="bg-slate-100 px-3 py-1.5 rounded-full text-xs font-black text-slate-600 border border-slate-200">
                                        {{ $item->quantity }} {{ strtoupper($item->inventoryItem->unit) }}
                                    </span>
                                </td>
                                <td class="px-8 py-5 text-right text-slate-500 font-bold text-sm">
                                    {{ number_format($item->unit_price, 2) }}
                                </td>
                                <td class="px-8 py-5 text-right font-black text-slate-900 tracking-tight">
                                    {{ number_format($item->total_price, 2) }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-indigo-50/20">
                            <tr class="border-t border-indigo-50">
                                <td colspan="3" class="px-8 py-6 text-right">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Transmission Aggregate Value:</span>
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <span class="text-2xl font-black text-blue-600 tracking-tighter">NPR {{ number_format($purchaseOrder->total_amount, 2) }}</span>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
