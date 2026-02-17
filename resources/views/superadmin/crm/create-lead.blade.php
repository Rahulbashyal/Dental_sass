@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900">Add New Lead</h1>
        <p class="text-slate-600 mt-2">Create a new potential customer lead</p>
    </div>

    <div class="bg-white shadow-sm rounded-xl border border-slate-200 p-8">
        <form action="{{ route('superadmin.crm.store-lead') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700 mb-2">Full Name *</label>
                    <input type="text" name="name" id="name" required class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-rose-500" value="{{ old('name') }}">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 mb-2">Email Address *</label>
                    <input type="email" name="email" id="email" required class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-rose-500" value="{{ old('email') }}">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="phone" class="block text-sm font-medium text-slate-700 mb-2">Phone Number</label>
                    <input type="text" name="phone" id="phone" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-rose-500" value="{{ old('phone') }}">
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="company" class="block text-sm font-medium text-slate-700 mb-2">Company</label>
                    <input type="text" name="company" id="company" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-rose-500" value="{{ old('company') }}">
                    @error('company')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="source" class="block text-sm font-medium text-slate-700 mb-2">Lead Source *</label>
                    <select name="source" id="source" required class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-rose-500">
                        <option value="">Select Source</option>
                        <option value="website" {{ old('source') === 'website' ? 'selected' : '' }}>Website</option>
                        <option value="referral" {{ old('source') === 'referral' ? 'selected' : '' }}>Referral</option>
                        <option value="social_media" {{ old('source') === 'social_media' ? 'selected' : '' }}>Social Media</option>
                        <option value="advertisement" {{ old('source') === 'advertisement' ? 'selected' : '' }}>Advertisement</option>
                        <option value="other" {{ old('source') === 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('source')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="potential_value" class="block text-sm font-medium text-slate-700 mb-2">Potential Value (NPR)</label>
                    <input type="number" name="potential_value" id="potential_value" min="0" step="0.01" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-rose-500" value="{{ old('potential_value') }}">
                    @error('potential_value')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="notes" class="block text-sm font-medium text-slate-700 mb-2">Notes</label>
                <textarea name="notes" id="notes" rows="4" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-rose-500" placeholder="Additional information about this lead...">{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between pt-6 border-t border-slate-200">
                <a href="{{ route('superadmin.crm.leads') }}" class="inline-flex items-center px-6 py-3 border border-slate-300 text-slate-700 font-medium rounded-lg hover:bg-slate-50 transition-colors duration-200">
                    Cancel
                </a>
                <button type="submit" class="inline-flex items-center px-6 py-3 bg-rose-600 hover:bg-rose-700 text-white font-semibold rounded-lg shadow-sm transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Create Lead
                </button>
            </div>
        </form>
    </div>
</div>
@endsection