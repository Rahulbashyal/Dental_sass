@extends('layouts.app')

@section('page-title', 'Clinical: Asset Registry')

@section('content')
<div class="page-fade-in space-y-8">
    <!-- Premium Operations Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="stagger-in">
            <div class="flex items-center space-x-3 text-slate-500 text-sm mb-2">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-600 transition-colors">Healthcare Hub</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                <span class="text-slate-900 font-medium">Asset Registry</span>
            </div>
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-100">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Clinical Assets</h1>
                    <p class="text-slate-500 font-medium italic">Operational Technology & Life-Support Matrix</p>
                </div>
            </div>
        </div>
        
        <div class="flex items-center space-x-3 stagger-in" style="--delay: 1">
            <a href="{{ route('clinic.equipment.create') }}" class="group relative px-6 py-3 bg-blue-600 text-white rounded-2xl font-bold flex items-center space-x-2 hover:bg-blue-700 transition-all shadow-lg shadow-blue-100 active:scale-95">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                <span>Initialize New Asset</span>
            </a>
        </div>
    </div>

    <!-- Asset Repository -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($equipment as $index => $item)
            <div class="stagger-in group" style="--delay: {{ 2 + $index }}">
                <div class="bg-white rounded-[2.5rem] p-6 border border-slate-100 shadow-sm hover:shadow-xl transition-all duration-500 relative overflow-hidden h-full flex flex-col">
                    <!-- Status Badge -->
                    <div class="absolute top-6 right-6">
                         @php
                            $statusChips = [
                                'operational' => 'bg-blue-50 text-blue-600 border-blue-100',
                                'under_maintenance' => 'bg-cyan-50 text-cyan-600 border-cyan-100',
                                'retired' => 'bg-slate-50 text-slate-500 border-slate-200',
                            ];
                        @endphp
                        <span class="flex items-center space-x-1.5 {{ $statusChips[$item->status] ?? 'bg-slate-50 text-slate-500 px-3 py-1.5 rounded-full border border-slate-100' }} px-3 py-1.5 rounded-full text-xs font-bold tracking-tight border">
                            @if($item->status == 'operational') <span class="w-1.5 h-1.5 bg-blue-500 rounded-full animate-pulse"></span> @endif
                            <span>{{ strtoupper(str_replace('_', ' ', $item->status)) }}</span>
                        </span>
                    </div>

                    <!-- Asset Header -->
                    <div class="flex items-center space-x-4 mb-6">
                        <div class="w-16 h-16 rounded-[1.5rem] bg-gradient-to-br from-blue-600 to-indigo-700 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-500 text-white text-2xl font-bold">
                            {{ substr($item->name, 0, 1) }}
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-slate-900 group-hover:text-blue-600 transition-colors leading-tight">
                                {{ $item->name }}
                            </h3>
                            <p class="text-slate-500 text-sm font-medium">{{ $item->model ?? 'Standard Protocol' }}</p>
                        </div>
                    </div>

                    <!-- Lifecycle Intel -->
                    <div class="grid grid-cols-2 gap-3 mb-6">
                        <div class="bg-slate-50 rounded-2xl p-3">
                            <span class="block text-[10px] font-bold text-slate-400 uppercase mb-0.5 tracking-wider">Serial Identity</span>
                            <span class="text-slate-700 font-bold block text-xs truncate">{{ $item->serial_number ?? 'NON-SERIALIZED' }}</span>
                        </div>
                        <div class="bg-slate-50 rounded-2xl p-3">
                            <span class="block text-[10px] font-bold text-slate-400 uppercase mb-0.5 tracking-wider">Warranty EOL</span>
                            <span class="text-slate-700 font-bold block text-xs">
                                {{ $item->warranty_expiry ? $item->warranty_expiry->format('M d, Y') : 'UNRESTRICTED' }}
                            </span>
                        </div>
                    </div>

                    <!-- Maintenance History -->
                    <div class="mb-6 flex-grow">
                        <div class="flex items-center space-x-2 mb-2">
                            <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Last Maintenance Sync</span>
                        </div>
                        <p class="text-slate-600 font-bold text-sm italic">
                            {{ $item->last_maintenance_at ? $item->last_maintenance_at->format('M d, Y') : 'No prior sync logs found.' }}
                        </p>
                    </div>

                    <!-- Action Hub -->
                    <div class="flex items-center space-x-3 pt-6 border-t border-slate-50">
                        <a href="{{ route('clinic.equipment.edit', $item) }}" class="flex-1 px-4 py-2.5 bg-blue-50 text-blue-700 rounded-xl font-bold text-sm hover:bg-blue-100 transition-colors flex items-center justify-center space-x-2">
                             <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            <span>Refactor node</span>
                        </a>
                        <form action="{{ route('clinic.equipment.destroy', $item) }}" method="POST" class="inline-block" onsubmit="return confirm('Decommission Asset: This will permanently remove the record. Continue?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-11 h-11 bg-rose-50 text-rose-600 rounded-xl flex items-center justify-center hover:bg-rose-100 transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="md:col-span-3 card text-center py-20 bg-slate-50 border-dashed border-2 border-slate-200 rounded-[3rem]">
                <div class="w-24 h-24 bg-white rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-sm">
                    <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-slate-900 mb-2">Asset Registry Offline</h3>
                <p class="text-slate-500 mb-8 max-w-sm mx-auto font-medium leading-relaxed">No clinical technology assets were found in the health repository. Begin by initializing your first asset node.</p>
                <a href="{{ route('clinic.equipment.create') }}" class="inline-flex items-center space-x-2 px-8 py-4 bg-blue-600 text-white rounded-2xl font-bold hover:bg-blue-700 transition-all shadow-lg shadow-blue-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    <span>Initialize Asset Flux</span>
                </a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($equipment->hasPages())
        <div class="flex justify-center pt-8">
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-4 px-6 scale-90 md:scale-100">
                {{ $equipment->links() }}
            </div>
        </div>
    @endif
</div>
@endsection
