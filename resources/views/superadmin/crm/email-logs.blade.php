@extends('layouts.app')

@section('page-title', 'Email Logs')

@section('content')
<div class="space-y-8 page-fade-in">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Email Logs</h1>
            <p class="text-slate-500 mt-1">Track email delivery performance and lead engagement</p>
        </div>
        <div class="flex gap-2">
            <button class="btn-secondary flex items-center gap-2">
                <i class="fas fa-download text-slate-400"></i>
                Export
            </button>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 stagger-in">
        <div class="stat-card group">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-all duration-300">
                        <i class="fas fa-paper-plane text-xl"></i>
                    </div>
                </div>
                <div class="ml-5">
                    <dt class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Total Sent</dt>
                    <dd class="text-3xl font-black text-slate-900">{{ number_format($stats['total_sent']) }}</dd>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <span class="text-emerald-500 font-bold flex items-center">
                    <i class="fas fa-arrow-up mr-1"></i> 12%
                </span>
                <span class="ml-2 text-slate-400">vs last month</span>
            </div>
        </div>

        <div class="stat-card group">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-14 h-14 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center group-hover:bg-emerald-600 group-hover:text-white transition-all duration-300">
                        <i class="fas fa-envelope-open text-xl"></i>
                    </div>
                </div>
                <div class="ml-5">
                    <dt class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Opened</dt>
                    <dd class="text-3xl font-black text-slate-900">{{ number_format($stats['total_opened']) }}</dd>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm text-slate-400">
                <span class="font-bold text-slate-600">{{ number_format(($stats['total_opened'] / max($stats['total_sent'], 1)) * 100, 1) }}%</span>
                <span class="ml-2 text-slate-400">Open rate</span>
            </div>
        </div>

        <div class="stat-card group">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-14 h-14 bg-purple-50 text-purple-600 rounded-2xl flex items-center justify-center group-hover:bg-purple-600 group-hover:text-white transition-all duration-300">
                        <i class="fas fa-mouse-pointer text-xl"></i>
                    </div>
                </div>
                <div class="ml-5">
                    <dt class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Clicked</dt>
                    <dd class="text-3xl font-black text-slate-900">{{ number_format($stats['total_clicked']) }}</dd>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm text-slate-400">
                <span class="font-bold text-slate-600">{{ number_format(($stats['total_clicked'] / max($stats['total_opened'], 1)) * 100, 1) }}%</span>
                <span class="ml-2 text-slate-400">CTR</span>
            </div>
        </div>

        <div class="stat-card group">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-14 h-14 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center group-hover:bg-rose-600 group-hover:text-white transition-all duration-300">
                        <i class="fas fa-exclamation-triangle text-xl"></i>
                    </div>
                </div>
                <div class="ml-5">
                    <dt class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Bounced</dt>
                    <dd class="text-3xl font-black text-slate-900">{{ number_format($stats['bounce_rate']) }}</dd>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <span class="text-rose-500 font-bold flex items-center">
                    <i class="fas fa-arrow-down mr-1"></i> 2%
                </span>
                <span class="ml-2 text-slate-400">Bounce rate</span>
            </div>
        </div>
    </div>

    <!-- Email Logs Activity Table -->
    <div class="card overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
            <h3 class="text-lg font-bold text-slate-900 flex items-center">
                <i class="fas fa-history mr-2 text-slate-400"></i>
                Recent Activity
            </h3>
            <div class="text-sm text-slate-500 font-medium">
                Last 30 days
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead>
                    <tr class="bg-slate-50">
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-widest">Recipient</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-widest hidden md:table-cell">Subject</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-widest hidden lg:table-cell">Campaign</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-widest">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-widest">Time</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($emailLogs as $log)
                        <tr class="group hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 mr-3">
                                        <i class="fas fa-user-circle text-lg"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-slate-900">{{ $log->to_email }}</div>
                                        @if($log->lead)
                                            <div class="text-xs text-slate-500 font-medium">{{ $log->lead->name }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap hidden md:table-cell">
                                <div class="text-sm text-slate-600 max-w-xs truncate">{{ $log->subject }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 hidden lg:table-cell">
                                <span class="px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 text-xs font-bold uppercase tracking-tighter">
                                    {{ $log->campaign->name ?? 'System' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-black uppercase
                                    @if($log->status === 'sent') bg-blue-50 text-blue-700
                                    @elseif($log->status === 'opened') bg-emerald-50 text-emerald-700
                                    @elseif($log->status === 'clicked') bg-purple-50 text-purple-700
                                    @elseif($log->status === 'bounced') bg-rose-50 text-rose-700
                                    @else bg-slate-50 text-slate-700
                                    @endif">
                                    <span class="w-1.5 h-1.5 rounded-full mr-2 
                                        @if($log->status === 'sent') bg-blue-500
                                        @elseif($log->status === 'opened') bg-emerald-500
                                        @elseif($log->status === 'clicked') bg-purple-500
                                        @elseif($log->status === 'bounced') bg-rose-500
                                        @else bg-slate-500
                                        @endif"></span>
                                    {{ $log->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                <div class="text-slate-900 font-medium">{{ $log->sent_at->diffForHumans() }}</div>
                                <div class="text-[10px] text-slate-400">{{ $log->sent_at->format('M j, Y H:i') }}</div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 bg-slate-50 text-slate-200 rounded-full flex items-center justify-center mb-4">
                                        <i class="fas fa-envelope-open-text text-3xl"></i>
                                    </div>
                                    <h3 class="text-lg font-bold text-slate-900 mb-1">No email logs found</h3>
                                    <p class="text-slate-500 max-w-xs mx-auto">Emails sent through the platform will appear here for tracking and analysis.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($emailLogs->hasPages())
            <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-100 font-bold">
                {{ $emailLogs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection