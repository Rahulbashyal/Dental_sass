@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 border-l-4 border-indigo-600 pl-4">Branch Comparison</h2>
            <p class="text-sm text-slate-500 mt-1">Deep-dive comparative analysis across different clinic locations.</p>
        </div>
        <a href="{{ route('executive.dashboard') }}" class="text-sm font-medium text-slate-500 hover:text-slate-700">&larr; Back back to Overview</a>
    </div>

    <!-- Comparison Matrix -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($branchComparison as $comp)
        <div class="bg-white rounded-2xl border {{ $loop->first ? 'border-indigo-600 ring-4 ring-indigo-50 shadow-2xl' : 'border-slate-200 shadow-sm' }} overflow-hidden flex flex-col transition-all hover:shadow-lg">
            <div class="p-8 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-black text-slate-900 mb-1">{{ $comp['branch']->name }}</h3>
                    <div class="flex items-center">
                        <span class="w-2 h-2 rounded-full {{ $comp['branch']->is_active ? 'bg-green-500' : 'bg-red-500' }} mr-2"></span>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">{{ $comp['branch']->is_active ? 'Online' : 'Offline' }}</p>
                    </div>
                </div>
                <div class="h-10 w-10 bg-slate-50 rounded-xl flex items-center justify-center text-slate-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
            </div>

            <div class="p-8 space-y-8 flex-grow">
                <!-- Revenue Column -->
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Branch Revenue</p>
                    <div class="flex items-baseline space-x-2">
                        <span class="text-2xl font-black text-slate-900">{{ tenant()->clinic->currency ?? '$' }}{{ number_format($comp['revenue'], 2) }}</span>
                    </div>
                </div>

                <!-- Metrics Grid -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-slate-50/50 rounded-xl p-4 border border-slate-100">
                        <p class="text-[9px] font-black text-slate-400 uppercase mb-1">Avg. Ticket</p>
                        <p class="text-sm font-bold text-slate-900">{{ tenant()->clinic->currency ?? '$' }}{{ number_format($comp['avg_ticket'], 0) }}</p>
                    </div>
                    <div class="bg-slate-50/50 rounded-xl p-4 border border-slate-100">
                        <p class="text-[9px] font-black text-slate-400 uppercase mb-1">Cancel Rate</p>
                        <p class="text-sm font-bold {{ $comp['cancel_rate'] > 10 ? 'text-red-500' : 'text-slate-900' }}">{{ number_format($comp['cancel_rate'], 1) }}%</p>
                    </div>
                </div>

                <!-- Staff Efficiency -->
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Staff Utilization</p>
                    <div class="flex items-center justify-between text-xs mb-2">
                        <span class="text-slate-600">{{ $comp['branch']->users_count }} Team Members</span>
                        <span class="font-bold text-indigo-600">65% Util.</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-1.5">
                        <div class="bg-indigo-600 h-1.5 rounded-full" style="width: 65%"></div>
                    </div>
                </div>
            </div>

            <div class="p-8 bg-slate-50/50 border-t border-slate-100 mt-auto">
                <button class="w-full py-3 bg-white border border-slate-200 text-slate-600 text-[11px] font-black uppercase tracking-widest rounded-xl hover:bg-slate-50 transition-all">
                    View full Branch Report
                </button>
            </div>
        </div>
        @endforeach

        <!-- Comparison Focus Card -->
        <div class="bg-indigo-900 rounded-2xl p-8 text-white relative flex flex-col justify-center overflow-hidden shadow-2xl shadow-indigo-100">
             <div class="relative z-10">
                <h3 class="text-xl font-black mb-2">Cross-Network Optimization</h3>
                <p class="text-sm text-indigo-300 mb-8">Identify bottlenecks and replicate performance from high-revenue branches across your entire dental network.</p>
                <div class="space-y-4">
                    <div class="flex items-center text-xs">
                        <div class="h-6 w-6 rounded bg-indigo-800 flex items-center justify-center mr-3">
                             <span class="text-[10px]">📊</span>
                        </div>
                        Financial parity monitoring
                    </div>
                    <div class="flex items-center text-xs">
                        <div class="h-6 w-6 rounded bg-indigo-800 flex items-center justify-center mr-3">
                             <span class="text-[10px]">🩺</span>
                        </div>
                        Case acceptance comparison
                    </div>
                </div>
            </div>
            <svg class="absolute bottom-0 right-0 w-32 h-32 text-indigo-800 opacity-20 -mb-8 -mr-8" fill="currentColor" viewBox="0 0 24 24">
                 <path d="M11 17h2v-1h5v-4h-5V7h-2v4H6v4h5v2zM12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"/>
            </svg>
        </div>
    </div>
</div>
@endsection
