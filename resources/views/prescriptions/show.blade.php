@extends('layouts.app')

@section('page-title', 'Prescription Details')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="flex justify-between items-center">
        <div class="flex items-center">
            <a href="{{ route('prescriptions.index') }}" class="text-blue-600 hover:text-blue-800 mr-4">← Back</a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Prescription Details</h1>
                <p class="text-gray-600 font-mono">{{ $prescription->prescription_number }}</p>
            </div>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('prescriptions.pdf', $prescription) }}" 
               class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                Download PDF
            </a>
            <a href="{{ route('prescriptions.print', $prescription) }}" 
               target="_blank"
               class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700">
                Print
            </a>
            @if($prescription->status === 'active')
                <a href="{{ route('prescriptions.edit', $prescription) }}" 
                   class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Edit
                </a>
            @endif
        </div>
    </div>

    <!-- Status Badge -->
    <div class="flex items-center space-x-4">
        @if($prescription->status === 'active')
            <span class="px-4 py-2 rounded-full bg-green-100 text-green-800 font-semibold">Active</span>
        @elseif($prescription->status === 'completed')
            <span class="px-4 py-2 rounded-full bg-blue-100 text-blue-800 font-semibold">Completed</span>
        @else
            <span class="px-4 py-2 rounded-full bg-red-100 text-red-800 font-semibold">Cancelled</span>
        @endif
        <span class="text-gray-600">Date: {{ $prescription->prescribed_date->format('M d, Y') }}</span>
    </div>

    <!-- Patient & Dentist Info -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-lg font-semibold mb-4 text-gray-900">Patient Information</h2>
            <div class="space-y-2">
                <p><span class="font-medium">Name:</span> {{ $prescription->patient->first_name }} {{ $prescription->patient->last_name }}</p>
                <p><span class="font-medium">Phone:</span> {{ $prescription->patient->phone }}</p>
                <p><span class="font-medium">Email:</span> {{ $prescription->patient->email ?? 'N/A' }}</p>
                @if($prescription->patient->date_of_birth)
                    <p><span class="font-medium">Age:</span> {{ $prescription->patient->date_of_birth->age }} years</p>
                @endif
            </div>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-lg font-semibold mb-4 text-gray-900">Prescribed By</h2>
            <div class="space-y-2">
                <p><span class="font-medium">Dentist:</span> Dr. {{ $prescription->dentist->name }}</p>
                <p><span class="font-medium">Clinic:</span> {{ $prescription->clinic->name }}</p>
                @if($prescription->appointment)
                    <p><span class="font-medium">Related Appointment:</span> {{ $prescription->appointment->appointment_date->format('M d, Y') }}</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Diagnosis & Treatment -->
    <div class="bg-white shadow-md rounded-lg p-6">
        <h2 class="text-lg font-semibold mb-4 text-gray-900">Diagnosis & Treatment</h2>
        
        @if($prescription->chief_complaint)
            <div class="mb-4">
                <p class="font-medium text-gray-700">Chief Complaint:</p>
                <p class="text-gray-600">{{ $prescription->chief_complaint }}</p>
            </div>
        @endif

        <div class="mb-4">
            <p class="font-medium text-gray-700">Diagnosis:</p>
            <p class="text-gray-600">{{ $prescription->diagnosis }}</p>
        </div>

        @if($prescription->treatment_provided)
            <div class="mb-4">
                <p class="font-medium text-gray-700">Treatment Provided:</p>
                <p class="text-gray-600">{{ $prescription->treatment_provided }}</p>
            </div>
        @endif

        @if($prescription->dental_notes)
            <div class="mb-4">
                <p class="font-medium text-gray-700">Clinical Notes:</p>
                <p class="text-gray-600">{{ $prescription->dental_notes }}</p>
            </div>
        @endif
    </div>

    <!-- Health Information -->
    @if($prescription->known_allergies || $prescription->current_medications || $prescription->medical_conditions)
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
            <h2 class="text-lg font-semibold mb-4 text-yellow-800">Patient Health Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @if($prescription->known_allergies)
                    <div>
                        <p class="font-medium text-yellow-800">Known Allergies:</p>
                        <p class="text-yellow-700">{{ $prescription->known_allergies }}</p>
                    </div>
                @endif
                @if($prescription->current_medications)
                    <div>
                        <p class="font-medium text-yellow-800">Current Medications:</p>
                        <p class="text-yellow-700">{{ $prescription->current_medications }}</p>
                    </div>
                @endif
                @if($prescription->medical_conditions)
                    <div>
                        <p class="font-medium text-yellow-800">Medical Conditions:</p>
                        <p class="text-yellow-700">{{ $prescription->medical_conditions }}</p>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- Medications List -->
    <div class="bg-white shadow-md rounded-lg p-6">
        <h2 class="text-lg font-semibold mb-4 text-gray-900">Prescribed Medications</h2>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Medication</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dosage</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Frequency</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Duration</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Instructions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($prescription->items as $index => $item)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $item->medication_name }}</p>
                                    @if($item->generic_name)
                                        <p class="text-xs text-gray-500">{{ $item->generic_name }}</p>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $item->dosage }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $item->frequency }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $item->duration_days }} days</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $item->quantity }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                <div>
                                    <p>{{ $item->route }}</p>
                                    @if($item->instructions)
                                        <p class="text-xs text-gray-500 mt-1">{{ $item->instructions }}</p>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- General Instructions -->
    @if($prescription->general_instructions)
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
            <h2 class="text-lg font-semibold mb-2 text-blue-800">General Instructions</h2>
            <p class="text-blue-700">{{ $prescription->general_instructions }}</p>
        </div>
    @endif

    <!-- Action Buttons -->
    <div class="flex justify-between items-center pt-6 border-t">
        <form action="{{ route('prescriptions.destroy', $prescription) }}" method="POST" 
              onsubmit="return confirm('Are you sure you want to delete this prescription?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                Delete Prescription
            </button>
        </form>

        <div class="flex space-x-2">
            <a href="{{ route('prescriptions.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                Back to List
            </a>
        </div>
    </div>
</div>
@endsection
