@extends('layouts.app')

@section('title', 'Appointment Details')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Appointment Details</h1>
            <p class="text-gray-600">{{ $appointment->appointment_date->format('M d, Y') }} at {{ $appointment->appointment_time->format('g:i A') }}</p>
        </div>
        <div class="space-x-4">
            <a href="{{ route('clinic.appointments.edit', ['appointment' => $appointment, 'iframe' => 1]) }}"
               data-modal-url="{{ route('clinic.appointments.edit', ['appointment' => $appointment, 'iframe' => 1]) }}"
               data-modal-title="Edit Appointment"
               class="btn-primary">Edit Appointment</a>
            <a href="{{ route('clinic.appointments.index') }}" class="btn-secondary">Back to List</a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <!-- Appointment Info -->
            <div class="card">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Appointment Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Patient</label>
                        <p class="text-gray-900">
                            <a href="{{ route('clinic.patients.show', $appointment->patient) }}" class="text-blue-600 hover:text-blue-800">
                                {{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}
                            </a>
                        </p>
                    </div>
                    <div>
                        <label class="form-label">Treatment Type</label>
                        <p class="text-gray-900">{{ ucfirst(str_replace('_', ' ', $appointment->type)) }}</p>
                    </div>
                    <div>
                        <label class="form-label">Status</label>
                        <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                            {{ ucfirst($appointment->status) }}
                        </span>
                    </div>
                    <div>
                        <label class="form-label">Duration</label>
                        <p class="text-gray-900">{{ $appointment->duration }} minutes</p>
                    </div>
                    @if($appointment->treatment_cost)
                    <div>
                        <label class="form-label">Treatment Cost</label>
                        <p class="text-gray-900">NPR {{ number_format($appointment->treatment_cost, 0) }}</p>
                    </div>
                    <div>
                        <label class="form-label">Payment Status</label>
                        <span class="px-2 py-1 text-xs rounded-full {{ $appointment->payment_status === 'paid' ? 'bg-blue-100 text-blue-800' : 'bg-cyan-100 text-cyan-800' }}">
                            {{ ucfirst($appointment->payment_status) }}
                        </span>
                    </div>
                    @endif
                </div>
            </div>

            @if($appointment->notes)
            <div class="card">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Notes</h2>
                <p class="text-gray-700">{{ $appointment->notes }}</p>
            </div>
            @endif
        </div>

        <!-- Quick Actions -->
        <div class="space-y-6">
            <div class="card">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h2>
                <div class="space-y-2">
                    @if($appointment->status === 'scheduled')
                        <form action="{{ route('clinic.appointments.update', $appointment) }}" method="POST" class="inline">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="completed">
                            <input type="hidden" name="patient_id" value="{{ $appointment->patient_id }}">
                            <input type="hidden" name="appointment_date" value="{{ $appointment->appointment_date->format('Y-m-d') }}">
                            <input type="hidden" name="appointment_time" value="{{ $appointment->appointment_time->format('H:i') }}">
                            <input type="hidden" name="type" value="{{ $appointment->type }}">
                            <button type="submit" class="block w-full btn-primary text-center">Mark as Completed</button>
                        </form>
                    @endif
                    <a href="{{ route('clinic.appointments.edit', ['appointment' => $appointment, 'iframe' => 1]) }}"
                       data-modal-url="{{ route('clinic.appointments.edit', ['appointment' => $appointment, 'iframe' => 1]) }}"
                       data-modal-title="Edit Appointment"
                       class="block w-full btn-secondary text-center">Edit Appointment</a>
                    <a href="{{ route('clinic.patients.show', $appointment->patient) }}" class="block w-full btn-secondary text-center">View Patient</a>
                </div>
            </div>

            <div class="card">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Patient Contact</h2>
                <div class="space-y-2">
                    @if($appointment->patient->phone)
                        <p class="text-sm"><strong>Phone:</strong> {{ $appointment->patient->phone }}</p>
                    @endif
                    @if($appointment->patient->email)
                        <p class="text-sm"><strong>Email:</strong> {{ $appointment->patient->email }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection