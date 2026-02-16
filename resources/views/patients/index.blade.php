@extends('layouts.app')

@section('page-title', 'Clinical Registry: Patients')

@section('content')
<div class="page-fade-in space-y-8">
    <!-- Premium Clinical Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="stagger-in">
            <div class="flex items-center space-x-3 text-slate-500 text-sm mb-2">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-600 transition-colors">Healthcare Hub</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                <span class="text-slate-900 font-medium">Patient Registry</span>
            </div>
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-200">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Clinical Registry</h1>
                    <p class="text-slate-500 font-medium italic">Comprehensive Patient Demographic Ledger</p>
                </div>
            </div>
        </div>
        
        <div class="flex items-center space-x-3 stagger-in" style="--delay: 1">
            <a href="{{ route('clinic.patients.create') }}" class="group relative px-6 py-3 bg-blue-600 text-white rounded-2xl font-bold flex items-center space-x-2 hover:bg-blue-700 transition-all shadow-lg shadow-blue-100 active:scale-95">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                <span>Onboard New Patient</span>
            </a>
        </div>
    </div>

    <!-- Advanced Filter Hub -->
    <div class="stagger-in bg-white rounded-3xl border border-slate-100 shadow-sm p-6" style="--delay: 2">
        <form method="GET" action="{{ route('clinic.patients.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Subject Search</label>
                <div class="relative group">
                    <input type="text" name="search" value="{{ request('search') }}" 
                        placeholder="Search by Name, Phone, or Email..." 
                        class="w-full pl-12 pr-4 py-3 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all text-slate-700 font-medium placeholder-slate-400">
                    <svg class="absolute left-4 top-3.5 w-5 h-5 text-slate-400 group-focus-within:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Clinical Status</label>
                <select name="status" class="w-full px-4 py-3 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all text-slate-700 font-medium">
                    <option value="active">Active Patients</option>
                    <option value="inactive">Archived Records</option>
                </select>
            </div>

            <div class="flex items-end">
                <button type="submit" class="w-full py-3 bg-slate-900 text-white rounded-2xl font-bold hover:bg-black transition-all active:scale-95 flex items-center justify-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    <span>Refine Results</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Registry Directory -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($patients as $index => $patient)
            <div class="stagger-in group" style="--delay: {{ 3 + $index }}">
                <div class="bg-white rounded-[2.5rem] p-6 border border-slate-100 shadow-sm hover:shadow-xl transition-all duration-500 relative overflow-hidden h-full flex flex-col">
                    <!-- Status Badge -->
                    <div class="absolute top-6 right-6">
                        <span class="flex items-center space-x-1.5 bg-blue-50 text-blue-600 px-3 py-1.5 rounded-full text-xs font-bold tracking-tight border border-blue-100">
                            <span class="w-1.5 h-1.5 bg-blue-500 rounded-full animate-pulse"></span>
                            <span>ACTIVE</span>
                        </span>
                    </div>

                    <!-- Profile Header -->
                    <div class="flex items-center space-x-4 mb-6">
                        <div class="relative">
                            <div class="w-16 h-16 rounded-[1.5rem] bg-gradient-to-br from-blue-600 to-indigo-700 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-500">
                                <span class="text-white font-bold text-2xl tracking-tighter">{{ substr($patient->first_name ?? $patient->name, 0, 1) }}</span>
                            </div>
                            <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-white rounded-full flex items-center justify-center shadow-md">
                                <div class="w-4 h-4 bg-blue-100 rounded-full flex items-center justify-center text-[10px] text-blue-600 font-bold">
                                    {{ $patient->gender ? substr($patient->gender, 0, 1) : 'P' }}
                                </div>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-slate-900 group-hover:text-blue-600 transition-colors leading-tight">
                                {{ $patient->name }}
                            </h3>
                            <p class="text-slate-500 text-sm font-medium">UID: #PAT-{{ str_pad($patient->id, 5, '0', STR_PAD_LEFT) }}</p>
                        </div>
                    </div>

                    <!-- Clinical Data Grid -->
                    <div class="grid grid-cols-2 gap-3 mb-6">
                        <div class="bg-slate-50 rounded-2xl p-3">
                            <span class="block text-[10px] font-bold text-slate-400 uppercase mb-0.5 tracking-wider">Contact</span>
                            <span class="text-slate-700 font-bold truncate block text-xs">{{ $patient->phone }}</span>
                        </div>
                        <div class="bg-slate-50 rounded-2xl p-3">
                            <span class="block text-[10px] font-bold text-slate-400 uppercase mb-0.5 tracking-wider">Demographics</span>
                            <span class="text-slate-700 font-bold block text-xs">
                                {{ $patient->date_of_birth ? \Carbon\Carbon::parse($patient->date_of_birth)->age . ' Yrs' : 'N/A' }}
                            </span>
                        </div>
                    </div>

                    <!-- Recent Instructions/History -->
                    <div class="mb-6 flex-grow">
                        <div class="flex items-center space-x-2 mb-2">
                            <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4a1 1 0 00-1.414-1.414L11 9.586V7z"></path></svg>
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">Medical Context</span>
                        </div>
                        <p class="text-slate-600 text-sm line-clamp-2 italic leading-relaxed">
                            {{ $patient->medical_history ?: 'No specific clinical history recorded for this subject.' }}
                        </p>
                    </div>

                    <!-- Action Hub -->
                    <div class="flex items-center space-x-3 pt-6 border-t border-slate-50">
                        <a href="{{ route('clinic.patients.show', $patient) }}" class="flex-1 px-4 py-2.5 bg-blue-50 text-blue-700 rounded-xl font-bold text-sm hover:bg-blue-100 transition-colors flex items-center justify-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            <span>Open Record</span>
                        </a>
                        <a href="{{ route('clinic.patients.edit', $patient) }}" class="w-11 h-11 bg-slate-50 text-slate-600 rounded-xl flex items-center justify-center hover:bg-slate-100 hover:text-blue-600 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="md:col-span-3 card text-center py-20 bg-slate-50 border-dashed border-2 border-slate-200 rounded-[3rem]">
                <div class="w-24 h-24 bg-white rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-sm">
                    <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <h3 class="text-2xl font-bold text-slate-900 mb-2">Registry Empty</h3>
                <p class="text-slate-500 mb-8 max-w-sm mx-auto font-medium leading-relaxed">No subject records were found in the clinical repository. Begin by onboarding your first subject.</p>
                <a href="{{ route('clinic.patients.create') }}" class="inline-flex items-center space-x-2 px-8 py-4 bg-blue-600 text-white rounded-2xl font-bold hover:bg-blue-700 transition-all shadow-lg shadow-blue-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    <span>Initialize Patient Registry</span>
                </a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($patients->hasPages())
        <div class="flex justify-center pt-8">
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-4 px-6 scale-90 md:scale-100">
                {{ $patients->links() }}
            </div>
        </div>
    @endif
</div>
@endsection