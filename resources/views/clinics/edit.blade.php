@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Edit Clinic</h1>
        <p class="text-gray-600 mt-1">Update clinic information</p>
    </div>

    <form method="POST" action="{{ route('clinics.update', $clinic) }}" class="space-y-8">
        @csrf
        @method('PUT')

        <!-- Basic Information -->
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                Basic Information
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div>
                    <label for="name" class="form-label">Clinic Name *</label>
                    <input type="text" name="name" id="name" class="form-input" value="{{ old('name', $clinic->name) }}" required>
                    @error('name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="email" class="form-label">Email *</label>
                    <input type="email" name="email" id="email" class="form-input" value="{{ old('email', $clinic->email) }}" required>
                    @error('email')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="phone" class="form-label">Phone</label>
                    <input type="text" name="phone" id="phone" class="form-input" value="{{ old('phone', $clinic->phone) }}">
                </div>
                <div>
                    <label for="city" class="form-label">City</label>
                    <input type="text" name="city" id="city" class="form-input" value="{{ old('city', $clinic->city) }}">
                </div>
                <div>
                    <label for="state" class="form-label">State</label>
                    <input type="text" name="state" id="state" class="form-input" value="{{ old('state', $clinic->state) }}">
                </div>
                <div>
                    <label for="country" class="form-label">Country *</label>
                    <select name="country" id="country" class="form-input" required>
                        <option value="Nepal" {{ old('country', $clinic->country) == 'Nepal' ? 'selected' : '' }}>Nepal</option>
                        <option value="India" {{ old('country', $clinic->country) == 'India' ? 'selected' : '' }}>India</option>
                        <option value="Other" {{ old('country', $clinic->country) == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
            </div>
            <div class="mt-4">
                <label for="address" class="form-label">Address</label>
                <textarea name="address" id="address" rows="2" class="form-input">{{ old('address', $clinic->address) }}</textarea>
            </div>
        </div>

        <!-- Clinic Admin Password Reset -->
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
                Reset Clinic Admin Password
            </h2>
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            Leave these fields blank if you do not want to change the password.
                            This will update the password for the user with email: <strong>{{ $clinic->email }}</strong>
                        </p>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="admin_password" class="form-label">New Password</label>
                    <input type="password" name="admin_password" id="admin_password" class="form-input" autocomplete="new-password">
                    @error('admin_password')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="admin_password_confirmation" class="form-label">Confirm Password</label>
                    <input type="password" name="admin_password_confirmation" id="admin_password_confirmation" class="form-input" autocomplete="new-password">
                </div>
            </div>
        </div>

        <div class="flex justify-end space-x-4">
            <a href="{{ route('clinics.index') }}" class="btn-secondary">Cancel</a>
            <button type="submit" class="btn-primary">Update Clinic</button>
        </div>
    </form>
</div>
@endsection
