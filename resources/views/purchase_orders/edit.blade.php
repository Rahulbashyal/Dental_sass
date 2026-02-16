@extends('layouts.app')

@section('page-title', 'Operations: Procurement Refactoring')

@section('content')
<div class="page-fade-in max-w-5xl mx-auto pb-12" x-data="orderForm()">
    <!-- Premium Header -->
    <div class="stagger-in mb-8">
        <div class="flex items-center space-x-3 text-slate-500 text-sm mb-2">
            <a href="{{ route('clinic.purchase-orders.index') }}" class="hover:text-indigo-600 transition-colors">Procurement Hub</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            <span class="text-slate-900 font-medium tracking-tight">Resource Transmission Refactoring</span>
        </div>
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-100">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
            </div>
            <div>
                <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Refactor Order</h1>
                <p class="text-slate-500 font-medium italic">Adjusting node: {{ $purchaseOrder->order_number }}</p>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('clinic.purchase-orders.update', $purchaseOrder) }}" class="space-y-6">
        @csrf
        @method('PUT')
        
        <!-- Header Section -->
        <div class="stagger-in bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm" style="--delay: 1">
            <div class="flex items-center space-x-3 mb-8">
                <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h2 class="text-xl font-bold text-slate-900 tracking-tight">Transmission Metadata Sync</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-sm">
                <div class="space-y-2">
                    <label for="supplier_id" class="block font-bold text-slate-700 tracking-tight">Origin Partner <span class="text-blue-500">*</span></label>
                    <select name="supplier_id" id="supplier_id" required 
                        class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-medium text-slate-700">
                        <option value="">Select Resource Source</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ old('supplier_id', $purchaseOrder->supplier_id) == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-2">
                    <label for="order_number" class="block font-bold text-slate-700 tracking-tight">Protocol Identifier <span class="text-blue-500">*</span></label>
                    <input type="text" name="order_number" id="order_number" 
                        class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 transition-all font-medium text-slate-700 font-mono" 
                        value="{{ old('order_number', $purchaseOrder->order_number) }}" required>
                </div>
            </div>
        </div>

        <!-- Matrix Detail Section -->
        <div class="stagger-in bg-white rounded-[2.5rem] overflow-hidden border border-slate-100 shadow-sm" style="--delay: 2">
            <div class="p-8 border-b border-slate-50 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                    </div>
                    <h2 class="text-xl font-bold text-slate-900 tracking-tight">Resource Matrix Synchronization</h2>
                </div>
                <button type="button" @click="addItem()" class="px-4 py-2 bg-blue-50 text-blue-700 rounded-xl font-bold text-xs hover:bg-blue-100 transition-colors flex items-center space-x-2 active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span>Add Unit</span>
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left font-medium space-x-2">
                    <thead class="bg-slate-50/50">
                        <tr>
                            <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Inventory Node</th>
                            <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest w-40 text-center">Volume</th>
                            <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest w-48 text-center">Unit Val</th>
                            <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest w-48 text-right">Aggregate</th>
                            <th class="px-8 py-4 w-16 text-center"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <template x-for="(item, index) in items" :key="index">
                            <tr class="group hover:bg-slate-50/50 transition-colors">
                                <td class="px-8 py-4">
                                    <select :name="'items[' + index + '][inventory_item_id]'" required x-model="item.inventory_item_id" 
                                        class="w-full px-4 py-3 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-indigo-500 transition-all font-medium text-slate-700 text-sm">
                                        <option value="">Select Resource Type</option>
                                        @foreach($inventoryItems as $invItem)
                                            <option value="{{ $invItem->id }}">{{ $invItem->name }} ({{ $invItem->unit }})</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="px-8 py-4">
                                    <input type="number" step="0.01" :name="'items[' + index + '][quantity]'" required x-model="item.quantity" @input="calculateTotal(index)" 
                                        class="w-full px-4 py-3 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-indigo-500 transition-all font-medium text-slate-700 text-sm text-center">
                                </td>
                                <td class="px-8 py-4">
                                    <input type="number" step="0.01" :name="'items[' + index + '][unit_price]'" required x-model="item.unit_price" @input="calculateTotal(index)" 
                                        class="w-full px-4 py-3 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-indigo-500 transition-all font-medium text-slate-700 text-sm text-center">
                                </td>
                                <td class="px-8 py-4 text-right">
                                    <div class="text-sm font-black text-slate-900 tracking-tight" x-text="formatCurrency(item.total_price)"></div>
                                </td>
                                <td class="px-8 py-4 text-center">
                                    <button type="button" @click="removeItem(index)" class="text-slate-300 hover:text-rose-500 transition-colors">
                                         <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                    <tfoot class="bg-indigo-50/30">
                        <tr>
                            <td colspan="3" class="px-8 py-6 text-right">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Sync Aggregate Value:</span>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <span class="text-xl font-black text-blue-600 tracking-tighter" x-text="formatCurrency(grandTotal)"></span>
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Submission Hub -->
        <div class="stagger-in flex flex-col md:flex-row md:items-center justify-end gap-3 pt-4" style="--delay: 3">
            <a href="{{ route('clinic.purchase-orders.show', $purchaseOrder) }}" class="px-8 py-4 bg-slate-50 text-slate-500 rounded-2xl font-bold hover:bg-slate-100 transition-all text-center">
                Abort Changes
            </a>
            <button type="submit" class="px-10 py-4 bg-blue-600 text-white rounded-2xl font-bold hover:bg-blue-700 transition-all shadow-lg shadow-blue-100 active:scale-95 flex items-center justify-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span>Commit Protocol Sync</span>
            </button>
        </div>
    </form>
</div>

<script>
    function orderForm() {
        return {
            items: @json($purchaseOrder->items->map(function($item) {
                return [
                    'inventory_item_id' => $item->inventory_item_id,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'total_price' => $item->total_price
                ];
            })),
            get grandTotal() {
                return this.items.reduce((total, item) => total + (parseFloat(item.total_price) || 0), 0);
            },
            addItem() {
                this.items.push({
                    inventory_item_id: '',
                    quantity: 1,
                    unit_price: 0,
                    total_price: 0
                });
            },
            removeItem(index) {
                if (this.items.length > 1) {
                    this.items.splice(index, 1);
                }
            },
            calculateTotal(index) {
                const item = this.items[index];
                item.total_price = (parseFloat(item.quantity) || 0) * (parseFloat(item.unit_price) || 0);
            },
            formatCurrency(value) {
                return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'NPR' }).format(value);
            }
        }
    }
</script>
@endsection
