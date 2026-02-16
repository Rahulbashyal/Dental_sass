@extends('patient-portal.layout')

@section('title', 'Structured Payments')

@section('content')
<div class="max-w-7xl mx-auto space-y-10 page-fade-in pb-20">
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
                Payment Plans
            </h1>
            <p class="text-slate-500 font-medium">Structured installment pathways for your long-term health investments.</p>
        </div>
        
        <div class="bg-white px-6 py-4 rounded-[1.5rem] border border-slate-200 shadow-sm flex items-center gap-4 w-full md:w-auto">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white shadow-lg shadow-sky-100" style="background-color: {{ $clinicColor }}">
                <i class="fas fa-layer-group text-sm"></i>
            </div>
            <div>
                <p class="text-xs font-black text-slate-500 uppercase tracking-widest">Global Balance</p>
                <p class="text-lg font-black text-slate-900 Outfit">NPR {{ number_format($paymentPlans->sum('remaining_amount'), 2) }}</p>
            </div>
        </div>
    </div>

    @if($paymentPlans->isEmpty())
        <div class="bg-white rounded-[2.5rem] p-24 text-center border border-slate-200 shadow-sm stagger-in">
            <div class="w-20 h-20 bg-slate-50 rounded-[2rem] flex items-center justify-center mx-auto mb-8 text-slate-200">
                <i class="fas fa-money-check-dollar text-3xl"></i>
            </div>
            <h3 class="text-2xl font-black text-slate-900 Outfit">Zero installment records</h3>
            <p class="text-slate-500 max-w-sm mx-auto mt-2 font-medium">You have no active payment plans. Talk to our administrative team at the clinic to structure your procedural costs.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10 stagger-in">
            @foreach($paymentPlans as $plan)
            <div class="group bg-white rounded-[2.5rem] border border-slate-200 shadow-sm hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-500 overflow-hidden relative p-8">
                <!-- Status Badge -->
                <div class="flex justify-between items-start mb-8 relative z-10">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black uppercase tracking-widest border shadow-sm
                        @if($plan->status === 'active') bg-emerald-50 text-emerald-600 border-emerald-100
                        @elseif($plan->status === 'completed') bg-sky-50 text-sky-600 border-sky-100
                        @else bg-slate-50 text-slate-500 border-slate-100 @endif">
                        <span class="w-1.5 h-1.5 rounded-full {{ $plan->status === 'active' ? 'bg-emerald-600 animate-pulse' : 'bg-slate-400' }}"></span>
                        {{ $plan->status }}
                    </span>
                    <p class="text-xs text-slate-500 font-bold uppercase tracking-widest">REF: #{{ $plan->id }}</p>
                </div>

                <!-- Core Value -->
                <div class="space-y-1 relative z-10">
                    <p class="text-xs font-black text-slate-500 uppercase tracking-widest">Total Plan Value</p>
                    <h3 class="text-3xl font-black text-slate-900 tracking-tight Outfit">NPR {{ number_format($plan->total_amount, 2) }}</h3>
                </div>

                <div class="mt-8 space-y-6 relative z-10">
                    <div class="flex justify-between items-end">
                        <div class="space-y-1">
                            <p class="text-xs font-black text-slate-500 uppercase tracking-widest">Monthly Cycle</p>
                            <p class="text-base font-black text-slate-800 Outfit">NPR {{ number_format($plan->installment_amount ?? 0, 2) }}</p>
                        </div>
                        <div class="text-right space-y-1">
                            <p class="text-xs font-black text-slate-500 uppercase tracking-widest">Residual</p>
                            <p class="text-base font-black text-rose-500 Outfit">NPR {{ number_format($plan->remaining_amount, 2) }}</p>
                        </div>
                    </div>
                    
                    <!-- Progress Interaction -->
                    @php
                        $progress = (($plan->total_amount - $plan->remaining_amount) / $plan->total_amount) * 100;
                    @endphp
                    <div class="space-y-2">
                        <div class="w-full bg-slate-50 rounded-full h-2 overflow-hidden border border-slate-100">
                            <div class="h-full rounded-full transition-all duration-1000 shadow-lg" 
                                 style="width: {{ $progress }}%; background-color: {{ $clinicColor }}"></div>
                        </div>
                        <div class="flex justify-between items-center">
                            <p class="text-xs font-black text-slate-500 uppercase tracking-widest">Amortization Progress</p>
                            <p class="text-xs font-black Outfit" style="color: {{ $clinicColor }}">{{ round($progress) }}%</p>
                        </div>
                    </div>
                </div>

                <div class="mt-10 relative z-10">
                    <a href="{{ route('patient.payment-plans.show', $plan) }}" 
                       class="w-full py-4 text-white rounded-2xl text-xs font-black uppercase tracking-widest flex items-center justify-center gap-3 shadow-lg active:scale-95 transition-all shadow-slate-100 group-hover:shadow-xl"
                       style="background-color: {{ $clinicColor }}">
                        View Schedule
                        <i class="fas fa-arrow-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        @if($paymentPlans->hasPages())
        <div class="mt-10">
            {{ $paymentPlans->links() }}
        </div>
        @endif
    @endif
</div>
@endsection
