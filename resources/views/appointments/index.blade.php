@extends('layouts.app')

@section('page-title', 'Appointments')

@section('content')
<div class="space-y-6">

    {{-- ── Header ──────────────────────────────────────────────────── --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Appointments</h1>
            <p class="text-sm text-slate-500 mt-0.5">{{ $appointments->total() }} total · {{ $appointments->currentPage() }} of {{ $appointments->lastPage() }} pages</p>
        </div>
        <a href="{{ route('clinic.appointments.create', ['iframe' => 1]) }}"
           data-modal-url="{{ route('clinic.appointments.create', ['iframe' => 1]) }}"
           data-modal-title="Schedule Appointment"
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white text-sm font-bold rounded-2xl hover:bg-blue-700 active:scale-95 transition-all shadow-lg shadow-blue-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Schedule Appointment
        </a>
    </div>

    {{-- ── Status filter pills ──────────────────────────────────────── --}}
    @php
        $statuses = [
            'all'         => ['label' => 'All',         'color' => 'bg-slate-100 text-slate-700 hover:bg-slate-200'],
            'scheduled'   => ['label' => 'Scheduled',   'color' => 'bg-blue-100 text-blue-700 hover:bg-blue-200'],
            'confirmed'   => ['label' => 'Confirmed',   'color' => 'bg-green-100 text-green-700 hover:bg-green-200'],
            'in_progress' => ['label' => 'In Progress', 'color' => 'bg-yellow-100 text-yellow-800 hover:bg-yellow-200'],
            'completed'   => ['label' => 'Completed',   'color' => 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200'],
            'cancelled'   => ['label' => 'Cancelled',   'color' => 'bg-red-100 text-red-700 hover:bg-red-200'],
            'no_show'     => ['label' => 'No Show',     'color' => 'bg-slate-100 text-slate-500 hover:bg-slate-200'],
        ];

        $statusBadge = [
            'scheduled'   => 'bg-blue-100 text-blue-700',
            'confirmed'   => 'bg-green-100 text-green-700',
            'in_progress' => 'bg-yellow-100 text-yellow-800',
            'completed'   => 'bg-emerald-100 text-emerald-700',
            'cancelled'   => 'bg-red-100 text-red-600',
            'no_show'     => 'bg-slate-100 text-slate-500',
            'pending'     => 'bg-orange-100 text-orange-700',
        ];

        $statusDot = [
            'scheduled'   => 'bg-blue-500',
            'confirmed'   => 'bg-green-500',
            'in_progress' => 'bg-yellow-500',
            'completed'   => 'bg-emerald-500',
            'cancelled'   => 'bg-red-500',
            'no_show'     => 'bg-slate-400',
            'pending'     => 'bg-orange-500',
        ];

        $statusGradient = [
            'scheduled'   => 'from-blue-500 to-blue-600',
            'confirmed'   => 'from-green-400 to-emerald-500',
            'in_progress' => 'from-yellow-400 to-amber-500',
            'completed'   => 'from-emerald-500 to-teal-600',
            'cancelled'   => 'from-red-400 to-rose-500',
            'no_show'     => 'from-slate-400 to-slate-500',
            'pending'     => 'from-orange-400 to-amber-500',
        ];

        $typeIcons = [
            'checkup'      => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',
            'cleaning'     => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4',
            'extraction'   => 'M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16',
            'filling'      => 'M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z',
            'default'      => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
        ];

        $currentFilter = request('status', 'all');
    @endphp

    <div class="flex flex-wrap gap-2">
        @foreach($statuses as $key => $s)
            <a href="{{ $key === 'all' ? route('clinic.appointments.index') : route('clinic.appointments.index', ['status' => $key]) }}"
               class="px-4 py-1.5 rounded-full text-xs font-bold transition-all {{ $currentFilter === $key ? 'ring-2 ring-offset-1 ring-blue-400 ' . $s['color'] : $s['color'] }}">
                {{ $s['label'] }}
            </a>
        @endforeach
    </div>

    {{-- ── Card grid ────────────────────────────────────────────────── --}}
    @forelse($appointments as $appointment)
        @php
            $grad   = $statusGradient[$appointment->status] ?? 'from-slate-400 to-slate-500';
            $badge  = $statusBadge[$appointment->status]   ?? 'bg-slate-100 text-slate-600';
            $dot    = $statusDot[$appointment->status]     ?? 'bg-slate-400';
            $icon   = $typeIcons[strtolower($appointment->type)] ?? $typeIcons['default'];
            $initials = strtoupper(substr($appointment->patient->first_name ?? '?', 0, 1) .
                                   substr($appointment->patient->last_name  ?? '', 0, 1));
        @endphp

        <div class="group bg-white rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200 overflow-hidden">
            {{-- Colored top bar --}}
            <div class="h-1.5 w-full bg-gradient-to-r {{ $grad }}"></div>

            <div class="p-5">
                {{-- Top row: avatar + name + status badge --}}
                <div class="flex items-start justify-between gap-3 mb-4">
                    <div class="flex items-center gap-3">
                        {{-- Avatar circle --}}
                        <div class="w-10 h-10 rounded-2xl bg-gradient-to-br {{ $grad }} flex items-center justify-center text-white font-black text-sm shadow-md flex-shrink-0">
                            {{ $initials }}
                        </div>
                        <div>
                            <p class="font-black text-slate-900 text-sm leading-tight group-hover:text-blue-700 transition-colors">
                                {{ $appointment->patient->name ?? ($appointment->patient->first_name . ' ' . $appointment->patient->last_name) }}
                            </p>
                            <p class="text-[11px] text-slate-400 font-semibold uppercase tracking-widest mt-0.5">
                                {{ $appointment->patient->patient_id ?? '—' }}
                            </p>
                        </div>
                    </div>
                    {{-- Status badge --}}
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold {{ $badge }} whitespace-nowrap flex-shrink-0">
                        <span class="w-1.5 h-1.5 rounded-full {{ $dot }}"></span>
                        {{ ucfirst(str_replace('_', ' ', $appointment->status)) }}
                    </span>
                </div>

                {{-- Info pills row --}}
                <div class="flex flex-wrap gap-2 mb-4">
                    {{-- Date --}}
                    <div class="flex items-center gap-1.5 bg-slate-50 px-3 py-1.5 rounded-xl">
                        <svg class="w-3.5 h-3.5 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span class="text-xs font-bold text-slate-700">{{ $appointment->appointment_date->format('d M, Y') }}</span>
                    </div>
                    {{-- Time --}}
                    <div class="flex items-center gap-1.5 bg-slate-50 px-3 py-1.5 rounded-xl">
                        <svg class="w-3.5 h-3.5 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-xs font-bold text-slate-700">
                            {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                        </span>
                    </div>
                    {{-- Type --}}
                    <div class="flex items-center gap-1.5 bg-slate-50 px-3 py-1.5 rounded-xl">
                        <svg class="w-3.5 h-3.5 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"/>
                        </svg>
                        <span class="text-xs font-bold text-slate-700 capitalize">{{ $appointment->type }}</span>
                    </div>
                </div>

                {{-- Dentist row --}}
                @if($appointment->dentist)
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-5 h-5 rounded-full bg-indigo-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-3 h-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <span class="text-xs text-slate-500 font-semibold">{{ $appointment->dentist->name }}</span>
                </div>
                @endif

                {{-- Divider --}}
                <div class="border-t border-slate-50 my-3"></div>

                {{-- Actions --}}
                <div class="flex items-center justify-end gap-2">
                    <a href="{{ route('clinic.appointments.show', $appointment) }}"
                       class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-slate-600 bg-slate-50 rounded-xl hover:bg-slate-100 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        View
                    </a>
                    <a href="{{ route('clinic.appointments.edit', ['appointment' => $appointment, 'iframe' => 1]) }}"
                       data-modal-url="{{ route('clinic.appointments.edit', ['appointment' => $appointment, 'iframe' => 1]) }}"
                       data-modal-title="Edit Appointment"
                       class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-indigo-700 bg-indigo-50 rounded-xl hover:bg-indigo-100 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit
                    </a>
                </div>
            </div>
        </div>

    @empty
        {{-- Empty state --}}
        <div class="col-span-full bg-white rounded-3xl border border-slate-100 shadow-sm text-center py-20 px-8">
            <div class="w-20 h-20 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-3xl flex items-center justify-center mx-auto mb-5">
                <svg class="w-10 h-10 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <h3 class="text-xl font-black text-slate-900 mb-2">No appointments yet</h3>
            <p class="text-slate-500 text-sm mb-6">Schedule the first appointment to get started.</p>
            <a href="{{ route('clinic.appointments.create', ['iframe' => 1]) }}"
               data-modal-url="{{ route('clinic.appointments.create', ['iframe' => 1]) }}"
               data-modal-title="Schedule Appointment"
               class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white text-sm font-bold rounded-2xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-100">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Schedule Appointment
            </a>
        </div>
    @endforelse

    {{-- ── Pagination ───────────────────────────────────────────────── --}}
    @if($appointments->hasPages())
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 px-5 py-3">
            {{ $appointments->links() }}
        </div>
    @endif

</div>

{{-- Override grid to 3 columns for the cards --}}
<style>
    /* Wrap the forelse output in a 3-col responsive grid */
    .space-y-6 > .group {
        /* cards live inside space-y-6; we override with grid on the parent */
    }
</style>

<script>
    // Wrap card elements in a CSS grid after render
    document.addEventListener('DOMContentLoaded', function () {
        var cards = document.querySelectorAll('.space-y-6 > .group');
        if (!cards.length) return;
        var grid = document.createElement('div');
        grid.style.cssText = 'display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:1rem;';
        cards[0].parentNode.insertBefore(grid, cards[0]);
        cards.forEach(function (c) { grid.appendChild(c); });
    });
</script>
@endsection