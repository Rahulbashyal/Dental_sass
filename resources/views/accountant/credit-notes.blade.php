@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Credit Notes</h1>
            <p class="text-gray-500 mt-1">Manage refunds and billing adjustments</p>
        </div>
        <button onclick="document.getElementById('issueModal').classList.remove('hidden')" class="bg-gray-900 text-white px-5 py-2.5 rounded-xl font-bold hover:bg-gray-800 transition-all shadow-sm flex items-center gap-2">
            <i class="fas fa-plus"></i> Issue Credit Note
        </button>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-gray-50">
                <tr class="text-gray-400 text-xs uppercase tracking-wider">
                    <th class="px-6 py-4 font-medium">Number</th>
                    <th class="px-6 py-4 font-medium">Patient</th>
                    <th class="px-6 py-4 font-medium">Invoice</th>
                    <th class="px-6 py-4 font-medium">Reason</th>
                    <th class="px-6 py-4 font-medium text-right">Amount</th>
                    <th class="px-6 py-4 font-medium">Status</th>
                    <th class="px-6 py-4 font-medium">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($creditNotes as $cn)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 font-bold text-gray-900">{{ $cn->credit_note_number }}</td>
                    <td class="px-6 py-4">{{ $cn->patient->name }}</td>
                    <td class="px-6 py-4 text-blue-600 font-medium">#{{ $cn->invoice->invoice_number }}</td>
                    <td class="px-6 py-4 text-gray-500 text-sm truncate max-w-xs">{{ $cn->reason }}</td>
                    <td class="px-6 py-4 text-right font-bold text-red-600">-${{ number_format($cn->amount, 2) }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2.5 py-1 bg-green-50 text-green-700 rounded-full text-xs font-bold uppercase tracking-wider border border-green-100">
                            {{ $cn->status }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-gray-400 text-sm">{{ $cn->created_at->format('M d, Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-400 italic">No credit notes issued yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="px-6 py-4 border-t border-gray-50">
            {{ $creditNotes->links() }}
        </div>
    </div>
</div>

<!-- Simple Issue Modal (Hidden by default) -->
<div id="issueModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <h3 class="font-bold text-xl text-gray-900">Issue Credit Note</h3>
            <button onclick="document.getElementById('issueModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>
        </div>
        <form action="{{ route('clinic.credit-notes.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Invoice Number</label>
                <input type="text" name="invoice_id" placeholder="ID of invoice" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-gray-900">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Refund Amount</label>
                <input type="number" step="0.01" name="amount" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-gray-900">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Reason for Adjustment</label>
                <textarea name="reason" rows="3" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-gray-900"></textarea>
            </div>
            <button type="submit" class="w-full bg-red-600 text-white py-3 rounded-xl font-bold hover:bg-red-700 transition-all shadow-md mt-2">
                Confirm & Issue
            </button>
        </form>
    </div>
</div>
@endsection
