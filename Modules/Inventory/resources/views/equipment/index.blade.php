@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">Equipment Tracker</h2>
            <p class="text-sm text-slate-500">Manage dental units, X-ray machines, and autoclave maintenance.</p>
        </div>
        <button class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow-sm">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Register Asset
        </button>
    </div>

    <!-- Equipment Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($equipment as $asset)
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden hover:shadow-md transition-all">
                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="h-12 w-12 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600">
                             <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path>
                             </svg>
                        </div>
                        @php
                            $statusClass = match($asset->status) {
                                'operational' => 'bg-green-100 text-green-700',
                                'under_maintenance' => 'bg-amber-100 text-amber-700',
                                'retired' => 'bg-red-100 text-red-700',
                            };
                        @endphp
                        <span class="px-2 py-0.5 rounded text-[9px] font-black uppercase tracking-widest {{ $statusClass }}">
                            {{ str_replace('_', ' ', $asset->status) }}
                        </span>
                    </div>
                    <h3 class="text-lg font-black text-slate-900 mb-1">{{ $asset->name }}</h3>
                    <p class="text-xs text-slate-500 mb-4">{{ $asset->model ?? 'Model Unknown' }} | S/N: {{ $asset->serial_number ?? 'N/A' }}</p>
                    
                    <div class="space-y-3 pt-4 border-t border-slate-50">
                        <div class="flex justify-between items-center">
                            <span class="text-[10px] font-bold text-slate-400 uppercase">Warranty Exp.</span>
                            <span class="text-xs font-bold text-slate-700">{{ $asset->warranty_expiry ? $asset->warranty_expiry->format('M d, Y') : 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-[10px] font-bold text-slate-400 uppercase">Last Service</span>
                            <span class="text-xs font-bold text-slate-700">{{ $asset->last_maintenance_at ? $asset->last_maintenance_at->format('M d, Y') : 'Never' }}</span>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50 px-6 py-4 flex items-center justify-between border-t border-slate-100">
                    <button class="text-xs font-bold text-indigo-600 hover:text-indigo-800">Log Maintenance</button>
                    <button class="text-xs font-bold text-slate-400 hover:text-slate-600">Details</button>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center bg-white rounded-2xl border border-dashed border-slate-300">
                <p class="text-slate-500 italic">No equipment assets registered yet.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
