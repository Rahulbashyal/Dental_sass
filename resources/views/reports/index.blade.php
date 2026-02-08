@extends('layouts.app')

@section('title', 'Reports & Analytics')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Reports & Analytics</h1>
        <p class="text-gray-600">Clinic performance insights</p>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="card">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 mr-4">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Patients</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_patients'] }}</p>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 mr-4">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">This Month</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['monthly_appointments'] }}</p>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 mr-4">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Monthly Revenue</p>
                    <p class="text-2xl font-bold text-gray-900">NPR {{ number_format($stats['monthly_revenue'], 0) }}</p>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 mr-4">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Completion Rate</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['completion_rate'] }}%</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="card">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Recent Appointments</h2>
            <div class="space-y-3">
                @forelse($recent_appointments as $appointment)
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                        <div>
                            <p class="font-medium">{{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}</p>
                            <p class="text-sm text-gray-600">{{ $appointment->appointment_date->format('M d') }} - {{ ucfirst($appointment->type) }}</p>
                        </div>
                        <span class="px-2 py-1 text-xs rounded-full {{ $appointment->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ ucfirst($appointment->status) }}
                        </span>
                    </div>
                @empty
                    <p class="text-gray-500">No recent appointments</p>
                @endforelse
            </div>
        </div>

        <div class="card">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Treatment Types</h2>
            <div class="space-y-3">
                @foreach($treatment_stats as $treatment)
                    <div class="flex justify-between items-center">
                        <span class="text-gray-700">{{ ucfirst(str_replace('_', ' ', $treatment->type)) }}</span>
                        <div class="flex items-center">
                            <div class="w-24 bg-gray-200 rounded-full h-2 mr-3">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ ($treatment->count / $stats['total_appointments']) * 100 }}%"></div>
                            </div>
                            <span class="text-sm font-medium">{{ $treatment->count }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection