@extends('layouts.app')


@section('content')
<div class="space-y-8">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Email Logs</h1>
            <p class="text-slate-600 mt-2">Track email delivery and engagement</p>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
        <div class="stat-card">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-slate-500 truncate">Total Sent</dt>
                        <dd class="text-2xl font-bold text-slate-900">{{ number_format($stats['total_sent']) }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-slate-500 truncate">Opened</dt>
                        <dd class="text-2xl font-bold text-slate-900">{{ number_format($stats['total_opened']) }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-slate-500 truncate">Clicked</dt>
                        <dd class="text-2xl font-bold text-slate-900">{{ number_format($stats['total_clicked']) }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-slate-500 truncate">Bounced</dt>
                        <dd class="text-2xl font-bold text-slate-900">{{ number_format($stats['bounce_rate']) }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <!-- Email Logs Table -->
    <div class="card">
        <div class="px-6 py-5 border-b border-slate-200">
            <h3 class="text-lg font-semibold text-slate-900">Email Activity</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Recipient</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Subject</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Campaign</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Sent At</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-200">
                    @forelse($emailLogs as $log)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-slate-900">{{ $log->to_email }}</div>
                                @if($log->lead)
                                    <div class="text-sm text-slate-500">{{ $log->lead->name }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-slate-900">{{ Str::limit($log->subject, 40) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900">
                                {{ $log->campaign->name ?? 'Direct Email' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($log->status === 'sent') bg-blue-100 text-blue-800
                                    @elseif($log->status === 'opened') bg-green-100 text-green-800
                                    @elseif($log->status === 'clicked') bg-purple-100 text-purple-800
                                    @elseif($log->status === 'bounced') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst($log->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                {{ $log->sent_at->format('M j, Y g:i A') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    <h3 class="text-lg font-medium text-slate-900 mb-2">No email logs found</h3>
                                    <p class="text-slate-500">Email activity will appear here once you start sending campaigns.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($emailLogs->hasPages())
            <div class="px-6 py-4 border-t border-slate-200">
                {{ $emailLogs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection