@extends('layouts.app')

@section('title', 'Patient Balances - ' . config('app.name'))

@section('page-title', 'Accounts Receivable')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
    <!-- Header Summary -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 bg-white p-8 rounded-4xl shadow-sm border border-slate-100">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-3xl bg-amber-50 flex items-center justify-center text-amber-600 shadow-inner">
                <i class="fas fa-file-invoice text-2xl"></i>
            </div>
            <div>
                <h2 class="text-2xl font-black text-slate-900 tracking-tight">Outstanding Balances</h2>
                <p class="text-sm text-slate-400 font-medium">Tracking unpaid patient invoices and credit risks</p>
            </div>
        </div>
        <div class="flex gap-4">
            <div class="px-6 py-3 bg-slate-50 border-2 border-slate-100 rounded-2xl text-center">
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Total Patients</p>
                <p class="text-lg font-black text-slate-900">{{ $patients->total() }}</p>
            </div>
            <button class="px-6 py-3 bg-blue-600 text-white rounded-2xl text-[11px] font-black uppercase tracking-widest hover:bg-blue-700 transition-all shadow-lg shadow-blue-200">
                Bulk Reminders
            </button>
        </div>
    </div>

    <!-- Patient Balances Table -->
    <div class="bg-white rounded-4xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Patient Details</th>
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Contact</th>
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Last Visit</th>
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Outstanding Amount</th>
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($patients as $patient)
                        <tr class="group hover:bg-amber-50/20 transition-colors">
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-400 font-black text-xs uppercase overflow-hidden ring-2 ring-white shadow-sm">
                                        @if($patient->photo)
                                            <img src="{{ Storage::url($patient->photo) }}" class="w-full h-full object-cover">
                                        @else
                                            {{ substr($patient->first_name, 0, 1) }}{{ substr($patient->last_name, 0, 1) }}
                                        @endif
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-slate-900 leading-tight">{{ $patient->full_name }}</div>
                                        <div class="text-[10px] text-slate-400 font-bold uppercase tracking-tighter">{{ $patient->patient_id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-5 text-center">
                                <span class="text-xs font-bold text-slate-600">{{ $patient->phone }}</span>
                            </td>
                            <td class="px-8 py-5">
                                <span class="text-xs font-bold text-slate-500">
                                    {{ $patient->appointments->first()?->appointment_date->format('M d, Y') ?? 'No visits' }}
                                </span>
                            </td>
                            <td class="px-8 py-5">
                                <span class="text-sm font-black text-rose-600 block">NRs. {{ number_format($patient->outstanding_balance, 2) }}</span>
                                @if($patient->outstanding_balance > 5000)
                                    <span class="text-[9px] font-black text-rose-400 uppercase tracking-widest">High Risk</span>
                                @endif
                            </td>
                            <td class="px-8 py-5 text-right">
                                <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('clinic.invoices.index', ['patient_id' => $patient->id]) }}" class="p-2 bg-slate-50 text-slate-400 rounded-lg hover:bg-slate-900 hover:text-white transition-all shadow-sm" title="View Invoices">
                                        <i class="fas fa-receipt text-xs"></i>
                                    </a>
                                    <button class="p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-600 hover:text-white transition-all shadow-sm" title="Send Reminder">
                                        <i class="fas fa-paper-plane text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-20 text-center">
                                <div class="w-20 h-20 bg-emerald-50 rounded-3xl flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-check-double text-3xl text-emerald-400"></i>
                                </div>
                                <h3 class="text-sm font-black text-slate-900 uppercase tracking-widest">Zero Balances!</h3>
                                <p class="text-xs text-slate-400 mt-1 font-medium">All patient accounts are currently up to date.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($patients->hasPages())
            <div class="px-8 py-4 bg-slate-50/50 border-t border-slate-50">
                {{ $patients->links() }}
            </div>
        @endif
    </div>
</div>

<style>
    .rounded-4xl { border-radius: 2.5rem; }
</style>
@endsection
