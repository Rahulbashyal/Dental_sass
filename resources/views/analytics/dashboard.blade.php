@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Analytics Dashboard</h1>

    <!-- Key Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-sm font-medium text-gray-500">Today's Appointments</h3>
            <p class="text-2xl font-bold text-blue-600">{{ $analytics['appointments_today'] }}</p>
        </div>
        
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-sm font-medium text-gray-500">This Week</h3>
            <p class="text-2xl font-bold text-green-600">{{ $analytics['appointments_this_week'] }}</p>
        </div>
        
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-sm font-medium text-gray-500">This Month</h3>
            <p class="text-2xl font-bold text-purple-600">{{ $analytics['appointments_this_month'] }}</p>
        </div>
        
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-sm font-medium text-gray-500">Completion Rate</h3>
            <p class="text-2xl font-bold text-orange-600">{{ $analytics['completion_rate'] }}%</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Popular Treatments -->
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold mb-4">Popular Treatments</h3>
            <div class="space-y-3">
                @foreach($analytics['popular_treatments'] as $treatment)
                <div class="flex justify-between items-center">
                    <span class="text-gray-700">{{ $treatment->type }}</span>
                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-sm">{{ $treatment->count }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Monthly Trends -->
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold mb-4">6-Month Trend</h3>
            <div class="space-y-3">
                @foreach($analytics['monthly_trends'] as $trend)
                <div class="flex justify-between items-center">
                    <span class="text-gray-700">{{ $trend['month'] }}</span>
                    <span class="text-gray-900 font-medium">{{ $trend['appointments'] }} appointments</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Patient Growth -->
    <div class="mt-6 bg-white p-6 rounded-lg shadow">
        <h3 class="text-lg font-semibold mb-4">Patient Growth This Month</h3>
        <p class="text-3xl font-bold text-green-600">+{{ $analytics['patient_growth'] }}</p>
        <p class="text-gray-500">new patients registered</p>
    </div>
</div>
@endsection