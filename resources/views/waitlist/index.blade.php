@extends('layouts.app')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Waitlist</h1>
    </div>
</div>

<div class="bg-white shadow rounded-lg">
    <div class="p-6">
        <div class="text-center py-8">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No patients on waitlist</h3>
            <p class="mt-1 text-sm text-gray-500">Waitlist feature coming soon.</p>
        </div>
    </div>
</div>
@endsection