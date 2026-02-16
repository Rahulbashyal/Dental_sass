@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 border-l-4 border-indigo-600 pl-4">Network Intelligence</h2>
            <p class="text-sm text-slate-500 mt-1">Aggregated executive overview of your dental practice network.</p>
        </div>
        <div class="mt-4 md:mt-0 flex space-x-3">
             <a href="{{ route('executive.comparison') }}" class="inline-flex items-center px-4 py-2 bg-white border border-slate-200 text-slate-700 text-sm font-bold rounded-xl shadow-sm hover:bg-slate-50 transition-all">
                <svg class="w-4 h-4 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                Branch Comparison
            </a>
            <button class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-indigo-100 transition-all">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Export Report
            </button>
        </div>
    </div>

    <!-- Executive Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm relative overflow-hidden group">
            <div class="relative z-10">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Network Revenue</p>
                <h3 class="text-3xl font-black text-slate-900 group-hover:text-indigo-600 transition-colors">{{ tenant()->clinic->currency ?? '$' }}{{ number_format($stats['total_revenue'], 2) }}</h3>
                <div class="mt-4 flex items-center text-xs">
                    <span class="text-green-500 font-bold">↑ 12.5%</span>
                    <span class="text-slate-400 ml-2">vs last month</span>
                </div>
            </div>
            <svg class="absolute bottom-0 right-0 w-24 h-24 text-slate-50 opacity-50 -mb-4 -mr-4 pointer-events-none" fill="currentColor" viewBox="0 0 24 24">
                <path d="M11.5 2C6.81 2 3 5.81 3 10.5S6.81 19 11.5 19 20 15.19 20 10.5 16.19 2 11.5 2zm1 14.5h-2v-2h2v2zm0-3.5h-2V7h2v6z"/>
            </svg>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm relative overflow-hidden group">
            <div class="relative z-10">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Network Cases</p>
                <h3 class="text-3xl font-black text-slate-900 group-hover:text-indigo-600 transition-colors">{{ number_format($stats['total_appointments']) }}</h3>
                <div class="mt-4 flex items-center text-xs text-slate-400 italic">
                    Aggregated across {{ $branches->count() }} branches
                </div>
            </div>
            <svg class="absolute bottom-0 right-0 w-24 h-24 text-slate-50 opacity-50 -mb-4 -mr-4 pointer-events-none" fill="currentColor" viewBox="0 0 24 24">
                <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
            </svg>
        </div>

        <div class="bg-indigo-900 rounded-2xl p-6 text-white shadow-xl shadow-indigo-100 relative overflow-hidden">
             <div class="relative z-10">
                <p class="text-[10px] font-bold text-indigo-300 uppercase tracking-widest mb-1">Active Branches</p>
                <div class="flex items-baseline mb-4">
                    <h3 class="text-3xl font-black">{{ $branches->where('is_active', true)->count() }}</h3>
                    <span class="text-indigo-400 text-sm ml-2">/ {{ $branches->count() }} registered</span>
                </div>
                <div class="flex items-center -space-x-2">
                    @foreach($branches->take(4) as $index => $branch)
                        <div class="h-8 w-8 rounded-full border-2 border-indigo-900 bg-indigo-500 flex items-center justify-center text-[10px] font-black" title="{{ $branch->name }}">
                            {{ substr($branch->name, 0, 1) }}
                        </div>
                    @endforeach
                    @if($branches->count() > 4)
                        <div class="h-8 w-8 rounded-full border-2 border-indigo-900 bg-indigo-800 flex items-center justify-center text-[10px] font-black text-indigo-300">
                            +{{ $branches->count() - 4 }}
                        </div>
                    @endif
                </div>
            </div>
            <svg class="absolute bottom-0 right-0 w-32 h-32 text-indigo-800 opacity-20 -mb-8 -mr-8" fill="currentColor" viewBox="0 0 24 24">
                 <path d="M12 7V3H2v18h20V7H12zM6 19H4v-2h2v2zm0-4H4v-2h2v2zm0-4H4V9h2v2zm0-4H4V5h2v2zm4 12H8v-2h2v2zm0-4H8v-2h2v2zm0-4H8V9h2v2zm0-4H8V5h2v2zm10 12h-8v-2h2v-2h-2v-2h2v-2h-2V9h8v10zm-2-8h-2v2h2v-2zm0 4h-2v2h2v-2z"/>
            </svg>
        </div>
    </div>

    <!-- Branch Performance List -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-8">
        <div class="px-8 py-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/30">
            <h4 class="text-sm font-black text-slate-900 uppercase tracking-widest">Branch Performance Rankings</h4>
            <span class="text-xs text-slate-400 italic">Financial Year: {{ date('Y') }}</span>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-8 py-4 text-left text-[10px] font-bold text-slate-500 uppercase tracking-wider">Rank</th>
                        <th class="px-8 py-4 text-left text-[10px] font-bold text-slate-500 uppercase tracking-wider">Branch Name</th>
                        <th class="px-8 py-4 text-left text-[10px] font-bold text-slate-500 uppercase tracking-wider">Revenue Share</th>
                        <th class="px-8 py-4 text-left text-[10px] font-bold text-slate-500 uppercase tracking-wider">Revenue</th>
                        <th class="px-8 py-4 text-left text-[10px] font-bold text-slate-500 uppercase tracking-wider">Clinical Volume</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($stats['branch_performance']->sortByDesc('revenue')->values() as $index => $perf)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-8 py-5 whitespace-nowrap">
                            <span class="inline-flex items-center justify-center h-6 w-6 rounded-lg {{ $index == 0 ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-700' }} text-[10px] font-black">
                                #{{ $index + 1 }}
                            </span>
                        </td>
                        <td class="px-8 py-5 whitespace-nowrap">
                            <div class="text-sm font-bold text-slate-900">{{ $perf['name'] }}</div>
                        </td>
                        <td class="px-8 py-5 whitespace-nowrap min-w-[200px]">
                            @php
                                $percent = $stats['total_revenue'] > 0 ? ($perf['revenue'] / $stats['total_revenue']) * 100 : 0;
                            @endphp
                            <div class="flex items-center space-x-3">
                                <div class="flex-grow bg-slate-100 rounded-full h-1.5 max-w-[100px]">
                                    <div class="bg-indigo-600 h-1.5 rounded-full" style="width: {{ $percent }}%"></div>
                                </div>
                                <span class="text-[10px] font-bold text-slate-500">{{ number_format($percent, 1) }}%</span>
                            </div>
                        </td>
                        <td class="px-8 py-5 whitespace-nowrap">
                            <div class="text-sm font-black text-slate-900">{{ tenant()->clinic->currency ?? '$' }}{{ number_format($perf['revenue'], 2) }}</div>
                        </td>
                        <td class="px-8 py-5 whitespace-nowrap">
                            <div class="text-sm font-medium text-slate-700">{{ $perf['appointments'] }} Appointments</div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
