@extends(request()->has('iframe') ? 'layouts.iframe' : 'layouts.app')

@section('title', 'Create Recurring Appointment')
@section('page-title', 'Create Recurring Appointment')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="card bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
        <div class="p-6 border-b border-gray-50 bg-gray-50/50">
            <h2 class="text-xl font-bold text-gray-900">New Recurring Schedule</h2>
            <p class="text-sm text-gray-500 mt-1">Setup a long-term treatment or follow-up schedule for your patient.</p>
        </div>
        
        <form action="{{ route('clinic.recurring-appointments.store') }}" method="POST" class="p-8 space-y-8" id="recurringForm">
    @if(request()->has('iframe'))
        <input type="hidden" name="iframe" value="1">
    @endif

            @csrf
            
            <!-- Basic Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-2">
                    <label class="form-label text-gray-700 font-semibold">Patient</label>
                    <div class="relative">
                        <select name="patient_id" class="form-input block w-full pl-10 h-12 rounded-xl border-gray-200 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition duration-150" required>
                            <option value="">Select Patient</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                                    {{ $patient->first_name }} {{ $patient->last_name }} ({{ $patient->patient_id }})
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                
                <div class="space-y-2">
                    <label class="form-label text-gray-700 font-semibold">Dentist</label>
                    <div class="relative">
                        <select name="dentist_id" class="form-input block w-full pl-10 h-12 rounded-xl border-gray-200 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition duration-150" required>
                            <option value="">Select Dentist</option>
                            @foreach($dentists as $dentist)
                                <option value="{{ $dentist->id }}" {{ old('dentist_id') == $dentist->id ? 'selected' : '' }}>
                                    Dr. {{ $dentist->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recurrence Details -->
            <div class="bg-indigo-50/30 p-6 rounded-2xl border border-indigo-100/50 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="space-y-2">
                        <label class="form-label text-gray-700 font-semibold">Frequency</label>
                        <select name="frequency" id="frequency" class="form-input block w-full h-12 rounded-xl border-gray-200 focus:border-indigo-500 shadow-sm" required>
                            <option value="daily" {{ old('frequency') == 'daily' ? 'selected' : '' }}>Daily</option>
                            <option value="weekly" {{ old('frequency') == 'weekly' ? 'selected' : 'selected' }}>Weekly</option>
                            <option value="monthly" {{ old('frequency') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                            <option value="quarterly" {{ old('frequency') == 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="form-label text-gray-700 font-semibold">Appointment Time</label>
                        <input type="time" name="appointment_time" class="form-input block w-full h-12 rounded-xl border-gray-200 focus:border-indigo-500 shadow-sm" value="{{ old('appointment_time', '09:00') }}" required>
                    </div>

                    <div class="space-y-2">
                        <label class="form-label text-gray-700 font-semibold">Service Type</label>
                        <input type="text" name="type" placeholder="e.g. Orthodontic Review" class="form-input block w-full h-12 rounded-xl border-gray-200 focus:border-indigo-500 shadow-sm" value="{{ old('type') }}" required>
                    </div>
                </div>

                <!-- Days of Week (Conditional) -->
                <div id="daysOfWeekContainer" class="space-y-3">
                    <label class="form-label text-gray-700 font-semibold block">Days of the Week</label>
                    <div class="flex flex-wrap gap-3">
                        @php
                            $days = [
                                0 => 'Sun', 1 => 'Mon', 2 => 'Tue', 3 => 'Wed', 4 => 'Thu', 5 => 'Fri', 6 => 'Sat'
                            ];
                        @endphp
                        @foreach($days as $val => $day)
                            <label class="relative inline-flex items-center cursor-pointer group">
                                <input type="checkbox" name="days_of_week[]" value="{{ $val }}" class="sr-only peer" {{ is_array(old('days_of_week')) && in_array($val, old('days_of_week')) ? 'checked' : '' }}>
                                <div class="w-12 h-12 bg-white border-2 border-gray-100 rounded-xl flex items-center justify-center text-sm font-bold text-gray-400 peer-checked:bg-indigo-600 peer-checked:border-indigo-600 peer-checked:text-white transition-all duration-200 hover:border-indigo-300">
                                    {{ $day }}
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Date Range -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-2">
                    <label class="form-label text-gray-700 font-semibold">Start Date</label>
                    <input type="date" name="start_date" class="form-input block w-full h-12 rounded-xl border-gray-200 focus:border-indigo-500 shadow-sm" value="{{ old('start_date', date('Y-m-d')) }}" required>
                </div>

                <div class="space-y-2">
                    <label class="form-label text-gray-700 font-semibold">End Date (Optional)</label>
                    <input type="date" name="end_date" class="form-input block w-full h-12 rounded-xl border-gray-200 focus:border-indigo-500 shadow-sm" value="{{ old('end_date') }}">
                </div>
            </div>
            
            <div class="pt-6 border-t border-gray-100 flex items-center justify-between">
                <p class="text-xs text-gray-400 italic">Individual appointments will be generated according to this schedule.</p>
                <div class="flex space-x-4">
                    <a href="{{ route('clinic.recurring-appointments.index') }}" class="px-6 py-3 rounded-xl border border-gray-200 text-gray-600 font-semibold hover:bg-gray-50 transition duration-150">
                        Cancel
                    </a>
                    <button type="submit" class="px-8 py-3 bg-indigo-600 text-white rounded-xl font-bold shadow-lg shadow-indigo-200 hover:bg-indigo-700 transform hover:-translate-y-0.5 transition duration-150">
                        Create Schedule
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    const freqSelect = document.getElementById('frequency');
    const daysContainer = document.getElementById('daysOfWeekContainer');

    function toggleDays() {
        if (freqSelect.value === 'weekly') {
            daysContainer.style.display = 'block';
        } else {
            daysContainer.style.display = 'none';
        }
    }

    freqSelect.addEventListener('change', toggleDays);
    toggleDays();
</script>
@endpush
@endsection

{{-- Auto-close modal script on success --}}
@if(session('success') && request()->has('iframe'))
    <script>
        setTimeout(() => {
            window.parent.location.reload();
        }, 1500);
    </script>
@endif
