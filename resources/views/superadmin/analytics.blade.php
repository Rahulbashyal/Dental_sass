@extends('layouts.app')

@section('content')
<!-- Header -->
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-12">
    <div>
        <h1 class="text-4xl font-black text-slate-900 tracking-tight flex items-center gap-4">
            <div class="w-12 h-12 bg-white border border-slate-100 rounded-2xl flex items-center justify-center shadow-sm">
                <i class="fas fa-chart-line text-blue-500"></i>
            </div>
            Analytics Dashboard
        </h1>
        <p class="text-slate-500 font-medium mt-2 flex items-center gap-2 italic">
            <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
            Real-time business performance and monthly growth metrics.
        </p>
    </div>
    <div class="flex items-center gap-4">
        <div class="relative bg-white/70 backdrop-blur-xl border border-slate-100 p-4 rounded-[2rem] flex items-center gap-5 shadow-sm group hover:shadow-xl transition-all duration-700">
            <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center shadow-inner group-hover:scale-110 transition-transform">
                <i class="fas fa-calendar-alt text-xs"></i>
            </div>
            <div>
                @php
                    $nepaliDate = \App\Helpers\NepaliDateHelper::getCurrentNepaliDate();
                @endphp
                <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $nepaliDate['day_of_week'] ? \App\Services\NepaliCalendarService::getDayName($nepaliDate['day_of_week']) : 'बुधबार' }}</div>
                <div class="text-xs font-black text-slate-900">{{ $nepaliDate['formatted'] ?? '२६ कार्तिक २०८२' }}</div>
            </div>
        </div>
    </div>
</div>

<div class="space-y-12">
    <!-- Key Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 stagger-in">
        <div class="stat-card group">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-blue-50/50 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-700"></div>
            <div class="relative z-10 flex flex-col gap-6">
                <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-[1.25rem] flex items-center justify-center shadow-inner group-hover:rotate-12 transition-all">
                    <i class="fas fa-arrow-trend-up text-xl"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Business Growth</p>
                    <h3 class="text-3xl font-black text-slate-900 tracking-tight">+15%</h3>
                    <div class="mt-2 flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span class="text-[8px] font-bold text-emerald-600 uppercase tracking-widest">Upward Trend</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="stat-card group">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-emerald-50/50 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-700"></div>
            <div class="relative z-10 flex flex-col gap-6">
                <div class="w-14 h-14 bg-emerald-50 text-emerald-600 rounded-[1.25rem] flex items-center justify-center shadow-inner group-hover:rotate-12 transition-all">
                    <i class="fas fa-hand-holding-dollar text-xl"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Average Revenue</p>
                    <h3 class="text-3xl font-black text-slate-900 tracking-tight">NPR 8.5K</h3>
                    <div class="mt-2 flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-slate-200"></span>
                        <span class="text-[8px] font-bold text-slate-500 uppercase tracking-widest">Per Clinic</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="stat-card group">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-purple-50/50 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-700"></div>
            <div class="relative z-10 flex flex-col gap-6">
                <div class="w-14 h-14 bg-purple-50 text-purple-600 rounded-[1.25rem] flex items-center justify-center shadow-inner group-hover:rotate-12 transition-all">
                    <i class="fas fa-users-viewfinder text-xl"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Active Users</p>
                    <h3 class="text-3xl font-black text-slate-900 tracking-tight">1,250</h3>
                    <div class="mt-2 flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-purple-400"></span>
                        <span class="text-[8px] font-bold text-purple-600 uppercase tracking-widest">Live Now</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="stat-card group">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-amber-50/50 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-700"></div>
            <div class="relative z-10 flex flex-col gap-6">
                <div class="w-14 h-14 bg-amber-50 text-amber-600 rounded-[1.25rem] flex items-center justify-center shadow-inner group-hover:rotate-12 transition-all">
                    <i class="fas fa-link text-xl"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Retention Rate</p>
                    <h3 class="text-3xl font-black text-slate-900 tracking-tight">92%</h3>
                    <div class="mt-2 flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                        <span class="text-[8px] font-bold text-amber-600 uppercase tracking-widest">Returning Customers</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Growth Chart -->
    <div class="relative">
        <div class="premium-table-container">
            <div class="px-10 py-8 border-b border-slate-50 flex items-center gap-4">
                <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center shadow-sm">
                    <i class="fas fa-layer-group text-xs"></i>
                </div>
                <div>
                    <h2 class="text-lg font-black text-slate-900 tracking-tight">Monthly Performance</h2>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5 italic">Monitor your monthly clinic growth and revenue.</p>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="premium-table">
                    <thead>
                        <tr>
                            <th>Month</th>
                            <th>New Clinics</th>
                            <th>Revenue (NPR)</th>
                            <th class="text-right">Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($monthlyStats as $stat)
                            <tr>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="w-2 h-2 rounded-full bg-blue-500"></div>
                                        <span class="font-black text-slate-900 tracking-tight uppercase">{{ $stat['month'] }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="px-3 py-1 bg-slate-100 rounded-lg text-xs font-black text-slate-700">
                                        {{ $stat['clinics'] }} Clinics
                                    </span>
                                </td>
                                <td>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-black text-slate-900 tracking-tight">NPR {{ number_format($stat['revenue']) }}</span>
                                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Total Earnings</span>
                                    </div>
                                </td>
                                <td class="text-right">
                                    <button class="btn-premium-ghost !px-3 !py-1.5 text-xs">
                                        <i class="fas fa-ellipsis-v text-[10px]"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection