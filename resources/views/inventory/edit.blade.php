@extends(request()->has('iframe') ? 'layouts.iframe' : 'layouts.app')

@section('page-title', 'Inventory: Resource Refactoring')

@section('content')
<div class="page-fade-in max-w-4xl mx-auto pb-12">
    <!-- Premium Header -->
    <div class="stagger-in mb-8">
        <div class="flex items-center space-x-3 text-slate-500 text-sm mb-2">
            <a href="{{ route('clinic.inventory.index') }}" class="hover:text-blue-600 transition-colors">Inventory Ledger</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            <span class="text-slate-900 font-medium tracking-tight">Resource Node Refactoring</span>
        </div>
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-100">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
            </div>
            <div>
                <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Refactor Resource</h1>
                <p class="text-slate-500 font-medium italic">Adjusting node: {{ $inventory->name }}</p>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('clinic.inventory.update', $inventory->id) }}" class="space-y-6">
        @if(request()->has('iframe'))
            <input type="hidden" name="iframe" value="1">
        @endif
        @csrf
        @method('PUT')
        
        <!-- Resource Identification Section -->
        <div class="stagger-in bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm" style="--delay: 1">
            <div class="flex items-center space-x-3 mb-8">
                <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </div>
                <h2 class="text-xl font-bold text-slate-900 tracking-tight">Resource Identification Synchronization</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-sm">
                <div class="space-y-2">
                    <label for="name" class="block font-bold text-slate-700 tracking-tight">Item Nomenclature <span class="text-blue-500">*</span></label>
                    <input type="text" name="name" id="name" 
                        class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-medium text-slate-700 placeholder-slate-400" 
                        placeholder="e.g. Dental Mirror" value="{{ old('name', $inventory->name) }}" required>
                    @error('name') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label for="sku" class="block font-bold text-slate-700 tracking-tight">Protocol SKU / Code</label>
                    <input type="text" name="sku" id="sku" 
                        class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-medium text-slate-700 placeholder-slate-400" 
                        placeholder="Optional internal code" value="{{ old('sku', $inventory->sku) }}">
                    @error('sku') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label for="category_id" class="block font-bold text-slate-700 tracking-tight">Categorical Node <span class="text-blue-500">*</span></label>
                    <select name="category_id" id="category_id" required 
                        class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-medium text-slate-700">
                        <option value="">Select Category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $inventory->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-2">
                    <label for="unit" class="block font-bold text-slate-700 tracking-tight">Measurement Scalar</label>
                    <input type="text" name="unit" id="unit" 
                        class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-medium text-slate-700 placeholder-slate-400" 
                        placeholder="pcs, boxes, ml" value="{{ old('unit', $inventory->unit) }}" required>
                </div>

                <div class="space-y-2 md:col-span-2">
                    <label for="unit_price" class="block font-bold text-slate-700 tracking-tight">Unit Valuation (NPR)</label>
                    <div class="relative group">
                        <span class="absolute left-5 top-3.5 text-slate-400 font-bold">Rs.</span>
                        <input type="number" step="0.01" name="unit_price" id="unit_price" 
                            class="w-full pl-14 pr-5 py-3.5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-medium text-slate-700" 
                            placeholder="0.00" value="{{ old('unit_price', $inventory->unit_price) }}">
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
                <h2 class="text-xl font-bold text-slate-900 tracking-tight">Logistics Control Sync</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-sm">
                <div class="space-y-2">
                    <label for="current_stock" class="block font-bold text-slate-700 tracking-tight">Current Lifecycle Volume <span class="text-blue-500">*</span></label>
                    <input type="number" name="current_stock" id="current_stock" 
                        class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-medium text-slate-700" 
                        value="{{ old('current_stock', $inventory->current_stock) }}" required>
                </div>

                <div class="space-y-2">
                    <label for="min_stock_level" class="block font-bold text-slate-700 tracking-tight">Warning Threshold <span class="text-blue-500">*</span></label>
                    <input type="number" name="min_stock_level" id="min_stock_level" 
                        class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-medium text-slate-700" 
                        value="{{ old('min_stock_level', $inventory->min_stock_level) }}" required>
                    <p class="text-[10px] font-bold text-amber-500 uppercase tracking-widest mt-1 italic">Alert trigger point</p>
                </div>
            </div>
        </div>

        <!-- Submission Hub -->
        <div class="stagger-in flex flex-col md:flex-row md:items-center justify-end gap-3 pt-4" style="--delay: 3">
            <a href="{{ route('clinic.inventory.index') }}" class="px-8 py-4 bg-slate-50 text-slate-500 rounded-2xl font-bold hover:bg-slate-100 transition-all text-center">
                Abort Protocol
            </a>
            <button type="submit" class="px-10 py-4 bg-blue-600 text-white rounded-2xl font-bold hover:bg-blue-700 transition-all shadow-lg shadow-blue-100 active:scale-95 flex items-center justify-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span>Commit Node Adjustments</span>
            </button>
        </div>
    </form>
</div>
@endsection


{{-- Auto-close modal script on success --}}
@if(session('success') && request()->has('iframe'))
    <script>
        setTimeout(() => {
            window.parent.location.reload();
        }, 1500);
    </script>
@endif
