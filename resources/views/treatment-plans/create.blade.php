@extends(request()->has('iframe') ? 'layouts.iframe' : 'layouts.app')

@section('page-title', 'Clinical: Treatment Strategy')

@section('content')
<div class="page-fade-in max-w-4xl mx-auto pb-12">
    <!-- Premium Header -->
    <div class="stagger-in mb-8">
        <div class="flex items-center space-x-3 text-slate-500 text-sm mb-2">
            <a href="{{ route('clinic.treatment-plans.index') }}" class="hover:text-blue-600 transition-colors">Strategy Ledger</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            <span class="text-slate-900 font-medium tracking-tight">New Treatment Roadmap</span>
        </div>
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-indigo-600 rounded-2xl flex items-center justify-center shadow-lg shadow-indigo-100">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Define Strategy</h1>
                    <p class="text-slate-500 font-medium italic">Architecting comprehensive patient care protocols</p>
                </div>
            </div>
            
            <div class="hidden md:block bg-white px-5 py-3 rounded-2xl border border-slate-100 shadow-sm text-right">
                <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Current Protocol Date</div>
                @php
                    $nepaliDate = \App\Helpers\NepaliDateHelper::getCurrentNepaliDate();
                @endphp
                <div class="text-sm font-black text-blue-600">{{ $nepaliDate['formatted'] ?? '२६ कार्तिक २०८२' }}</div>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('clinic.treatment-plans.store') }}" class="space-y-6">
        @if(request()->has('iframe'))
            <input type="hidden" name="iframe" value="1">
        @endif
        @csrf
        
        <!-- Strategy Foundation -->
        <div class="stagger-in bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm" style="--delay: 1">
            <div class="flex items-center space-x-3 mb-8">
                <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </div>
                <h2 class="text-xl font-bold text-slate-900 tracking-tight">Strategy Foundation</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-sm">
                <div class="space-y-2 md:col-span-2">
                    <label for="patient_id" class="block font-bold text-slate-700 tracking-tight">Clinical Subject <span class="text-blue-500">*</span></label>
                    <select name="patient_id" id="patient_id" required 
                        class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-bold text-slate-700 appearance-none">
                        <option value="">Select Target Patient</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>{{ $patient->name }}</option>
                        @endforeach
                    </select>
                    @error('patient_id') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2 md:col-span-2">
                    <label for="title" class="block font-bold text-slate-700 tracking-tight">Plan Nomenclature <span class="text-blue-500">*</span></label>
                    <input type="text" name="title" id="title" 
                        class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-bold text-slate-700 placeholder-slate-400" 
                        placeholder="e.g. Multi-Phase Root Canal Strategy" value="{{ old('title') }}" required>
                    @error('title') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2 md:col-span-2">
                    <label for="description" class="block font-bold text-slate-700 tracking-tight">Clinical Narrative <span class="text-blue-500">*</span></label>
                    <textarea name="description" id="description" rows="4" required
                        class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-bold text-slate-700 placeholder-slate-400 resize-none" 
                        placeholder="Detailed roadmap of the proposed treatment intervention...">{{ old('description') }}</textarea>
                    @error('description') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <!-- Logistics & Priority -->
        <div class="stagger-in bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm" style="--delay: 2">
            <div class="flex items-center space-x-3 mb-8">
                <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h2 class="text-xl font-bold text-slate-900 tracking-tight">Intervention Logistics</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-sm">
                <div class="space-y-2">
                    <label for="estimated_cost" class="block font-bold text-slate-700 tracking-tight">Financial Projection (NPR) <span class="text-blue-500">*</span></label>
                    <div class="relative group">
                        <span class="absolute left-5 top-4 text-slate-400 font-bold">Rs.</span>
                        <input type="number" name="estimated_cost" id="estimated_cost" required
                            class="w-full pl-14 pr-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 transition-all font-bold text-slate-700" 
                            placeholder="0.00" value="{{ old('estimated_cost') }}">
                    </div>
                    @error('estimated_cost') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label for="estimated_duration" class="block font-bold text-slate-700 tracking-tight">Timeline Expectation <span class="text-blue-500">*</span></label>
                    <input type="text" name="estimated_duration" id="estimated_duration" required
                        class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 transition-all font-bold text-slate-700 placeholder-slate-400" 
                        placeholder="e.g. 4 Sessions | 2 Weeks" value="{{ old('estimated_duration') }}">
                    @error('estimated_duration') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2 md:col-span-2">
                    <label for="priority" class="block font-bold text-slate-700 tracking-tight">Clinical Priority <span class="text-blue-500">*</span></label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach(['low' => 'bg-slate-50 text-slate-600', 'medium' => 'bg-blue-50 text-blue-600', 'high' => 'bg-orange-50 text-orange-600', 'urgent' => 'bg-rose-50 text-rose-600'] as $key => $style)
                            <label class="relative cursor-pointer group">
                                <input type="radio" name="priority" value="{{ $key }}" class="peer sr-only" {{ old('priority', 'medium') == $key ? 'checked' : '' }}>
                                <div class="w-full py-4 text-center rounded-2xl font-black uppercase text-[10px] tracking-widest {{ $style }} border-2 border-transparent peer-checked:border-current transition-all group-hover:scale-[1.02]">
                                    {{ $key }}
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Final Commitment -->
        <div class="stagger-in flex flex-col md:flex-row md:items-center justify-end gap-3 pt-4" style="--delay: 3">
            <a href="{{ route('clinic.treatment-plans.index') }}" class="px-8 py-4 bg-slate-50 text-slate-500 rounded-2xl font-bold hover:bg-slate-100 transition-all text-center">
                Abort Strategy
            </a>
            <button type="submit" class="px-10 py-4 bg-indigo-600 text-white rounded-2xl font-black uppercase tracking-widest text-xs hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-100 active:scale-95 flex items-center justify-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>Commit Strategy</span>
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
