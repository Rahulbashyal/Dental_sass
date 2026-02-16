@extends('patient-portal.layout')

@section('title', 'Clinical Timeline')

@section('content')
<div class="max-w-6xl mx-auto space-y-10 page-fade-in pb-20">
    @php
        $patient = Auth::guard('patient')->user();
        $clinic = $patient ? $patient->clinic : null;
        $clinicColor = $clinic && $clinic->primary_color ? $clinic->primary_color : '#0ea5e9';
    @endphp

    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div class="space-y-1">
            <h1 class="text-3xl font-black text-slate-900 tracking-tight flex items-center gap-4 Outfit">
                <span class="w-2 h-8 rounded-full" style="background-color: {{ $clinicColor }}"></span>
                Clinical Timeline
            </h1>
            <p class="text-slate-500 font-medium">Tracking your oral health journey and upcoming procedural goals.</p>
        </div>

        <div class="flex items-center gap-3 w-full md:w-auto">
            <div class="bg-white px-6 py-4 rounded-[1.5rem] border border-slate-200 shadow-sm flex-1 md:flex-none">
                <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Total Records</p>
                <p class="text-xl font-black text-slate-900 Outfit">{{ $appointments->total() }}</p>
            </div>
            <button onclick="window.print()" class="w-14 h-14 bg-slate-900 text-white rounded-[1.2rem] flex items-center justify-center hover:bg-slate-800 transition-all shadow-lg active:scale-95">
                <i class="fas fa-print text-sm"></i>
            </button>
        </div>
    </div>

    <!-- Timeline Grid -->
    <div class="space-y-6 stagger-in">
        @forelse($appointments as $appointment)
        <div class="group relative bg-white rounded-[2.5rem] border border-slate-200 shadow-sm hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-500 overflow-hidden">
            <!-- Decorative Color Accent -->
            <div class="absolute top-0 left-0 bottom-0 w-2 
                @if($appointment->status === 'completed') bg-emerald-500
                @elseif($appointment->status === 'scheduled' || $appointment->status === 'confirmed') bg-sky-500
                @elseif($appointment->status === 'cancelled') bg-rose-500
                @else bg-slate-300 @endif">
            </div>

            <div class="flex flex-col lg:flex-row">
                <!-- Date/Time Panel -->
                <div class="lg:w-72 bg-slate-50/50 p-8 flex flex-col justify-center border-b lg:border-b-0 lg:border-r border-slate-100">
                    <div class="text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Scheduled Node</div>
                    <p class="text-2xl font-black text-slate-900 Outfit leading-tight">{{ $appointment->appointment_date->format('l') }}</p>
                    <p class="text-xl font-bold Outfit" style="color: {{ $clinicColor }}">{{ $appointment->appointment_date->format('M d, Y') }}</p>
                    
                    <div class="flex items-center gap-2 mt-6 p-2 px-3 bg-white rounded-xl border border-slate-100 w-fit shadow-sm">
                        <i class="fas fa-clock text-[10px] text-slate-300"></i>
                        <span class="text-xs font-black text-slate-700 uppercase tracking-tighter">{{ $appointment->appointment_time }}</span>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="flex-1 p-8 flex flex-col md:flex-row justify-between gap-8">
                    <div class="space-y-6">
                        <div class="flex items-start gap-5">
                            <div class="w-16 h-16 bg-slate-50 rounded-[1.5rem] flex items-center justify-center text-slate-400 group-hover:bg-white group-hover:shadow-lg transition-all duration-500 border border-transparent group-hover:border-slate-100">
                                <i class="fas fa-tooth text-2xl"></i>
                            </div>
                            <div>
                                <div class="flex items-center flex-wrap gap-3 mb-2">
                                    <h3 class="text-2xl font-black text-slate-900 Outfit">{{ ucfirst($appointment->type) }}</h3>
                                    <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-widest border shadow-sm
                                        @if($appointment->status === 'completed') bg-emerald-50 text-emerald-600 border-emerald-100
                                        @elseif($appointment->status === 'scheduled') bg-sky-50 text-sky-600 border-sky-100
                                        @elseif($appointment->status === 'confirmed') bg-indigo-50 text-indigo-600 border-indigo-100
                                        @elseif($appointment->status === 'cancelled') bg-rose-50 text-rose-600 border-rose-100
                                        @else bg-slate-50 text-slate-500 border-slate-100 @endif">
                                        {{ str_replace('_', ' ', $appointment->status) }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-3 text-slate-500">
                                    <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider">
                                        <i class="fas fa-hospital-alt text-slate-300 text-[10px]"></i>
                                        {{ $appointment->clinic->name }}
                                    </div>
                                    <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                                    <div class="text-xs font-bold text-slate-400">ID: #{{ $appointment->id }}</div>
                                </div>
                            </div>
                        </div>

                        @if($appointment->notes)
                        <div class="bg-slate-50/50 rounded-2xl p-5 border border-slate-100 group-hover:bg-white transition-all">
                            <p class="text-sm text-slate-600 font-medium leading-relaxed italic">
                                <i class="fas fa-quote-left text-slate-200 mr-2 text-xs"></i>
                                {{ $appointment->notes }}
                            </p>
                        </div>
                        @else
                        <div class="text-xs text-slate-400 font-medium italic opacity-60">No procedural notes established for this session.</div>
                        @endif
                    </div>

                    <!-- Financial Detail & Actions -->
                    <div class="flex flex-col justify-between items-end gap-6 min-w-[200px]">
                        <div class="text-right bg-slate-50/50 p-4 rounded-2xl border border-slate-100 w-full md:w-auto group-hover:bg-white transition-all">
                            <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Expected Fee</p>
                            <p class="text-2xl font-black text-slate-900 Outfit">NPR {{ number_format($appointment->treatment_cost ?? 0, 2) }}</p>
                        </div>
                        
                        <div class="flex gap-3 w-full md:w-auto">
                            @if($appointment->status === 'scheduled' || $appointment->status === 'confirmed')
                                <button onclick="alert('Please contact the clinic directly for scheduling adjustments.')" class="flex-1 md:flex-none px-6 py-3 rounded-xl border border-slate-200 text-xs font-black uppercase tracking-widest text-slate-600 hover:bg-slate-50 transition-all active:scale-95 shadow-sm">
                                    Reschedule
                                </button>
                            @endif
                            <button onclick="window.print()" class="flex-1 md:flex-none px-6 py-3 rounded-xl text-xs font-black uppercase tracking-widest text-white transition-all shadow-lg active:scale-95 shadow-slate-100" 
                                    style="background-color: {{ $clinicColor }}">
                                Records
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-[2.5rem] p-24 text-center border border-slate-200 shadow-sm">
            <div class="w-20 h-20 bg-slate-50 rounded-[2rem] flex items-center justify-center mx-auto mb-8 text-slate-200">
                <i class="fas fa-calendar-times text-3xl"></i>
            </div>
            <h3 class="text-2xl font-black text-slate-900 Outfit">Timeline Inactive</h3>
            <p class="text-slate-500 max-w-sm mx-auto mt-2 font-medium">Your procedural history and upcoming sessions will map here automatically as you engage with our clinical team.</p>
            <a href="{{ route('clinic.book', $clinic->slug ?? '') }}" class="mt-10 inline-block px-10 py-5 rounded-2xl text-xs font-black text-white uppercase tracking-widest shadow-lg shadow-sky-100" style="background-color: {{ $clinicColor }}">
                Map Initial Session
            </a>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($appointments->hasPages())
    <div class="mt-10">
        {{ $appointments->links() }}
    </div>
    @endif

    <!-- Trust Footer -->
    <div class="flex items-center justify-center gap-6 text-slate-400 pt-10 opacity-60">
        <div class="flex items-center gap-2">
            <i class="fas fa-calendar-check text-xs"></i>
            <span class="text-xs font-black uppercase tracking-widest">Real-Time Sync</span>
        </div>
        <div class="w-1.5 h-1.5 rounded-full bg-slate-300"></div>
        <div class="flex items-center gap-2">
            <i class="fas fa-dna text-xs"></i>
            <span class="text-xs font-black uppercase tracking-widest">Clinical Integrity Protocol</span>
        </div>
    </div>
</div>
@endsection
