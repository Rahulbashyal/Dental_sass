@extends('layouts.app')

@section('title', 'Patient History - ' . $patient->full_name)

@section('page-title', 'Clinical History')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
    <!-- Patient Profile Header -->
    <div class="bg-white rounded-4xl shadow-sm border border-slate-100 p-8 flex flex-col md:flex-row items-center gap-8">
        <div class="w-24 h-24 md:w-32 md:h-32 rounded-3xl bg-slate-100 flex items-center justify-center text-slate-400 font-black text-4xl uppercase overflow-hidden ring-4 ring-white shadow-xl">
            @if($patient->photo)
                <img src="{{ Storage::url($patient->photo) }}" class="w-full h-full object-cover">
            @else
                {{ substr($patient->first_name, 0, 1) }}{{ substr($patient->last_name, 0, 1) }}
            @endif
        </div>
        <div class="flex-1 text-center md:text-left space-y-2">
            <div class="flex flex-wrap items-center justify-center md:justify-start gap-3">
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">{{ $patient->full_name }}</h1>
                <span class="px-3 py-1 bg-blue-50 text-blue-600 text-[10px] font-black uppercase tracking-widest rounded-full border border-blue-100">
                    {{ $patient->patient_id }}
                </span>
            </div>
            <div class="flex flex-wrap items-center justify-center md:justify-start gap-4 pt-1">
                <span class="text-xs text-slate-500 font-bold flex items-center gap-1.5">
                    <i class="fa-solid fa-venus-mars opacity-50 text-blue-400"></i> {{ ucfirst($patient->gender) }}
                </span>
                <span class="text-xs text-slate-500 font-bold flex items-center gap-1.5 border-l border-slate-200 pl-4">
                    <i class="fa-solid fa-cake-candles opacity-50 text-blue-400"></i> {{ $patient->date_of_birth->format('M d, Y') }} ({{ $patient->date_of_birth->age }} yrs)
                </span>
                <span class="text-xs text-slate-500 font-bold flex items-center gap-1.5 border-l border-slate-200 pl-4">
                    <i class="fa-solid fa-phone opacity-50 text-blue-400"></i> {{ $patient->phone }}
                </span>
            </div>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('clinic.patients.show', $patient) }}" class="px-6 py-3 bg-slate-100 text-slate-600 rounded-2xl text-[11px] font-black uppercase tracking-widest hover:bg-slate-200 transition-all">
                Full Profile
            </a>
            <a href="{{ route('clinic.appointments.create', ['patient_id' => $patient->id]) }}" class="px-6 py-3 bg-blue-600 text-white rounded-2xl text-[11px] font-black uppercase tracking-widest hover:bg-blue-700 transition-all shadow-lg shadow-blue-500/20">
                New Visit
            </a>
        </div>
    </div>

    <!-- Visits Timeline -->
    <div class="bg-white rounded-4xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-8 py-6 border-b border-slate-50 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600">
                    <i class="fas fa-history"></i>
                </div>
                <div>
                    <h2 class="text-lg font-black text-slate-900 tracking-tight">Visit History</h2>
                    <p class="text-xs text-slate-400 font-medium">Complete record of every clinical interaction</p>
                </div>
            </div>
        </div>

        <div class="p-8">
            <div class="relative space-y-8 before:absolute before:inset-0 before:ml-5 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-blue-50 before:via-blue-100 before:to-transparent">
                @forelse($appointments as $appointment)
                    <div class="relative flex items-center gap-8">
                        <!-- Date Marker -->
                        <div class="flex-shrink-0 w-10 h-10 rounded-2xl bg-white border-4 border-blue-50 shadow-sm flex items-center justify-center z-10 text-blue-600">
                            <i class="fas fa-calendar-check text-xs"></i>
                        </div>
                        
                        <!-- Details Card -->
                        <div class="flex-1 bg-slate-50/50 hover:bg-white hover:shadow-xl hover:shadow-slate-200/50 rounded-3xl p-6 border border-slate-100 transition-all duration-300">
                            <div class="flex flex-col md:flex-row justify-between gap-4">
                                <div class="space-y-1">
                                    <div class="text-[10px] font-black text-blue-600 uppercase tracking-widest">
                                        {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('l, M d, Y') }} @ {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                                    </div>
                                    <h3 class="text-lg font-black text-slate-900 tracking-tight">{{ ucfirst($appointment->type) }}</h3>
                                    <p class="text-sm text-slate-500 italic font-medium">"{{ $appointment->notes ?? 'No visit notes recorded.' }}"</p>
                                </div>
                                
                                <div class="flex flex-col md:items-end gap-2">
                                    @php
                                        $statusColors = [
                                            'scheduled' => 'bg-amber-100 text-amber-700',
                                            'confirmed' => 'bg-blue-100 text-blue-700',
                                            'completed' => 'bg-emerald-100 text-emerald-700',
                                            'no_show' => 'bg-slate-200 text-slate-700',
                                            'cancelled' => 'bg-rose-100 text-rose-700',
                                        ];
                                    @endphp
                                    <span class="px-3 py-1 {{ $statusColors[$appointment->status] ?? 'bg-slate-100 text-slate-600' }} text-[10px] font-black uppercase tracking-widest rounded-full text-center">
                                        {{ str_replace('_', ' ', $appointment->status) }}
                                    </span>
                                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">
                                        Dentist: <span class="text-slate-900">{{ $appointment->dentist->name }}</span>
                                    </div>
                                    @if($appointment->treatment_cost)
                                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">
                                            Billing: <span class="font-black text-slate-900">NRs. {{ number_format($appointment->treatment_cost, 2) }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="py-12 text-center">
                        <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-folder-open text-2xl text-slate-200"></i>
                        </div>
                        <h3 class="text-sm font-black text-slate-900 uppercase tracking-widest">Initial Profile</h3>
                        <p class="text-xs text-slate-400 mt-1 font-medium">This patient hasn't had any clinical appointments yet.</p>
                    </div>
                @endforelse
            </div>
            
            <div class="mt-12">
                {{ $appointments->links() }}
            </div>
        </div>
    </div>
</div>

<style>
    .rounded-4xl { border-radius: 2.5rem; }
    .pagination { @apply flex items-center justify-center gap-2; }
    .pagination .active { @apply px-4 py-2 bg-blue-600 text-white rounded-xl font-black text-xs; }
    .pagination a { @apply px-4 py-2 bg-slate-50 text-slate-600 rounded-xl font-black text-xs hover:bg-slate-100; }
</style>
@endsection
