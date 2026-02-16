@extends('patient-portal.layout')

@section('title', 'Systemic Treatments')

@section('content')
<div class="max-w-6xl mx-auto space-y-10 page-fade-in pb-20">
    @php
        $patient = Auth::guard('patient')->user();
        $clinicColor = ($patient && $patient->clinic) ? $patient->clinic->primary_color : '#0ea5e9';
    @endphp

    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div class="space-y-1">
            <h1 class="text-3xl font-black text-slate-900 tracking-tight Outfit">Treatment Protocols</h1>
            <p class="text-slate-500 font-medium">Monitoring your long-term clinical sequences and automated appointment cycles.</p>
        </div>
        
        <div class="bg-white px-6 py-4 rounded-[1.5rem] border border-slate-200 shadow-sm flex items-center gap-4 w-full md:w-auto">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white shadow-lg shadow-sky-100" style="background-color: {{ $clinicColor }}">
                <i class="fas fa-sync text-sm animate-spin-slow"></i>
            </div>
            <div>
                <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Active Sequences</p>
                <p class="text-lg font-black text-slate-900 Outfit">{{ $recurringAppointments->total() }} Protocols</p>
            </div>
        </div>
    </div>

    @if($recurringAppointments->isEmpty())
        <div class="bg-white rounded-[2.5rem] p-24 text-center border border-slate-200 shadow-sm stagger-in">
            <div class="w-20 h-20 bg-slate-50 rounded-[2rem] flex items-center justify-center mx-auto mb-8 text-slate-200">
                <i class="fas fa-redo-alt text-3xl"></i>
            </div>
            <h3 class="text-2xl font-black text-slate-900 Outfit">No cycles established</h3>
            <p class="text-slate-500 max-w-sm mx-auto mt-2 font-medium">Long-term treatment adjustments or maintenance cycles will be mapped here by your clinical advisor.</p>
        </div>
    @else
        <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-sm overflow-hidden stagger-in">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100">
                            <th class="px-8 py-5 text-xs font-black text-slate-400 uppercase tracking-widest">Protocol Unit</th>
                            <th class="px-8 py-5 text-xs font-black text-slate-400 uppercase tracking-widest">Clinical Lead</th>
                            <th class="px-8 py-5 text-xs font-black text-slate-400 uppercase tracking-widest">Chronology</th>
                            <th class="px-8 py-5 text-right text-xs font-black text-slate-400 uppercase tracking-widest">State</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($recurringAppointments as $ra)
                        <tr class="group hover:bg-slate-50/30 transition-all duration-200">
                            <!-- Protocol Type -->
                            <td class="px-8 py-6 whitespace-nowrap">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-400 group-hover:bg-white group-hover:shadow-md transition-all duration-300">
                                        <i class="fas fa-redo-alt text-xl"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-black text-slate-900 tracking-tight capitalize">{{ str_replace('_', ' ', $ra->type) }}</p>
                                        <p class="text-xs font-bold text-slate-400 uppercase tracking-tighter">Every {{ $ra->frequency }}</p>
                                    </div>
                                </div>
                            </td>

                            <!-- Dentist / Lead -->
                            <td class="px-8 py-6 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-xs font-black text-slate-600 border border-slate-200">
                                        {{ substr($ra->dentist->name, 0, 1) }}
                                    </div>
                                    <span class="text-xs text-slate-700 font-bold Outfit">Dr. {{ $ra->dentist->name }}</span>
                                </div>
                            </td>

                            <!-- Dates -->
                            <td class="px-8 py-6 whitespace-nowrap">
                                <div class="flex flex-col">
                                    <p class="text-sm font-black text-slate-800 Outfit">Starting {{ $ra->start_date->format('M d, Y') }}</p>
                                    <p class="text-xs text-slate-400 font-bold uppercase tracking-tighter">
                                        Limits: {{ $ra->end_date ? $ra->end_date->format('M d, Y') : 'Open Protocol' }}
                                    </p>
                                </div>
                            </td>

                            <!-- Status -->
                            <td class="px-8 py-6 whitespace-nowrap text-right">
                                <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-widest border shadow-sm
                                    {{ $ra->is_active ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-rose-50 text-rose-600 border-rose-100' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $ra->is_active ? 'bg-emerald-500 animate-pulse' : 'bg-rose-500' }}"></span>
                                    {{ $ra->is_active ? 'Active' : 'Paused' }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        @if($recurringAppointments->hasPages())
        <div class="mt-8">
            {{ $recurringAppointments->links() }}
        </div>
        @endif
    @endif

    <!-- Trust Footer -->
    <div class="flex items-center justify-center gap-4 text-slate-400 pt-8 opacity-60">
        <div class="flex items-center gap-1.5">
            <i class="fas fa-clock text-xs"></i>
            <span class="text-xs font-black uppercase tracking-widest">Automated Node Scheduling</span>
        </div>
        <div class="w-1 h-1 rounded-full bg-slate-300"></div>
        <div class="flex items-center gap-1.5">
            <i class="fas fa-file-medical text-xs"></i>
            <span class="text-xs font-black uppercase tracking-widest">Clinical Protocol Tracking</span>
        </div>
    </div>
</div>
@endsection
