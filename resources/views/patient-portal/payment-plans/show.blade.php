@extends('patient-portal.layout')

@section('title', 'Amortization Details')

@section('content')
<div class="max-w-7xl mx-auto space-y-10 page-fade-in pb-20">
    @php
        $patient = Auth::guard('patient')->user();
        $clinic = $patient ? $patient->clinic : null;
        $clinicColor = $clinic && $clinic->primary_color ? $clinic->primary_color : '#0ea5e9';
    @endphp

    <!-- Breadcrumb -->
    <div class="flex items-center justify-between">
        <a href="{{ route('patient.payment-plans') }}" class="inline-flex items-center gap-2 text-xs font-black uppercase tracking-widest text-slate-400 hover:text-slate-900 transition-colors">
            <i class="fas fa-arrow-left text-[8px]"></i>
            Return to Deployment Hub
        </a>
    </div>

    <!-- Main Card -->
    <div class="bg-white rounded-[3rem] border border-slate-200 shadow-2xl overflow-hidden stagger-in">
        <div class="grid grid-cols-1 lg:grid-cols-12 min-h-[70vh]">
            <!-- Left Sidebar: Financial Analytics -->
            <div class="lg:col-span-4 bg-slate-50/50 p-12 lg:border-r border-slate-100 flex flex-col space-y-12">
                <div>
                    <h1 class="text-3xl font-black text-slate-900 tracking-tight leading-none mb-4 Outfit">Plan Analytics</h1>
                    <p class="text-xs font-black text-slate-400 leading-relaxed uppercase tracking-widest opacity-60">Serialized breakdown of procedural investments.</p>
                </div>

                <div class="space-y-8">
                    <div class="bg-white rounded-[2rem] border border-slate-200 p-8 shadow-sm group">
                        <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-2 flex items-center justify-between">
                        <p class="text-xs font-black text-slate-500 uppercase tracking-widest mb-2 flex items-center justify-between">
                            Total Net Value
                            <i class="fas fa-chart-pie text-slate-300 group-hover:rotate-180 transition-transform duration-700"></i>
                        </p>
                        <p class="text-3xl font-black text-slate-900 tracking-tight Outfit">NPR {{ number_format($paymentPlan->total_amount, 2) }}</p>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="bg-emerald-50 rounded-[1.5rem] border border-emerald-100 p-6">
                            <p class="text-xs font-black text-emerald-600 uppercase tracking-widest mb-1 flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Fulfilled
                            </p>
                            <p class="text-xl font-black text-emerald-800 Outfit">NPR {{ number_format($paymentPlan->total_amount - $paymentPlan->remaining_amount, 2) }}</p>
                        </div>
                        <div class="bg-rose-50 rounded-[1.5rem] border border-rose-100 p-6">
                            <p class="text-xs font-black text-rose-600 uppercase tracking-widest mb-1 flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span> Outstanding
                            </p>
                            <p class="text-xl font-black text-rose-800 Outfit">NPR {{ number_format($paymentPlan->remaining_amount, 2) }}</p>
                        </div>
                    </div>
                </div>

                <div class="pt-12 border-t border-slate-200 mt-auto">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-white border border-slate-100 flex items-center justify-center text-slate-400 shadow-sm">
                            <i class="fas fa-id-card text-sm"></i>
                        </div>
                        <div>
                            <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Protocol Group</p>
                            <p class="text-sm font-black text-slate-800 Outfit">{{ $paymentPlan->clinic->name }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Installment List -->
            <div class="lg:col-span-8 p-12 bg-white">
                <div class="flex items-center justify-between mb-12">
                    <h3 class="text-2xl font-black text-slate-900 tracking-tight Outfit">Amortization Sequence</h3>
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-slate-50 rounded-2xl border border-slate-100">
                        <span class="text-xs font-black text-slate-400 uppercase tracking-widest">Lifecycle:</span>
                        <span class="text-xs font-black uppercase Outfit" style="color: {{ $clinicColor }}">{{ $paymentPlan->status }}</span>
                    </div>
                </div>
                
                <div class="space-y-8 relative">
                    <!-- Global Timeline Line -->
                    <div class="absolute top-10 bottom-10 left-[2.5rem] w-px bg-slate-100 z-0 hidden md:block"></div>

                    @foreach($paymentPlan->paymentInstallments as $installment)
                    <div class="relative group z-10">
                        <div class="flex flex-col md:flex-row items-center p-8 rounded-[2.5rem] border-2 transition-all duration-500 gap-10 md:gap-14
                            {{ $installment->status == 'paid' ? 'bg-emerald-50/20 border-emerald-50' : 'bg-white border-slate-200 shadow-sm hover:shadow-xl hover:-translate-y-1' }}">
                            
                            <!-- Icon/Phase -->
                            <div class="w-12 h-12 rounded-[2.5rem] flex items-center justify-center flex-shrink-0 transition-all duration-500
                                {{ $installment->status == 'paid' ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-200' : 'bg-slate-50 text-slate-300 group-hover:text-white transition-all group-hover:scale-110' }}"
                                style="{{ $installment->status != 'paid' ? '--hover-bg: ' . $clinicColor : '' }}"
                                onmouseover="{{ $installment->status != 'paid' ? 'this.style.backgroundColor=getComputedStyle(this).getPropertyValue(\'--hover-bg\')' : '' }}"
                                onmouseout="{{ $installment->status != 'paid' ? 'this.style.backgroundColor=\'\'' : '' }}">
                                @if($installment->status == 'paid')
                                    <i class="fas fa-check text-base"></i>
                                @else
                                    <i class="fas fa-bolt text-base"></i>
                                @endif
                            </div>
                            
                            <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-8 text-center md:text-left">
                                <div>
                                    <p class="text-xs font-black text-slate-500 uppercase tracking-widest mb-1">Installment Node</p>
                                    <h4 class="text-xl font-black text-slate-900 Outfit">NPR {{ number_format($installment->amount, 2) }}</h4>
                                </div>
                                <div>
                                    <p class="text-xs font-black text-slate-500 uppercase tracking-widest mb-1">Temporal Deadline</p>
                                    <p class="text-sm font-black text-slate-900 Outfit">{{ $installment->due_date->format('M d, Y') }}</p>
                                </div>
                            </div>

                            <!-- CTA / Status -->
                            <div class="mt-6 md:mt-0 ml-0 md:ml-auto md:pl-8 flex items-center justify-center md:justify-end min-w-[150px]">
                                @if($installment->status == 'pending')
                                    <a href="{{ route('patient.payment', $paymentPlan->invoice_id) }}?installment_id={{ $installment->id }}" 
                                       class="px-6 py-3 rounded-xl text-xs font-black text-white uppercase tracking-widest shadow-lg transform active:scale-95 transition-all shadow-slate-100"
                                       style="background-color: {{ $clinicColor }}">
                                        Fulfill Node
                                    </a>
                                @else
                                    <div class="text-right flex flex-col items-center md:items-end">
                                        <span class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-lg text-xs font-black uppercase tracking-widest border border-emerald-100 shadow-sm">Fulfilled</span>
                                        <p class="text-xs text-slate-400 font-bold mt-2 Outfit">{{ $installment->payment_date ? $installment->payment_date->format('M d, Y') : 'Processed' }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
