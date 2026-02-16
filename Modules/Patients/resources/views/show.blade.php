@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
        <div class="flex items-center">
            <div class="h-20 w-20 rounded-2xl bg-indigo-600 flex items-center justify-center text-white text-3xl font-bold shadow-lg shadow-indigo-200">
                {{ substr($patient->first_name, 0, 1) }}{{ substr($patient->last_name, 0, 1) }}
            </div>
            <div class="ml-6">
                <div class="flex items-center">
                    <h2 class="text-2xl font-bold text-slate-900">{{ $patient->full_name }}</h2>
                    @if($patient->is_active)
                        <span class="ml-3 px-2 py-0.5 bg-green-100 text-green-700 text-[10px] font-bold rounded-full uppercase tracking-wider">Active</span>
                    @else
                        <span class="ml-3 px-2 py-0.5 bg-slate-100 text-slate-500 text-[10px] font-bold rounded-full uppercase tracking-wider">Inactive</span>
                    @endif
                </div>
                <div class="flex items-center mt-1 text-sm text-slate-500 space-x-4">
                    <span class="flex items-center">
                        <svg class="h-4 w-4 mr-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-5m-4 0V5a2 2 0 012-2h2a2 2 0 012 2v1m-4 0h4"></path>
                        </svg>
                        {{ $patient->patient_id }}
                    </span>
                    <span class="flex items-center capitalize">
                        <svg class="h-4 w-4 mr-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                        {{ $patient->gender ?? 'Unspecified' }}
                    </span>
                    <span class="flex items-center">
                        <svg class="h-4 w-4 mr-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        @if($patient->date_of_birth)
                            {{ $patient->date_of_birth->format('M d, Y') }} ({{ $patient->date_of_birth->age }} yrs)
                        @else
                            Age Unknown
                        @endif
                    </span>
                </div>
            </div>
        </div>
        <div class="mt-4 md:mt-0 flex space-x-3">
            <a href="{{ route('clinic.patients.edit', $patient) }}" class="inline-flex items-center px-4 py-2 bg-white border border-slate-200 text-slate-700 text-sm font-semibold rounded-lg hover:bg-slate-50 transition-colors shadow-sm">
                Edit Records
            </a>
            <button class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-lg hover:bg-indigo-700 transition-colors shadow-lg shadow-indigo-100">
                Schedule Appointment
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column: Patient Profile Details -->
        <div class="space-y-8">
            <!-- Contact Info -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="text-xs font-bold text-slate-900 uppercase tracking-widest">Contact Information</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-tight">Email Address</span>
                        <span class="text-sm text-slate-700 font-medium">{{ $patient->email ?? 'Not provided' }}</span>
                    </div>
                    <div>
                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-tight">Phone Number</span>
                        <span class="text-sm text-slate-700 font-medium">{{ $patient->phone ?? 'Not provided' }}</span>
                    </div>
                    <div>
                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-tight">Residential Address</span>
                        <span class="text-sm text-slate-700 font-medium">{{ $patient->address ?? 'Not provided' }}</span>
                    </div>
                </div>
            </div>

            <!-- Medical Summary (Secure) -->
            <div class="bg-slate-900 rounded-2xl shadow-xl p-6 text-white relative overflow-hidden">
                <div class="relative z-10">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4 flex items-center">
                        Medical Summary
                        <svg class="h-3 w-3 ml-2 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </h3>
                    
                    <div class="space-y-6">
                        <div>
                            <span class="block text-[10px] font-bold text-slate-500 uppercase tracking-tight mb-1">Known Allergies</span>
                            <p class="text-sm {{ $patient->allergies ? 'text-red-400 font-bold' : 'text-slate-400' }}">
                                {{ $patient->allergies ?? 'No known allergies reported.' }}
                            </p>
                        </div>
                        <div>
                            <span class="block text-[10px] font-bold text-slate-500 uppercase tracking-tight mb-1">Clinical History</span>
                            <div class="text-xs text-slate-400 line-clamp-4 leading-relaxed">
                                {{ $patient->medical_history ?? 'No clinical history available.' }}
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Decorative Icon -->
                <svg class="absolute -right-6 -bottom-6 h-32 w-32 text-slate-800 opacity-50" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M19 3H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V5a2 2 0 00-2-2zm-2 10h-4v4h-2v-4H7v-2h4V7h2v4h4v2z"/>
                </svg>
            </div>
        </div>

        <!-- Right Column: Timeline & Operations -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Tabs -->
            <div class="flex space-x-6 border-b border-slate-200 px-2">
                <button class="pb-4 text-sm font-bold text-indigo-600 border-b-2 border-indigo-600">Appointments</button>
                <button class="pb-4 text-sm font-medium text-slate-500 hover:text-slate-700">Treatment Plans</button>
                <button class="pb-4 text-sm font-medium text-slate-500 hover:text-slate-700">Invoices</button>
                <button class="pb-4 text-sm font-medium text-slate-500 hover:text-slate-700">Documents</button>
            </div>

            <!-- Content Area: Appointments (Placeholder) -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-12 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-50 text-slate-400 mb-4">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h4 class="text-sm font-bold text-slate-900">No appointments scheduled</h4>
                    <p class="text-xs text-slate-500 mt-1 max-w-xs mx-auto">This patient hasn't had any appointments yet or none are upcoming.</p>
                    <button class="mt-6 text-sm font-bold text-indigo-600 hover:text-indigo-800">Assign to Dentist</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
