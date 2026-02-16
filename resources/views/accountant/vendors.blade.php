@extends('layouts.app')

@section('page-title', 'Financial: Vendor Repository')

@section('content')
<div class="page-fade-in space-y-8 pb-12">
    <!-- Premium Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="stagger-in">
            <div class="flex items-center space-x-3 text-slate-500 text-sm mb-2">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-600 transition-colors">Healthcare Hub</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                <span class="text-slate-900 font-medium">Vendor Repository</span>
            </div>
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-100">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Partner Entities</h1>
                    <p class="text-slate-500 font-medium italic">Strategic Procurement & Service Matrix</p>
                </div>
            </div>
        </div>
        
        <div class="flex items-center space-x-3 stagger-in" style="--delay: 1">
            <button onclick="document.getElementById('vendorModal').classList.remove('hidden')" class="group relative px-6 py-3 bg-blue-600 text-white rounded-2xl font-bold flex items-center space-x-2 hover:bg-blue-700 transition-all shadow-lg shadow-blue-100 active:scale-95">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                <span>Initialize Vendor Node</span>
            </button>
        </div>
    </div>

    <!-- Stats & Filters Registry -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="stagger-in bg-white rounded-[2rem] p-6 border border-slate-100 shadow-sm" style="--delay: 1.5">
            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Entites</span>
            <div class="flex items-end space-x-2">
                <span class="text-3xl font-black text-slate-900 tracking-tighter">{{ $vendors->total() }}</span>
                <span class="text-emerald-500 font-bold text-xs mb-1.5 flex items-center">
                    <svg class="w-3 h-3 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    Partner Growth
                </span>
            </div>
        </div>

        <!-- Filters Hub -->
        <div class="md:col-span-3 stagger-in bg-white rounded-[2rem] p-4 border border-slate-100 shadow-sm flex items-center" style="--delay: 2">
            <div class="flex flex-col md:flex-row w-full gap-3 p-2">
                <input type="text" placeholder="Search by name, category or ID..." class="flex-1 px-5 py-3 bg-slate-50 border-none rounded-2xl text-sm font-medium focus:ring-2 focus:ring-blue-500 transition-all">
                <select class="px-5 py-3 bg-slate-50 border-none rounded-2xl text-sm font-medium focus:ring-2 focus:ring-blue-500 transition-all w-48">
                    <option value="">All Categories</option>
                    <option value="supplies">Supplies</option>
                    <option value="equipment">Equipment</option>
                </select>
                <button class="px-6 py-3 bg-slate-900 text-white rounded-2xl font-bold text-sm hover:bg-slate-800 transition-all shadow-lg active:scale-95">
                    Sync Registry
                </button>
            </div>
        </div>
    </div>

    <!-- Vendor Directory -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($vendors as $index => $vendor)
            <div class="stagger-in group" style="--delay: {{ 3 + $index }}">
                <div class="bg-white rounded-[2.5rem] p-6 border border-slate-100 shadow-sm hover:shadow-xl transition-all duration-500 relative overflow-hidden h-full flex flex-col">
                    <!-- Status Badge -->
                    <div class="absolute top-6 right-6">
                        <span class="flex items-center space-x-1.5 bg-blue-50 text-blue-600 px-3 py-1.5 rounded-full text-[10px] font-black tracking-tight border border-blue-100 uppercase">
                            {{ $vendor->category ?? 'General Partner' }}
                        </span>
                    </div>

                    <!-- Vendor Header -->
                    <div class="flex items-center space-x-4 mb-6">
                        <div class="w-16 h-16 rounded-[1.5rem] bg-gradient-to-br from-blue-600 to-indigo-700 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-500 text-white text-2xl font-bold">
                            {{ substr($vendor->name, 0, 1) }}
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-slate-900 group-hover:text-blue-600 transition-colors leading-tight">
                                {{ $vendor->name }}
                            </h3>
                            <p class="text-slate-500 text-sm font-medium">{{ $vendor->contact_person ?? 'Protocol Identity' }}</p>
                        </div>
                    </div>

                    <!-- Communication Channel -->
                    <div class="mb-6 space-y-3 flex-grow">
                        <div class="bg-slate-50 rounded-2xl p-3">
                            <span class="block text-[10px] font-bold text-slate-400 uppercase mb-1 tracking-wider">Transmission Vector</span>
                            @if($vendor->email)
                            <div class="flex items-center space-x-2 text-slate-700 font-bold text-xs mb-1 truncate">
                                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                <span>{{ $vendor->email }}</span>
                            </div>
                            @endif
                            @if($vendor->phone)
                            <div class="flex items-center space-x-2 text-slate-700 font-bold text-xs">
                                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                <span>{{ $vendor->phone }}</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Action Hub -->
                    <div class="flex items-center space-x-3 pt-6 border-t border-slate-50">
                        <button class="flex-1 px-4 py-2.5 bg-blue-50 text-blue-700 rounded-xl font-bold text-sm hover:bg-blue-100 transition-colors flex items-center justify-center space-x-2">
                             <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            <span>Refine Node</span>
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="md:col-span-3 card text-center py-20 bg-slate-50 border-dashed border-2 border-slate-200 rounded-[3rem]">
                <div class="w-24 h-24 bg-white rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-sm">
                    <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-slate-900 mb-2">Registry Offline</h3>
                <p class="text-slate-500 mb-8 max-w-sm mx-auto font-medium leading-relaxed">No strategic entities were found in the operations health repository.</p>
                <button onclick="document.getElementById('vendorModal').classList.remove('hidden')" class="inline-flex items-center space-x-2 px-8 py-4 bg-blue-600 text-white rounded-2xl font-bold hover:bg-blue-700 transition-all shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    <span>Initialize Partner Node</span>
                </button>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($vendors->hasPages())
        <div class="flex justify-center pt-8">
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-4 px-6">
                {{ $vendors->links() }}
            </div>
        </div>
    @endif
</div>

<!-- Add Vendor Modal -->
<div id="vendorModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-md z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-xl overflow-hidden border border-slate-100">
        <div class="p-8 border-b border-slate-50 flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                </div>
                <h3 class="font-bold text-2xl text-slate-900 tracking-tight">Onboard Vendor Node</h3>
            </div>
            <button onclick="document.getElementById('vendorModal').classList.add('hidden')" class="w-10 h-10 bg-slate-50 text-slate-400 rounded-xl flex items-center justify-center hover:bg-slate-100 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <form action="{{ route('clinic.vendors.store') }}" method="POST" class="p-8 space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-sm">
                <div class="space-y-2 md:col-span-2">
                    <label class="block font-bold text-slate-700 tracking-tight">Entity Identity <span class="text-blue-500">*</span></label>
                    <input type="text" name="name" required 
                           class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-medium text-slate-700 placeholder-slate-400" 
                           placeholder="Corporate or Individual Designation">
                </div>

                <div class="space-y-2">
                    <label class="block font-bold text-slate-700 tracking-tight">Primary Liaison</label>
                    <input type="text" name="contact_person" 
                           class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-medium text-slate-700 placeholder-slate-400" 
                           placeholder="Liaison Name">
                </div>

                <div class="space-y-2">
                    <label class="block font-bold text-slate-700 tracking-tight">Categorical Node</label>
                    <select name="category" class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-medium text-slate-700">
                        <option value="Supplies">Clinical Supplies</option>
                        <option value="Equipment">Healthcare Technology</option>
                        <option value="IT">IT Infrastructure</option>
                        <option value="Maintenance">Facility Maintenance</option>
                        <option value="Lab">Laboratory Partners</option>
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="block font-bold text-slate-700 tracking-tight">Transmission Email</label>
                    <input type="email" name="email" 
                           class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-medium text-slate-700 placeholder-slate-400" 
                           placeholder="partner@domain.com">
                </div>

                <div class="space-y-2">
                    <label class="block font-bold text-slate-700 tracking-tight">Contact Sequence</label>
                    <input type="text" name="phone" 
                           class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-medium text-slate-700 placeholder-slate-400" 
                           placeholder="+977-XXX-XXXXXX">
                </div>
            </div>

            <div class="pt-6 border-t border-slate-50">
                <button type="submit" class="w-full bg-blue-600 text-white py-4 rounded-2xl font-bold hover:bg-blue-700 transition-all shadow-lg shadow-blue-100 active:scale-95 flex items-center justify-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span>Validate & Commit Node</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
