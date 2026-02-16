@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">Appointment Calendar</h2>
            <p class="text-sm text-slate-500">Visual overview of clinic chair availability and dental schedules.</p>
        </div>
        <div class="mt-4 md:mt-0 flex items-center space-x-3">
            <a href="{{ route('clinic.appointments.index') }}" class="p-2 bg-white border border-slate-200 rounded-lg text-slate-600 hover:bg-slate-50 transition-colors">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                </svg>
            </a>
            <a href="{{ route('clinic.appointments.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                New Appointment
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Sidebar: Filters & Mini Calendar -->
        <div class="space-y-6">
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">View Filters</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1 uppercase tracking-tight">Select Branch</label>
                        <select id="branch-filter" class="w-full rounded-lg border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">All Branches</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="bg-indigo-900 rounded-2xl p-6 text-white shadow-xl">
                <h4 class="text-sm font-bold mb-2">Quick Tip</h4>
                <p class="text-indigo-200 text-xs leading-relaxed">Drag and drop appointments to reschedule, or click any empty slot to create a new one instantly.</p>
            </div>
        </div>

        <!-- Main Calendar -->
        <div class="lg:col-span-3">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 overflow-hidden">
                <div id="calendar" class="min-h-[700px]"></div>
            </div>
        </div>
    </div>
</div>

<!-- Styles for FullCalendar consistency -->
<style>
    .fc { font-family: inherit; }
    .fc-header-toolbar { padding: 1rem; }
    .fc-toolbar-title { font-size: 1.25rem !important; font-weight: 800 !important; color: #0f172a !important; }
    .fc-button-primary { background-color: #f8fafc !important; border-color: #e2e8f0 !important; color: #475569 !important; font-weight: 600 !important; font-size: 0.875rem !important; text-transform: capitalize !important; }
    .fc-button-primary:hover { background-color: #f1f5f9 !important; border-color: #cbd5e1 !important; }
    .fc-button-active { background-color: #4f46e5 !important; border-color: #4f46e5 !important; color: white !important; }
    .fc-day-today { background-color: #f8fafc !important; }
    .fc-event { border-radius: 6px !important; padding: 2px 4px !important; border: none !important; }
    .fc-v-event { background-color: #4f46e5 !important; }
</style>

<!-- FullCalendar Dependencies -->
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('calendar');
        const branchFilter = document.getElementById('branch-filter');
        
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'timeGridWeek',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            slotMinTime: '08:00:00',
            slotMaxTime: '20:00:00',
            allDaySlot: false,
            height: 'auto',
            nowIndicator: true,
            events: {
                url: '{{ route("appointments.events") }}',
                extraParams: function() {
                    return {
                        branch_id: branchFilter.value
                    };
                }
            },
            dateClick: function(info) {
                const date = info.dateStr.split('T')[0];
                const time = info.dateStr.split('T')[1]?.substring(0,5) || '';
                window.location.href = `{{ route('clinic.appointments.create') }}?appointment_date=${date}&appointment_time=${time}&branch_id=${branchFilter.value}`;
            },
            eventClick: function(info) {
                // For now, redirect to patient profile or show a simple alert
                alert('Appointment: ' + info.event.title + '\nDentist: ' + info.event.extendedProps.dentist);
            }
        });
        
        calendar.render();

        branchFilter.addEventListener('change', function() {
            calendar.refetchEvents();
        });
    });
</script>
@endsection
