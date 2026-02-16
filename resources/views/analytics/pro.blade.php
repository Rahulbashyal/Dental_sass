@extends('layouts.app')

@section('content')
<div class="page-fade-in space-y-8 pb-12">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 py-4">
        <div class="stagger-in">
            <h1 class="text-4xl font-black text-slate-900 tracking-tight">Revenue Analytics <span class="text-blue-600">Pro</span></h1>
            <p class="text-slate-500 font-medium">Advanced financial intelligence and efficiency metrics.</p>
        </div>
        <div class="flex items-center space-x-3 stagger-in" style="--delay: 1">
            <button class="px-5 py-2.5 bg-white text-slate-700 border border-slate-200 rounded-xl font-bold hover:bg-slate-50 transition-all flex items-center space-x-2">
                <i class="fas fa-download"></i>
                <span>Export PDF</span>
            </button>
            <div class="px-5 py-2.5 bg-blue-600 text-white rounded-xl font-bold shadow-lg shadow-blue-100 flex items-center space-x-2">
                <i class="fas fa-calendar-alt"></i>
                <span>{{ now()->format('M Y') }}</span>
            </div>
        </div>
    </div>

    <!-- Top KPI Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Monthly Growth Card -->
        <div class="stagger-in bg-white rounded-[2rem] p-8 border border-slate-100 shadow-sm relative overflow-hidden group hover:border-blue-200 transition-all" style="--delay: 2">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <i class="fas fa-chart-line text-6xl text-blue-600"></i>
            </div>
            <span class="block text-slate-400 text-[11px] font-black uppercase tracking-widest mb-4">Monthly Growth</span>
            <div class="flex items-center space-x-4">
                <h3 class="text-4xl font-black text-slate-900 leading-none">{{ $analytics['monthly_growth'] }}%</h3>
                <div class="px-2 py-1 {{ $analytics['monthly_growth'] >= 0 ? 'bg-emerald-50 text-emerald-600' : 'bg-red-50 text-red-600' }} rounded-lg text-xs font-bold">
                    <i class="fas {{ $analytics['monthly_growth'] >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }} mr-1"></i>
                    Growth
                </div>
            </div>
            <p class="mt-4 text-xs font-medium text-slate-400">Compared to previous month revenue</p>
        </div>

        <!-- Revenue Per Patient (LTV) -->
        <div class="stagger-in bg-white rounded-[2rem] p-8 border border-slate-100 shadow-sm relative overflow-hidden group hover:border-indigo-200 transition-all" style="--delay: 3">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <i class="fas fa-user-graduate text-6xl text-indigo-600"></i>
            </div>
            <span class="block text-slate-400 text-[11px] font-black uppercase tracking-widest mb-4">Rev. Per Patient</span>
            <div class="flex items-center space-x-4">
                <h3 class="text-4xl font-black text-slate-900 leading-none">${{ number_format($analytics['revenue_per_patient'], 2) }}</h3>
            </div>
            <p class="mt-4 text-xs font-medium text-slate-400">Total revenue divided by unique patients</p>
        </div>

        <!-- Repeat Visit Rate -->
        <div class="stagger-in bg-white rounded-[2rem] p-8 border border-slate-100 shadow-sm relative overflow-hidden group hover:border-purple-200 transition-all" style="--delay: 4">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <i class="fas fa-redo text-6xl text-purple-600"></i>
            </div>
            <span class="block text-slate-400 text-[11px] font-black uppercase tracking-widest mb-4">Retention Rate</span>
            <div class="flex items-center space-x-4">
                <h3 class="text-4xl font-black text-slate-900 leading-none">{{ $analytics['repeat_visit_rate'] }}%</h3>
            </div>
            <p class="mt-4 text-xs font-medium text-slate-400">Patients with more than one visit</p>
        </div>

        <!-- Chair Utilization Rate -->
        <div class="stagger-in bg-white rounded-[2rem] p-8 border border-slate-100 shadow-sm relative overflow-hidden group hover:border-amber-200 transition-all" style="--delay: 5">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <i class="fas fa-chair text-6xl text-amber-600"></i>
            </div>
            <span class="block text-slate-400 text-[11px] font-black uppercase tracking-widest mb-4">Chair Utilization</span>
            <div class="flex items-center space-x-4">
                <h3 class="text-4xl font-black text-slate-900 leading-none">{{ $analytics['chair_utilization'] }}%</h3>
            </div>
            <div class="mt-4 w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                <div class="bg-amber-500 h-full" style="width: {{ $analytics['chair_utilization'] }}%"></div>
            </div>
            <p class="mt-2 text-xs font-medium text-slate-400">Active time vs available chair slots</p>
        </div>
    </div>

    <!-- Detailed Analytics Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Dentist Performance Table -->
        <div class="lg:col-span-12 xl:col-span-8 stagger-in" style="--delay: 6">
            <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-50 flex items-center justify-between bg-slate-50/30">
                    <div>
                        <h3 class="text-xl font-bold text-slate-900 tracking-tight">Physician Performance</h3>
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Revenue and appointment volume by doctor</p>
                    </div>
                </div>
                <div class="p-0 overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-slate-400 text-[10px] font-black uppercase tracking-widest bg-slate-50/50">
                                <th class="px-8 py-4">Dentist</th>
                                <th class="px-8 py-4">Appointments</th>
                                <th class="px-8 py-4">Total Revenue</th>
                                <th class="px-8 py-4">Avg Transaction</th>
                                <th class="px-8 py-4 text-right">Efficacy</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($analytics['doctor_performance'] as $performance)
                            <tr class="hover:bg-slate-50 transition-colors group">
                                <td class="px-8 py-5">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 font-bold">
                                            {{ substr($performance['name'], 0, 1) }}
                                        </div>
                                        <span class="font-bold text-slate-700 tracking-tight">{{ $performance['name'] }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-5">
                                    <span class="text-sm font-black text-slate-500">{{ $performance['appointments_count'] }}</span>
                                </td>
                                <td class="px-8 py-5">
                                    <span class="text-sm font-black text-slate-900">${{ number_format($performance['revenue'], 2) }}</span>
                                </td>
                                <td class="px-8 py-5">
                                    <span class="text-sm font-bold text-slate-500">${{ $performance['appointments_count'] > 0 ? number_format($performance['revenue'] / $performance['appointments_count'], 2) : '0.00' }}</span>
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <div class="w-16 h-2 bg-slate-100 rounded-full inline-block overflow-hidden align-middle ml-2">
                                        <div class="bg-blue-600 h-full" style="width: {{ min(100, ($performance['appointments_count'] / 50) * 100) }}%"></div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Popular Treatments Chart / List -->
        <div class="lg:col-span-12 xl:col-span-4 stagger-in" style="--delay: 7">
            <div class="bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm flex flex-col h-full">
                <h3 class="text-xl font-bold text-slate-900 tracking-tight mb-2">Service Efficacy</h3>
                <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mb-8">Most requested dental procedures</p>
                
                <div class="space-y-6 flex-grow">
                    @foreach($analytics['popular_treatments'] as $treatment)
                    <div class="group">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-bold text-slate-600 group-hover:text-blue-600 transition-colors">{{ ucfirst(str_replace('_', ' ', $treatment['type'])) }}</span>
                            <span class="text-xs font-black text-slate-900">{{ $treatment['count'] }} <span class="text-slate-400 font-medium">Ops</span></span>
                        </div>
                        <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 h-full" style="width: {{ min(100, ($treatment['count'] / max(1, collect($analytics['popular_treatments'])->max('count'))) * 100) }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="mt-8 pt-8 border-t border-slate-50">
                    <div class="bg-blue-50 rounded-2xl p-4 flex items-center space-x-4">
                        <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-blue-100">
                            <i class="fas fa-lightbulb"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-blue-800 uppercase tracking-widest leading-none mb-1">Clinic Insight</p>
                            <p class="text-xs font-medium text-blue-600 leading-tight">Focus on marketing <strong>{{ $analytics['popular_treatments']->first()['type'] ?? 'N/A' }}</strong> services based on current volume.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .page-fade-in {
        animation: fadeIn 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    .stagger-in {
        opacity: 0;
        animation: fadeIn 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        animation-delay: calc(var(--delay, 0) * 0.1s);
    }
</style>
@endsection
