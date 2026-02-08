@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Clinics</h1>
        <a href="{{ route('clinics.create') }}" class="btn-primary">Add New Clinic</a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('new_clinic_info'))
        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">Clinic Admin Created</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <p class="mb-2">The clinic admin can now login with the following credentials:</p>
                        <ul class="list-disc pl-5 space-y-1">
                            <li><strong>Login URL:</strong> <a href="{{ session('new_clinic_info.login_url') }}" class="underline">{{ session('new_clinic_info.login_url') }}</a></li>
                            <li><strong>Email:</strong> {{ session('new_clinic_info.email') }}</li>
                            <li><strong>Password:</strong> The password you just set</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <ul class="divide-y divide-gray-200">
            @forelse($clinics as $clinic)
                <li>
                    <div class="px-4 py-4 flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <div class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center">
                                    <span class="text-white font-medium">{{ substr($clinic->name, 0, 1) }}</span>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $clinic->name }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $clinic->email }} • {{ $clinic->phone }}
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $clinic->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $clinic->is_active ? 'Active' : 'Inactive' }}
                            </span>
                            <a href="{{ route('clinics.show', $clinic) }}" class="text-blue-600 hover:text-blue-900">View</a>
                            <a href="{{ route('clinics.edit', $clinic) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                        </div>
                    </div>
                </li>
            @empty
                <li class="px-4 py-8 text-center text-gray-500">
                    No clinics found. <a href="{{ route('clinics.create') }}" class="text-blue-600">Add your first clinic</a>
                </li>
            @endforelse
        </ul>
    </div>

    <div class="mt-6">
        {{ $clinics->links() }}
    </div>
</div>
@endsection