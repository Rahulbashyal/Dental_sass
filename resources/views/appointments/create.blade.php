@extends('layouts.app')

@section('page-title', 'Schedule Appointment')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Schedule Appointment</h1>
            <p class="text-gray-600">Book a new appointment</p>
        </div>
        <div class="bg-blue-50 p-3 rounded-lg">
            <div class="text-sm text-gray-600">आज</div>
            @php
                $nepaliDate = \App\Helpers\NepaliDateHelper::getCurrentNepaliDate();
            @endphp
            <div class="text-lg font-bold text-blue-800">{{ $nepaliDate['formatted'] ?? '२६ कार्तिक २०८२' }}</div>
            <div class="text-sm text-gray-600">{{ $nepaliDate['day_of_week'] ? \App\Services\NepaliCalendarService::getDayName($nepaliDate['day_of_week']) : 'बुधबार' }}</div>
        </div>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6 max-w-2xl">
        <form action="{{ route('clinic.appointments.store') }}" method="POST">
            @csrf
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Patient</label>
                    <select name="patient_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Select Patient</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}" {{ request('patient_id') == $patient->id ? 'selected' : '' }}>
                                {{ $patient->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('patient_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-nepali-date-input 
                            name="appointment_date"
                            label="Appointment Date (नियुक्ति मिति)"
                            :value="old('appointment_date')"
                            required
                            :minDate="date('Y-m-d')"
                            help="Select appointment date in Nepali calendar"
                        />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Appointment Time</label>
                        <input type="time" name="appointment_time" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        @error('appointment_time')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Treatment Type</label>
                    <select name="type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Select Treatment</option>
                        <option value="consultation">Consultation</option>
                        <option value="cleaning">Cleaning</option>
                        <option value="filling">Filling</option>
                        <option value="extraction">Extraction</option>
                        <option value="root_canal">Root Canal</option>
                        <option value="crown">Crown</option>
                        <option value="other">Other</option>
                    </select>
                    @error('type')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Treatment Cost (NPR)</label>
                    <input type="number" name="treatment_cost" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" step="1" min="0" placeholder="Enter cost in Nepali Rupees">
                    @error('treatment_cost')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                    <textarea name="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Any special instructions or notes..."></textarea>
                    @error('notes')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end space-x-4">
                    <a href="{{ route('clinic.appointments.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">Cancel</a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Schedule Appointment</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection