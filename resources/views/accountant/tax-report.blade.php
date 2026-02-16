@extends('layouts.app')

@section('title', 'Tax Compliance Report - ' . config('app.name'))

@section('page-title', 'Financial Reporting')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
    <!-- Header -->
    <div class="bg-white p-8 rounded-4xl shadow-sm border border-slate-100 flex flex-col md:flex-row justify-between items-center gap-6">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-3xl bg-slate-900 flex items-center justify-center text-white shadow-xl">
                <i class="fas fa-building-columns text-xl"></i>
            </div>
            <div>
                <h2 class="text-2xl font-black text-slate-900 tracking-tight">Tax Report {{ $year }}</h2>
                <p class="text-sm text-slate-400 font-medium">Quarterly taxation summary and liability tracking</p>
            </div>
        </div>
        <form action="{{ route('clinic.tax-report') }}" method="GET">
            <select name="year" onchange="this.form.submit()" class="px-6 py-3 bg-slate-50 border-2 border-slate-100 rounded-2xl text-xs font-black uppercase tracking-widest focus:border-blue-500 focus:outline-none transition-all">
                @for($y = now()->year; $y >= now()->year - 5; $y--)
                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>Fiscal Year {{ $y }}</option>
                @endfor
            </select>
        </form>
    </div>

    <!-- Quarterly Summary Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        @foreach($quarterlyData as $quarter => $data)
            <div class="bg-white rounded-4xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-300">
                <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/30 flex justify-between items-center">
                    <h3 class="text-xs font-black text-slate-900 uppercase tracking-widest">Quarter 0{{ $quarter }}</h3>
                    <span class="px-2 py-1 bg-white text-[9px] font-black text-slate-400 border border-slate-100 rounded-lg uppercase">Jul - Sep</span>
                </div>
                <div class="p-8 space-y-6">
                    <div class="flex justify-between items-end">
                        <div>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Revenue</p>
                            <h4 class="text-xl font-black text-slate-900 tracking-tight">NRs. {{ number_format($data['revenue'], 2) }}</h4>
                        </div>
                        <div class="text-right">
                            <p class="text-[9px] font-black text-rose-400 uppercase tracking-widest mb-1">Tax Liability (13%)</p>
                            <h4 class="text-xl font-black text-rose-600 tracking-tight">NRs. {{ number_format($data['tax_due'], 2) }}</h4>
                        </div>
                    </div>
                    
                    <div class="w-full h-1.5 bg-slate-50 rounded-full overflow-hidden">
                        <div class="h-full bg-slate-900 opacity-20" style="width: 100%"></div>
                    </div>
                    
                    <div class="flex justify-between items-center opacity-40">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-check-circle text-xs text-emerald-500"></i>
                            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-tighter">Drafted</span>
                        </div>
                        <span class="text-[10px] font-black text-slate-400 uppercase italic">Pending Filing</span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Yearly Projection Footer -->
    <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 p-10 rounded-4xl text-white shadow-2xl relative overflow-hidden ring-8 ring-slate-50">
        <div class="absolute right-0 top-0 p-10 opacity-10">
            <i class="fas fa-calculator text-9xl"></i>
        </div>
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-8">
            <div class="space-y-2">
                <p class="text-[10px] font-black text-white/50 uppercase tracking-widest">Annual Aggregated Totals</p>
                <h3 class="text-3xl font-black tracking-tighter">Fiscal Performance Summary</h3>
            </div>
            <div class="grid grid-cols-2 gap-12">
                <div class="text-right">
                    <p class="text-[10px] font-black text-white/40 uppercase tracking-widest mb-1">Total Annual Revenue</p>
                    <p class="text-2xl font-black tracking-tighter">NRs. {{ number_format(collect($quarterlyData)->sum('revenue'), 2) }}</p>
                </div>
                <div class="text-right">
                    <p class="text-[10px] font-black text-rose-400 uppercase tracking-widest mb-1">Total Tax Provision</p>
                    <p class="text-2xl font-black text-rose-400 tracking-tighter">NRs. {{ number_format(collect($quarterlyData)->sum('tax_due'), 2) }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .rounded-4xl { border-radius: 2.5rem; }
</style>
@endsection
