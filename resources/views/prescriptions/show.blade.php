@extends('layouts.app')

@section('page-title', 'Clinical Record Ledger')

@section('content')
<div class="max-w-5xl mx-auto page-fade-in">
    <!-- Header & Action Hub -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
        <div class="flex items-center space-x-4">
            <a href="{{ route('clinic.prescriptions.index') }}" class="p-2 bg-white border border-slate-200 rounded-xl text-slate-400 hover:text-blue-600 hover:border-blue-100 transition-all shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Clinical Record</h1>
                <p class="text-xs font-mono font-bold text-blue-600 tracking-tighter uppercase">{{ $prescription->prescription_number }}</p>
            </div>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('clinic.prescriptions.pdf', $prescription) }}" 
               class="flex items-center px-5 py-2.5 bg-emerald-50 text-emerald-700 text-sm font-black rounded-xl hover:bg-emerald-100 transition-all border border-emerald-100">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                PDF Ledger
            </a>
            <a href="{{ route('clinic.prescriptions.print', $prescription) }}" target="_blank"
               class="flex items-center px-5 py-2.5 bg-slate-100 text-slate-700 text-sm font-black rounded-xl hover:bg-slate-200 transition-all">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Direct Print
            </a>
            @if($prescription->status === 'active')
                <a href="{{ route('clinic.prescriptions.edit', $prescription) }}" 
                   class="flex items-center px-5 py-2.5 bg-blue-600 text-white text-sm font-black rounded-xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-600/20">
                    Refine Entry
                </a>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Sidebar Info -->
        <div class="lg:col-span-1 space-y-8">
            <!-- Patient Mini Card -->
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-200 overflow-hidden">
                <div class="p-8">
                    <div class="flex items-center space-x-4 mb-6">
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-500 to-blue-600 flex items-center justify-center text-white text-xl font-black shadow-lg shadow-blue-500/20">
                            {{ substr($prescription->patient->first_name, 0, 1) }}
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Medical Subject</p>
                            <h3 class="text-lg font-black text-slate-900 leading-tight">{{ $prescription->patient->first_name }} {{ $prescription->patient->last_name }}</h3>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between text-sm">
                            <span class="font-bold text-slate-500">Contact</span>
                            <span class="font-black text-slate-900">{{ $prescription->patient->phone }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="font-bold text-slate-500">Age</span>
                            <span class="font-black text-slate-900">{{ $prescription->patient->date_of_birth ? $prescription->patient->date_of_birth->age . ' yrs' : 'N/A' }}</span>
                        </div>
                        <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Life Cycle</span>
                            @if($prescription->status === 'active')
                                <span class="px-3 py-1 bg-emerald-50 text-emerald-700 text-[10px] font-black uppercase rounded-full">Active</span>
                            @elseif($prescription->status === 'completed')
                                <span class="px-3 py-1 bg-blue-50 text-blue-700 text-[10px] font-black uppercase rounded-full">Closed</span>
                            @else
                                <span class="px-3 py-1 bg-rose-50 text-rose-700 text-[10px] font-black uppercase rounded-full">Voided</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Practitioner Card -->
            <div class="bg-slate-900 rounded-[2rem] p-8 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 -mt-8 -mr-8 w-32 h-32 bg-white/5 rounded-full blur-2xl"></div>
                <div class="relative">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Certifying Ledger</p>
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center border border-white/10">
                            <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        </div>
                        <div>
                            <h4 class="font-black text-sm">Dr. {{ $prescription->dentist->name }}</h4>
                            <p class="text-[10px] text-slate-400 font-bold uppercase">{{ $prescription->clinic->name }}</p>
                        </div>
                    </div>
                    <div class="text-[10px] font-medium text-slate-400 leading-relaxed italic">
                        Verified medical professional certification for medication administration and treatment protocol.
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content (Clinical Ledger) -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Diagnosis Section -->
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-200 overflow-hidden">
                <div class="p-8 lg:p-10">
                    <div class="flex items-center space-x-3 pb-4 border-b border-slate-100 mb-8">
                        <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center text-blue-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                        </div>
                        <h3 class="text-sm font-black text-slate-900 uppercase tracking-widest">Clinical Diagnosis</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Chief Complaint</p>
                            <p class="text-sm font-bold text-slate-900">{{ $prescription->chief_complaint ?? 'No specific complaint recorded.' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Certified Date</p>
                            <p class="text-sm font-bold text-slate-900">{{ $prescription->prescribed_date->format('l, d F Y') }}</p>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="p-6 bg-slate-50 rounded-3xl border border-slate-100">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Clinical Assessment</p>
                            <p class="text-sm font-medium text-slate-700 leading-relaxed">{{ $prescription->diagnosis }}</p>
                        </div>
                        
                        @if($prescription->treatment_provided || $prescription->dental_notes)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @if($prescription->treatment_provided)
                                    <div>
                                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Primary Procedure</p>
                                        <p class="text-[13px] font-bold text-slate-700 leading-relaxed">{{ $prescription->treatment_provided }}</p>
                                    </div>
                                @endif
                                @if($prescription->dental_notes)
                                    <div>
                                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Sub-Procedural Notes</p>
                                        <p class="text-[13px] font-bold text-slate-700 leading-relaxed">{{ $prescription->dental_notes }}</p>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Health Information Warning -->
            @if($prescription->known_allergies || $prescription->current_medications || $prescription->medical_conditions)
                <div class="p-8 bg-amber-50 rounded-[2.5rem] border border-amber-100">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center shadow-sm text-amber-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </div>
                        <h3 class="text-sm font-black text-amber-900 uppercase tracking-widest">Medical Subject Sensitivity</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @if($prescription->known_allergies)
                            <div>
                                <p class="text-[10px] font-black text-amber-800/60 uppercase tracking-widest mb-1 ml-1">Detected Allergies</p>
                                <p class="text-sm font-black text-amber-900">{{ $prescription->known_allergies }}</p>
                            </div>
                        @endif
                        @if($prescription->current_medications)
                            <div>
                                <p class="text-[10px] font-black text-amber-800/60 uppercase tracking-widest mb-1 ml-1">Current Regiment</p>
                                <p class="text-sm font-black text-amber-900">{{ $prescription->current_medications }}</p>
                            </div>
                        @endif
                        @if($prescription->medical_conditions)
                            <div>
                                <p class="text-[10px] font-black text-amber-800/60 uppercase tracking-widest mb-1 ml-1">Vital Conditions</p>
                                <p class="text-sm font-black text-amber-900">{{ $prescription->medical_conditions }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Medications List -->
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-200 overflow-hidden">
                <div class="p-8 lg:p-10 border-b border-slate-100 bg-slate-50/30">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-emerald-50 rounded-lg flex items-center justify-center text-emerald-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                        </div>
                        <h3 class="text-sm font-black text-emerald-900 uppercase tracking-widest">Medication Regiment</h3>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50/50">
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Formula & Composition</th>
                                <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Regiment (Dosage/Freq)</th>
                                <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Cycle (Time/Qty)</th>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Route & Notes</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 group">
                            @foreach($prescription->items as $item)
                                <tr class="hover:bg-slate-50/80 transition-all duration-200">
                                    <td class="px-8 py-6">
                                        <p class="text-sm font-black text-slate-900">{{ $item->medication_name }}</p>
                                        @if($item->generic_name)
                                            <p class="text-xs font-bold text-slate-400 uppercase tracking-tighter">{{ $item->generic_name }}</p>
                                        @endif
                                    </td>
                                    <td class="px-6 py-6 font-bold text-sm text-slate-700">
                                        {{ $item->dosage }} <span class="text-slate-300 mx-1">•</span> {{ $item->frequency }}
                                    </td>
                                    <td class="px-6 py-6">
                                        <p class="text-sm font-bold text-slate-900">{{ $item->duration_days }} Days</p>
                                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $item->quantity }} Units Total</p>
                                    </td>
                                    <td class="px-8 py-6">
                                        <p class="text-sm font-black text-indigo-600">{{ $item->route }}</p>
                                        @if($item->instructions)
                                            <p class="text-xs font-medium text-slate-500 italic mt-1">{{ $item->instructions }}</p>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Global Instructions -->
            @if($prescription->general_instructions)
                <div class="bg-blue-600 rounded-[2.5rem] p-8 lg:p-10 text-white relative overflow-hidden shadow-xl shadow-blue-600/20">
                    <div class="absolute top-0 right-0 -mt-20 -mr-20 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
                    <div class="relative">
                        <div class="flex items-center space-x-3 mb-4">
                            <div class="w-8 h-8 bg-white/10 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <h3 class="text-sm font-black uppercase tracking-widest">Global Practitioner Instructions</h3>
                        </div>
                        <p class="text-lg font-bold leading-relaxed text-blue-50/90">{{ $prescription->general_instructions }}</p>
                    </div>
                </div>
            @endif

            <!-- Danger Zone -->
            <div class="flex items-center justify-between p-8 border border-rose-100 bg-rose-50/30 rounded-[2.5rem]">
                <div>
                    <h4 class="text-sm font-black text-rose-900 uppercase tracking-widest mb-1">Record Archival</h4>
                    <p class="text-xs font-medium text-rose-700/70">Proceed with caution. Voiding a record is permanent.</p>
                </div>
                <form action="{{ route('clinic.prescriptions.destroy', $prescription) }}" method="POST" 
                      onsubmit="return confirm('Immediate Archival: Are you sure you want to void this clinical record?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-6 py-3 bg-white text-rose-600 font-black rounded-xl border border-rose-100 hover:bg-rose-600 hover:text-white transition-all shadow-sm">
                        Void Record
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
