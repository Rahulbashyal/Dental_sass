@extends('layouts.app')

@section('title', 'Expense Tracking - ' . config('app.name'))

@section('page-title', 'Financial Management')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
    <!-- Header with Action -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white p-8 rounded-4xl shadow-sm border border-slate-100">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-3xl bg-rose-50 flex items-center justify-center text-rose-600 shadow-inner">
                <i class="fas fa-wallet text-2xl"></i>
            </div>
            <div>
                <h2 class="text-2xl font-black text-slate-900 tracking-tight">Expense Tracking</h2>
                <p class="text-sm text-slate-400 font-medium">Monthly expenditure overview and management</p>
            </div>
        </div>
        <div class="flex items-center gap-6">
            <div class="text-right">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total This Month</p>
                <h3 class="text-2xl font-black text-rose-600">NRs. {{ number_format($monthlyTotal, 2) }}</h3>
            </div>
            <button class="px-6 py-4 bg-slate-900 text-white rounded-2xl text-[11px] font-black uppercase tracking-widest hover:bg-slate-800 transition-all shadow-xl shadow-slate-200">
                <i class="fas fa-plus mr-2"></i> Record Expense
            </button>
        </div>
    </div>

    <!-- Expense List -->
    <div class="bg-white rounded-4xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Date</th>
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Category</th>
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Description</th>
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Amount</th>
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Status</th>
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($expenses as $expense)
                        <tr class="group hover:bg-slate-50/30 transition-colors">
                            <td class="px-8 py-5">
                                <span class="text-sm font-bold text-slate-600">{{ \Carbon\Carbon::parse($expense->expense_date)->format('M d, Y') }}</span>
                            </td>
                            <td class="px-8 py-5">
                                <span class="px-3 py-1 bg-blue-50 text-blue-600 text-[10px] font-black uppercase tracking-widest rounded-lg border border-blue-100">
                                    {{ $expense->category }}
                                </span>
                            </td>
                            <td class="px-8 py-5">
                                <div class="text-sm text-slate-900 font-medium">{{ $expense->description }}</div>
                                @if($expense->reference_number)
                                    <div class="text-[10px] text-slate-400 font-bold uppercase tracking-tighter">Ref: {{ $expense->reference_number }}</div>
                                @endif
                            </td>
                            <td class="px-8 py-5">
                                <span class="text-sm font-black text-slate-900">NRs. {{ number_format($expense->amount, 2) }}</span>
                            </td>
                            <td class="px-8 py-5 text-center">
                                <span class="inline-flex px-3 py-1 items-center gap-1.5 {{ $expense->status === 'paid' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }} text-[10px] font-black uppercase tracking-widest rounded-full">
                                    <span class="w-1 h-1 rounded-full bg-current"></span>
                                    {{ ucfirst($expense->status) }}
                                </span>
                            </td>
                            <td class="px-8 py-5 text-right">
                                <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button class="p-2 bg-slate-50 text-slate-400 rounded-lg hover:bg-slate-900 hover:text-white transition-all">
                                        <i class="fas fa-edit text-xs"></i>
                                    </button>
                                    @if($expense->receipt_path)
                                        <a href="{{ Storage::url($expense->receipt_path) }}" target="_blank" class="p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-600 hover:text-white transition-all">
                                            <i class="fas fa-receipt text-xs"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-8 py-20 text-center">
                                <div class="w-20 h-20 bg-slate-50 rounded-3xl flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-receipt text-3xl text-slate-200"></i>
                                </div>
                                <h3 class="text-sm font-black text-slate-900 uppercase tracking-widest">No Expenses Found</h3>
                                <p class="text-xs text-slate-400 mt-1 font-medium">We couldn't find any expense records for this month.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($expenses->hasPages())
            <div class="px-8 py-4 bg-slate-50/50 border-t border-slate-50">
                {{ $expenses->links() }}
            </div>
        @endif
    </div>
</div>

<style>
    .rounded-4xl { border-radius: 2.5rem; }
</style>
@endsection
