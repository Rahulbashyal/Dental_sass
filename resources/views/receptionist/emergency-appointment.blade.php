@extends('layouts.app')

@section('page-title', 'Emergency SOS Booking')

@section('content')
<div class="page-fade-in max-w-4xl mx-auto space-y-8 pb-12">
    <!-- Header -->
    <div class="text-center stagger-in">
        <div class="w-20 h-20 bg-red-100 text-red-600 rounded-[2rem] flex items-center justify-center mx-auto mb-6 shadow-xl shadow-red-100/50">
            <i class="fas fa-ambulance text-3xl"></i>
        </div>
        <h1 class="text-4xl font-black text-slate-900 tracking-tight mb-2">Emergency SOS Portal</h1>
        <p class="text-slate-500 font-medium max-w-md mx-auto">Rapidly bypass the standard schedule to secure immediate care for acute clinical cases.</p>
    </div>

    <!-- SOS Form -->
    <div class="stagger-in bg-white rounded-[3rem] p-12 border-2 border-red-50 shadow-2xl shadow-red-100/20" style="--delay: 1">
        <form action="{{ route('clinic.emergency-appointment') }}" method="POST" class="space-y-8">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Patient Selection -->
                <div class="space-y-3">
                    <label class="block text-[10px] font-black text-slate-400 border-b border-slate-100 pb-2 uppercase tracking-[0.2em]">Priority Patient Identity</label>
                    <div class="relative group">
                        <select name="patient_id" required class="w-full bg-slate-50 border-2 border-transparent group-hover:bg-white group-hover:border-red-100 rounded-3xl px-6 py-5 font-black text-slate-900 focus:outline-none focus:ring-4 focus:ring-red-50 transition-all appearance-none">
                            <option value="">— Find Patient —</option>
                            @foreach(\App\Models\Patient::where('clinic_id', auth()->user()->clinic_id)->orderBy('first_name')->get() as $p)
                            <option value="{{ $p->id }}">{{ $p->first_name }} {{ $p->last_name }} (PID: {{ $p->id }})</option>
                            @endforeach
                        </select>
                        <i class="fas fa-chevron-down absolute right-6 top-1/2 -translate-y-1/2 text-slate-300 pointer-events-none"></i>
                    </div>
                </div>

                <!-- Dentist Assignment -->
                <div class="space-y-3">
                    <label class="block text-[10px] font-black text-slate-400 border-b border-slate-100 pb-2 uppercase tracking-[0.2em]">Assigned Responding Clinician</label>
                    <div class="relative group">
                        <select name="dentist_id" required class="w-full bg-slate-50 border-2 border-transparent group-hover:bg-white group-hover:border-red-100 rounded-3xl px-6 py-5 font-black text-slate-900 focus:outline-none focus:ring-4 focus:ring-red-50 transition-all appearance-none">
                            <option value="">— Select Dentist —</option>
                            @foreach(\App\Models\User::role('dentist')->where('clinic_id', auth()->user()->clinic_id)->get() as $d)
                            <option value="{{ $d->id }}">Dr. {{ $d->name }}</option>
                            @endforeach
                        </select>
                        <i class="fas fa-chevron-down absolute right-6 top-1/2 -translate-y-1/2 text-slate-300 pointer-events-none"></i>
                    </div>
                </div>
            </div>

            <!-- Clinical Triage Notes -->
            <div class="space-y-3">
                <label class="block text-[10px] font-black text-slate-400 border-b border-slate-100 pb-2 uppercase tracking-[0.2em]">Acute Triage Notes</label>
                <textarea name="notes" rows="4" required placeholder="Describe the acute condition (e.g., severe trauma, pulpitis, abscess)..."
                    class="w-full bg-slate-50 border-2 border-transparent hover:bg-white hover:border-red-100 rounded-[2rem] px-8 py-6 font-medium text-slate-900 focus:outline-none focus:ring-4 focus:ring-red-50 transition-all resize-none"></textarea>
            </div>

            <!-- SOS Execution Button -->
            <button type="submit" class="w-full py-6 bg-red-600 text-white rounded-3xl font-black text-lg uppercase tracking-widest shadow-2xl shadow-red-200 hover:bg-red-700 hover:-translate-y-1 active:scale-[0.98] transition-all flex items-center justify-center gap-4">
                <i class="fas fa-bolt text-xl"></i>
                Execute Emergency Booking
            </button>
        </form>
    </div>

    <!-- Quick Info -->
    <div class="stagger-in grid grid-cols-1 md:grid-cols-2 gap-6" style="--delay: 2">
        <div class="bg-blue-50/50 p-6 rounded-[2rem] border border-blue-100 flex items-start gap-4">
            <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600 shrink-0">
                <i class="fas fa-info-circle"></i>
            </div>
            <div>
                <p class="text-sm font-bold text-blue-900 mb-1">Instant Activation</p>
                <p class="text-xs text-blue-600 font-medium">This booking will automatically set the status to 'Confirmed' and the time to 'Current System Time'.</p>
            </div>
        </div>
        <div class="bg-emerald-50/50 p-6 rounded-[2rem] border border-emerald-100 flex items-start gap-4">
            <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center text-emerald-600 shrink-0">
                <i class="fas fa-check-double"></i>
            </div>
            <div>
                <p class="text-sm font-bold text-emerald-900 mb-1">Dashboard Alert</p>
                <p class="text-xs text-emerald-600 font-medium">The assigned responding clinician will receive an immediate notification on their live dashboard.</p>
            </div>
        </div>
    </div>
</div>
@endsection
