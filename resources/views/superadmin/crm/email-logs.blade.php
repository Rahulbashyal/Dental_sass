@extends('layouts.app')

@section('page-title', 'Email Logs')

@section('content')
<!-- Header -->
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-12">
    <div>
        <h1 class="text-4xl font-black text-slate-900 tracking-tight flex items-center gap-4">
            <div class="w-12 h-12 bg-white border border-slate-100 rounded-2xl flex items-center justify-center shadow-sm">
                <i class="fas fa-history text-blue-500"></i>
            </div>
            Communication Logs
        </h1>
        <p class="text-slate-500 font-medium mt-2 flex items-center gap-2 italic">
            <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
            Monitor your sent emails and track how users interact with them.
        </p>
    </div>
    <div class="flex items-center gap-4">
        <button class="btn-premium-outline !py-4 !px-8 !rounded-2xl !text-xs !bg-white group shadow-sm">
            <i class="fas fa-download mr-2 text-slate-400 group-hover:text-slate-900 transition-all"></i>
            Download Logs
        </button>
    </div>
</div>

<div class="space-y-12">
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 stagger-in">
        <div class="stat-card group">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-blue-50/50 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-700"></div>
            <div class="relative z-10 flex flex-col gap-6">
                <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-[1.25rem] flex items-center justify-center shadow-inner group-hover:rotate-12 transition-all">
                    <i class="fas fa-paper-plane text-xl"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Emails Sent</p>
                    <h3 class="text-3xl font-black text-slate-900 tracking-tight">{{ number_format($stats['total_sent']) }}</h3>
                    <div class="flex items-center gap-1.5 mt-1">
                        <i class="fas fa-arrow-trend-up text-[8px] text-emerald-500"></i>
                        <p class="text-[8px] font-black text-emerald-500 uppercase tracking-widest italic">+12% from last month</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="stat-card group">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-emerald-50/50 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-700"></div>
            <div class="relative z-10 flex flex-col gap-6">
                <div class="w-14 h-14 bg-emerald-50 text-emerald-600 rounded-[1.25rem] flex items-center justify-center shadow-inner group-hover:rotate-12 transition-all">
                    <i class="fas fa-envelope-open text-xl"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Emails Opened</p>
                    <h3 class="text-3xl font-black text-slate-900 tracking-tight">{{ number_format($stats['total_opened']) }}</h3>
                    <p class="text-[8px] font-black text-emerald-500 uppercase tracking-widest mt-1 italic">
                        {{ number_format(($stats['total_opened'] / max($stats['total_sent'], 1)) * 100, 1) }}% Open rate
                    </p>
                </div>
            </div>
        </div>

        <div class="stat-card group">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-purple-50/50 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-700"></div>
            <div class="relative z-10 flex flex-col gap-6">
                <div class="w-14 h-14 bg-purple-50 text-purple-600 rounded-[1.25rem] flex items-center justify-center shadow-inner group-hover:rotate-12 transition-all">
                    <i class="fas fa-mouse-pointer text-xl"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Total Clicks</p>
                    <h3 class="text-3xl font-black text-slate-900 tracking-tight">{{ number_format($stats['total_clicked']) }}</h3>
                    <p class="text-[8px] font-black text-purple-500 uppercase tracking-widest mt-1 italic">
                        {{ number_format(($stats['total_clicked'] / max($stats['total_opened'], 1)) * 100, 1) }}% Click rate
                    </p>
                </div>
            </div>
        </div>

        <div class="stat-card group">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-rose-50/50 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-700"></div>
            <div class="relative z-10 flex flex-col gap-6">
                <div class="w-14 h-14 bg-rose-50 text-rose-600 rounded-[1.25rem] flex items-center justify-center shadow-inner group-hover:rotate-12 transition-all">
                    <i class="fas fa-exclamation-triangle text-xl"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Bounced Emails</p>
                    <h3 class="text-3xl font-black text-slate-900 tracking-tight">{{ number_format($stats['bounce_rate']) }}</h3>
                    <div class="flex items-center gap-1.5 mt-1">
                        <i class="fas fa-arrow-trend-down text-[8px] text-rose-500"></i>
                        <p class="text-[8px] font-black text-rose-500 uppercase tracking-widest italic">Failed delivery rate</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Email Logs Registry -->
    <div class="relative">
        <div class="premium-table-container">
            <div class="px-10 py-8 border-b border-slate-50 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center shadow-sm">
                        <i class="fas fa-list-ul text-xs"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-black text-slate-900 tracking-tight">Log History</h2>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5 italic">View history of all sent messages.</p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest bg-slate-50 px-3 py-1.5 rounded-full border border-slate-100">
                        Past 720 Hours Cycle
                    </span>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="premium-table">
                    <thead>
                        <tr>
                            <th>Recipient Node</th>
                            <th class="hidden md:table-cell">Transmission Subject</th>
                            <th class="hidden lg:table-cell">Modulation Vector</th>
                            <th>Active State</th>
                            <th class="text-right">Time Sequence</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($emailLogs as $log)
                            <tr class="group">
                                <td>
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 border border-slate-100 group-hover:bg-blue-50 group-hover:text-blue-600 group-hover:border-blue-100 transition-all duration-300">
                                            <i class="fas fa-user-circle text-lg"></i>
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-sm font-black text-slate-900 tracking-tight group-hover:text-blue-600 transition-colors">{{ $log->to_email }}</span>
                                            @if($log->lead)
                                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest italic">{{ $log->lead->name }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="hidden md:table-cell">
                                    <div class="text-xs font-medium text-slate-600 max-w-xs truncate italic">"{{ $log->subject }}"</div>
                                </td>
                                <td class="hidden lg:table-cell">
                                    <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest bg-slate-50 px-2 py-1 rounded-md border border-slate-100 italic">
                                        {{ $log->campaign->name ?? 'SYSTEM_CORE' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="chip
                                        @if($log->status === 'sent') chip-info
                                        @elseif($log->status === 'opened') chip-success
                                        @elseif($log->status === 'clicked') bg-purple-50 text-purple-600 border-purple-100
                                        @elseif($log->status === 'bounced') chip-danger
                                        @else chip-warning
                                        @endif">
                                        <span class="w-1.5 h-1.5 rounded-full
                                            @if($log->status === 'sent') bg-blue-500
                                            @elseif($log->status === 'opened') bg-emerald-500
                                            @elseif($log->status === 'clicked') bg-purple-500
                                            @elseif($log->status === 'bounced') bg-rose-500
                                            @else bg-amber-500
                                            @endif"></span>
                                        {{ $log->status }}
                                    </span>
                                </td>
                                <td class="text-right">
                                    <div class="flex flex-col items-end">
                                        <span class="text-sm font-black text-slate-900 tracking-tight">{{ $log->sent_at->diffForHumans() }}</span>
                                        <span class="text-[9px] font-bold text-slate-400 tracking-widest italic uppercase">{{ $log->sent_at->format('M j, Y H:i') }}</span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-24 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-24 h-24 bg-slate-50 rounded-[2.5rem] flex items-center justify-center mb-6 shadow-inner border border-slate-100 group">
                                            <i class="fas fa-satellite text-4xl text-slate-200 group-hover:scale-110 group-hover:text-blue-500/20 transition-all duration-700"></i>
                                        </div>
                                        <h3 class="text-2xl font-black text-slate-900 tracking-tight">Signal Dormant</h3>
                                        <p class="text-slate-500 max-w-sm mx-auto text-sm font-medium mt-2 italic text-center">Outbound communication logs have not been detected within the current session matrix.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($emailLogs->hasPages())
                <div class="px-10 py-6 border-t border-slate-50">
                    {{ $emailLogs->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
</div>
@endsection