@extends(request()->has('iframe') ? 'layouts.iframe' : 'layouts.app')

@section('page-title', 'Patient Onboarding Hub')

@section('content')
<div class="page-fade-in max-w-4xl mx-auto pb-12">
    <!-- Premium Header -->
    <div class="stagger-in mb-8">
        <div class="flex items-center space-x-3 text-slate-500 text-sm mb-2">
            <a href="{{ route('clinic.patients.index') }}" class="hover:text-blue-600 transition-colors">Patient Registry</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            <span class="text-slate-900 font-medium tracking-tight">New Subject Onboarding</span>
        </div>
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-100">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                </svg>
            </div>
            <div>
                <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Onboard Subject</h1>
                <p class="text-slate-500 font-medium italic">Establishing a new Clinical Record entry</p>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('clinic.patients.store') }}" class="space-y-6">
    @if(request()->has('iframe'))
        <input type="hidden" name="iframe" value="1">
    @endif

        @csrf
        
        <!-- Subject Demographics Section -->
        <div class="stagger-in bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm" style="--delay: 1">
            <div class="flex items-center space-x-3 mb-8">
                <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </div>
                <h2 class="text-xl font-bold text-slate-900 tracking-tight">Subject Demographics</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-sm">
                <div class="space-y-2">
                    <label for="first_name" class="block font-bold text-slate-700 tracking-tight">First Given Name <span class="text-blue-500">*</span></label>
                    <input type="text" name="first_name" id="first_name" 
                        class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-medium text-slate-700 placeholder-slate-400" 
                        placeholder="e.g. Rahul" value="{{ old('first_name') }}" required>
                    @error('first_name') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label for="last_name" class="block font-bold text-slate-700 tracking-tight">Family Descriptor <span class="text-blue-500">*</span></label>
                    <input type="text" name="last_name" id="last_name" 
                        class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-medium text-slate-700 placeholder-slate-400" 
                        placeholder="e.g. Bashyal" value="{{ old('last_name') }}" required>
                    @error('last_name') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label for="gender" class="block font-bold text-slate-700 tracking-tight">Biological Gender</label>
                    <select name="gender" id="gender" class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-medium text-slate-700">
                        <option value="">Select Identifying Gender</option>
                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male Identifying</option>
                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female Identifying</option>
                        <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other/Prefer not to disclose</option>
                    </select>
                </div>

                <div class="space-y-2">
                    <x-nepali-date-input 
                        name="date_of_birth"
                        label="Natal Date (जन्म मिति)"
                        :value="old('date_of_birth')"
                        :maxDate="date('Y-m-d')"
                        help="Subject's date of birth in BS"
                    />
                </div>
            </div>
        </div>

        <!-- Contact Intel Section -->
        <div class="stagger-in bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm" style="--delay: 2">
            <div class="flex items-center space-x-3 mb-8">
                <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                </div>
                <h2 class="text-xl font-bold text-slate-900 tracking-tight">Communication Intel</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-sm">
                <div class="space-y-2">
                    <label for="phone" class="block font-bold text-slate-700 tracking-tight">Telephonic Line <span class="text-blue-500">*</span></label>
                    <input type="text" name="phone" id="phone" 
                        class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-medium text-slate-700 placeholder-slate-400" 
                        placeholder="+977-XXXXXXXXXX" value="{{ old('phone') }}" required>
                    @error('phone') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label for="email" class="block font-bold text-slate-700 tracking-tight">Electronic Mail Address</label>
                    <input type="email" name="email" id="email" 
                        class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-medium text-slate-700 placeholder-slate-400" 
                        placeholder="patient@medical-hub.com" value="{{ old('email') }}">
                    @error('email') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <!-- Location Intel Section -->
        <div class="stagger-in bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm" style="--delay: 3">
            <div class="flex items-center space-x-3 mb-8">
                <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </div>
                <h2 class="text-xl font-bold text-slate-900 tracking-tight">Geospatial Residence</h2>
            </div>
            
            <div class="grid grid-cols-1 gap-8 text-sm">
                <div class="space-y-2">
                    <label for="address" class="block font-bold text-slate-700 tracking-tight">Geographic Address <span class="text-blue-500">*</span></label>
                    <textarea name="address" id="address" rows="3" 
                        class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-medium text-slate-700 placeholder-slate-400" 
                        placeholder="Complete civic address..." required>{{ old('address') }}</textarea>
                    @error('address') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <!-- Clinical Context Section -->
        <div class="stagger-in bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm" style="--delay: 4">
            <div class="flex items-center space-x-3 mb-8">
                <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center text-amber-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
                <h2 class="text-xl font-bold text-slate-900 tracking-tight">Clinical Context</h2>
            </div>
            
            <div class="space-y-2 text-sm">
                <label for="medical_history" class="block font-bold text-slate-700 tracking-tight">Initial Medical Observation / Allergies</label>
                <textarea name="medical_history" id="medical_history" rows="4"
                    class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-medium text-slate-700 placeholder-slate-400" 
                    placeholder="Document any chronic conditions, drug sensitivities, or prior surgical history here...">{{ old('medical_history') }}</textarea>
                @error('medical_history') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <!-- Onboarding Submission -->
        <div class="stagger-in flex flex-col md:flex-row md:items-center justify-end gap-3 pt-4" style="--delay: 5">
            <a href="{{ route('clinic.patients.index') }}" class="px-8 py-4 bg-slate-50 text-slate-500 rounded-2xl font-bold hover:bg-slate-100 transition-all text-center">
                Abort Onboarding
            </a>
            <button type="submit" class="px-10 py-4 bg-blue-600 text-white rounded-2xl font-bold hover:bg-blue-700 transition-all shadow-lg shadow-blue-100 active:scale-95 flex items-center justify-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span>Validate & Register Subject</span>
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
