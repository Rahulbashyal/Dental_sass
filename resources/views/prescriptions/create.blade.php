@extends('layouts.app')

@section('page-title', 'Issue New Prescription')

@section('content')
<div class="max-w-4xl mx-auto page-fade-in">
    <!-- Breadcrumb & Title -->
    <div class="flex items-center space-x-4 mb-8">
        <a href="{{ route('clinic.prescriptions.index') }}" class="p-2 bg-white border border-slate-200 rounded-xl text-slate-400 hover:text-blue-600 hover:border-blue-100 transition-all shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Issue Prescription</h1>
            <p class="text-sm text-slate-500 font-medium italic">Create a verified clinical medication regiment.</p>
        </div>
    </div>

    <form action="{{ route('clinic.prescriptions.store') }}" method="POST" id="prescription-form" class="space-y-8">
        @csrf
        
        <!-- Section 1: Clinical Subject -->
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-8 lg:p-10">
                <div class="flex items-center space-x-3 pb-4 border-b border-slate-100 mb-8">
                    <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center text-blue-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <h3 class="text-sm font-black text-slate-900 uppercase tracking-widest">Clinical Subject</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Select Patient *</label>
                        <select name="patient_id" id="patient_id" required
                                class="w-full px-5 py-4 bg-slate-50 border-slate-200 rounded-2xl text-slate-900 font-bold focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none appearance-none">
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
                        @error('patient_id') <p class="text-rose-500 text-[10px] font-bold mt-1 ml-1 uppercase">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Related Visit (Optional)</label>
                        <select name="appointment_id"
                                class="w-full px-5 py-4 bg-slate-50 border-slate-200 rounded-2xl text-slate-900 font-bold focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none appearance-none">
                            <option value="">No specific visit</option>
                            @foreach($appointments as $appointment)
                                <option value="{{ $appointment->id }}">
                                    {{ $appointment->appointment_date->format('M d, Y') }} - {{ $appointment->type }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Live Health Alert -->
                <div id="patient-info-display" class="mt-8 p-6 bg-amber-50 rounded-3xl border border-amber-100 hidden animate-bounce-in">
                    <div class="flex items-start">
                        <div class="p-2 bg-white rounded-xl shadow-sm mr-4 text-amber-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </div>
                        <div>
                            <p class="font-black text-amber-900 uppercase tracking-tight text-sm mb-1">Health Warning System</p>
                            <div id="patient-allergies" class="text-sm font-bold text-amber-800/80"></div>
                            <div id="patient-medical-history" class="text-sm font-bold text-amber-800/80 mt-1"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 2: Diagnosis & Vitality -->
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-8 lg:p-10 border-b border-slate-100">
                <div class="flex items-center space-x-3 pb-4 border-b border-slate-100 mb-8">
                    <div class="w-8 h-8 bg-indigo-50 rounded-lg flex items-center justify-center text-indigo-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                    </div>
                    <h3 class="text-sm font-black text-slate-900 uppercase tracking-widest">Diagnosis & Vitality</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    <div class="space-y-2">
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Chief Complaint</label>
                        <input type="text" name="chief_complaint" value="{{ old('chief_complaint') }}" placeholder="Ex: Severe Lower Molar Pain"
                               class="w-full px-5 py-4 bg-slate-50 border-slate-200 rounded-2xl text-slate-900 font-bold focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none">
                    </div>

                    <div class="space-y-2">
                        <x-nepali-date-input name="prescribed_date" label="Prescription Date" :value="old('prescribed_date', date('Y-m-d'))" required />
                    </div>
                </div>

                <div class="space-y-2 mb-8">
                    <label class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Clinical Diagnosis *</label>
                    <textarea name="diagnosis" rows="3" required placeholder="Describe the diagnosed condition..."
                              class="w-full px-5 py-4 bg-slate-50 border-slate-200 rounded-2xl text-slate-900 font-bold focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none">{{ old('diagnosis') }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Treatment Provided</label>
                        <textarea name="treatment_provided" rows="2" placeholder="Ex: Scaling & Root Planing"
                                  class="w-full px-5 py-4 bg-slate-50 border-slate-200 rounded-2xl text-slate-900 font-bold focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none">{{ old('treatment_provided') }}</textarea>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Clinical Observations</label>
                        <textarea name="dental_notes" rows="2" placeholder="Any additional findings..."
                                  class="w-full px-5 py-4 bg-slate-50 border-slate-200 rounded-2xl text-slate-900 font-bold focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none">{{ old('dental_notes') }}</textarea>
                    </div>
                </div>
            </div>
            
            <!-- Hidden Health Verification -->
            <div class="p-8 bg-slate-50/50">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6">Security Verification: Subject Health Context</p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="space-y-2">
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Detected Allergies</label>
                        <input type="text" name="known_allergies" value="{{ old('known_allergies') }}"
                               class="w-full px-4 py-3 bg-white border-slate-200 rounded-xl text-slate-900 font-bold outline-none">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Current Meds</label>
                        <input type="text" name="current_medications" value="{{ old('current_medications') }}"
                               class="w-full px-4 py-3 bg-white border-slate-200 rounded-xl text-slate-900 font-bold outline-none">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Vital Conditions</label>
                        <input type="text" name="medical_conditions" value="{{ old('medical_conditions') }}"
                               class="w-full px-4 py-3 bg-white border-slate-200 rounded-xl text-slate-900 font-bold outline-none">
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 3: Medication Regiment -->
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-8 lg:p-10">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-8">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-emerald-50 rounded-lg flex items-center justify-center text-emerald-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                        </div>
                        <h3 class="text-sm font-black text-slate-900 uppercase tracking-widest">Medication Regiment</h3>
                    </div>
                    <button type="button" id="add-medication" class="inline-flex items-center px-4 py-2 bg-emerald-50 text-emerald-700 font-black rounded-xl hover:bg-emerald-100 transition-all text-xs">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Add Entry
                    </button>
                </div>

                <div id="medications-container" class="space-y-6">
                    <!-- Medications injected via template -->
                </div>
            </div>
        </div>

        <!-- Section 4: Final Instructions -->
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-8 lg:p-10">
                <div class="flex items-center space-x-3 pb-4 border-b border-slate-100 mb-8">
                    <div class="w-8 h-8 bg-slate-50 rounded-lg flex items-center justify-center text-slate-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
                    </div>
                    <h3 class="text-sm font-black text-slate-900 uppercase tracking-widest">Global Instructions</h3>
                </div>
                <textarea name="general_instructions" rows="3" placeholder="Ex: Take all medications after meals. Avoid spicy food for 3 days."
                          class="w-full px-5 py-4 bg-slate-50 border-slate-200 rounded-2xl text-slate-900 font-bold focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none">{{ old('general_instructions') }}</textarea>
            </div>
        </div>

        <!-- Submit Actions -->
        <div class="flex items-center justify-between p-8 bg-slate-900 rounded-[2.5rem] shadow-xl shadow-slate-900/20">
            <div class="hidden sm:block">
                <p class="text-white font-black text-lg">Verify Ledger Entry</p>
                <p class="text-slate-400 text-xs font-medium">Auto-notifies patient via professional portal.</p>
            </div>
            <div class="flex gap-4">
                <a href="{{ route('clinic.prescriptions.index') }}" class="px-8 py-4 bg-white/10 text-white font-black rounded-2xl hover:bg-white/20 transition-all">
                    Discard
                </a>
                <button type="submit" class="px-8 py-4 bg-blue-600 text-white font-black rounded-2xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-600/20 active:scale-95">
                    Certify & Issue
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Premium Medication Row Template -->
<template id="medication-row-template">
    <div class="medication-row bg-slate-50/50 border border-slate-200 rounded-[2rem] p-6 lg:p-8 relative group hover:bg-white hover:shadow-2xl hover:shadow-slate-200/50 transition-all duration-300">
        <button type="button" class="remove-medication absolute -top-2 -right-2 w-8 h-8 bg-white border border-rose-100 text-rose-500 rounded-full shadow-lg flex items-center justify-center hover:bg-rose-500 hover:text-white transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <div class="lg:col-span-12 space-y-2">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Medication / Formula</label>
                <select name="medications[INDEX][medication_id]" required class="medication-select w-full px-5 py-4 bg-white border-slate-200 rounded-2xl text-slate-900 font-bold focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all outline-none appearance-none">
                    <option value="">-- Choose Medication --</option>
                    @foreach($medications as $med)
                        <option value="{{ $med->id }}" data-dosages="{{ json_encode($med->common_dosages) }}">
                            {{ $med->name }} ({{ $med->category }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="lg:col-span-4 space-y-2">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Dosage</label>
                <input type="text" name="medications[INDEX][dosage]" placeholder="Ex: 500mg" required
                       class="w-full px-5 py-4 bg-white border-slate-200 rounded-2xl text-slate-900 font-bold outline-none">
            </div>

            <div class="lg:col-span-4 space-y-2">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Frequency</label>
                <select name="medications[INDEX][frequency]" required class="w-full px-5 py-4 bg-white border-slate-200 rounded-2xl text-slate-900 font-bold outline-none appearance-none">
                    <option value="">Choose...</option>
                    <option value="1 time daily">Once daily (OD)</option>
                    <option value="2 times daily">Twice (BD)</option>
                    <option value="3 times daily">Thrice (TDS)</option>
                    <option value="4 times daily">Four times (QDS)</option>
                    <option value="As needed">As needed (PRN)</option>
                    <option value="Every 4 hours">Every 4 hrs</option>
                    <option value="Every 8 hours">Every 8 hrs</option>
                </select>
            </div>

            <div class="lg:col-span-4 space-y-2">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Route</label>
                <select name="medications[INDEX][route]" required class="w-full px-5 py-4 bg-white border-slate-200 rounded-2xl text-slate-900 font-bold outline-none appearance-none">
                    <option value="Oral" selected>Oral</option>
                    <option value="Topical">Topical</option>
                    <option value="Rinse/Gargle">Rinse</option>
                    <option value="Injectable">Injectable</option>
                </select>
            </div>

            <div class="lg:col-span-3 space-y-2">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Duration (D)</label>
                <input type="number" name="medications[INDEX][duration_days]" min="1" value="5" required
                       class="w-full px-5 py-4 bg-white border-slate-200 rounded-2xl text-slate-900 font-bold outline-none">
            </div>

            <div class="lg:col-span-3 space-y-2">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Unit Qty</label>
                <input type="number" name="medications[INDEX][quantity]" min="1" value="10" required
                       class="w-full px-5 py-4 bg-white border-slate-200 rounded-2xl text-slate-900 font-bold outline-none">
            </div>

            <div class="lg:col-span-6 space-y-2">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Notes / Instructions</label>
                <input type="text" name="medications[INDEX][instructions]" placeholder="Ex: Post-meal session"
                       class="w-full px-5 py-4 bg-white border-slate-200 rounded-2xl text-slate-900 font-bold outline-none">
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

    function addMedicationRow() {
        const clone = template.content.cloneNode(true);
        const html = clone.querySelector('.medication-row').outerHTML.replace(/INDEX/g, medicationIndex);
        container.insertAdjacentHTML('beforeend', html);
        
        const lastRow = container.lastElementChild;
        lastRow.querySelector('.remove-medication').addEventListener('click', function() {
            if (container.children.length > 1) lastRow.remove();
            else alert('Medication regiment requires at least one entry.');
        });
        
        medicationIndex++;
    }

    addMedicationRow();
    addButton.addEventListener('click', addMedicationRow);

    patientSelect.addEventListener('change', function() {
        const selected = this.options[this.selectedIndex];
        if (!selected.value) {
            document.getElementById('patient-info-display').classList.add('hidden');
            return;
        }

        const allergies = selected.dataset.allergies || '';
        const medicalHistory = selected.dataset.medicalHistory || '';
        const display = document.getElementById('patient-info-display');
        
        if (allergies || medicalHistory) {
            display.classList.remove('hidden');
            document.getElementById('patient-allergies').innerHTML = allergies ? `<strong>Allergies:</strong> ${allergies}` : '<em>No known allergies</em>';
            document.getElementById('patient-medical-history').innerHTML = medicalHistory ? `<strong>History:</strong> ${medicalHistory}` : '';
            
            document.querySelector('[name="known_allergies"]').value = allergies;
            document.querySelector('[name="current_medications"]').value = medicalHistory;
        } else {
            display.classList.add('hidden');
        }
    });
});
</script>
@endsection
