@extends(request()->has('iframe') ? 'layouts.iframe' : 'layouts.app')

@section('page-title', 'Schedule Appointment')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    {{-- ── Header ──────────────────────────────────────────────────── --}}
    @if(!request()->has('iframe'))
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Schedule Appointment</h1>
            <p class="text-sm text-slate-500 mt-1">Book a new appointment for a patient</p>
        </div>
        <div class="bg-blue-50 px-4 py-2.5 rounded-2xl border border-blue-100/50 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                @php
                    $nepaliDate = \App\Helpers\NepaliDateHelper::getCurrentNepaliDate();
                @endphp
                <div class="text-sm font-black text-blue-900 leading-tight">{{ $nepaliDate['formatted'] ?? '२६ कार्तिक २०८२' }}</div>
                <div class="text-[11px] font-bold text-blue-600/70 uppercase tracking-widest">{{ $nepaliDate['day_of_week'] ? \App\Services\NepaliCalendarService::getDayName($nepaliDate['day_of_week']) : 'बुधबार' }}</div>
            </div>
        </div>
    </div>
    @endif

    {{-- ── Form Card ───────────────────────────────────────────────── --}}
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
        {{-- Top blue accent line --}}
        <div class="h-1.5 w-full bg-gradient-to-r from-blue-500 to-indigo-500"></div>

        <div class="p-6 sm:p-8">
            <form id="appointment-form" action="{{ route('clinic.appointments.store') }}" method="POST">
                @csrf

                <div class="space-y-6">
                    
                    {{-- Patient Selection --}}
                    <div>
                        <label class="block text-sm font-bold text-slate-700 tracking-tight mb-2">Patient <span class="text-blue-500">*</span></label>
                        <select name="patient_id" class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm font-semibold rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 block p-3.5 transition-all" required>
                            <option value="">Select a registered patient...</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->id }}" {{ request('patient_id') == $patient->id ? 'selected' : '' }}>
                                    {{ $patient->name }} ({{ $patient->patient_id }})
                                </option>
                            @endforeach
                        </select>
                        @error('patient_id')
                            <p class="text-red-500 text-xs font-semibold mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Date & Time Row --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <x-nepali-date-input 
                                name="appointment_date"
                                label="Appointment Date"
                                :value="old('appointment_date')"
                                required
                                :minDate="date('Y-m-d')"
                                help="BS Date format automatically converted."
                            />
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 tracking-tight mb-2">Time <span class="text-blue-500">*</span></label>
                            <input type="time" name="appointment_time" 
                                   class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm font-semibold rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 block p-3.5 transition-all" required>
                            @error('appointment_time')
                                <p class="text-red-500 text-xs font-semibold mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <hr class="border-slate-100">

                    {{-- Treatment Type --}}
                    <div>
                        <label class="block text-sm font-bold text-slate-700 tracking-tight mb-2">Treatment Type <span class="text-blue-500">*</span></label>
                        <div class="relative">
                            <select name="type" class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm font-semibold rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 block p-3.5 appearance-none cursor-pointer transition-all" required>
                                <option value="">Select required treatment...</option>
                                <option value="consultation">Consultation / Checkup</option>
                                <option value="cleaning">Teeth Cleaning</option>
                                <option value="filling">Dental Filling</option>
                                <option value="extraction">Tooth Extraction</option>
                                <option value="root_canal">Root Canal Therapy</option>
                                <option value="crown">Crown / Bridge</option>
                                <option value="braces">Orthodontics (Braces)</option>
                                <option value="whitening">Teeth Whitening</option>
                                <option value="other">Other / Not Sure</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </div>
                        @error('type')
                            <p class="text-red-500 text-xs font-semibold mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Estimated Cost --}}
                    <div>
                        <label class="block text-sm font-bold text-slate-700 tracking-tight mb-2">Estimated Cost <span class="text-slate-400 font-normal">(Optional)</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                                <span class="text-slate-400 font-bold text-sm">NPR</span>
                            </div>
                            <input type="number" name="treatment_cost" 
                                   class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm font-semibold rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 block pl-14 p-3.5 transition-all" 
                                   step="1" min="0" placeholder="0.00">
                        </div>
                        @error('treatment_cost')
                            <p class="text-red-500 text-xs font-semibold mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Notes --}}
                    <div>
                        <label class="block text-sm font-bold text-slate-700 tracking-tight mb-2">Notes & Instructions <span class="text-slate-400 font-normal">(Optional)</span></label>
                        <textarea name="notes" rows="3" 
                                  class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm font-medium rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 block p-3.5 transition-all resize-none" 
                                  placeholder="E.g., Patient is allergic to penicillin..."></textarea>
                        @error('notes')
                            <p class="text-red-500 text-xs font-semibold mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Actions --}}
                    <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                        @if(request()->has('iframe'))
                            <button type="button" onclick="window.parent.closeSlideModal()" 
                                    class="px-5 py-2.5 text-sm font-bold text-slate-500 hover:text-slate-700 hover:bg-slate-100 rounded-xl transition-all">
                                Cancel
                            </button>
                        @else
                            <a href="{{ route('clinic.appointments.index') }}" 
                               class="px-5 py-2.5 text-sm font-bold text-slate-500 hover:text-slate-700 hover:bg-slate-100 rounded-xl transition-all">
                                Cancel
                            </a>
                        @endif
                        <button type="submit" 
                                class="inline-flex items-center gap-2 px-6 py-2.5 text-sm font-bold text-white bg-blue-600 rounded-xl hover:bg-blue-700 active:scale-95 transition-all shadow-lg shadow-blue-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                            </svg>
                            Confirm Booking
                        </button>
                    </div>

                </div>
            </form>
        </div>
        
        @if(request()->has('iframe'))
            <input type="hidden" name="iframe" value="1" form="appointment-form">
        @endif
    </div>

</div>

{{-- If successfully scheduled and inside iframe, close modal and reload parent --}}
@if(session('success') && request()->has('iframe'))
    <script>
        setTimeout(() => {
            window.parent.location.reload();
        }, 1500); // 1.5s delay to allow toast to be readable
    </script>
@endif
@endsection