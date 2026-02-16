@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Schedule Appointment</h2>
            <p class="text-sm text-slate-500">Coordinate dental visits with doctors and chair availability.</p>
        </div>
        <a href="{{ route('clinic.appointments.index') }}" class="text-sm font-medium text-slate-500 hover:text-slate-700 flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to List
        </a>
    </div>

    @if ($errors->any())
        <div class="mb-6 rounded-xl bg-red-50 border border-red-100 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-bold text-red-800">Scheduling errors:</h3>
                    <ul class="mt-2 text-sm text-red-700 list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <form action="{{ route('clinic.appointments.store') }}" method="POST" class="p-8">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Left Side: Logistics -->
                <div class="space-y-6">
                    <div>
                        <label for="branch_id" class="block text-sm font-bold text-slate-700 mb-1">Clinic Branch *</label>
                        <select name="branch_id" id="branch_id" required class="block w-full rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            <option value="">Select Branch</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="patient_id" class="block text-sm font-bold text-slate-700 mb-1">Patient *</label>
                        <select name="patient_id" id="patient_id" required class="block w-full rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm" data-search="true">
                            <option value="">Select Patient</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->id }}" {{ (old('patient_id') == $patient->id || ($selectedPatient && $selectedPatient->id == $patient->id)) ? 'selected' : '' }}>
                                    {{ $patient->full_name }} ({{ $patient->patient_id }})
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-2 text-xs text-slate-400">Can't find patient? <a href="{{ route('clinic.patients.create') }}" class="text-indigo-600 font-bold hover:underline">Register New</a></p>
                    </div>

                    <div>
                        <label for="dentist_id" class="block text-sm font-bold text-slate-700 mb-1">Attending Dentist *</label>
                        <select name="dentist_id" id="dentist_id" required class="block w-full rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            <option value="">Select Dentist</option>
                            @foreach($dentists as $dentist)
                                <option value="{{ $dentist->id }}" {{ old('dentist_id') == $dentist->id ? 'selected' : '' }}>Dr. {{ $dentist->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="type" class="block text-sm font-bold text-slate-700 mb-1">Appointment Type *</label>
                        <select name="type" id="type" required class="block w-full rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            <option value="Checkup" {{ old('type') == 'Checkup' ? 'selected' : '' }}>Routine Checkup</option>
                            <option value="Cleaning" {{ old('type') == 'Cleaning' ? 'selected' : '' }}>Scaling & Cleaning</option>
                            <option value="Extraction" {{ old('type') == 'Extraction' ? 'selected' : '' }}>Tooth Extraction</option>
                            <option value="Filling" {{ old('type') == 'Filling' ? 'selected' : '' }}>Dental Filling</option>
                            <option value="Consultation" {{ old('type') == 'Consultation' ? 'selected' : '' }}>Specialist Consultation</option>
                            <option value="Root Canal" {{ old('type') == 'Root Canal' ? 'selected' : '' }}>Root Canal Treatment</option>
                            <option value="Other" {{ old('type') == 'Other' ? 'selected' : '' }}>Other Procedure</option>
                        </select>
                    </div>
                </div>

                <!-- Right Side: Timing -->
                <div class="space-y-6">
                    <div class="p-6 bg-slate-50 rounded-2xl border border-slate-100">
                        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Date & Time Slots</h4>
                        <div class="space-y-4">
                            <div>
                                <label for="appointment_date" class="block text-xs font-bold text-slate-600 mb-1 uppercase tracking-tight">Preferred Date</label>
                                <input type="date" name="appointment_date" id="appointment_date" value="{{ old('appointment_date', date('Y-m-d')) }}" required class="block w-full rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="appointment_time" class="block text-xs font-bold text-slate-600 mb-1 uppercase tracking-tight">Time</label>
                                    <input type="time" name="appointment_time" id="appointment_time" value="{{ old('appointment_time') }}" required class="block w-full rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                </div>
                                <div>
                                    <label for="duration" class="block text-xs font-bold text-slate-600 mb-1 uppercase tracking-tight">Est. Duration (Min)</label>
                                    <input type="number" name="duration" id="duration" value="{{ old('duration', 30) }}" step="15" min="15" required class="block w-full rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="notes" class="block text-sm font-bold text-slate-700 mb-1">Administrative Notes</label>
                        <textarea name="notes" id="notes" rows="4" placeholder="Patient concerns, specific requests..." class="block w-full rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="mt-12 pt-8 border-t border-slate-100 flex justify-end space-x-3">
                <button type="reset" class="px-6 py-2 border border-slate-200 text-sm font-medium rounded-lg text-slate-600 hover:bg-slate-50 transition-colors">
                    Reset Form
                </button>
                <button type="submit" class="px-8 py-2 bg-indigo-600 shadow-lg shadow-indigo-100 text-white text-sm font-bold rounded-lg hover:bg-indigo-700 transition-all">
                    Confirm Appointment
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
