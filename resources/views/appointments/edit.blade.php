@extends(request()->has('iframe') ? 'layouts.iframe' : 'layouts.app')

@section('page-title', 'Edit Appointment')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    {{-- ── Header ──────────────────────────────────────────────────── --}}
    @if(!request()->has('iframe'))
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Edit Appointment</h1>
            <p class="text-sm text-slate-500 mt-1">Update appointment details for {{ $appointment->patient->name }}</p>
        </div>
        <div class="bg-indigo-50 px-4 py-2.5 rounded-2xl border border-indigo-100/50 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
            </div>
            <div>
                <div class="text-[11px] font-bold text-indigo-600/70 uppercase tracking-widest">Status</div>
                <div class="text-sm font-black text-indigo-900 leading-tight">{{ ucfirst(str_replace('_', ' ', $appointment->status)) }}</div>
            </div>
        </div>
    </div>
    @endif

    {{-- ── Form Card ───────────────────────────────────────────────── --}}
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
        {{-- Top indigo accent line --}}
        <div class="h-1.5 w-full bg-gradient-to-r from-indigo-500 to-purple-500"></div>

        <div class="p-6 sm:p-8">
            <form id="appointment-form" action="{{ route('clinic.appointments.update', $appointment) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    
                    {{-- Patient & Status Row --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 tracking-tight mb-2">Patient <span class="text-indigo-500">*</span></label>
                            <input type="hidden" name="patient_id" value="{{ $appointment->patient_id }}">
                            <div class="w-full bg-slate-50 border border-slate-200 text-slate-500 text-sm font-semibold rounded-2xl block p-3.5 cursor-not-allowed">
                                {{ $appointment->patient->name ?? ($appointment->patient->first_name . ' ' . $appointment->patient->last_name) }}
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 tracking-tight mb-2">Status <span class="text-indigo-500">*</span></label>
                            <div class="relative">
                                <select name="status" class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm font-semibold rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 block p-3.5 appearance-none cursor-pointer transition-all" required>
                                    <option value="scheduled" {{ $appointment->status === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                    <option value="confirmed" {{ $appointment->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                    <option value="in_progress" {{ $appointment->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="completed" {{ $appointment->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ $appointment->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    <option value="no_show" {{ $appointment->status === 'no_show' ? 'selected' : '' }}>No Show</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                            </div>
                            @error('status')
                                <p class="text-red-500 text-xs font-semibold mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Date & Time Row --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <x-nepali-date-input 
                                name="appointment_date"
                                label="Appointment Date"
                                :value="old('appointment_date', $appointment->appointment_date ? $appointment->appointment_date->format('Y-m-d') : null)"
                                required
                                help="BS Date format automatically converted."
                            />
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 tracking-tight mb-2">Time <span class="text-indigo-500">*</span></label>
                            <input type="time" name="appointment_time" 
                                   value="{{ old('appointment_time', \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i')) }}"
                                   class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm font-semibold rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 block p-3.5 transition-all" required>
                            @error('appointment_time')
                                <p class="text-red-500 text-xs font-semibold mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <hr class="border-slate-100">

                    {{-- Treatment Type --}}
                    <div>
                        <label class="block text-sm font-bold text-slate-700 tracking-tight mb-2">Treatment Type <span class="text-indigo-500">*</span></label>
                        <div class="relative">
                            <select name="type" class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm font-semibold rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 block p-3.5 appearance-none cursor-pointer transition-all" required>
                                <option value="consultation" {{ $appointment->type === 'consultation' ? 'selected' : '' }}>Consultation / Checkup</option>
                                <option value="cleaning" {{ $appointment->type === 'cleaning' ? 'selected' : '' }}>Teeth Cleaning</option>
                                <option value="filling" {{ $appointment->type === 'filling' ? 'selected' : '' }}>Dental Filling</option>
                                <option value="extraction" {{ $appointment->type === 'extraction' ? 'selected' : '' }}>Tooth Extraction</option>
                                <option value="root_canal" {{ $appointment->type === 'root_canal' ? 'selected' : '' }}>Root Canal Therapy</option>
                                <option value="crown" {{ $appointment->type === 'crown' ? 'selected' : '' }}>Crown / Bridge</option>
                                <option value="braces" {{ $appointment->type === 'braces' ? 'selected' : '' }}>Orthodontics (Braces)</option>
                                <option value="whitening" {{ $appointment->type === 'whitening' ? 'selected' : '' }}>Teeth Whitening</option>
                                <option value="other" {{ $appointment->type === 'other' ? 'selected' : '' }}>Other / Not Sure</option>
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
                                   value="{{ old('treatment_cost', $appointment->treatment_cost) }}"
                                   class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm font-semibold rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 block pl-14 p-3.5 transition-all" 
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
                                  class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm font-medium rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 block p-3.5 transition-all resize-none" 
                                  placeholder="E.g., Patient is allergic to penicillin...">{{ old('notes', $appointment->notes) }}</textarea>
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
                                class="inline-flex items-center gap-2 px-6 py-2.5 text-sm font-bold text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 active:scale-95 transition-all shadow-lg shadow-indigo-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Save Changes
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

{{-- If successfully updated and inside iframe, close modal and reload parent --}}
@if(session('success') && request()->has('iframe'))
    <script>
        setTimeout(() => {
            window.parent.location.reload();
        }, 1500);
    </script>
@endif
@endsection