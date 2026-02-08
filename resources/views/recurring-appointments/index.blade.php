@extends('layouts.app')

@section('title', 'Recurring Appointments')
@section('page-title', 'Recurring Appointments')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Recurring Appointments</h1>
        <a href="{{ route('recurring-appointments.create') }}" class="btn-primary">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Create Recurring Appointment
        </a>
    </div>

    <div class="card">
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900">No recurring appointments</h3>
            <p class="mt-2 text-gray-500">Get started by creating your first recurring appointment.</p>
        </div>
    </div>
</div>
@endsection