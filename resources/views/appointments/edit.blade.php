@extends('layouts.app')

@section('title', 'Edit Appointment')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Edit Appointment</h1>
        <p class="text-gray-600">Update appointment details</p>
    </div>

    <div class="card max-w-2xl">
        <form action="{{ route('clinic.appointments.update', $appointment) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-6">
                <div>
                    <label class="form-label">Patient</label>
                    <select name="patient_id" class="form-input" required>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}" {{ $appointment->patient_id == $patient->id ? 'selected' : '' }}>
                                {{ $patient->first_name }} {{ $patient->last_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-nepali-date-input 
                            name="appointment_date"
                            label="Appointment Date (नियुक्ति मिति)"
                            :value="old('appointment_date', $appointment->appointment_date->format('Y-m-d'))"
                            required
                            :minDate="date('Y-m-d')"
                        />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Appointment Time</label>
                        <input type="time" name="appointment_time" value="{{ old('appointment_time', $appointment->appointment_time) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        @error('appointment_time')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="form-label">Treatment Type</label>
                        <select name="type" class="form-input" required>
                            <option value="consultation" {{ $appointment->type === 'consultation' ? 'selected' : '' }}>Consultation</option>
                            <option value="cleaning" {{ $appointment->type === 'cleaning' ? 'selected' : '' }}>Cleaning</option>
                            <option value="filling" {{ $appointment->type === 'filling' ? 'selected' : '' }}>Filling</option>
                            <option value="extraction" {{ $appointment->type === 'extraction' ? 'selected' : '' }}>Extraction</option>
                            <option value="root_canal" {{ $appointment->type === 'root_canal' ? 'selected' : '' }}>Root Canal</option>
                            <option value="crown" {{ $appointment->type === 'crown' ? 'selected' : '' }}>Crown</option>
                            <option value="other" {{ $appointment->type === 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Status</label>
                        <select name="status" class="form-input" required>
                            <option value="scheduled" {{ $appointment->status === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                            <option value="confirmed" {{ $appointment->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="in_progress" {{ $appointment->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="completed" {{ $appointment->status === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ $appointment->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            <option value="no_show" {{ $appointment->status === 'no_show' ? 'selected' : '' }}>No Show</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="form-label">Treatment Cost (NPR)</label>
                    <input type="number" name="treatment_cost" value="{{ $appointment->treatment_cost }}" class="form-input" step="1" min="0">
                </div>

                <div>
                    <label class="form-label">Notes</label>
                    <textarea name="notes" rows="3" class="form-input" placeholder="Any special instructions or notes...">{{ $appointment->notes }}</textarea>
                </div>

                <div class="flex justify-end space-x-4">
                    <a href="{{ route('clinic.appointments.show', $appointment) }}" class="btn-secondary">Cancel</a>
                    <button type="submit" class="btn-primary">Update Appointment</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection