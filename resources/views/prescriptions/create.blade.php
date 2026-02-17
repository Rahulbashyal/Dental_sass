@extends('layouts.app')

@section('page-title', 'Create Prescription')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="flex items-center mb-6">
        <a href="{{ route('prescriptions.index') }}" class="text-blue-600 hover:text-blue-800 mr-4">← Back</a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">New Prescription</h1>
            <p class="text-gray-600">Create a new prescription for patient</p>
        </div>
    </div>

    <form action="{{ route('prescriptions.store') }}" method="POST" id="prescription-form">
        @csrf
        
        <!-- Patient Information -->
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <h2 class="text-lg font-semibold mb-4">Patient Information</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Patient *</label>
                    <select name="patient_id" id="patient_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Select Patient --</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}" 
                                    data-allergies="{{ $patient->allergies ?? '' }}"
                                    data-medical-history="{{ $patient->medical_history ?? '' }}"
                                    {{ $selectedPatient && $selectedPatient->id == $patient->id ? 'selected' : '' }}>
                                {{ $patient->first_name }} {{ $patient->last_name }} ({{ $patient->phone }})
                            </option>
                        @endforeach
                    </select>
                    @error('patient_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Related Appointment (Optional)</label>
                    <select name="appointment_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md">
                        <option value="">-- Select Appointment --</option>
                        @foreach($appointments as $appointment)
                            <option value="{{ $appointment->id }}">
                                {{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }} - 
                                {{ $appointment->appointment_date->format('M d, Y') }} - {{ $appointment->type }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div id="patient-info-display" class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded hidden">
                <p class="font-semibold text-yellow-800">Patient Health Information:</p>
                <div id="patient-allergies" class="text-sm text-yellow-700"></div>
                <div id="patient-medical-history" class="text-sm text-yellow-700 mt-1"></div>
            </div>
        </div>

        <!-- Dental Diagnosis & Treatment -->
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <h2 class="text-lg font-semibold mb-4">Diagnosis & Treatment</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Chief Complaint</label>
                    <input type="text" name="chief_complaint" value="{{ old('chief_complaint') }}"
                           placeholder="e.g., Toothache, Swelling"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md">
                </div>

                <div>
                    <x-nepali-date-input 
                        name="prescribed_date"
                        label="Prescription Date (प्रिस्क्रिप्शन मिति)"
                        :value="old('prescribed_date', date('Y-m-d'))"
                        required
                    />
                </div>
            </div>

            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Diagnosis *</label>
                <textarea name="diagnosis" rows="3" required
                          placeholder="e.g., Acute periapical abscess, Pulpitis"
                          class="w-full px-3 py-2 border border-gray-300 rounded-md">{{ old('diagnosis') }}</textarea>
                @error('diagnosis')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Treatment Provided</label>
                <textarea name="treatment_provided" rows="2"
                          placeholder="e.g., Root canal treatment, Tooth extraction"
                          class="w-full px-3 py-2 border border-gray-300 rounded-md">{{ old('treatment_provided') }}</textarea>
            </div>

            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Clinical Notes</label>
                <textarea name="dental_notes" rows="2"
                          placeholder="Any additional clinical observations"
                          class="w-full px-3 py-2 border border-gray-300 rounded-md">{{ old('dental_notes') }}</textarea>
            </div>
        </div>

        <!-- Patient Health Details (for safety) -->
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <h2 class="text-lg font-semibold mb-4">Patient Health Information (for prescription safety)</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Known Allergies</label>
                    <input type="text" name="known_allergies" value="{{ old('known_allergies') }}"
                           placeholder="e.g., Penicillin, Aspirin"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Current Medications</label>
                    <input type="text" name="current_medications" value="{{ old('current_medications') }}"
                           placeholder="Other medicines patient is taking"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Medical Conditions</label>
                    <input type="text" name="medical_conditions" value="{{ old('medical_conditions') }}"
                           placeholder="e.g., Diabetes, Hypertension"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md">
                </div>
            </div>
        </div>

        <!-- Medications -->
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold">Medications *</h2>
                <button type="button" id="add-medication" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                    + Add Medication
                </button>
            </div>

            <div id="medications-container">
                <!-- Medications will be added here -->
            </div>

            @error('medications')
                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>

        <!-- General Instructions -->
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <h2 class="text-lg font-semibold mb-4">General Instructions</h2>
            <textarea name="general_instructions" rows="3"
                      placeholder="e.g., Take all medications after meals, Avoid hot/cold foods"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md">{{ old('general_instructions') }}</textarea>
        </div>

        <!-- Submit -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('prescriptions.index') }}" class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                Create Prescription
            </button>
        </div>
    </form>
</div>

<!-- Medication Row Template -->
<template id="medication-row-template">
    <div class="medication-row border border-gray-300 rounded-lg p-4 mb-4 relative">
        <button type="button" class="remove-medication absolute top-2 right-2 text-red-600 hover:text-red-800">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-3">
                <label class="block text-sm font-medium text-gray-700 mb-2">Medication *</label>
                <select name="medications[INDEX][medication_id]" class="medication-select w-full px-3 py-2 border border-gray-300 rounded-md" required>
                    <option value="">-- Select Medication --</option>
                    @foreach($medications as $med)
                        <option value="{{ $med->id }}" 
                                data-dosages="{{ json_encode($med->common_dosages) }}"
                                data-category="{{ $med->category }}">
                            {{ $med->name }} ({{ $med->category }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Dosage *</label>
                <input type="text" name="medications[INDEX][dosage]" placeholder="e.g., 500mg" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Frequency *</label>
                <select name="medications[INDEX][frequency]" required class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="">Select Frequency</option>
                    <option value="1 time daily">1 time daily</option>
                    <option value="2 times daily">2 times daily</option>
                    <option value="3 times daily">3 times daily</option>
                    <option value="4 times daily">4 times daily</option>
                    <option value="As needed">As needed</option>
                    <option value="Every 4 hours">Every 4 hours</option>
                    <option value="Every 6 hours">Every 6 hours</option>
                    <option value="Every 8 hours">Every 8 hours</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Route *</label>
                <select name="medications[INDEX][route]" required class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="Oral" selected>Oral</option>
                    <option value="Topical">Topical</option>
                    <option value="Rinse/Gargle">Rinse/Gargle</option>
                    <option value="Injectable">Injectable</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Duration (Days) *</label>
                <input type="number" name="medications[INDEX][duration_days]" min="1" value="5" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Total Quantity *</label>
                <input type="number" name="medications[INDEX][quantity]" min="1" value="10" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Special Instructions</label>
                <input type="text" name="medications[INDEX][instructions]" placeholder="e.g., Take after meals"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>
        </div>
    </div>
</template>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let medicationIndex = 0;
    const container = document.getElementById('medications-container');
    const template = document.getElementById('medication-row-template');
    const addButton = document.getElementById('add-medication');
    const patientSelect = document.getElementById('patient_id');

    // Add first medication row on load
    addMedicationRow();

    // Add medication button
    addButton.addEventListener('click', function() {
        addMedicationRow();
    });

    // Function to add medication row
    function addMedicationRow() {
        const clone = template.content.cloneNode(true);
        const html = clone.querySelector('.medication-row').outerHTML.replace(/INDEX/g, medicationIndex);
        container.insertAdjacentHTML('beforeend', html);
        medicationIndex++;

        // Add event listener to remove button
        const lastRow = container.lastElementChild;
        lastRow.querySelector('.remove-medication').addEventListener('click', function() {
            if (container.children.length > 1) {
                lastRow.remove();
            } else {
                alert('At least one medication is required');
            }
        });
    }

    // Patient selection handler - show allergies/medical history
    patientSelect.addEventListener('change', function() {
        const selected = this.options[this.selectedIndex];
        const allergies = selected.dataset.allergies || '';
        const medicalHistory = selected.dataset.medicalHistory || '';
        const display = document.getElementById('patient-info-display');
        const allergiesDiv = document.getElementById('patient-allergies');
        const medicalDiv = document.getElementById('patient-medical-history');

        if (allergies || medicalHistory) {
            display.classList.remove('hidden');
            allergiesDiv.innerHTML = allergies ? `<strong>Allergies:</strong> ${allergies}` : '';
            medicalDiv.innerHTML = medicalHistory ? `<strong>Medical History:</strong> ${medicalHistory}` : '';
            
            // Pre-fill the health fields
            document.querySelector('[name="known_allergies"]').value = allergies;
            document.querySelector('[name="current_medications"]').value = medicalHistory;
        } else {
            display.classList.add('hidden');
        }
    });
});
</script>
@endsection
