@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Journal Entries</h1>
        <a href="{{ route('journal-entries.create') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
            <i class="fas fa-plus mr-2"></i>New Entry
        </a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Debit Account</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Credit Account</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($entries as $entry)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $entry['date'] }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $entry['description'] }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $entry['debit_account'] }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $entry['credit_account'] }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${{ number_format($entry['amount'], 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection