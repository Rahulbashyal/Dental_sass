@extends('layouts.app')

@section('title', 'Patient Details')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $patient->first_name }} {{ $patient->last_name }}</h1>
            <p class="text-gray-600">Patient Details</p>
        </div>
        <div class="space-x-4">
            <a href="{{ route('clinic.appointments.create') }}?patient_id={{ $patient->id }}" class="btn-primary">Schedule Appointment</a>
            <a href="{{ route('patients.edit', $patient) }}" class="btn-secondary">Edit Patient</a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Patient Info -->
        <div class="lg:col-span-2 space-y-6">
            <div class="card">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Personal Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Email</label>
                        <p class="text-gray-900">{{ $patient->email ?: 'Not provided' }}</p>
                    </div>
                    <div>
                        <label class="form-label">Phone</label>
                        <p class="text-gray-900">{{ $patient->phone ?: 'Not provided' }}</p>
                    </div>
                    <div>
                        <label class="form-label">Date of Birth</label>
                        <p class="text-gray-900">{{ $patient->date_of_birth ? $patient->date_of_birth->format('M d, Y') : 'Not provided' }}</p>
                    </div>
                    <div>
                        <label class="form-label">Gender</label>
                        <p class="text-gray-900">{{ $patient->gender ? ucfirst($patient->gender) : 'Not provided' }}</p>
                    </div>
                </div>
                @if($patient->address)
                    <div class="mt-4">
                        <label class="form-label">Address</label>
                        <p class="text-gray-900">{{ $patient->address }}</p>
                    </div>
                @endif
            </div>

            @if($patient->medical_history)
            <div class="card">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Medical History</h2>
                <p class="text-gray-700">{{ $patient->medical_history }}</p>
            </div>
            @endif

            <!-- Appointments History -->
            <div class="card">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Appointment History</h2>
                @if($patient->appointments->count() > 0)
                    <div class="space-y-3">
                        @foreach($patient->appointments->take(5) as $appointment)
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                                <div>
                                    <p class="font-medium">{{ $appointment->appointment_date->format('M d, Y') }} at {{ $appointment->appointment_time->format('g:i A') }}</p>
                                    <p class="text-sm text-gray-600">{{ ucfirst(str_replace('_', ' ', $appointment->type)) }}</p>
                                </div>
                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                    {{ ucfirst($appointment->status) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500">No appointments yet.</p>
                @endif
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="space-y-6">
            <div class="card">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Quick Stats</h2>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total Appointments</span>
                        <span class="font-semibold">{{ $patient->appointments->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Completed</span>
                        <span class="font-semibold">{{ $patient->appointments->where('status', 'completed')->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Last Visit</span>
                        <span class="font-semibold">{{ $patient->appointments->where('status', 'completed')->sortByDesc('appointment_date')->first()?->appointment_date?->format('M d, Y') ?: 'Never' }}</span>
                    </div>
                </div>
            </div>

            <div class="card">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h2>
                <div class="space-y-2">
                    <a href="{{ route('clinic.appointments.create') }}?patient_id={{ $patient->id }}" class="block w-full btn-primary text-center">Schedule Appointment</a>
                    <a href="{{ route('patients.edit', $patient) }}" class="block w-full btn-secondary text-center">Edit Information</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection