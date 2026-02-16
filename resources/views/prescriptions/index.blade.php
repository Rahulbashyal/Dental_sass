@extends('layouts.app')

@section('page-title', 'Clinical Ledger: Prescriptions')

@section('content')
<div class="space-y-8 page-fade-in">
    <!-- Premium Header Section -->
    <div class="relative overflow-hidden bg-white rounded-3xl p-8 shadow-sm border border-slate-200">
        <div class="absolute top-0 right-0 -mt-20 -mr-20 w-64 h-64 bg-blue-500/5 rounded-full blur-3xl"></div>
        <div class="relative flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div>
                <div class="flex items-center space-x-3 mb-2">
                    <div class="p-2 bg-blue-100 rounded-lg text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h1 class="text-3xl font-black text-slate-900 tracking-tight">Clinical Ledger</h1>
                </div>
                <p class="text-slate-500 font-medium italic">Pharmacopeian record of patient prescriptions and medication history.</p>
            </div>
            
            <a href="{{ route('clinic.prescriptions.create') }}" class="group flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-2xl shadow-lg shadow-blue-600/20 transition-all hover:-translate-y-0.5 active:scale-95">
                <svg class="w-5 h-5 mr-2 transition-transform group-hover:rotate-180 duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Issue New Prescription
            </a>
        </div>
    </div>

    <!-- Advanced Filter Interface -->
    <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="space-y-2">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Archive Search</label>
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Ex: PRX-2025..."
                           class="w-full pl-10 pr-4 py-3 bg-slate-50 border-slate-200 rounded-2xl text-slate-900 font-bold focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none">
                    <svg class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
            </div>

            <div class="space-y-2">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Patient Subject</label>
                <select name="patient_id" class="w-full px-4 py-3 bg-slate-50 border-slate-200 rounded-2xl text-slate-900 font-bold focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none appearance-none">
                    <option value="">All Subjects</option>
                    @foreach($patients as $patient)
                        <option value="{{ $patient->id }}" {{ request('patient_id') == $patient->id ? 'selected' : '' }}>
                            {{ $patient->first_name }} {{ $patient->last_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="space-y-2">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Medication Status</label>
                <select name="status" class="w-full px-4 py-3 bg-slate-50 border-slate-200 rounded-2xl text-slate-900 font-bold focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none appearance-none">
                    <option value="">Full Records</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active Regiment</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed Cycle</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Voided/Cancelled</option>
                </select>
            </div>

            <div class="flex items-end gap-3">
                <button type="submit" class="flex-1 px-4 py-3 bg-slate-900 text-white font-black rounded-2xl hover:bg-slate-800 transition-all shadow-lg shadow-slate-900/10">
                    Apply Filter
                </button>
                <a href="{{ route('clinic.prescriptions.index') }}" class="px-4 py-3 bg-slate-100 text-slate-600 font-bold rounded-2xl hover:bg-slate-200 transition-all">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Clinical Ledger Table -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-200 overflow-hidden">
        @if($prescriptions->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100">Index & ID</th>
                            <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100">Patient Data</th>
                            <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100">Practitioner</th>
                            <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100">Items</th>
                            <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100 text-center">Lifecycle</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 stagger-in">
                        @foreach($prescriptions as $prescription)
                            <tr class="group hover:bg-slate-50/80 transition-all duration-300">
                                <td class="px-8 py-6">
                                    <div class="space-y-1">
                                        <p class="text-sm font-black text-blue-600 font-mono tracking-tighter">{{ $prescription->prescription_number }}</p>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $prescription->prescribed_date->format('d M, Y') }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-6">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-blue-600 flex items-center justify-center text-white font-black shadow-sm transition-transform group-hover:scale-110">
                                            {{ substr($prescription->patient->first_name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-black text-slate-900">{{ $prescription->patient->first_name }} {{ $prescription->patient->last_name }}</p>
                                            <p class="text-[10px] font-bold text-slate-400">{{ $prescription->patient->phone }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-6">
                                    <div class="flex items-center space-x-2">
                                        <span class="w-2 h-2 rounded-full bg-blue-400"></span>
                                        <p class="text-sm font-bold text-slate-700">Dr. {{ $prescription->dentist->name }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-6">
                                    <div class="inline-flex items-center space-x-2 px-3 py-1.5 bg-slate-100 rounded-xl text-slate-600">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                        <span class="text-xs font-black">{{ $prescription->items->count() }} Meds</span>
                                    </div>
                                </td>
                                <td class="px-6 py-6 text-center">
                                    @if($prescription->status === 'active')
                                        <span class="relative inline-flex items-center px-3 py-1 text-[10px] font-black text-emerald-700 uppercase tracking-widest bg-emerald-50 border border-emerald-100 rounded-full overflow-hidden">
                                            <span class="absolute left-0 top-0 h-full w-1 bg-emerald-500 animate-pulse"></span>
                                            Active
                                        </span>
                                    @elseif($prescription->status === 'completed')
                                        <span class="inline-flex items-center px-3 py-1 text-[10px] font-black text-blue-700 uppercase tracking-widest bg-blue-50 border border-blue-100 rounded-full">
                                            Completed
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 text-[10px] font-black text-rose-700 uppercase tracking-widest bg-rose-50 border border-rose-100 rounded-full">
                                            Voided
                                        </span>
                                    @endif
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex items-center justify-end space-x-3">
                                        <a href="{{ route('clinic.prescriptions.show', $prescription) }}" 
                                           class="p-2 text-blue-600 hover:bg-blue-50 rounded-xl transition-all hover:scale-110" title="Full Record">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        </a>
                                        <a href="{{ route('clinic.prescriptions.pdf', $prescription) }}" 
                                           class="p-2 text-emerald-600 hover:bg-emerald-50 rounded-xl transition-all hover:scale-110" title="Download PDF">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        </a>
                                        <a href="{{ route('clinic.prescriptions.print', $prescription) }}" 
                                           target="_blank"
                                           class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all hover:scale-110" title="Direct Print">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            @if($prescriptions->hasPages())
                <div class="px-8 py-6 bg-slate-50/50 border-t border-slate-100">
                    {{ $prescriptions->links() }}
                </div>
            @endif
        @else
            <div class="px-8 py-24 text-center">
                <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="h-12 w-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-black text-slate-900 mb-2">Empty Ledger</h3>
                <p class="text-slate-500 font-medium mb-8">No prescriptions have been issued matching your current filters.</p>
                <a href="{{ route('clinic.prescriptions.create') }}" class="inline-flex items-center px-8 py-4 bg-blue-600 text-white rounded-2xl font-bold shadow-lg shadow-blue-600/20 hover:scale-105 transition-transform">
                    Start New Clinical Record
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
