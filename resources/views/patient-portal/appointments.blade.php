@extends('patient-portal.layout')

@section('title', 'My Appointments')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">My Appointments</h1>
        <p class="text-gray-600">View your appointment history and upcoming visits</p>
    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <ul class="divide-y divide-gray-200">
            @forelse($appointments as $appointment)
            <li class="px-4 py-4 sm:px-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center">
                                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <div class="text-lg font-medium text-gray-900">{{ ucfirst($appointment->type) }}</div>
                            <div class="text-sm text-gray-500">{{ $appointment->clinic->name }}</div>
                            <div class="text-sm text-gray-500">
                                {{ $appointment->appointment_date->format('F j, Y') }} at {{ $appointment->appointment_time }}
                            </div>
                            @if($appointment->notes)
                                <div class="text-sm text-gray-600 mt-1">{{ $appointment->notes }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        @if($appointment->treatment_cost)
                            <div class="text-right">
                                <div class="text-sm font-medium text-gray-900">NPR {{ number_format($appointment->treatment_cost) }}</div>
                                <div class="text-xs text-gray-500">Cost</div>
                            </div>
                        @endif
                        <div class="flex-shrink-0">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($appointment->status === 'completed') bg-green-100 text-green-800
                                @elseif($appointment->status === 'scheduled') bg-blue-100 text-blue-800
                                @elseif($appointment->status === 'confirmed') bg-indigo-100 text-indigo-800
                                @elseif($appointment->status === 'in_progress') bg-yellow-100 text-yellow-800
                                @elseif($appointment->status === 'cancelled') bg-red-100 text-red-800
                                @elseif($appointment->status === 'no_show') bg-gray-100 text-gray-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst(str_replace('_', ' ', $appointment->status)) }}
                            </span>
                        </div>
                    </div>
                </div>
            </li>
            @empty
            <li class="px-4 py-8 text-center">
                <div class="text-gray-400">
                    <svg class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No appointments</h3>
                    <p class="mt-1 text-sm text-gray-500">You haven't scheduled any appointments yet.</p>
                </div>
            </li>
            @endforelse
        </ul>
    </div>

    @if($appointments->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $appointments->links() }}
        </div>
    @endif
</div>
@endsection