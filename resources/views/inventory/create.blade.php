@extends(request()->has('iframe') ? 'layouts.iframe' : 'layouts.app')

@section('page-title', 'Supply Chain: Ledger Initialization')

@section('content')
<div class="page-fade-in max-w-4xl mx-auto pb-12">
    <!-- Premium Header -->
    <div class="stagger-in mb-8">
        <div class="flex items-center space-x-3 text-slate-500 text-sm mb-2">
            <a href="{{ route('clinic.inventory.index') }}" class="hover:text-blue-600 transition-colors">Resources Ledger</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            <span class="text-slate-900 font-medium tracking-tight">Supply Line Expansion</span>
        </div>
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-100">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
            </div>
            <div>
                <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Initialize Resource</h1>
                <p class="text-slate-500 font-medium italic">Commissioning a new Clinical Supply asset node</p>
            </div>
        </div>
    </div>

    <form action="{{ route('clinic.inventory.store') }}" method="POST" class="space-y-6">
        @if(request()->has('iframe'))
            <input type="hidden" name="iframe" value="1">
        @endif
        @csrf
        
        <!-- Resource Protocol Configuration -->
        <div class="stagger-in bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm" style="--delay: 1">
            <div class="flex items-center space-x-3 mb-8">
                <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                </div>
                <h2 class="text-xl font-bold text-slate-900 tracking-tight">Resource Protocol</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-sm">
                <div class="space-y-2 md:col-span-2">
                    <label for="name" class="block font-bold text-slate-700 tracking-tight">Resource Nomenclature <span class="text-blue-500">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="e.g. Disposable Nitrile Gloves (Large)" required
                        class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-bold text-slate-700 placeholder-slate-400">
                    @error('name') <p class="text-rose-500 text-[10px] font-black mt-1 ml-1 uppercase">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label for="sku" class="block font-bold text-slate-700 tracking-tight">System SKU / ID</label>
                    <input type="text" id="sku" name="sku" value="{{ old('sku') }}" placeholder="GLV-NIT-L"
                        class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-bold text-slate-700 placeholder-slate-400">
                </div>

                <div class="space-y-2">
                    <label for="category_id" class="block font-bold text-slate-700 tracking-tight">Catalog Category <span class="text-blue-500">*</span></label>
                    <select id="category_id" name="category_id" required
                        class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-bold text-slate-700 appearance-none">
                        <option value="">Select Category...</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Logistics & Volume -->
        <div class="stagger-in bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm" style="--delay: 2">
            <div class="flex items-center space-x-3 mb-8">
                <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                </div>
                <h2 class="text-xl font-bold text-slate-900 tracking-tight">Volume Logistics</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-sm">
                <div class="space-y-2">
                    <label for="current_stock" class="block font-bold text-slate-700 tracking-tight">Active Volume <span class="text-blue-500">*</span></label>
                    <input type="number" id="current_stock" name="current_stock" value="{{ old('current_stock', 0) }}" required
                        class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-bold text-slate-700">
                </div>

                <div class="space-y-2">
                    <label for="min_stock_level" class="block font-bold text-slate-700 tracking-tight">Threshold Alert Level <span class="text-blue-500">*</span></label>
                    <input type="number" id="min_stock_level" name="min_stock_level" value="{{ old('min_stock_level', 10) }}" required
                        class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-bold text-slate-700">
                </div>

                <div class="space-y-2">
                    <label for="unit" class="block font-bold text-slate-700 tracking-tight">Metric Unit <span class="text-blue-500">*</span></label>
                    <input type="text" id="unit" name="unit" value="{{ old('unit', 'pcs') }}" placeholder="pcs, box, kg, etc." required
                        class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-bold text-slate-700">
                </div>

                <div class="space-y-2">
                    <label for="unit_price" class="block font-bold text-slate-700 tracking-tight">Unit Valuation (NPR)</label>
                    <input type="number" step="0.01" id="unit_price" name="unit_price" value="{{ old('unit_price') }}" placeholder="0.00"
                        class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-bold text-slate-700">
                </div>
            </div>
        </div>

        <!-- Supply Line Fulfillment -->
        <div class="stagger-in bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm" style="--delay: 3">
            <div class="flex items-center space-x-3 mb-8">
                <div class="w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center text-slate-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <h2 class="text-xl font-bold text-slate-900 tracking-tight">Supply Source (Optional)</h2>
            </div>
            
            <div class="space-y-4 text-sm">
                <label for="description" class="block font-bold text-slate-700 tracking-tight">Resource Metadata / Notes</label>
                <textarea id="description" name="description" rows="3" placeholder="Enter resource specifics, supplier data, or storage protocols..."
                    class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-bold text-slate-700 resize-none placeholder-slate-400">{{ old('description') }}</textarea>
            </div>
        </div>

        <!-- Commitment Hub -->
        <div class="stagger-in flex flex-col md:flex-row md:items-center justify-end gap-3 pt-4" style="--delay: 4">
            <a href="{{ route('clinic.inventory.index') }}" class="px-8 py-4 bg-slate-50 text-slate-500 rounded-2xl font-bold hover:bg-slate-100 transition-all text-center">
                Abort Initialization
            </a>
            <button type="submit" class="px-10 py-4 bg-blue-600 text-white rounded-2xl font-black uppercase tracking-widest text-xs hover:bg-blue-700 transition-all shadow-lg shadow-blue-100 active:scale-95 flex items-center justify-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>Commit Resource Flow</span>
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
