@extends('layouts.app')

@section('page-title', 'Supply Chain: Partners')

@section('content')
<div class="page-fade-in space-y-8">
    <!-- Premium Operations Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="stagger-in">
            <div class="flex items-center space-x-3 text-slate-500 text-sm mb-2">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-600 transition-colors">Operations Hub</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                <span class="text-slate-900 font-medium">Supplier Portfolio</span>
            </div>
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-100">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Supplier Portfolio</h1>
                    <p class="text-slate-500 font-medium italic">Global Procurement Node Directory</p>
                </div>
            </div>
        </div>
        
        <div class="flex items-center space-x-3 stagger-in" style="--delay: 1">
            <a href="{{ route('clinic.suppliers.create') }}" class="group relative px-6 py-3 bg-blue-600 text-white rounded-2xl font-bold flex items-center space-x-2 hover:bg-blue-700 transition-all shadow-lg shadow-blue-100 active:scale-95">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                <span>Initialize Key Partner</span>
            </a>
        </div>
    </div>

    <!-- Partner Directory -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($suppliers as $index => $supplier)
            <div class="stagger-in group" style="--delay: {{ 2 + $index }}">
                <div class="bg-white rounded-[2.5rem] p-6 border border-slate-100 shadow-sm hover:shadow-xl transition-all duration-500 relative overflow-hidden h-full flex flex-col">
                    <!-- Order Badge -->
                    <div class="absolute top-6 right-6">
                        <span class="flex items-center space-x-1.5 {{ $supplier->purchase_orders_count > 0 ? 'bg-blue-50 text-blue-600 border-blue-100' : 'bg-slate-50 text-slate-400 border-slate-100' }} px-3 py-1.5 rounded-full text-xs font-bold tracking-tight border">
                            <span>{{ $supplier->purchase_orders_count }} ACTIVE ORDERS</span>
                        </span>
                    </div>

                    <!-- Partner Header -->
                    <div class="flex items-center space-x-4 mb-6">
                        <div class="w-16 h-16 rounded-[1.5rem] bg-gradient-to-br from-blue-600 to-indigo-700 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-500 font-bold text-white text-2xl">
                            {{ substr($supplier->name, 0, 1) }}
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-slate-900 group-hover:text-blue-600 transition-colors leading-tight">
                                {{ $supplier->name }}
                            </h3>
                            <p class="text-slate-500 text-sm font-medium">Partner Node #{{ str_pad($supplier->id, 4, '0', STR_PAD_LEFT) }}</p>
                        </div>
                    </div>

                    <!-- Logistics Intel Grid -->
                    <div class="grid grid-cols-1 gap-3 mb-6">
                        <div class="bg-slate-50 rounded-2xl p-4">
                            <span class="block text-[10px] font-bold text-slate-400 uppercase mb-2 tracking-wider">Communication Channel</span>
                            @if($supplier->email)
                            <div class="flex items-center space-x-2 text-slate-700 font-bold text-xs mb-1">
                                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                <span>{{ $supplier->email }}</span>
                            </div>
                            @endif
                            @if($supplier->phone)
                            <div class="flex items-center space-x-2 text-slate-700 font-bold text-xs">
                                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                <span>{{ $supplier->phone }}</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Action Hub -->
                    <div class="flex items-center space-x-3 pt-6 border-t border-slate-50 mt-auto">
                        <a href="{{ route('clinic.suppliers.edit', $supplier) }}" class="flex-1 px-4 py-2.5 bg-blue-50 text-blue-700 rounded-xl font-bold text-sm hover:bg-blue-100 transition-colors flex items-center justify-center space-x-2">
                             <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            <span>Refine Details</span>
                        </a>
                        <form action="{{ route('clinic.suppliers.destroy', $supplier) }}" method="POST" class="inline-block" onsubmit="return confirm('Abort Partnership: This will permanently remove the supplier. Continue?')">
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
                    <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
                <h3 class="text-2xl font-bold text-slate-900 mb-2">Portfolio Empty</h3>
                <p class="text-slate-500 mb-8 max-w-sm mx-auto font-medium leading-relaxed">No strategic partners were found in the operations repository. Begin by initializing your first supply node.</p>
                <a href="{{ route('clinic.suppliers.create') }}" class="inline-flex items-center space-x-2 px-8 py-4 bg-blue-600 text-white rounded-2xl font-bold hover:bg-blue-700 transition-all shadow-lg shadow-blue-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    <span>Initialize Supply Chain</span>
                </a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($suppliers->hasPages())
        <div class="flex justify-center pt-8">
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-4 px-6 scale-90 md:scale-100">
                {{ $suppliers->links() }}
            </div>
        </div>
    @endif
</div>
@endsection
