@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">Expense Management</h2>
            <p class="text-sm text-slate-500">Track and categorize clinic operational costs and overheads.</p>
        </div>
        <button onclick="document.getElementById('expense-modal').classList.remove('hidden')" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow-sm">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Record Expense
        </button>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Expenses</p>
            <h4 class="text-2xl font-black text-slate-900">{{ tenant()->clinic->currency ?? '$' }}{{ number_format($stats['total'], 2) }}</h4>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <p class="text-[10px] font-bold text-indigo-500 uppercase tracking-widest mb-1">This Month</p>
            <h4 class="text-2xl font-black text-slate-900">{{ tenant()->clinic->currency ?? '$' }}{{ number_format($stats['this_month'], 2) }}</h4>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Inventory Spend</p>
            <h4 class="text-xl font-black text-slate-900">{{ tenant()->clinic->currency ?? '$' }}{{ number_format($stats['inventory'], 2) }}</h4>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm text-center flex flex-col items-center justify-center bg-indigo-50/50 border-indigo-100">
            <p class="text-[10px] font-black text-indigo-600 uppercase tracking-widest mb-1">Net Margin (Est.)</p>
            <span class="text-xs font-bold text-indigo-400 italic">Calculating...</span>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead>
                    <tr class="bg-slate-50">
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-200">
                @forelse($expenses as $expense)
                    <tr class="hover:bg-slate-50/80 transition-colors group">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                            {{ $expense->expense_date->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 rounded bg-slate-100 text-slate-700 text-[10px] font-bold uppercase tracking-wider">{{ $expense->category }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">
                            {{ $expense->description }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-black text-red-600">
                            -{{ tenant()->clinic->currency ?? '$' }}{{ number_format($expense->amount, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                            <form action="{{ route('expenses.destroy', $expense) }}" method="POST" onsubmit="return confirm('Delete this record?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 text-slate-400 hover:text-red-600 transition-colors">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-500 italic text-sm">No expenses recorded yet.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if($expenses->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $expenses->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Add Expense Modal -->
<div id="expense-modal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="document.getElementById('expense-modal').classList.add('hidden')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-8 pt-8 pb-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-black text-slate-900" id="modal-title">Record New Expense</h3>
                    <button onclick="document.getElementById('expense-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-500">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l18 18"></path>
                        </svg>
                    </button>
                </div>
                <form action="{{ route('expenses.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Category</label>
                        <select name="category" required class="w-full rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            <option value="Inventory">Medical Inventory / Supplies</option>
                            <option value="Salary">Staff Salaries</option>
                            <option value="Rent">Clinic Rent / Utilities</option>
                            <option value="Maintenance">Equipment Maintenance</option>
                            <option value="Marketing">Marketing / ADS</option>
                            <option value="Other">Miscellaneous</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Description</label>
                        <input type="text" name="description" required placeholder="e.g. Monthly Rent, Dental Chairs Repair" class="w-full rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Amount ({{ tenant()->clinic->currency ?? '$' }})</label>
                            <input type="number" name="amount" step="0.01" required placeholder="0.00" class="w-full rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm font-bold">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Expense Date</label>
                            <input type="date" name="expense_date" required value="{{ date('Y-m-d') }}" class="w-full rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>
                    </div>
                    <div class="mt-8 pt-6 border-t border-slate-100 flex justify-end space-x-3">
                        <button type="button" onclick="document.getElementById('expense-modal').classList.add('hidden')" class="px-6 py-2 text-sm font-bold text-slate-500 hover:text-slate-700 transition-colors">Cancel</button>
                        <button type="submit" class="px-8 py-2 bg-indigo-600 text-white text-sm font-bold rounded-lg hover:bg-indigo-700 transition-shadow shadow-lg shadow-indigo-100">Save Expense</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
