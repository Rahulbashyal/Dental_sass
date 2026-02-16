@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">Add Stock Item</h2>
            <p class="text-sm text-slate-500">Register a new product or consumable into the database.</p>
        </div>
        <a href="{{ route('inventory.index') }}" class="text-sm font-medium text-slate-500 hover:text-slate-700">Back back to Inventory</a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <form action="{{ route('inventory.store') }}" method="POST" class="p-8">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="md:col-span-2">
                    <label for="name" class="block text-sm font-bold text-slate-700 mb-1">Item Name</label>
                    <input type="text" name="name" id="name" required class="block w-full rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm h-11">
                </div>

                <div>
                    <label for="category_id" class="block text-sm font-bold text-slate-700 mb-1">Category</label>
                    <select name="category_id" id="category_id" required class="block w-full rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm h-11">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="sku" class="block text-sm font-bold text-slate-700 mb-1">SKU / Catalog #</label>
                    <input type="text" name="sku" id="sku" placeholder="e.g. DENT-001" class="block w-full rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm h-11 font-mono">
                </div>

                <div>
                    <label for="unit" class="block text-sm font-bold text-slate-700 mb-1">Unit of Measure</label>
                    <select name="unit" id="unit" required class="block w-full rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm h-11">
                        @foreach(['Pcs', 'Box', 'Packet', 'Bottle', 'Vial', 'ml', 'g', 'kg'] as $u)
                            <option value="{{ $u }}">{{ $u }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="unit_price" class="block text-sm font-bold text-slate-700 mb-1">Unit Price ({{ tenant()->clinic->currency ?? '$' }})</label>
                    <input type="number" name="unit_price" id="unit_price" step="0.01" value="0.00" class="block w-full rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm h-11">
                </div>

                <div>
                    <label for="current_stock" class="block text-sm font-bold text-slate-700 mb-1">Opening Stock</label>
                    <input type="number" name="current_stock" id="current_stock" step="0.01" required value="0" class="block w-full rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm h-11">
                </div>

                <div>
                    <label for="min_stock_level" class="block text-sm font-bold text-slate-700 mb-1">Min. (Alert) Level</label>
                    <input type="number" name="min_stock_level" id="min_stock_level" step="0.01" required value="10" class="block w-full rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm h-11">
                </div>

                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-bold text-slate-700 mb-1">Description / Notes</label>
                    <textarea name="description" id="description" rows="3" class="block w-full rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm"></textarea>
                </div>
            </div>

            <div class="flex justify-end pt-6 border-t border-slate-100">
                <button type="submit" class="px-8 py-3 bg-indigo-600 shadow-xl shadow-indigo-100 text-white text-sm font-bold rounded-xl hover:bg-indigo-700 transition-all flex items-center">
                    Register Item
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
