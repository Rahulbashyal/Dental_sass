@extends('layouts.app')


@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900">Create New Campaign</h1>
        <p class="text-slate-600 mt-2">Set up a new marketing campaign</p>
    </div>

    <div class="card">
        <form action="{{ route('superadmin.crm.store-campaign') }}" method="POST" class="space-y-6">
            @csrf
            
            <div>
                <label for="name" class="form-label">Campaign Name *</label>
                <input type="text" name="name" id="name" required class="form-input" value="{{ old('name') }}">
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" rows="3" class="form-input">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="type" class="form-label">Campaign Type *</label>
                    <select name="type" id="type" required class="form-input">
                        <option value="">Select Type</option>
                        <option value="email" {{ old('type') === 'email' ? 'selected' : '' }}>Email</option>
                        <option value="sms" {{ old('type') === 'sms' ? 'selected' : '' }}>SMS</option>
                        <option value="social_media" {{ old('type') === 'social_media' ? 'selected' : '' }}>Social Media</option>
                        <option value="advertisement" {{ old('type') === 'advertisement' ? 'selected' : '' }}>Advertisement</option>
                    </select>
                    @error('type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="budget" class="form-label">Budget (NPR)</label>
                    <input type="number" name="budget" id="budget" min="0" step="0.01" class="form-input" value="{{ old('budget') }}">
                    @error('budget')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <x-nepali-date-input 
                        name="start_date"
                        label="Start Date (सुरु मिति)"
                        :value="old('start_date')"
                        :minDate="date('Y-m-d')"
                        help="Campaign start date"
                    />
                </div>

                <div>
                    <x-nepali-date-input 
                        name="end_date"
                        label="End Date (अन्त्य मिति)"
                        :value="old('end_date')"
                        :minDate="date('Y-m-d')"
                        help="Campaign end date"
                    />
                </div>
            </div>

            <div class="flex items-center justify-between pt-6 border-t border-slate-200">
                <a href="{{ route('superadmin.crm.campaigns') }}" class="btn-secondary">Cancel</a>
                <button type="submit" class="btn-primary">Create Campaign</button>
            </div>
        </form>
    </div>
</div>
@endsection