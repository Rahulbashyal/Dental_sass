@extends('patient-portal.layout')

@section('title', 'Patient Dashboard')

@section('content')
<div class="max-w-7xl mx-auto space-y-10 page-fade-in pb-20">
    @php
        $patient = Auth::guard('patient')->user();
        $clinic = $patient ? $patient->clinic : null;
        $clinicColor = $clinic && $clinic->primary_color ? $clinic->primary_color : '#0ea5e9';
    @endphp

    <!-- Premium Welcome Header -->
    <div class="relative overflow-hidden rounded-[2.5rem] bg-slate-900 p-8 md:p-12 text-white shadow-2xl" 
         style="background: linear-gradient(135deg, #0f172a 0%, {{ $clinicColor }} 100%)">
        <div class="absolute top-0 right-0 w-1/3 h-full bg-gradient-to-l from-white/5 to-transparent pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-24 w-72 h-72 bg-white/5 rounded-full blur-3xl pointer-events-none"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
            <div class="space-y-4">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-white text-xs font-black uppercase tracking-widest border border-white/20">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    Patient Account Verified
                </div>
                <h1 class="text-3xl md:text-4xl font-black tracking-tight leading-tight Outfit">
                    Good Day, <br class="md:hidden">
                    <span class="opacity-90">{{ $patient->first_name }}!</span>
                </h1>
                <p class="text-white/70 text-lg font-medium max-w-md leading-relaxed">
                    Welcome to your clinical portal. You have {{ $stats['upcoming_appointments'] }} upcoming visits scheduled.
                </p>
            </div>
            
            <div class="flex flex-col items-center md:items-end gap-4">
                <div class="bg-white/10 backdrop-blur-xl rounded-3xl p-6 border border-white/10 shadow-inner flex flex-col items-center md:items-end w-full md:w-auto">
                    <p class="text-xs font-black text-white/60 uppercase tracking-widest mb-1">Unique Patient ID</p>
                    <p class="text-3xl font-black tracking-tighter text-white Outfit">{{ $patient->patient_id }}</p>
                </div>
                <div class="flex gap-2">
                    <span class="px-3 py-1 bg-white/10 text-white text-xs font-black uppercase border border-white/20 rounded-lg">Verified Session</span>
                    <span class="px-3 py-1 bg-white/10 text-white text-xs font-black uppercase border border-white/20 rounded-lg">Encrypted</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Analytics & Quick Stats -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 md:gap-6 stagger-in">
        <a href="{{ route('patient.appointments') }}" class="bg-white p-6 rounded-[2rem] border border-slate-200 shadow-sm group hover:scale-[1.02] transition-all duration-300">
            <div class="w-12 h-12 bg-slate-50 text-slate-400 rounded-2xl flex items-center justify-center group-hover:text-white transition-all duration-300" 
                 style="--hover-bg: {{ $clinicColor }}" onmouseover="this.style.backgroundColor=getComputedStyle(this).getPropertyValue('--hover-bg')" onmouseout="this.style.backgroundColor=''">
                <i class="fas fa-calendar-alt text-xl"></i>
            </div>
            <div class="mt-4">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Upcoming</p>
                <p class="text-2xl font-black text-slate-900 Outfit">{{ $stats['upcoming_appointments'] }} Visits</p>
            </div>
        </a>

        <a href="{{ route('patient.recurring-appointments') }}" class="bg-white p-6 rounded-[2rem] border border-slate-200 shadow-sm group hover:scale-[1.02] transition-all duration-300">
            <div class="w-12 h-12 bg-slate-50 text-slate-400 rounded-2xl flex items-center justify-center group-hover:text-white transition-all duration-300"
                 style="--hover-bg: {{ $clinicColor }}" onmouseover="this.style.backgroundColor=getComputedStyle(this).getPropertyValue('--hover-bg')" onmouseout="this.style.backgroundColor=''">
                <i class="fas fa-sync text-xl"></i>
            </div>
            <div class="mt-4">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Treatments</p>
                <p class="text-2xl font-black text-slate-900 Outfit">Timeline</p>
            </div>
        </a>

        <a href="{{ route('patient.payment-plans') }}" class="bg-white p-6 rounded-[2rem] border border-slate-200 shadow-sm group hover:scale-[1.02] transition-all duration-300">
            <div class="w-12 h-12 bg-slate-50 text-slate-400 rounded-2xl flex items-center justify-center group-hover:text-white transition-all duration-300"
                 style="--hover-bg: {{ $clinicColor }}" onmouseover="this.style.backgroundColor=getComputedStyle(this).getPropertyValue('--hover-bg')" onmouseout="this.style.backgroundColor=''">
                <i class="fas fa-credit-card text-xl"></i>
            </div>
            <div class="mt-4">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Active</p>
                <p class="text-2xl font-black text-slate-900 Outfit">{{ $stats['active_payment_plans'] }} Plans</p>
            </div>
        </a>

        <a href="{{ route('patient.consents') }}" class="bg-white p-6 rounded-[2rem] border border-slate-200 shadow-sm group hover:scale-[1.02] transition-all duration-300">
            <div class="w-12 h-12 {{ $stats['pending_consents'] > 0 ? 'bg-rose-50 text-rose-500' : 'bg-slate-50 text-slate-400' }} rounded-2xl flex items-center justify-center group-hover:text-white transition-all duration-300"
                 style="--hover-bg: {{ $clinicColor }}" onmouseover="this.style.backgroundColor=getComputedStyle(this).getPropertyValue('--hover-bg')" onmouseout="this.style.backgroundColor=''">
                <i class="fas fa-file-signature text-xl"></i>
            </div>
            <div class="mt-4">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Pending</p>
                <p class="text-2xl font-black {{ $stats['pending_consents'] > 0 ? 'text-rose-500' : 'text-slate-900' }} Outfit">{{ $stats['pending_consents'] }} Docs</p>
            </div>
        </a>

        <a href="{{ route('patient.invoices') }}" class="bg-white p-6 rounded-[2rem] border border-slate-200 shadow-sm group hover:scale-[1.02] transition-all duration-300 col-span-2 md:col-span-1">
            <div class="w-12 h-12 bg-slate-50 text-slate-400 rounded-2xl flex items-center justify-center group-hover:text-white transition-all duration-300"
                 style="--hover-bg: {{ $clinicColor }}" onmouseover="this.style.backgroundColor=getComputedStyle(this).getPropertyValue('--hover-bg')" onmouseout="this.style.backgroundColor=''">
                <i class="fas fa-wallet text-xl"></i>
            </div>
            <div class="mt-4">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Outstanding</p>
                <p class="text-xl font-black text-slate-900 truncate Outfit">NPR {{ number_format($stats['total_amount_due']) }}</p>
            </div>
        </a>
    </div>

    <!-- Content Blocks -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        <!-- Next Visit - Priority Block -->
        <div class="lg:col-span-2 space-y-6">
            <div class="flex items-center justify-between">
                <h3 class="text-2xl font-black text-slate-900 flex items-center gap-3 Outfit">
                    <span class="w-2 h-8 rounded-full" style="background-color: {{ $clinicColor }}"></span>
                    Clinical History
                </h3>
                <a href="{{ route('patient.appointments') }}" class="text-xs font-black text-slate-400 hover:text-slate-900 uppercase tracking-widest transition-colors flex items-center gap-2">
                    Timeline <i class="fas fa-arrow-right text-[10px]"></i>
                </a>
            </div>

            <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50/50 border-b border-slate-100">
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Procedural Item</th>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Temporal Status</th>
                                <th class="px-8 py-5 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest">Detail</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 bg-white">
                            @forelse($recentAppointments as $appointment)
                            <tr class="group hover:bg-slate-50/30 transition-colors">
                                <td class="px-8 py-6 whitespace-nowrap">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center text-slate-400 group-hover:bg-white group-hover:shadow-sm transition-all duration-300">
                                            <i class="fas fa-tooth"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-black text-slate-900 tracking-tight">{{ ucfirst($appointment->type) }}</p>
                                            <p class="text-xs font-bold text-slate-400 uppercase">{{ $appointment->clinic->name }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6 whitespace-nowrap text-sm text-slate-500 font-medium">
                                    <div class="flex flex-col">
                                        <p class="text-sm font-black text-slate-800 Outfit">{{ $appointment->appointment_date->format('M d, Y') }}</p>
                                        <p class="text-[10px] text-slate-400 font-bold uppercase">{{ $appointment->appointment_time }}</p>
                                    </div>
                                </td>
                                <td class="px-8 py-6 whitespace-nowrap text-right">
                                    <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-widest border
                                        @if($appointment->status === 'completed') bg-emerald-50 text-emerald-600 border-emerald-100
                                        @elseif($appointment->status === 'scheduled') bg-sky-50 text-sky-600 border-sky-100
                                        @elseif($appointment->status === 'confirmed') bg-indigo-50 text-indigo-600 border-indigo-100
                                        @elseif($appointment->status === 'cancelled') bg-rose-50 text-rose-600 border-rose-100
                                        @else bg-slate-50 text-slate-500 border-slate-100 @endif">
                                        {{ str_replace('_', ' ', $appointment->status) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-8 py-24 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-6">
                                            <i class="fas fa-calendar-alt text-3xl text-slate-200"></i>
                                        </div>
                                        <h3 class="text-xl font-black text-slate-900 Outfit">No record found</h3>
                                        <p class="text-slate-500 max-w-xs mx-auto text-sm mt-1 font-medium">Your procedural timeline will populate here as you complete visits.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sidebar Section -->
        <div class="space-y-10">
            <!-- Profile Info Card -->
            <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-sm overflow-hidden transition-all duration-300 hover:shadow-xl hover:shadow-slate-200/50">
                <div class="h-24" style="background-color: {{ $clinicColor }}"></div>
                <div class="px-8 pb-8 -mt-12">
                    <div class="flex flex-col items-center">
                        <div class="w-24 h-24 rounded-[2rem] bg-white p-1 shadow-xl">
                            <div class="w-full h-full rounded-[1.8rem] bg-slate-50 flex items-center justify-center text-slate-400 text-3xl font-black border border-slate-100 Outfit">
                                {{ substr($patient->first_name, 0, 1) }}
                            </div>
                        </div>
                        <div class="text-center mt-6">
                            <h4 class="text-2xl font-black text-slate-900 tracking-tight Outfit">{{ $patient->full_name }}</h4>
                            <p class="text-sm text-slate-400 font-semibold tracking-tighter">{{ $patient->email }}</p>
                        </div>
                    </div>

                    <div class="mt-10 space-y-4">
                        <div class="flex items-center gap-4 bg-slate-50/50 p-4 rounded-2xl border border-slate-100 hover:bg-white hover:shadow-md transition-all">
                            <i class="fas fa-phone-alt text-slate-300 text-sm"></i>
                            <div>
                                <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Mobile Contact</p>
                                <p class="text-sm font-black text-slate-800 tracking-tight">{{ $patient->phone ?? 'Not Linked' }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-4 bg-slate-50/50 p-4 rounded-2xl border border-slate-100 hover:bg-white hover:shadow-md transition-all">
                            <i class="fas fa-id-card text-slate-300 text-sm"></i>
                            <div>
                                <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Patient File</p>
                                <p class="text-sm font-black text-slate-800 tracking-tight">{{ $patient->patient_id }}</p>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('patient.profile') }}" class="w-full py-4 mt-10 rounded-2xl text-xs font-black text-white uppercase tracking-widest flex items-center justify-center gap-3 shadow-lg shadow-sky-100 transform active:scale-95 transition-all"
                       style="background-color: {{ $clinicColor }}">
                        Account Management
                        <i class="fas fa-shield-alt text-sm"></i>
                    </a>
                </div>
            </div>

            <!-- Billing Highlight -->
            @if($stats['pending_invoices'] > 0)
            <div class="bg-slate-900 rounded-[2.5rem] p-8 text-white relative overflow-hidden group">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/5 rounded-full blur-2xl group-hover:scale-125 transition-all duration-700"></div>
                <div class="relative z-10 flex flex-col gap-6">
                    <div class="w-14 h-14 bg-white/10 rounded-2xl flex items-center justify-center border border-white/20">
                        <i class="fas fa-wallet text-2xl" style="color: {{ $clinicColor }}"></i>
                    </div>
                    <div>
                        <p class="text-xs font-black text-white/40 uppercase tracking-widest">Payment Due</p>
                        <p class="text-3xl font-black Outfit">NPR {{ number_format($stats['total_amount_due']) }}</p>
                        <p class="text-xs text-white/60 mt-2 font-medium">Resolution required for {{ $stats['pending_invoices'] }} invoices</p>
                    </div>
                    <a href="{{ route('patient.invoices') }}" class="w-fit px-6 py-3 bg-white text-slate-900 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-opacity-90 active:scale-95 transition-all">
                        Initiate Fulfullment
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
