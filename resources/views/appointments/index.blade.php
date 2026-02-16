@extends('layouts.app')

@section('page-title', 'Appointments')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-gray-900 to-gray-700 bg-clip-text text-transparent">Appointments</h1>
            <p class="text-gray-600 mt-1">Manage patient appointments and schedules</p>
        </div>
        <a href="{{ route('clinic.appointments.create') }}" class="btn-primary flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            <span>Schedule Appointment</span>
        </a>
    </div>

    <div class="grid gap-4">
        @forelse($appointments as $appointment)
            <div class="card group hover:shadow-lg transition-all duration-300 {{ $appointment->status === 'completed' ? 'border-l-4 border-sky-500' : ($appointment->status === 'cancelled' ? 'border-l-4 border-slate-400' : 'border-l-4 border-blue-500') }}">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <div class="h-14 w-14 rounded-2xl bg-gradient-to-br {{ $statusColors[$appointment->status] ?? $statusColors['pending'] }} flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $statusIcons[$appointment->status] ?? $statusIcons['pending'] }}"></path>
                                </svg>
                            </div>
                            <div class="absolute -bottom-1 -right-1 px-2 py-1 text-xs font-bold rounded-full {{ $appointment->status === 'completed' ? 'bg-sky-500 text-white' : ($appointment->status === 'cancelled' ? 'bg-slate-500 text-white' : 'bg-blue-500 text-white') }}">
                                {{ strtoupper(substr($appointment->status, 0, 1)) }}
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center space-x-3 mb-2">
                                <h3 class="text-lg font-semibold text-gray-900 group-hover:text-blue-700 transition-colors">{{ $appointment->patient->name }}</h3>
                                <span class="inline-flex items-center px-3 py-1 text-xs font-medium rounded-full
                                    {{ $appointment->status === 'completed' ? 'bg-sky-100 text-sky-800' : 
                                       ($appointment->status === 'cancelled' ? 'bg-slate-100 text-slate-800' : 
                                       ($appointment->status === 'confirmed' ? 'bg-indigo-100 text-indigo-800' : 'bg-blue-100 text-blue-800')) }}">
                                    {{ ucfirst($appointment->status) }}
                                </span>
                            </div>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm text-gray-600">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <span class="font-medium">{{ $appointment->appointment_date->format('M d, Y') }}</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <span class="font-medium">{{ $appointment->appointment_time }}</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                                    <span class="font-medium">{{ $appointment->type }}</span>
                                </div>
                                @if($appointment->treatment_cost)
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path></svg>
                                        <span class="font-medium text-blue-600">NPR {{ number_format($appointment->treatment_cost) }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        @if($appointment->patient->email && in_array($appointment->status, ['confirmed', 'pending']))
                        <form action="{{ route('emails.appointment.reminder', $appointment) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-medium text-blue-700 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                Send Reminder
                            </button>
                        </form>
                        @endif
                        <a href="{{ route('clinic.appointments.show', $appointment) }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-blue-700 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            View
                        </a>
                        <a href="{{ route('clinic.appointments.edit', $appointment) }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-indigo-700 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            Edit
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="card text-center py-12">
                <div class="w-20 h-20 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">No appointments scheduled</h3>
                <p class="text-gray-600 mb-4">Start by scheduling your first appointment</p>
                <a href="{{ route('clinic.appointments.create') }}" class="btn-primary inline-flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <span>Schedule Your First Appointment</span>
                </a>
            </div>
        @endforelse
    </div>

    @if($appointments->hasPages())
        <div class="flex justify-center">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                {{ $appointments->links() }}
            </div>
        </div>
    @endif
</div>
@endsection