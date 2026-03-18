@extends('layouts.app')

@section('page-title', 'Provisioning Logs')

@section('content')
<div class="max-w-5xl mx-auto space-y-8 page-fade-in">
<!-- Header -->
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-12">
    <div>
        <h1 class="text-4xl font-black text-slate-900 tracking-tight flex items-center gap-4">
            <div class="w-12 h-12 bg-white border border-slate-100 rounded-2xl flex items-center justify-center shadow-sm">
                <i class="fas fa-terminal text-blue-500"></i>
            </div>
            Execution History
            <span class="text-[10px] font-black px-3 py-1 bg-slate-100 text-slate-600 rounded-lg border border-slate-200 uppercase tracking-widest shadow-sm">
                Node: {{ $tenant->id }}
            </span>
        </h1>
        <p class="text-slate-500 font-medium mt-2 flex items-center gap-2 italic">
            <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
            Detailed diagnostic stream for infrastructure provisioning.
        </p>
    </div>
    <div class="flex items-center gap-4">
        <a href="{{ route('superadmin.tenants.index') }}" class="btn-premium-outline !py-3 !px-6 !rounded-2xl !text-xs !bg-white group">
            <i class="fas fa-arrow-left mr-2 text-slate-400 group-hover:text-slate-900 transition-colors"></i>
            Back to Registry
        </a>
    </div>
</div>

<div class="relative bg-white rounded-[3rem] border border-slate-100 overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-700">
    <!-- Decorative Accents -->
    <div class="absolute -top-24 -right-24 w-64 h-64 bg-slate-50/50 rounded-full blur-[100px] pointer-events-none"></div>
    <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-blue-50/20 rounded-full blur-[100px] pointer-events-none"></div>

    <div class="relative z-10 px-10 py-8 border-b border-slate-50 flex justify-between items-center">
        <div class="flex items-center gap-3">
            <div class="w-1.5 h-6 bg-blue-500 rounded-full"></div>
            <h2 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Live Timeline Feed</h2>
        </div>
        <div class="flex items-center gap-3 px-4 py-2 bg-emerald-50 rounded-2xl border border-emerald-100/50">
            <span class="relative flex h-2 w-2">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
            </span>
            <span class="text-[9px] font-black text-emerald-700 uppercase tracking-widest italic">Synchronizing...</span>
        </div>
    </div>

    <div class="p-10 relative z-10">
        <ul role="list" class="-mb-12 stagger-in">
            @forelse($logs as $index => $log)
                <li>
                    <div class="relative pb-12">
                        @if(!$loop->last)
                            <span class="absolute top-5 left-5 -ml-px h-full w-0.5 bg-slate-50" aria-hidden="true"></span>
                        @endif
                        <div class="relative flex items-start gap-6">
                            <div class="relative">
                                <span class="h-10 w-10 rounded-2xl flex items-center justify-center ring-8 ring-white shadow-xl/10
                                    @if($log->level === 'error') bg-rose-50 text-rose-500 border border-rose-100 shadow-rose-500/10
                                    @elseif($log->level === 'warning') bg-amber-50 text-amber-500 border border-amber-100 shadow-amber-500/10
                                    @else bg-emerald-50 text-emerald-500 border border-emerald-100 shadow-emerald-500/10
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
                            <div class="flex-1 min-w-0">
                                <div class="flex justify-between items-start gap-4">
                                    <div>
                                        <p class="text-[13px] font-black text-slate-900 group-hover:text-blue-600 transition-colors leading-relaxed">
                                            {{ $log->message }}
                                        </p>
                                        @if(isset($log->context) && !empty($log->context))
                                            <div class="mt-4 bg-slate-50/50 border border-slate-100/50 rounded-2xl p-5 group-hover:bg-white transition-all duration-500">
                                                <div class="flex items-center gap-2 mb-2">
                                                    <i class="fas fa-code text-[10px] text-slate-300"></i>
                                                    <span class="text-[8px] font-black text-slate-400 uppercase tracking-[0.2em]">Context Snapshot</span>
                                                </div>
                                                <code class="text-[10px] font-mono text-slate-500 leading-relaxed block overflow-x-auto whitespace-pre-wrap">
                                                    {{ json_encode($log->context, JSON_PRETTY_PRINT) }}
                                                </code>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="text-right flex flex-col items-end shrink-0">
                                        <time datetime="{{ $log->created_at }}" class="text-[9px] font-bold text-slate-400 uppercase tracking-widest italic">
                                            {{ $log->created_at->format('M j, Y H:i:s') }}
                                        </time>
                                        <div class="mt-2">
                                            <span class="px-3 py-1 rounded-xl text-[8px] font-black uppercase tracking-widest border
                                                @if($log->level === 'error') bg-rose-50 text-rose-600 border-rose-100
                                                @elseif($log->level === 'warning') bg-amber-50 text-amber-600 border-amber-100
                                                @else bg-emerald-50 text-emerald-600 border-emerald-100
                                                @endif">
                                                {{ $log->level }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            @empty
                <li class="py-24 text-center">
                    <div class="flex flex-col items-center justify-center stagger-in">
                        <div class="w-24 h-24 bg-slate-50 rounded-[2.5rem] flex items-center justify-center mb-6 shadow-inner border border-slate-100 group">
                            <i class="fas fa-satellite-dish text-4xl text-slate-200 group-hover:scale-110 group-hover:text-blue-500/20 transition-all duration-700"></i>
                        </div>
                        <h3 class="text-2xl font-black text-slate-900 tracking-tight">Silent Broadcast</h3>
                        <p class="text-slate-500 max-w-sm mx-auto text-sm font-medium mt-2 italic">Node initialization diagnostics have not yet generated telemetry packets.</p>
                    </div>
                </li>
            @endforelse
        </ul>
    </div>

    @if($logs->hasPages())
        <div class="px-10 py-6 bg-slate-50/50 border-t border-slate-50">
            {{ $logs->links() }}
        </div>
    @endif
</div>
</div>
@endsection
