@extends('layouts.app')

@section('page-title', 'Inventory: Resource Initialization')

@section('content')
<div class="page-fade-in max-w-4xl mx-auto pb-12">
    <!-- Premium Header -->
    <div class="stagger-in mb-8">
        <div class="flex items-center space-x-3 text-slate-500 text-sm mb-2">
            <a href="{{ route('clinic.inventory.index') }}" class="hover:text-blue-600 transition-colors">Inventory Ledger</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            <span class="text-slate-900 font-medium tracking-tight">New Resource Onboarding</span>
        </div>
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-100">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
            </div>
            <div>
                <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Onboard Resource</h1>
                <p class="text-slate-500 font-medium italic">Establishing a new Clinical Supply entry</p>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('clinic.inventory.store') }}" class="space-y-6">
        @csrf
        
        <!-- Resource Identification Section -->
        <div class="stagger-in bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm" style="--delay: 1">
            <div class="flex items-center space-x-3 mb-8">
                <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </div>
                <h2 class="text-xl font-bold text-slate-900 tracking-tight">Resource Identification</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-sm">
                <div class="space-y-2">
                    <label for="name" class="block font-bold text-slate-700 tracking-tight">Item Nomenclature <span class="text-blue-500">*</span></label>
                    <input type="text" name="name" id="name" 
                        class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-medium text-slate-700 placeholder-slate-400" 
                        placeholder="e.g. Dental Mirror" value="{{ old('name') }}" required>
                    @error('name') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label for="sku" class="block font-bold text-slate-700 tracking-tight">Protocol SKU / Code</label>
                    <input type="text" name="sku" id="sku" 
                        class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-medium text-slate-700 placeholder-slate-400" 
                        placeholder="Optional internal code" value="{{ old('sku') }}">
                    @error('sku') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label for="category_id" class="block font-bold text-slate-700 tracking-tight">Categorical Node <span class="text-blue-500">*</span></label>
                    <select name="category_id" id="category_id" required 
                        class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-medium text-slate-700">
                        <option value="">Select Category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-2">
                    <label for="unit" class="block font-bold text-slate-700 tracking-tight">Measurement Scalar</label>
                    <input type="text" name="unit" id="unit" 
                        class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-medium text-slate-700 placeholder-slate-400" 
                        placeholder="pcs, boxes, ml" value="{{ old('unit', 'pcs') }}" required>
                </div>

                <div class="space-y-2 md:col-span-2">
                    <label for="unit_price" class="block font-bold text-slate-700 tracking-tight">Unit Valuation (NPR)</label>
                    <div class="relative group">
                        <span class="absolute left-5 top-3.5 text-slate-400 font-bold">Rs.</span>
                        <input type="number" step="0.01" name="unit_price" id="unit_price" 
                            class="w-full pl-14 pr-5 py-3.5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-medium text-slate-700" 
                            placeholder="0.00" value="{{ old('unit_price') }}">
                    </div>
                </div>
            </div>
        </div>

        <!-- Logistics Control Section -->
        <div class="stagger-in bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm" style="--delay: 2">
            <div class="flex items-center space-x-3 mb-8">
                <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"></path></svg>
                </div>
                <h2 class="text-xl font-bold text-slate-900 tracking-tight">Logistics Control</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-sm">
                <div class="space-y-2">
                    <label for="current_stock" class="block font-bold text-slate-700 tracking-tight">Initial Volume <span class="text-blue-500">*</span></label>
                    <input type="number" name="current_stock" id="current_stock" 
                        class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-medium text-slate-700" 
                        value="{{ old('current_stock', 0) }}" required>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Current quantity on hand</p>
                </div>

                <div class="space-y-2">
                    <label for="min_stock_level" class="block font-bold text-slate-700 tracking-tight">Warning Threshold <span class="text-blue-500">*</span></label>
                    <input type="number" name="min_stock_level" id="min_stock_level" 
                        class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-medium text-slate-700" 
                        value="{{ old('min_stock_level', 10) }}" required>
                    <p class="text-[10px] font-bold text-amber-500 uppercase tracking-widest mt-1 italic">Alert trigger point</p>
                </div>
            </div>
        </div>

        <!-- Context Section -->
        <div class="stagger-in bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm" style="--delay: 3">
            <div class="flex items-center space-x-3 mb-8">
                <div class="w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center text-slate-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                </div>
                <h2 class="text-xl font-bold text-slate-900 tracking-tight">Resource Context</h2>
            </div>
            
            <div class="space-y-2 text-sm">
                <label for="description" class="block font-bold text-slate-700 tracking-tight">Supplemental Notes</label>
                <textarea name="description" id="description" rows="3"
                    class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-medium text-slate-700 placeholder-slate-400" 
                    placeholder="Technical specifications or storage requirements...">{{ old('description') }}</textarea>
            </div>
        </div>

        <!-- Submission Hub -->
        <div class="stagger-in flex flex-col md:flex-row md:items-center justify-end gap-3 pt-4" style="--delay: 4">
            <a href="{{ route('clinic.inventory.index') }}" class="px-8 py-4 bg-slate-50 text-slate-500 rounded-2xl font-bold hover:bg-slate-100 transition-all text-center">
                Abort Onboarding
            </a>
            <button type="submit" class="px-10 py-4 bg-blue-600 text-white rounded-2xl font-bold hover:bg-blue-700 transition-all shadow-lg shadow-blue-100 active:scale-95 flex items-center justify-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span>Validate & Register Resource</span>
            </button>
        </div>
    </form>
</div>
@endsection
