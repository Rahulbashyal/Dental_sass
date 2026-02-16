@extends('layouts.app')

@section('page-title', 'Edit Treatment Plan')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Treatment Plan</h1>
            <p class="text-gray-600">Update treatment plan details</p>
        </div>
        <div class="bg-blue-50 p-3 rounded-lg">
            <div class="text-sm text-gray-600">आज</div>
            @php
                $nepaliDate = \App\Helpers\NepaliDateHelper::getCurrentNepaliDate();
            @endphp
            <div class="text-lg font-bold text-blue-800">{{ $nepaliDate['formatted'] ?? '२६ कार्तिक २०८२' }}</div>
            <div class="text-sm text-gray-600">{{ $nepaliDate['day_of_week'] ? \App\Services\NepaliCalendarService::getDayName($nepaliDate['day_of_week']) : 'बुधबार' }}</div>
        </div>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6 max-w-2xl">
        <form action="{{ route('clinic.treatment-plans.update', $treatmentPlan) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Patient</label>
                    <select name="patient_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Select Patient</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}" {{ old('patient_id', $treatmentPlan->patient_id) == $patient->id ? 'selected' : '' }}>
                                {{ $patient->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('patient_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Treatment Plan Title</label>
                    <input type="text" name="title" value="{{ old('title', $treatmentPlan->title) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                           placeholder="e.g., Complete Dental Restoration" required>
                    @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" rows="4" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                              placeholder="Detailed treatment plan description..." required>{{ old('description', $treatmentPlan->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Estimated Cost (NPR)</label>
                        <input type="number" name="estimated_cost" value="{{ old('estimated_cost', $treatmentPlan->estimated_cost) }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                               step="1" min="0" placeholder="Enter cost in NPR" required>
                        @error('estimated_cost')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Estimated Duration</label>
                        <input type="text" name="estimated_duration" value="{{ old('estimated_duration', $treatmentPlan->estimated_duration) }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                               placeholder="e.g., 3 weeks, 2 months" required>
                        @error('estimated_duration')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Priority Level</label>
                        <select name="priority" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            <option value="">Select Priority</option>
                            <option value="low" {{ old('priority', $treatmentPlan->priority) === 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ old('priority', $treatmentPlan->priority) === 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ old('priority', $treatmentPlan->priority) === 'high' ? 'selected' : '' }}>High</option>
                            <option value="urgent" {{ old('priority', $treatmentPlan->priority) === 'urgent' ? 'selected' : '' }}>Urgent</option>
                        </select>
                        @error('priority')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            <option value="">Select Status</option>
                            <option value="draft" {{ old('status', $treatmentPlan->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="approved" {{ old('status', $treatmentPlan->status) === 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="in_progress" {{ old('status', $treatmentPlan->status) === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="completed" {{ old('status', $treatmentPlan->status) === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ old('status', $treatmentPlan->status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        @error('status')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end space-x-4">
                    <a href="{{ route('clinic.treatment-plans.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Cancel
                    </a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Update Treatment Plan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection