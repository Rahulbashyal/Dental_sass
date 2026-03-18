@extends(request()->has('iframe') ? 'layouts.iframe' : 'layouts.app')

@section('page-title', 'Clinical Assets: Equipment Registry')

@section('content')
<div class="page-fade-in max-w-4xl mx-auto pb-12">
    <!-- Premium Header -->
    <div class="stagger-in mb-8">
        <div class="flex items-center space-x-3 text-slate-500 text-sm mb-2">
            <a href="{{ route('clinic.equipment.index') }}" class="hover:text-blue-600 transition-colors">Asset Inventory</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            <span class="text-slate-900 font-medium tracking-tight">New Technical Asset</span>
        </div>
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 bg-amber-600 rounded-2xl flex items-center justify-center shadow-lg shadow-amber-100">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </div>
            <div>
                <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Register Asset</h1>
                <p class="text-slate-500 font-medium italic">Commissioning high-fidelity clinical equipment</p>
            </div>
        </div>
    </div>

    <form action="{{ route('clinic.equipment.store') }}" method="POST" class="space-y-6">
        @if(request()->has('iframe'))
            <input type="hidden" name="iframe" value="1">
        @endif
        @csrf
        
        <!-- Technical Specifications -->
        <div class="stagger-in bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm" style="--delay: 1">
            <div class="flex items-center space-x-3 mb-8">
                <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center text-amber-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <h2 class="text-xl font-bold text-slate-900 tracking-tight">Technical Specifications</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-sm">
                <div class="space-y-2 md:col-span-2">
                    <label for="name" class="block font-bold text-slate-700 tracking-tight">Asset Nomenclature <span class="text-blue-500">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="e.g. A-Dec 500 Dental Chair" required
                        class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-amber-500 transition-all font-bold text-slate-700 placeholder-slate-400">
                    @error('name') <p class="text-rose-500 text-[10px] font-black mt-1 ml-1 uppercase">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label for="serial_number" class="block font-bold text-slate-700 tracking-tight">System Serial Number</label>
                    <input type="text" id="serial_number" name="serial_number" value="{{ old('serial_number') }}" placeholder="SN-123-ABC-XYZ"
                           class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-amber-500 transition-all font-bold text-slate-700 placeholder-slate-400">
                </div>

                <div class="space-y-2">
                    <label for="model_number" class="block font-bold text-slate-700 tracking-tight">Asset Model Designator</label>
                    <input type="text" id="model_number" name="model_number" value="{{ old('model_number') }}" placeholder="Model-500-Series"
                           class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-amber-500 transition-all font-bold text-slate-700 placeholder-slate-400">
                </div>
            </div>
        </div>

        <!-- Lifecycle Management -->
        <div class="stagger-in bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm" style="--delay: 2">
            <div class="flex items-center space-x-3 mb-8">
                <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <h2 class="text-xl font-bold text-slate-900 tracking-tight">Lifecycle Intelligence</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-sm">
                <div class="space-y-2">
                    <label for="purchase_date" class="block font-bold text-slate-700 tracking-tight">Acquisition Date</label>
                    <input type="date" id="purchase_date" name="purchase_date" value="{{ old('purchase_date') }}"
                           class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-bold text-slate-700">
                </div>

                <div class="space-y-2">
                    <label for="warranty_expiry_date" class="block font-bold text-slate-700 tracking-tight">Warranty Threshold Date</label>
                    <input type="date" id="warranty_expiry_date" name="warranty_expiry_date" value="{{ old('warranty_expiry_date') }}"
                           class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-bold text-slate-700">
                </div>

                <div class="space-y-2">
                    <label for="last_maintenance_date" class="block font-bold text-slate-700 tracking-tight">Previous Maintenance Cycle</label>
                    <input type="date" id="last_maintenance_date" name="last_maintenance_date" value="{{ old('last_maintenance_date') }}"
                           class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-bold text-slate-700">
                </div>

                <div class="space-y-2">
                    <label for="next_maintenance_date" class="block font-bold text-slate-700 tracking-tight">Forecasted Maintenance Cycle</label>
                    <input type="date" id="next_maintenance_date" name="next_maintenance_date" value="{{ old('next_maintenance_date') }}"
                           class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-bold text-slate-700">
                </div>
            </div>
        </div>

        <!-- Asset Health Status -->
        <div class="stagger-in bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm" style="--delay: 3">
            <div class="flex items-center space-x-3 mb-8">
                <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h2 class="text-xl font-bold text-slate-900 tracking-tight">Operational Heartbeat</h2>
            </div>
            
            <div class="space-y-6 text-sm">
                <label for="status" class="block font-bold text-slate-700 tracking-tight">Active Deployment Status <span class="text-blue-500">*</span></label>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach(['functional' => 'bg-emerald-50 text-emerald-600', 'maintenance' => 'bg-amber-50 text-amber-600', 'repaired' => 'bg-blue-50 text-blue-600', 'obsolete' => 'bg-slate-50 text-slate-600'] as $key => $style)
                        <label class="relative cursor-pointer group">
                            <input type="radio" name="status" value="{{ $key }}" class="peer sr-only" {{ old('status', 'functional') == $key ? 'checked' : '' }}>
                            <div class="w-full py-4 text-center rounded-2xl font-black uppercase text-[10px] tracking-widest {{ $style }} border-2 border-transparent peer-checked:border-current transition-all group-hover:scale-[1.02]">
                                {{ $key }}
                            </div>
                        </label>
                    @endforeach
                </div>
                
                <div class="space-y-2 pt-4">
                    <label for="notes" class="block font-bold text-slate-700 tracking-tight">Asset Metadata / Incident Logs</label>
                    <textarea id="notes" name="notes" rows="4" placeholder="Enter technical notes, service provider details, or operational constraints..."
                        class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-amber-500 transition-all font-bold text-slate-700 resize-none placeholder-slate-400">{{ old('notes') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Commitment Hub -->
        <div class="stagger-in flex flex-col md:flex-row md:items-center justify-end gap-3 pt-4" style="--delay: 4">
            <a href="{{ route('clinic.equipment.index') }}" class="px-8 py-4 bg-slate-50 text-slate-500 rounded-2xl font-bold hover:bg-slate-100 transition-all text-center">
                Abort Commissioning
            </a>
            <button type="submit" class="px-10 py-4 bg-amber-600 text-white rounded-2xl font-black uppercase tracking-widest text-xs hover:bg-amber-700 transition-all shadow-lg shadow-amber-100 active:scale-95 flex items-center justify-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>Commit Asset Record</span>
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
