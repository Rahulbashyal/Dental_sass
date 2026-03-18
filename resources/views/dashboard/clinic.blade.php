@extends('layouts.app')

@section('content')
<div class="space-y-8 page-fade-in">
    <!-- Premium Welcome Section -->
    <div class="relative overflow-hidden bg-gradient-to-br from-indigo-900 via-blue-900 to-slate-900 rounded-3xl p-8 shadow-2xl border border-white/10">
        <div class="absolute top-0 right-0 -mt-20 -mr-20 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl"></div>
        
        <div class="relative flex flex-col lg:flex-row lg:items-center lg:justify-between">
            <div class="mb-6 lg:mb-0">
                <div class="flex items-center space-x-3 mb-4">
                    <span class="px-3 py-1 bg-blue-500/20 text-blue-300 text-xs font-bold uppercase tracking-wider rounded-full border border-blue-500/30">
                        Clinic Administration
                    </span>
                    <span class="px-3 py-1 bg-cyan-500/20 text-cyan-300 text-xs font-bold uppercase tracking-wider rounded-full border border-cyan-500/30">
                        Live System
                    </span>
                </div>
                <h1 class="text-3xl lg:text-5xl font-extrabold text-white mb-3">
                    Good morning, {{ auth()->user()->name }} 👋
                </h1>
                <p class="text-blue-100/80 text-lg lg:text-xl font-medium max-w-2xl">
                    Welcome back to <span class="text-white font-bold underline decoration-blue-500 underline-offset-4">{{ $clinic ? $clinic->name : 'Your Dental Clinic' }}</span>. 
                    Everything looks great today!
                </p>
            </div>
            <div class="flex flex-col sm:flex-row gap-4">
                <div class="flex items-center space-x-4 px-6 py-4 bg-white/10 backdrop-blur-md rounded-2xl border border-white/10 shadow-inner group transition-all hover:bg-white/15">
                    <div class="p-3 bg-blue-500 rounded-xl shadow-lg shadow-blue-500/30 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    @php
                        $nepaliDate = \App\Helpers\NepaliDateHelper::getCurrentNepaliDate();
                    @endphp
                    <div>
                        <p class="text-xs text-blue-200 font-bold uppercase tracking-tighter">आजको मिति</p>
                        <p class="text-white font-bold">{{ $nepaliDate['formatted'] ?? '२६ कार्तिक २०८२' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- AI Insights Section -->
    <div class="stagger-in" style="--delay: 0.5">
        <div class="flex items-center space-x-2 mb-4">
            <div class="p-2 bg-blue-100 rounded-lg">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                </svg>
            </div>
            <h2 class="text-xl font-black text-slate-800 tracking-tight uppercase text-xs tracking-widest">AI Clinic Intelligence</h2>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            @forelse($aiInsights as $insight)
            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-2 opacity-10 group-hover:scale-125 transition-transform">
                    <i class="fas fa-{{ $insight['icon'] }} text-4xl"></i>
                </div>
                <div class="flex items-start space-x-3">
                    <div class="mt-1">
                        @if($insight['type'] == 'success')
                            <span class="flex h-2 w-2 rounded-full bg-blue-500"></span>
                        @elseif($insight['type'] == 'warning')
                            <span class="flex h-2 w-2 rounded-full bg-cyan-500"></span>
                        @elseif($insight['type'] == 'danger')
                            <span class="flex h-2 w-2 rounded-full bg-indigo-500"></span>
                        @else
                            <span class="flex h-2 w-2 rounded-full bg-blue-500"></span>
                        @endif
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-900 mb-1">{{ $insight['title'] }}</h4>
                        <p class="text-xs text-slate-500 leading-relaxed">{{ $insight['message'] }}</p>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full py-4 text-center text-slate-400 text-sm italic">
                AI is analyzing your clinic data. Insights will appear here soon.
            </div>
            @endforelse
        </div>
    </div>

    <!-- Stats Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 stagger-in">
        <!-- Total Patients -->
        <div class="relative group bg-white p-6 rounded-2xl border border-slate-200 shadow-sm transition-all duration-300 hover:shadow-xl hover:-translate-y-1 hover:border-blue-300 overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-blue-50 -mr-12 -mt-12 rounded-full transition-transform group-hover:scale-150"></div>
            <div class="relative flex items-center justify-between">
                <div>
                    <p class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-2">Total Patients</p>
                    <p class="text-4xl font-black text-slate-900 leading-none mb-2">{{ $stats['total_patients'] }}</p>
                    <div class="flex items-center text-xs font-bold text-blue-600">
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd" /></svg>
                        +12.5% Inc
                    </div>
                </div>
                <div class="p-4 bg-gradient-to-br from-blue-500 to-blue-700 rounded-2xl shadow-lg shadow-blue-500/20 text-white">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Today's Appointments -->
        <div class="relative group bg-white p-6 rounded-2xl border border-slate-200 shadow-sm transition-all duration-300 hover:shadow-xl hover:-translate-y-1 hover:border-emerald-300 overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-emerald-50 -mr-12 -mt-12 rounded-full transition-transform group-hover:scale-150"></div>
            <div class="relative flex items-center justify-between">
                <div>
                    <p class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-2">Today's Visits</p>
                    <p class="text-4xl font-black text-slate-900 leading-none mb-2">{{ $stats['todays_appointments'] }}</p>
                    <div class="flex items-center text-xs font-bold text-sky-600">
                        <span class="w-2 h-2 rounded-full bg-sky-500 animate-pulse mr-2"></span>
                        On Schedule
                    </div>
                </div>
                <div class="p-4 bg-gradient-to-br from-sky-500 to-blue-600 rounded-2xl shadow-lg shadow-sky-500/20 text-white">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Monthly Revenue -->
        <div class="relative group bg-white p-6 rounded-2xl border border-slate-200 shadow-sm transition-all duration-300 hover:shadow-xl hover:-translate-y-1 hover:border-purple-300 overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-purple-50 -mr-12 -mt-12 rounded-full transition-transform group-hover:scale-150"></div>
            <div class="relative flex items-center justify-between">
                <div>
                    <p class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-2">Monthly Revenue</p>
                    <p class="text-3xl font-black text-slate-900 leading-none mb-2">NPR {{ number_format($stats['monthly_revenue'], 0) }}</p>
                    <div class="flex items-center text-xs font-bold text-blue-600">
                        Target: 85% Met
                    </div>
                </div>
                <div class="p-4 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-2xl shadow-lg shadow-blue-400/20 text-white">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Staff -->
        <div class="relative group bg-white p-6 rounded-2xl border border-slate-200 shadow-sm transition-all duration-300 hover:shadow-xl hover:-translate-y-1 hover:border-amber-300 overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-amber-50 -mr-12 -mt-12 rounded-full transition-transform group-hover:scale-150"></div>
            <div class="relative flex items-center justify-between">
                <div>
                    <p class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-2">Clinic Staff</p>
                    <p class="text-4xl font-black text-slate-900 leading-none mb-2">{{ $stats['total_staff'] ?? count($recentPatients) }}</p>
                    <div class="flex items-center text-xs font-bold text-cyan-600">
                        Active Members
                    </div>
                </div>
                <div class="p-4 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-2xl shadow-lg shadow-cyan-500/20 text-white">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Area: Two Columns -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        <!-- Left Column: Recent Appointments & Patients -->
        <div class="xl:col-span-2 space-y-8 stagger-in">
            <!-- Recent Appointments Table -->
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-200 flex items-center justify-between bg-slate-50/50">
                    <div>
                        <h2 class="text-xl font-bold text-slate-900">Recent Appointments</h2>
                        <p class="text-sm text-slate-500">Track the latest visits and upcoming patients.</p>
                    </div>
                    <a href="{{ route('clinic.appointments.index') }}" class="text-sm font-bold text-blue-600 hover:text-blue-800 transition-colors">View All</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-slate-400 text-xs font-bold uppercase tracking-widest bg-slate-50/30">
                                <th class="px-8 py-4">Patient</th>
                                <th class="px-8 py-4">Dentist</th>
                                <th class="px-8 py-4">Date & Time</th>
                                <th class="px-8 py-4 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($recentAppointments as $apt)
                            <tr class="hover:bg-slate-50 transition-colors group">
                                <td class="px-8 py-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold group-hover:scale-110 transition-transform">
                                            {{ substr($apt->patient->name ?? 'P', 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-slate-900">{{ $apt->patient->name ?? 'Unknown' }}</p>
                                            <p class="text-xs text-slate-500">ID: #{{ $apt->patient->id ?? '0' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-4 text-sm text-slate-600 font-medium italic">
                                    Dr. {{ $apt->dentist->name ?? 'Staff' }}
                                </td>
                                <td class="px-8 py-4 text-sm font-bold text-slate-700">
                                    {{ $apt->appointment_date ? \Carbon\Carbon::parse($apt->appointment_date)->format('M d, Y') : 'N/A' }}
                                    <span class="block text-xs font-normal text-slate-400 mt-1">{{ $apt->appointment_time ?? '' }}</span>
                                </td>
                                <td class="px-8 py-4 text-center">
                                    <span class="px-3 py-1 text-[10px] font-black uppercase tracking-tighter rounded-full border 
                                        {{ $apt->status === 'completed' ? 'bg-blue-50 text-blue-700 border-blue-200' : 'bg-sky-50 text-sky-700 border-sky-200' }}">
                                        {{ $apt->status ?? 'Scheduled' }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-8 py-16 text-center">
                                    <div class="mb-4">
                                        <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <p class="text-slate-500 font-medium">No appointments found.</p>
                                    <a href="{{ route('clinic.appointments.create', ['iframe' => 1]) }}" data-modal-url="{{ route('clinic.appointments.create', ['iframe' => 1]) }}" data-modal-title="Form" class="mt-4 inline-block text-blue-600 font-bold hover:underline">Schedule First Appointment</a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Recent Patients Grid -->
            <div>
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-black text-slate-900 tracking-tight">Recent Patients</h2>
                    <a href="{{ route('clinic.patients.index') }}" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-xl text-sm font-bold hover:bg-slate-200 transition-colors">See Directory</a>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($recentPatients as $patient)
                    <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm hover:shadow-lg transition-all border-l-4 border-l-blue-500 group">
                        <div class="flex items-start justify-between mb-4">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center text-white font-black text-lg shadow-lg group-hover:rotate-6 transition-transform">
                                {{ substr($patient->name, 0, 1) }}
                            </div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $patient->created_at->diffForHumans() }}</span>
                        </div>
                        <h3 class="text-lg font-extrabold text-slate-900 mb-1 truncate">{{ $patient->name }}</h3>
                        <p class="text-sm text-slate-500 mb-4">{{ $patient->phone ?? 'No contact info' }}</p>
                        <a href="{{ route('clinic.patients.show', $patient) }}" class="flex items-center text-sm font-bold text-blue-600 group-hover:translate-x-1 transition-transform">
                            View Profile
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </div>
                    @empty
                    <div class="col-span-full p-12 bg-white rounded-3xl border border-dashed border-slate-300 text-center">
                        <p class="text-slate-500 font-medium">No patients registered yet.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Right Column: Quick Actions & Local Context -->
        <div class="space-y-8 stagger-in">
            <!-- Quick Actions Grid -->
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-8">
                <h2 class="text-xl font-black text-slate-900 mb-6 tracking-tight">Quick Actions</h2>
                <div class="grid grid-cols-2 gap-4">
                    <a href="{{ route('clinic.patients.create', ['iframe' => 1]) }}" data-modal-url="{{ route('clinic.patients.create', ['iframe' => 1]) }}" data-modal-title="Add" class="flex flex-col items-center justify-center p-4 bg-blue-50 rounded-2xl border border-blue-100 group hover:bg-blue-100 transition-all">
                        <div class="p-3 bg-white rounded-xl shadow-sm text-blue-600 mb-2 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        </div>
                        <span class="text-xs font-bold text-blue-900 group-hover:text-blue-700">Add Patient</span>
                    </a>
                    <a href="{{ route('clinic.appointments.create', ['iframe' => 1]) }}" data-modal-url="{{ route('clinic.appointments.create', ['iframe' => 1]) }}" data-modal-title="New" class="flex flex-col items-center justify-center p-4 bg-sky-50 rounded-2xl border border-sky-100 group hover:bg-sky-100 transition-all">
                        <div class="p-3 bg-white rounded-xl shadow-sm text-sky-600 mb-2 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <span class="text-xs font-bold text-sky-900 group-hover:text-sky-700">New Visit</span>
                    </a>
                    <a href="{{ route('clinic.invoices.create', ['iframe' => 1]) }}" data-modal-url="{{ route('clinic.patients.create') }}" class="flex flex-col items-center justify-center p-4 bg-blue-50 rounded-2xl border border-blue-100 group hover:bg-blue-100 transition-all">
                        <div class="p-3 bg-white rounded-xl shadow-sm text-blue-600 mb-2 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        </div>
                        <span class="text-xs font-bold text-blue-900 group-hover:text-blue-700">Add Patient</span>
                    </a>
                    <a href="{{ route('clinic.appointments.create', ['iframe' => 1]) }}" data-modal-url="{{ route('clinic.appointments.create', ['iframe' => 1]) }}" data-modal-title="New" class="flex flex-col items-center justify-center p-4 bg-sky-50 rounded-2xl border border-sky-100 group hover:bg-sky-100 transition-all">
                        <div class="p-3 bg-white rounded-xl shadow-sm text-sky-600 mb-2 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <span class="text-xs font-bold text-sky-900 group-hover:text-sky-700">New Visit</span>
                    </a>
                    <a href="{{ route('clinic.invoices.create', ['iframe' => 1]) }}" data-modal-title="Bill Invoice
                    
                    
                        
                            
                        
                        Hire Staff" class="flex flex-col items-center justify-center p-4 bg-cyan-50 rounded-2xl border border-cyan-100 group hover:bg-cyan-100 transition-all">
                        <div class="p-3 bg-white rounded-xl shadow-sm text-cyan-600 mb-2 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        </div>
                        <span class="text-xs font-bold text-cyan-900 group-hover:text-cyan-700">Bill Invoice</span>
                    </a>
                    <a href="{{ route('clinic.staff.create', ['iframe' => 1]) }}" data-modal-url="{{ route('clinic.staff.create', ['iframe' => 1]) }}" data-modal-title="Form" class="flex flex-col items-center justify-center p-4 bg-indigo-50 rounded-2xl border border-indigo-100 group hover:bg-indigo-100 transition-all">
                        <div class="p-3 bg-white rounded-xl shadow-sm text-indigo-600 mb-2 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path></svg>
                        </div>
                        <span class="text-xs font-bold text-indigo-900 group-hover:text-indigo-700">Hire Staff</span>
                    </a>
                </div>
            </div>

            <!-- Nepal Market Features Upgrade -->
            <div class="bg-gradient-to-br from-slate-900 to-slate-800 rounded-3xl p-8 border border-white/5 shadow-2xl relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-32 h-32 bg-blue-500/10 rounded-full blur-2xl group-hover:bg-blue-500/20 transition-all"></div>
                <h2 class="text-xl font-black text-white mb-6 tracking-tight flex items-center">
                    <svg class="w-6 h-6 mr-2 text-blue-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 6a3 3 0 013-3h10a1 1 0 01.8 1.6L14.25 10l2.55 5.4A1 1 0 0116 17H6a3 3 0 01-3-3V6z" clip-rule="evenodd" /></svg>
                    Local Compliance
                </h2>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 bg-white/5 rounded-2xl border border-white/10 hover:bg-white/10 transition-colors">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-lg bg-blue-500/20 flex items-center justify-center text-blue-400 mr-3">
                                <span class="text-sm font-bold">रु</span>
                            </div>
                            <span class="text-sm font-bold text-slate-100">NPR Currency</span>
                        </div>
                        <span class="px-2 py-0.5 bg-blue-500/20 text-blue-400 text-[10px] font-black rounded uppercase tracking-tighter">Active</span>
                    </div>
                    <div class="flex items-center justify-between p-4 bg-white/5 rounded-2xl border border-white/10 hover:bg-white/10 transition-colors">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-lg bg-sky-500/20 flex items-center justify-center text-sky-400 mr-3">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
                            </div>
                            <span class="text-sm font-bold text-slate-100">SMS Gateway</span>
                        </div>
                        <span class="px-2 py-0.5 bg-sky-500/20 text-sky-400 text-[10px] font-black rounded uppercase tracking-tighter">Ready</span>
                    </div>
                    <div class="flex items-center justify-between p-4 bg-white/5 rounded-2xl border border-white/10 hover:bg-white/10 transition-colors">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-lg bg-cyan-500/20 flex items-center justify-center text-cyan-400 mr-3">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <span class="text-sm font-bold text-slate-100">Local Calendar</span>
                        </div>
                        <span class="px-2 py-0.5 bg-cyan-500/20 text-cyan-400 text-[10px] font-black rounded uppercase tracking-tighter">Syncing</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection