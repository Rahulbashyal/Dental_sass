@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">New Treatment Plan</h2>
            <p class="text-sm text-slate-500">Draft a clinical pathway for a patient.</p>
        </div>
        <a href="{{ route('clinic.treatment-plans.index') }}" class="text-sm font-medium text-slate-500 hover:text-slate-700 flex items-center">
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
                    <h3 class="text-sm font-bold text-red-800">Errors found:</h3>
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
        <form action="{{ route('clinic.treatment-plans.store') }}" method="POST" class="p-8">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Logistics -->
                <div class="space-y-6">
                    <div>
                        <label for="patient_id" class="block text-sm font-bold text-slate-700 mb-1">Patient *</label>
                        <select name="patient_id" id="patient_id" required class="block w-full rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            <option value="">Select Patient</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->id }}" {{ $selectedPatientId == $patient->id ? 'selected' : '' }}>
                                    {{ $patient->full_name }} ({{ $patient->patient_id }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="title" class="block text-sm font-bold text-slate-700 mb-1">Plan Title *</label>
                        <input type="text" name="title" id="title" required placeholder="e.g. Tooth Extraction & Implant" class="block w-full rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    </div>

                    <div>
                        <label for="priority" class="block text-sm font-bold text-slate-700 mb-1">Priority *</label>
                        <select name="priority" id="priority" required class="block w-full rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            <option value="low">Low</option>
                            <option value="medium" selected>Medium</option>
                            <option value="high">High</option>
                        </select>
                    </div>
                </div>

                <!-- Timing & Cost -->
                <div class="space-y-6">
                    <div>
                        <label for="estimated_cost" class="block text-sm font-bold text-slate-700 mb-1">Estimated Cost ({{ tenant()->clinic->currency ?? '$' }}) *</label>
                        <input type="number" name="estimated_cost" id="estimated_cost" step="0.01" required value="{{ old('estimated_cost', 0) }}" class="block w-full rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm font-bold">
                    </div>

                    <div>
                        <label for="estimated_duration" class="block text-sm font-bold text-slate-700 mb-1">Estimated Duration</label>
                        <input type="text" name="estimated_duration" id="estimated_duration" placeholder="e.g. 3 Months, 2 Sessions" class="block w-full rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    </div>
                </div>
            </div>

            <div class="mt-8">
                <label for="description" class="block text-sm font-bold text-slate-700 mb-1">Comprehensive Description *</label>
                <textarea name="description" id="description" rows="5" required placeholder="Outline the steps, materials, and clinical objectives..." class="block w-full rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm"></textarea>
            </div>

            <div class="mt-12 pt-8 border-t border-slate-100 flex justify-end space-x-3">
                <button type="reset" class="px-6 py-2 border border-slate-200 text-sm font-medium rounded-lg text-slate-600 hover:bg-slate-50 transition-colors">
                    Reset
                </button>
                <button type="submit" class="px-8 py-2 bg-indigo-600 shadow-lg shadow-indigo-100 text-white text-sm font-bold rounded-lg hover:bg-indigo-700 transition-all">
                    Create Plan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
