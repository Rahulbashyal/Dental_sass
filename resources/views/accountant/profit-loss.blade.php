@extends('layouts.app')

@section('title', 'Profit & Loss Statement - ' . config('app.name'))

@section('page-title', 'Profitability Analytics')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
    <!-- Period Selector -->
    <div class="bg-white p-6 rounded-4xl shadow-sm border border-slate-100 flex flex-col md:flex-row justify-between items-center gap-6">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600">
                <i class="fas fa-chart-line"></i>
            </div>
            <div>
                <h3 class="text-sm font-black text-slate-900 uppercase tracking-tight">Statement Period</h3>
                <p class="text-[10px] text-slate-400 font-bold uppercase">{{ \Carbon\Carbon::parse($startDate)->format('M d, Y') }} — {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}</p>
            </div>
        </div>
        <form action="{{ route('clinic.profit-loss') }}" method="GET" class="flex gap-4">
            <input type="date" name="start_date" value="{{ $startDate }}" class="px-4 py-2 bg-slate-50 border-2 border-slate-100 rounded-xl text-xs font-bold text-slate-700 focus:border-blue-500 focus:outline-none transition-all">
            <input type="date" name="end_date" value="{{ $endDate }}" class="px-4 py-2 bg-slate-50 border-2 border-slate-100 rounded-xl text-xs font-bold text-slate-700 focus:border-blue-500 focus:outline-none transition-all">
            <button type="submit" class="px-6 py-2 bg-slate-900 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-800 transition-all">Apply</button>
        </form>
    </div>

    <!-- Summary Widgets -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-8 rounded-4xl shadow-sm border border-slate-100 relative overflow-hidden group">
            <div class="absolute -right-4 -bottom-4 opacity-[0.03] group-hover:scale-110 transition-transform duration-700">
                <i class="fas fa-hand-holding-dollar text-9xl"></i>
            </div>
            <div class="relative z-10 space-y-4">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 shadow-sm">
                    <i class="fas fa-plus text-xs"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Revenue</p>
                    <h2 class="text-3xl font-black text-slate-900">NRs. {{ number_format($revenue, 2) }}</h2>
                </div>
                <div class="text-[10px] text-emerald-600 font-bold flex items-center gap-1">
                    <i class="fas fa-arrow-up"></i> Collected Funds
                </div>
            </div>
        </div>

        <div class="bg-white p-8 rounded-4xl shadow-sm border border-slate-100 relative overflow-hidden group">
            <div class="absolute -right-4 -bottom-4 opacity-[0.03] group-hover:scale-110 transition-transform duration-700">
                <i class="fas fa-money-bill-transfer text-9xl"></i>
            </div>
            <div class="relative z-10 space-y-4">
                <div class="w-10 h-10 rounded-xl bg-rose-50 flex items-center justify-center text-rose-600 shadow-sm">
                    <i class="fas fa-minus text-xs"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Expenses</p>
                    <h2 class="text-3xl font-black text-slate-900">NRs. {{ number_format($expenses, 2) }}</h2>
                </div>
                <div class="text-[10px] text-rose-600 font-bold flex items-center gap-1">
                    <i class="fas fa-arrow-down"></i> Operational Costs
                </div>
            </div>
        </div>

        <div class="{{ $profit >= 0 ? 'bg-blue-600' : 'bg-rose-600' }} p-8 rounded-4xl shadow-xl shadow-blue-200 relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
            <div class="relative z-10 space-y-4 text-white">
                <div class="w-10 h-10 rounded-xl bg-white/10 backdrop-blur-md flex items-center justify-center text-white border border-white/20">
                    <i class="fas fa-layer-group text-xs"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-white/60 uppercase tracking-widest mb-1">Net Profit / Loss</p>
                    <h2 class="text-3xl font-black text-white tracking-tighter">NRs. {{ number_format($profit, 2) }}</h2>
                </div>
                <div class="text-[10px] text-white/80 font-bold flex items-center gap-1 uppercase tracking-widest">
                    {{ $profit >= 0 ? 'Surplus Achievement' : 'Deficit Awareness' }}
                </div>
            </div>
        </div>
    </div>

    <!-- Details Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Revenue by Category -->
        <div class="bg-white rounded-4xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-8 py-6 border-b border-slate-50 flex items-center justify-between">
                <h3 class="text-sm font-black text-slate-900 uppercase tracking-widest">Revenue Streams</h3>
                <i class="fas fa-pie-chart text-slate-300"></i>
            </div>
            <div class="p-8 space-y-6">
                @forelse($revenueByCategory as $item)
                    <div class="group">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-xs font-black text-slate-700 uppercase tracking-tight">{{ $item->treatment_type ?? 'Other Services' }}</span>
                            <span class="text-sm font-black text-slate-900">NRs. {{ number_format($item->total, 2) }}</span>
                        </div>
                        <div class="w-full h-2 bg-slate-50 rounded-full overflow-hidden">
                            @php $percent = $revenue > 0 ? ($item->total / $revenue) * 100 : 0; @endphp
                            <div class="h-full bg-blue-600 rounded-full group-hover:bg-indigo-600 transition-all duration-1000" style="width: {{ $percent }}%"></div>
                        </div>
                    </div>
                @empty
                    <div class="py-10 text-center text-slate-400">
                        <p class="text-xs font-bold italic">No categorized revenue records available for this period.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Profit Summary Card -->
        <div class="bg-slate-900 rounded-4xl p-8 text-white relative flex flex-col justify-center">
            <div class="space-y-6 text-center">
                <div class="w-16 h-16 rounded-3xl bg-blue-600 flex items-center justify-center text-white mx-auto shadow-xl shadow-blue-500/20">
                    <i class="fas fa-file-invoice-dollar text-2xl"></i>
                </div>
                <div class="space-y-2">
                    <h2 class="text-2xl font-black tracking-tight">Financial Health Check</h2>
                    <p class="text-slate-400 text-xs font-medium max-w-xs mx-auto">This statement represents the fundamental financial performance of your clinic for the selected duration.</p>
                </div>
                <div class="pt-4">
                    <button class="px-8 py-4 bg-white text-slate-900 rounded-2xl text-[11px] font-black uppercase tracking-widest hover:bg-slate-100 hover:scale-[1.02] transition-all">
                        Export Report PDF
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .rounded-4xl { border-radius: 2.5rem; }
</style>
@endsection
