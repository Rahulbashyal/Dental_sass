@extends('layouts.app')

@section('page-title', 'Provisioning Logs')

@section('content')
<div class="max-w-5xl mx-auto space-y-8 page-fade-in">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-slate-900 text-white rounded-2xl flex items-center justify-center shadow-lg">
                <i class="fas fa-terminal"></i>
            </div>
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight flex items-center gap-3">
                    Provisioning Logs
                    <span class="text-sm font-medium px-2 py-0.5 bg-slate-100 text-slate-600 rounded-md border border-slate-200">
                        {{ $tenant->id }}
                    </span>
                </h1>
                <p class="text-slate-500 mt-1">Detailed execution history and diagnostics for tenant initialization</p>
            </div>
        </div>
        <a href="{{ route('superadmin.tenants.index') }}" class="btn-secondary flex items-center gap-2">
            <i class="fas fa-arrow-left text-slate-400"></i>
            Back to Tenants
        </a>
    </div>

    <div class="card p-0 overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
            <h3 class="text-lg font-bold text-slate-900 flex items-center">
                <i class="fas fa-list-ul mr-2 text-slate-400"></i>
                Execution Timeline
            </h3>
            <div class="flex items-center gap-2">
                <span class="h-2 w-2 bg-emerald-500 rounded-full"></span>
                <span class="text-xs font-bold text-slate-500 uppercase">Live Stream</span>
            </div>
        </div>

        <div class="p-6">
            <div class="flow-root">
                <ul role="list" class="-mb-8 stagger-in">
                    @forelse($logs as $index => $log)
                        <li>
                            <div class="relative pb-8">
                                @if(!$loop->last)
                                    <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-slate-200" aria-hidden="true"></span>
                                @endif
                                <div class="relative flex space-x-3">
                                    <div>
                                        <span class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white
                                            @if($log->level === 'error') bg-rose-500 text-white
                                            @elseif($log->level === 'warning') bg-amber-500 text-white
                                            @else bg-emerald-500 text-white
                                            @endif">
                                            @if($log->level === 'error')
                                                <i class="fas fa-times text-xs"></i>
                                            @elseif($log->level === 'warning')
                                                <i class="fas fa-exclamation text-xs"></i>
                                            @else
                                                <i class="fas fa-check text-xs"></i>
                                            @endif
                                        </span>
                                    </div>
                                    <div class="flex-1 min-w-0 flex justify-between space-x-4 pt-1.5">
                                        <div>
                                            <p class="text-sm font-bold text-slate-900">
                                                {{ $log->message }}
                                            </p>
                                            @if(isset($log->context) && !empty($log->context))
                                                <div class="mt-2 bg-slate-50 rounded-lg p-3 font-mono text-xs text-slate-600 border border-slate-100 italic">
                                                    {{ json_encode($log->context) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="text-right text-xs whitespace-nowrap text-slate-500 font-medium">
                                            <time datetime="{{ $log->created_at }}">{{ $log->created_at->format('M j, Y H:i:s') }}</time>
                                            <div class="mt-1 flex justify-end">
                                                <span class="px-2 py-0.5 rounded-md text-[10px] font-black uppercase tracking-tighter
                                                    @if($log->level === 'error') bg-rose-50 text-rose-700
                                                    @elseif($log->level === 'warning') bg-amber-50 text-amber-700
                                                    @else bg-emerald-50 text-emerald-700
                                                    @endif">
                                                    {{ $log->level }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @empty
                        <li class="py-12 text-center">
                            <i class="fas fa-search text-slate-200 text-4xl mb-4"></i>
                            <p class="text-slate-500 font-medium">No logs recorded for this tenant yet.</p>
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>

        @if($logs->hasPages())
            <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-100">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
