@extends('layouts.app')

@section('page-title', 'Clinical Notes Directory')

@section('content')
<div class="page-fade-in space-y-8 pb-12">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="stagger-in">
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">Clinical Archive</h1>
            <p class="text-slate-500 font-medium">Consolidated repository of all patient clinical interactions and medical notes.</p>
        </div>
        <div class="flex gap-3 stagger-in" style="--delay: 1">
            <div class="relative">
                <input type="text" placeholder="Search notes..." class="pl-10 pr-4 py-3 bg-white border border-slate-200 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-blue-500 outline-none transition-all w-64 shadow-sm">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
            </div>
            <button class="px-6 py-3 bg-slate-900 text-white rounded-2xl font-bold shadow-lg shadow-slate-200 hover:bg-slate-800 transition-all flex items-center gap-2">
                <i class="fas fa-filter text-xs"></i> Advanced Filters
            </button>
        </div>
    </div>

    <!-- Stats Matrix -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 stagger-in" style="--delay: 2">
        <div class="premium-card p-6 flex items-center bg-gradient-to-br from-blue-50 to-white border-blue-100">
            <div class="w-14 h-14 bg-blue-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-blue-200">
                <i class="fas fa-file-medical text-2xl"></i>
            </div>
            <div class="ml-5">
                <p class="text-[10px] font-black text-blue-500 uppercase tracking-widest">Total Archive</p>
                <p class="text-3xl font-black text-slate-900 leading-tight">{{ $clinicalNotes->total() }}</p>
            </div>
        </div>
        <div class="premium-card p-6 flex items-center bg-gradient-to-br from-emerald-50 to-white border-emerald-100">
            <div class="w-14 h-14 bg-emerald-500 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-emerald-200">
                <i class="fas fa-user-check text-2xl"></i>
            </div>
            <div class="ml-5">
                <p class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">Patients Seen</p>
                <p class="text-3xl font-black text-slate-900 leading-tight">{{ $clinicalNotes->unique('patient_id')->count() }}</p>
            </div>
        </div>
        <div class="premium-card p-6 flex items-center bg-gradient-to-br from-purple-50 to-white border-purple-100">
            <div class="w-14 h-14 bg-purple-500 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-purple-200">
                <i class="fas fa-calendar-alt text-2xl"></i>
            </div>
            <div class="ml-5">
                <p class="text-[10px] font-black text-purple-600 uppercase tracking-widest">Logged This Month</p>
                <p class="text-3xl font-black text-slate-900 leading-tight">{{ $clinicalNotes->where('created_at', '>=', now()->startOfMonth())->count() }}</p>
            </div>
        </div>
    </div>

    <!-- Archive Table -->
    <div class="stagger-in bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden" style="--delay: 3">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50/50">
                    <tr class="text-slate-400 text-[10px] font-black uppercase tracking-widest border-b border-slate-100">
                        <th class="px-8 py-5">Timestamp</th>
                        <th class="px-8 py-5">Patient Identity</th>
                        <th class="px-8 py-5">Responsible Clinician</th>
                        <th class="px-8 py-5">Note Summary</th>
                        <th class="px-8 py-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($clinicalNotes as $note)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-8 py-6">
                            <div class="text-sm font-black text-slate-900">{{ $note->created_at->format('M d, Y') }}</div>
                            <div class="text-[10px] font-bold text-slate-400 uppercase">{{ $note->created_at->format('h:i A') }}</div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center text-blue-600 font-black text-xs">
                                    {{ substr($note->patient->first_name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-slate-900">{{ $note->patient->first_name }} {{ $note->patient->last_name }}</div>
                                    <div class="text-[10px] font-medium text-slate-400">PID: {{ $note->patient->id }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-2">
                                <span class="w-6 h-6 rounded-full bg-slate-100 flex items-center justify-center">
                                    <i class="fas fa-user-md text-[10px] text-slate-500"></i>
                                </span>
                                <span class="text-sm font-semibold text-slate-700">Dr. {{ $note->dentist->name }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-6 max-w-xs">
                            <div class="text-sm text-slate-600 truncate font-medium">{{ $note->content }}</div>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('clinic.patients.show', $note->patient_id) }}?tab=clinical" class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 hover:bg-blue-600 hover:text-white transition-all shadow-sm">
                                    <i class="fas fa-eye text-xs"></i>
                                </a>
                                <form action="{{ route('clinic.clinical-notes.destroy', $note) }}" method="POST" onsubmit="return confirm('Archive deletion is irreversible. Proceed?')">
                                    @csrf @method('DELETE')
                                    <button class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 hover:bg-rose-600 hover:text-white transition-all shadow-sm">
                                        <i class="fas fa-trash-alt text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-24 text-center">
                            <div class="max-w-xs mx-auto">
                                <div class="w-20 h-20 bg-slate-50 rounded-[2rem] flex items-center justify-center mx-auto mb-6 text-slate-200">
                                    <i class="fas fa-file-medical text-3xl"></i>
                                </div>
                                <h3 class="text-lg font-black text-slate-900 mb-2 uppercase tracking-tight">No Clinical Records Found</h3>
                                <p class="text-sm text-slate-400 font-medium">Capture your first clinical note within a patient's case file to begin the digital archive.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($clinicalNotes->hasPages())
        <div class="px-8 py-6 bg-slate-50/50 border-t border-slate-100">
            {{ $clinicalNotes->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
