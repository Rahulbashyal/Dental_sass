@extends('layouts.app')

@section('title', 'Create Recurring Appointment')
@section('page-title', 'Create Recurring Appointment')

@section('content')
<div class="space-y-6">
    <div class="card">
        <h2 class="text-xl font-bold text-gray-900 mb-6">Create New Recurring Appointment</h2>
        
        <form action="{{ route('recurring-appointments.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="form-label">Patient</label>
                    <select name="patient_id" class="form-input" required>
                        <option value="">Select Patient</option>
                    </select>
                </div>
                
                <div>
                    <label class="form-label">Dentist</label>
                    <select name="dentist_id" class="form-input" required>
                        <option value="">Select Dentist</option>
                    </select>
                </div>
                
                <div>
                    <label class="form-label">Frequency</label>
                    <select name="frequency" class="form-input" required>
                        <option value="weekly">Weekly</option>
                        <option value="monthly">Monthly</option>
                        <option value="quarterly">Quarterly</option>
                    </select>
                </div>
                
                <div>
                    <x-nepali-date-input 
                        name="start_date"
                        label="Start Date (सुरु मिति)"
                        :value="old('start_date')"
                        required
                        :minDate="date('Y-m-d')"
                        help="First appointment date"
                    />
                </div>
            </div>
            
            <div class="flex justify-end space-x-4">
                <a href="{{ route('recurring-appointments.index') }}" class="btn-secondary">Cancel</a>
                <button type="submit" class="btn-primary">Create Recurring Appointment</button>
            </div>
        </form>
    </div>
</div>
@endsection