@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="flex items-center mb-6">
        <a href="{{ route('journal-entries') }}" class="text-gray-600 hover:text-gray-800 mr-4">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="text-2xl font-bold">Create Journal Entry</h1>
    </div>

    <div class="bg-white rounded-lg shadow p-6 max-w-2xl">
        <form action="{{ route('journal-entries.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-6">
                    <x-nepali-date-input 
                        name="date"
                        label="Entry Date (प्रविष्टि मिति)"
                        :value="old('date', date('Y-m-d'))"
                        required
                        help="Select journal entry date"
                    />
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Amount</label>
                    <input type="number" name="amount" step="0.01" class="w-full border rounded px-3 py-2" required>
                </div>
            </div>

            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <input type="text" name="description" class="w-full border rounded px-3 py-2" required>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Debit Account</label>
                    <select name="debit_account" class="w-full border rounded px-3 py-2" required>
                        <option value="">Select Account</option>
                        @foreach($accounts as $account)
                        <option value="{{ $account }}">{{ $account }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Credit Account</label>
                    <select name="credit_account" class="w-full border rounded px-3 py-2" required>
                        <option value="">Select Account</option>
                        @foreach($accounts as $account)
                        <option value="{{ $account }}">{{ $account }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mt-8 flex space-x-4">
                <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700">
                    Create Entry
                </button>
                <a href="{{ route('journal-entries') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded hover:bg-gray-400">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection