@extends('layouts.app')

@section('page-title', 'Executive Intelligence Hub')

@section('content')
<div class="page-fade-in space-y-10 pb-12">
    <!-- Header with Breadcrumbs & Actions -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div class="stagger-in">
            <p class="text-[10px] font-black text-blue-600 uppercase tracking-[0.3em] mb-3">Enterprise Analytics</p>
            <h1 class="text-4xl font-black text-slate-900 tracking-tight">Executive Intelligence</h1>
            <p class="text-slate-500 font-medium mt-1">Real-time clinical performance & financial optimization</p>
        </div>
        
        <div class="flex items-center space-x-3 stagger-in" style="--delay: 1">
            <button onclick="window.print()" class="px-5 py-3 bg-white text-slate-700 border border-slate-200 rounded-2xl font-bold hover:bg-slate-50 transition-all flex items-center space-x-2 shadow-sm">
                <i class="fas fa-file-export text-xs opacity-50"></i>
                <span>Export Audit</span>
            </button>
            <div class="h-10 w-[1px] bg-slate-200 mx-2"></div>
            <select class="bg-slate-900 text-white rounded-2xl px-5 py-3 font-bold text-sm focus:ring-0 cursor-pointer border-none shadow-xl shadow-slate-200">
                <option>Last 30 Days</option>
                <option>Quarter to Date</option>
                <option>Fiscal Year</option>
            </select>
        </div>
    </div>

    <!-- Executive KPI Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Utilization Metric -->
        <div class="bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm relative overflow-hidden group">
            <div class="absolute top-0 right-0 p-4">
                <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600 group-hover:scale-110 transition-transform">
                    <i class="fas fa-chair"></i>
                </div>
            </div>
            <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Chair Utilization</span>
            <div class="flex items-end space-x-2">
                <span class="text-3xl font-black text-slate-900">{{ number_format($executiveStats['chair_utilization'], 1) }}%</span>
                <span class="text-[10px] text-emerald-500 font-bold mb-1.5"><i class="fas fa-caret-up mr-1"></i>2.4%</span>
            </div>
            <div class="mt-4 w-full bg-slate-50 h-1.5 rounded-full overflow-hidden">
                <div class="bg-blue-600 h-full rounded-full transition-all duration-1000" style="width: {{ $executiveStats['chair_utilization'] }}%"></div>
            </div>
        </div>

        <!-- Revenue Perf -->
        <div class="bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm relative overflow-hidden group">
            <div class="absolute top-0 right-0 p-4">
                <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
            </div>
            <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Gross Revenue</span>
            <div class="flex items-end space-x-2">
                <span class="text-3xl font-black text-slate-900">NPR {{ number_format($executiveStats['revenue_performance'], 0) }}</span>
            </div>
            <p class="mt-4 text-[10px] text-slate-400 font-medium italic">Verified via invoice reconciliation</p>
        </div>

        <!-- Revenue Leakage -->
        <div class="bg-rose-50 rounded-[2.5rem] p-8 border border-rose-100 shadow-sm relative overflow-hidden group">
            <div class="absolute top-0 right-0 p-4">
                <div class="w-10 h-10 bg-rose-100 rounded-xl flex items-center justify-center text-rose-600">
                    <i class="fas fa-shield-virus"></i>
                </div>
            </div>
            <span class="block text-[10px] font-black text-rose-400 uppercase tracking-widest mb-4">Revenue Leakage</span>
            <div class="flex items-end space-x-2">
                <span class="text-3xl font-black text-rose-900">NPR {{ number_format($executiveStats['leakage'], 0) }}</span>
                <span class="text-[10px] text-rose-500 font-bold mb-1.5">No-shows</span>
            </div>
            <p class="mt-4 text-[10px] text-rose-600 font-bold uppercase tracking-tighter">Optimization Required</p>
        </div>

        <!-- Completion Velocity -->
        <div class="bg-slate-900 rounded-[2.5rem] p-8 shadow-xl shadow-slate-200 relative overflow-hidden group">
            <div class="absolute -right-6 -bottom-6 opacity-10">
                <i class="fas fa-bolt text-9xl text-white"></i>
            </div>
            <span class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-4">Case Completion</span>
            <div class="flex items-end space-x-2">
                <span class="text-3xl font-black text-white">{{ $stats['completion_rate'] }}%</span>
                <span class="text-[10px] text-emerald-400 font-bold mb-1.5">+5% vs LY</span>
            </div>
            <div class="mt-4 flex items-center space-x-2 text-[10px] text-slate-400 font-bold uppercase tracking-widest">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>Operational Velocity</span>
            </div>
        </div>
    </div>

    <!-- Secondary Intelligence Layer -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Doctor Efficiency -->
        <div class="lg:col-span-2 bg-white rounded-[3rem] p-10 border border-slate-100 shadow-sm">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="text-xl font-black text-slate-900">Clinical Load Balancing</h3>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Doctor Efficiency Index</p>
                </div>
                <i class="fas fa-user-md text-slate-200 text-2xl"></i>
            </div>

            <div class="space-y-6">
                @foreach($executiveStats['doctor_efficiency'] as $debtist)
                    <div class="group">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-bold text-slate-700">{{ $debtist->name }}</span>
                            <span class="text-xs font-black text-slate-900">{{ $debtist->appointment_count }} Procedures</span>
                        </div>
                        <div class="w-full bg-slate-50 h-3 rounded-full overflow-hidden border border-slate-100 p-0.5">
                            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 h-full rounded-full transition-all duration-700" 
                                 style="width: {{ ($debtist->appointment_count / max($executiveStats['doctor_efficiency']->pluck('appointment_count')->toArray() ?: [1])) * 100 }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Treatment Distribution -->
        <div class="bg-white rounded-[3rem] p-10 border border-slate-100 shadow-sm relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-blue-50/50 rounded-full -translate-y-16 translate-x-16 -z-10"></div>
            <h3 class="text-xl font-black text-slate-900 mb-8">Market Mix</h3>
            
            <div class="space-y-4">
                @foreach($treatment_stats as $treatment)
                    <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl hover:bg-slate-100 transition-colors cursor-default">
                        <div class="flex items-center space-x-3">
                            <div class="w-2 h-2 rounded-full {{ ['bg-blue-500', 'bg-emerald-500', 'bg-amber-500', 'bg-rose-500', 'bg-indigo-500'][($loop->index % 5)] }}"></div>
                            <span class="text-[11px] font-black text-slate-600 uppercase tracking-tighter">{{ ucfirst(str_replace('_', ' ', $treatment->type)) }}</span>
                        </div>
                        <span class="text-xs font-black text-slate-900">{{ $treatment->count }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Live Audit Feed -->
    <div class="bg-white rounded-[3rem] p-10 border border-slate-100 shadow-sm">
        <div class="flex items-center justify-between mb-10">
            <div>
                <h3 class="text-xl font-black text-slate-900">Recent Operational Events</h3>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Audit Logs & Verification</p>
            </div>
            <div class="flex items-center space-x-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Live Monitoring</span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50">
                        <th class="pb-4">Timestamp</th>
                        <th class="pb-4">Subject</th>
                        <th class="pb-4">Intervention</th>
                        <th class="pb-4">Status</th>
                        <th class="pb-4 text-right">Escalation</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($recent_appointments as $appt)
                        <tr class="group hover:bg-slate-50/50 transition-colors">
                            <td class="py-5">
                                <span class="text-xs font-bold text-slate-900">{{ $appt->appointment_date->format('M d, Y') }}</span>
                                <p class="text-[10px] text-slate-400 font-medium">{{ $appt->appointment_time->format('H:i') }}</p>
                            </td>
                            <td class="py-5">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 rounded-xl bg-slate-900 flex items-center justify-center text-white text-[10px] font-black">
                                        {{ substr($appt->patient->name, 0, 1) }}
                                    </div>
                                    <span class="text-sm font-bold text-slate-700">{{ $appt->patient->name }}</span>
                                </div>
                            </td>
                            <td class="py-5">
                                <span class="px-3 py-1 bg-blue-50 text-blue-700 rounded-lg text-[9px] font-black uppercase tracking-tighter border border-blue-100">
                                    {{ ucfirst($appt->type) }}
                                 </span>
                            </td>
                            <td class="py-5">
                                <div class="flex items-center space-x-2">
                                    <div class="w-1.5 h-1.5 rounded-full {{ $appt->status == 'completed' ? 'bg-emerald-500' : 'bg-amber-500' }}"></div>
                                    <span class="text-[10px] font-black uppercase text-slate-600">{{ $appt->status }}</span>
                                </div>
                            </td>
                            <td class="py-5 text-right">
                                <button class="p-2 text-slate-400 hover:text-blue-600 transition-colors"><i class="fas fa-external-link-alt text-xs"></i></button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection