@extends('layouts.app')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-6">General Ledger</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($accounts as $accountName => $data)
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-2">{{ $accountName }}</h3>
            <div class="text-2xl font-bold text-green-600 mb-2">${{ number_format($data['balance'], 2) }}</div>
            <p class="text-gray-600 text-sm">{{ $data['entries'] }} entries</p>
            <button class="mt-4 text-blue-600 hover:text-blue-800 text-sm">View Details</button>
        </div>
        @endforeach
    </div>
</div>
@endsection