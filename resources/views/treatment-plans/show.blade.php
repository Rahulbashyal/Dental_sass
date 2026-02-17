@extends('layouts.app')

@section('page-title', 'Treatment Plan Details')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $treatmentPlan->title }}</h1>
            <p class="text-gray-600">Patient: {{ $treatmentPlan->patient->name }}</p>
        </div>
        <div class="flex space-x-4">
            <a href="{{ route('treatment-plans.edit', $treatmentPlan) }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                Edit Plan
            </a>
            <a href="{{ route('treatment-plans.index') }}" class="border border-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-50">
                Back to Plans
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Treatment Description</h2>
                <p class="text-gray-700 leading-relaxed">{{ $treatmentPlan->description }}</p>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white shadow-md rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Plan Details</h3>
                <div class="space-y-3">
                    <div>
                        <span class="text-sm font-medium text-gray-600">Status:</span>
                        <span class="ml-2 px-2 py-1 text-xs font-semibold rounded-full 
                            {{ $treatmentPlan->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $treatmentPlan->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $treatmentPlan->status === 'approved' ? 'bg-purple-100 text-purple-800' : '' }}
                            {{ $treatmentPlan->status === 'draft' ? 'bg-gray-100 text-gray-800' : '' }}">
                            {{ ucfirst(str_replace('_', ' ', $treatmentPlan->status)) }}
                        </span>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-600">Priority:</span>
                        <span class="ml-2 px-2 py-1 text-xs font-semibold rounded-full 
                            {{ $treatmentPlan->priority === 'urgent' ? 'bg-red-100 text-red-800' : '' }}
                            {{ $treatmentPlan->priority === 'high' ? 'bg-orange-100 text-orange-800' : '' }}
                            {{ $treatmentPlan->priority === 'medium' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $treatmentPlan->priority === 'low' ? 'bg-green-100 text-green-800' : '' }}">
                            {{ ucfirst($treatmentPlan->priority) }}
                        </span>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-600">Estimated Cost:</span>
                        <span class="ml-2 text-lg font-bold text-green-600">NPR {{ number_format($treatmentPlan->estimated_cost) }}</span>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-600">Duration:</span>
                        <span class="ml-2 text-gray-900">{{ $treatmentPlan->estimated_duration }}</span>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-600">Created:</span>
                        <span class="ml-2 text-gray-900">{{ $treatmentPlan->created_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-white shadow-md rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Patient Information</h3>
                <div class="space-y-2">
                    <div>
                        <span class="text-sm font-medium text-gray-600">Name:</span>
                        <span class="ml-2 text-gray-900">{{ $treatmentPlan->patient->name }}</span>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-600">Phone:</span>
                        <span class="ml-2 text-gray-900">{{ $treatmentPlan->patient->phone }}</span>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-600">Email:</span>
                        <span class="ml-2 text-gray-900">{{ $treatmentPlan->patient->email }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection