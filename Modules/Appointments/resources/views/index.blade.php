@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">Daily Schedule</h2>
            <p class="text-sm text-slate-500">Manage patient flow and appointments for {{ \Carbon\Carbon::parse($date)->format('F d, Y') }}</p>
        </div>
        <div class="mt-4 md:mt-0 flex items-center space-x-3">
            <a href="{{ route('clinic.appointments.calendar') }}" class="p-2 bg-white border border-slate-200 rounded-lg text-slate-600 hover:bg-slate-50 transition-colors">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </a>
            <a href="{{ route('clinic.appointments.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                New Appointment
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm mb-6 flex flex-col md:flex-row md:items-center space-y-4 md:space-y-0 md:space-x-6">
        <div class="flex-1">
            <form action="{{ route('clinic.appointments.index') }}" method="GET" id="filter-form" class="flex flex-col md:flex-row md:items-center space-y-4 md:space-y-0 md:space-x-4">
                <div class="relative">
                    <input type="date" name="date" value="{{ $date }}" onchange="this.form.submit()" class="pl-10 pr-4 py-2 rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
                <div class="relative">
                    <select name="branch_id" onchange="this.form.submit()" class="pl-10 pr-8 py-2 rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm appearance-none">
                        <option value="">All Branches</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ $branchId == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                </div>
            </form>
        </div>
        <div class="flex items-center space-x-1">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-widest mr-2">Quick Stats:</span>
            <div class="flex items-center bg-indigo-50 px-3 py-1 rounded-full border border-indigo-100">
                <div class="w-1.5 h-1.5 rounded-full bg-indigo-500 mr-2"></div>
                <span class="text-xs font-bold text-indigo-700">{{ $appointments->count() }} Total</span>
            </div>
        </div>
    </div>

    <!-- Appointments List -->
    <div class="space-y-4">
        @forelse($appointments as $appointment)
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow overflow-hidden group">
                <div class="flex items-stretch">
                    <!-- Time Indicator -->
                    <div class="w-24 bg-slate-50 border-r border-slate-100 flex flex-col items-center justify-center p-4">
                        <span class="text-lg font-bold text-slate-900">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') }}</span>
                        <span class="text-[10px] font-bold text-slate-400 uppercase">{{ $appointment->duration }} Min</span>
                    </div>

                    <!-- Main Info -->
                    <div class="flex-1 p-6 flex flex-col md:flex-row md:items-center justify-between">
                        <div class="flex items-center">
                            <div class="h-12 w-12 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600 font-bold border border-indigo-100">
                                {{ substr($appointment->patient->first_name, 0, 1) }}{{ substr($appointment->patient->last_name, 0, 1) }}
                            </div>
                            <div class="ml-4">
                                <a href="{{ route('clinic.patients.show', $appointment->patient_id) }}" class="text-base font-bold text-slate-900 hover:text-indigo-600 transition-colors">{{ $appointment->patient->full_name }}</a>
                                <div class="flex items-center mt-1 space-x-3 text-xs text-slate-500">
                                    <span class="flex items-center">
                                        <svg class="h-3 w-3 mr-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        Dr. {{ $appointment->dentist->name }}
                                    </span>
                                    <span class="flex items-center">
                                        <svg class="h-3 w-3 mr-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                        {{ $appointment->branch->name }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 md:mt-0 flex items-center space-x-4">
                            <!-- Type Badge -->
                            <span class="px-2 py-1 bg-slate-100 text-slate-600 text-[10px] font-bold rounded border border-slate-200 uppercase tracking-tight">{{ $appointment->type }}</span>
                            
                            <!-- Status Dropdown -->
                            <div class="relative inline-block text-left">
                                <form action="{{ route('clinic.appointments.status.update', $appointment) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" onchange="this.form.submit()" class="block w-full rounded-lg border-slate-200 text-xs font-bold py-1.5 pl-3 pr-8 focus:border-indigo-500 focus:ring-indigo-500
                                        @if($appointment->status == 'scheduled') bg-blue-50 text-blue-700 border-blue-200 @endif
                                        @if($appointment->status == 'confirmed') bg-indigo-50 text-indigo-700 border-indigo-200 @endif
                                        @if($appointment->status == 'arrived') bg-amber-50 text-amber-700 border-amber-200 @endif
                                        @if($appointment->status == 'in_progress') bg-purple-50 text-purple-700 border-purple-200 @endif
                                        @if($appointment->status == 'completed') bg-green-50 text-green-700 border-green-200 @endif
                                        @if($appointment->status == 'cancelled') bg-red-50 text-red-700 border-red-200 @endif
                                        @if($appointment->status == 'no_show') bg-slate-100 text-slate-700 border-slate-300 @endif
                                    ">
                                        <option value="scheduled" {{ $appointment->status == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                        <option value="confirmed" {{ $appointment->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                        <option value="arrived" {{ $appointment->status == 'arrived' ? 'selected' : '' }}>Arrived</option>
                                        <option value="in_progress" {{ $appointment->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="completed" {{ $appointment->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="cancelled" {{ $appointment->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        <option value="no_show" {{ $appointment->status == 'no_show' ? 'selected' : '' }}>No Show</option>
                                    </select>
                                </form>
                            </div>

                            <button class="p-2 text-slate-400 hover:text-indigo-600 transition-colors">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl border border-dashed border-slate-300 p-16 text-center shadow-sm">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-50 text-slate-300 mb-4">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="text-base font-bold text-slate-900">No appointments for this day</h3>
                <p class="text-sm text-slate-500 mt-1">Enjoy the break or schedule a new patient clinic.</p>
                <a href="{{ route('clinic.appointments.create') }}" class="mt-6 inline-flex items-center px-6 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-lg hover:bg-indigo-700 transition-all">
                    Schedule Now
                </a>
            </div>
        @endforelse
    </div>
</div>
@endsection
