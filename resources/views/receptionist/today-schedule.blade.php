@extends('layouts.app')

@section('title', 'Today\'s Schedule - ' . config('app.name'))

@section('page-title', 'Today\'s Clinical Schedule')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-600 shadow-inner">
                <i class="fas fa-calendar-day text-lg"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Total</p>
                <h3 class="text-2xl font-black text-slate-900">{{ $appointments->count() }}</h3>
            </div>
        </div>
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600 shadow-inner">
                <i class="fas fa-check-circle text-lg"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Completed</p>
                <h3 class="text-2xl font-black text-slate-900">{{ $appointments->where('status', 'completed')->count() }}</h3>
            </div>
        </div>
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-amber-50 flex items-center justify-center text-amber-600 shadow-inner">
                <i class="fas fa-clock text-lg"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Upcoming</p>
                <h3 class="text-2xl font-black text-slate-900">{{ $appointments->where('status', 'scheduled')->count() }}</h3>
            </div>
        </div>
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-rose-50 flex items-center justify-center text-rose-600 shadow-inner">
                <i class="fas fa-user-clock text-lg"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">In-Progress</p>
                <h3 class="text-2xl font-black text-slate-900">{{ $appointments->where('status', 'confirmed')->count() }}</h3>
            </div>
        </div>
    </div>

    <!-- Schedule Table -->
    <div class="bg-white rounded-4xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-8 py-6 border-b border-slate-50 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600">
                    <i class="fas fa-list-ul"></i>
                </div>
                <div>
                    <h2 class="text-lg font-black text-slate-900 tracking-tight">Daily Appointments</h2>
                    <p class="text-xs text-slate-400 font-medium">Timeline of scheduled visits for {{ today()->format('M d, Y') }}</p>
                </div>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('clinic.print-schedule') }}" target="_blank" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-slate-200 transition-all flex items-center gap-2">
                    <i class="fas fa-print"></i> Print
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Time</th>
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Patient</th>
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Dentist</th>
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Service Type</th>
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Status</th>
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($appointments as $appointment)
                        <tr class="group hover:bg-blue-50/30 transition-colors duration-200">
                            <td class="px-8 py-5">
                                <span class="text-sm font-black text-blue-600">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</span>
                            </td>
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-400 font-black text-xs uppercase overflow-hidden ring-2 ring-white shadow-sm">
                                        @if($appointment->patient->photo)
                                            <img src="{{ Storage::url($appointment->patient->photo) }}" class="w-full h-full object-cover">
                                        @else
                                            {{ substr($appointment->patient->first_name, 0, 1) }}{{ substr($appointment->patient->last_name, 0, 1) }}
                                        @endif
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-slate-900">{{ $appointment->patient->full_name }}</div>
                                        <div class="text-[10px] text-slate-400 font-bold uppercase tracking-tight">{{ $appointment->patient->patient_id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-5">
                                <span class="text-xs font-bold text-slate-600 flex items-center gap-2">
                                    <i class="fa-solid fa-stethoscope text-slate-300"></i>
                                    {{ $appointment->dentist->name }}
                                </span>
                            </td>
                            <td class="px-8 py-5">
                                <span class="px-3 py-1 bg-indigo-50 text-indigo-600 text-[10px] font-black uppercase tracking-widest rounded-lg border border-indigo-100">
                                    {{ ucfirst($appointment->type) }}
                                </span>
                            </td>
                            <td class="px-8 py-5 text-center">
                                @php
                                    $statusColors = [
                                        'scheduled' => 'bg-amber-100 text-amber-700 border-amber-200',
                                        'confirmed' => 'bg-blue-100 text-blue-700 border-blue-200',
                                        'completed' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                        'no_show' => 'bg-slate-100 text-slate-600 border-slate-200',
                                        'cancelled' => 'bg-rose-100 text-rose-700 border-rose-200',
                                    ];
                                @endphp
                                <span class="inline-flex px-3 py-1 items-center gap-1.5 {{ $statusColors[$appointment->status] ?? 'bg-slate-100 text-slate-600 border-slate-200' }} text-[10px] font-black uppercase tracking-widest rounded-full border">
                                    <span class="w-1 h-1 rounded-full bg-current"></span>
                                    {{ str_replace('_', ' ', $appointment->status) }}
                                </span>
                            </td>
                            <td class="px-8 py-5 text-right">
                                <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                    @if($appointment->status === 'scheduled')
                                        <form action="{{ route('clinic.appointments.check-in', $appointment) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="p-2 bg-emerald-50 text-emerald-600 rounded-lg hover:bg-emerald-600 hover:text-white transition-all shadow-sm" title="Check In">
                                                <i class="fas fa-user-check text-xs"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('clinic.appointments.no-show', $appointment) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="p-2 bg-slate-50 text-slate-600 rounded-lg hover:bg-slate-600 hover:text-white transition-all shadow-sm" title="Mark No Show">
                                                <i class="fas fa-user-times text-xs"></i>
                                            </button>
                                        </form>
                                    @endif
                                    <a href="{{ route('clinic.appointments.show', $appointment) }}" class="p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-600 hover:text-white transition-all shadow-sm" title="View Details">
                                        <i class="fas fa-eye text-xs"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-8 py-20 text-center">
                                <div class="w-20 h-20 bg-slate-50 rounded-3xl flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-calendar-times text-3xl text-slate-200"></i>
                                </div>
                                <h3 class="text-sm font-black text-slate-900 uppercase tracking-widest">No Appointments Scheduled</h3>
                                <p class="text-xs text-slate-400 mt-1 font-medium">There are no patient visits in the calendar for today.</p>
                                <a href="{{ route('clinic.appointments.create', ['iframe' => 1]) }}" data-modal-url="{{ route('clinic.appointments.create', ['iframe' => 1]) }}" data-modal-title="New" class="mt-6 inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white text-[11px] font-black uppercase tracking-widest rounded-2xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-500/20">
                                    <i class="fas fa-plus"></i> New Appointment
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .rounded-4xl { border-radius: 2.5rem; }
</style>
@endsection
