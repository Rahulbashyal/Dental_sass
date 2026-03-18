@extends(request()->has('iframe') ? 'layouts.iframe' : 'layouts.app')

@section('page-title', 'Clinical: Asset Refactoring')

@section('content')
<div class="page-fade-in max-w-4xl mx-auto pb-12">
    <!-- Premium Header -->
    <div class="stagger-in mb-8">
        <div class="flex items-center space-x-3 text-slate-500 text-sm mb-2">
            <a href="{{ route('clinic.equipment.index') }}" class="hover:text-amber-600 transition-colors">Asset Registry</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            <span class="text-slate-900 font-medium tracking-tight">Asset Node Refactoring</span>
        </div>
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-100">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
            </div>
            <div>
                <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Refactor Asset</h1>
                <p class="text-slate-500 font-medium italic">Adjusting node: {{ $equipment->name }}</p>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('clinic.equipment.update', $equipment) }}" class="space-y-6">
    @if(request()->has('iframe'))
        <input type="hidden" name="iframe" value="1">
    @endif

        @csrf
        @method('PUT')
        
        <!-- Identity Section -->
        <div class="stagger-in bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm" style="--delay: 1">
            <div class="flex items-center space-x-3 mb-8">
                <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600">
                     <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 011-1h1a2 2 0 100-4H7a1 1 0 01-1-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"></path></svg>
                </div>
                <h2 class="text-xl font-bold text-slate-900 tracking-tight">Node Identity Synchronization</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-sm">
                <div class="space-y-2 md:col-span-2">
                    <label for="name" class="block font-bold text-slate-700 tracking-tight">Asset Designation <span class="text-blue-500">*</span></label>
                    <input type="text" name="name" id="name" 
                        class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-medium text-slate-700 placeholder-slate-400" 
                        placeholder="e.g. Ultrasonic Scaler 3000" value="{{ old('name', $equipment->name) }}" required>
                    @error('name') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label for="model" class="block font-bold text-slate-700 tracking-tight">Architectural Model</label>
                    <input type="text" name="model" id="model" 
                        class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-amber-500 transition-all font-medium text-slate-700 placeholder-slate-400" 
                        placeholder="Model Revision" value="{{ old('model', $equipment->model) }}">
                </div>
                
                <div class="space-y-2">
                     <label for="serial_number" class="block font-bold text-slate-700 tracking-tight">Serial Sequence</label>
                    <input type="text" name="serial_number" id="serial_number" 
                        class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-amber-500 transition-all font-medium text-slate-700 placeholder-slate-400" 
                        placeholder="SN-000-000" value="{{ old('serial_number', $equipment->serial_number) }}">
                </div>
            </div>
        </div>

        <!-- Lifecycle Section -->
        <div class="stagger-in bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm" style="--delay: 2">
            <div class="flex items-center space-x-3 mb-8">
                <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <h2 class="text-xl font-bold text-slate-900 tracking-tight">Lifecycle Intel synchronization</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-sm">
                <div class="space-y-2">
                    <label for="purchase_date" class="block font-bold text-slate-700 tracking-tight">Acquisition Date</label>
                    <input type="date" name="purchase_date" id="purchase_date" 
                        class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-amber-500 transition-all font-medium text-slate-700" 
                        value="{{ old('purchase_date', $equipment->purchase_date ? $equipment->purchase_date->format('Y-m-d') : '') }}">
                </div>

                <div class="space-y-2">
                    <label for="warranty_expiry" class="block font-bold text-slate-700 tracking-tight">Warranty EOL</label>
                    <input type="date" name="warranty_expiry" id="warranty_expiry" 
                        class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-amber-500 transition-all font-medium text-slate-700" 
                        value="{{ old('warranty_expiry', $equipment->warranty_expiry ? $equipment->warranty_expiry->format('Y-m-d') : '') }}">
                </div>

                <div class="space-y-2 md:col-span-2">
                    <label for="status" class="block font-bold text-slate-700 tracking-tight">Operational State <span class="text-blue-500">*</span></label>
                    <select name="status" id="status" required 
                        class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-medium text-slate-700">
                        <option value="operational" {{ old('status', $equipment->status) == 'operational' ? 'selected' : '' }}>Operational Identifying</option>
                        <option value="under_maintenance" {{ old('status', $equipment->status) == 'under_maintenance' ? 'selected' : '' }}>Under Maintenance</option>
                        <option value="retired" {{ old('status', $equipment->status) == 'retired' ? 'selected' : '' }}>Retired / Archived</option>
                    </select>
                </div>

                <div class="space-y-2 md:col-span-2">
                    <label for="last_maintenance_at" class="block font-bold text-slate-700 tracking-tight">Last Maintenance Sync Log</label>
                    <input type="date" name="last_maintenance_at" id="last_maintenance_at" 
                         class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-amber-500 transition-all font-medium text-slate-700" 
                         value="{{ old('last_maintenance_at', $equipment->last_maintenance_at ? $equipment->last_maintenance_at->format('Y-m-d') : '') }}">
                </div>
            </div>
        </div>

        <!-- Action Hub -->
        <div class="stagger-in flex flex-col md:flex-row md:items-center justify-end gap-3 pt-4" style="--delay: 3">
            <a href="{{ route('clinic.equipment.index') }}" class="px-8 py-4 bg-slate-50 text-slate-500 rounded-2xl font-bold hover:bg-slate-100 transition-all text-center">
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
