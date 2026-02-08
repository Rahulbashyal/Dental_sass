@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Add New Patient</h1>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <form method="POST" action="{{ route('patients.store') }}">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="first_name" class="form-label">First Name *</label>
                    <input type="text" name="first_name" id="first_name" class="form-input" value="{{ old('first_name') }}" required>
                    @error('first_name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="last_name" class="form-label">Last Name *</label>
                    <input type="text" name="last_name" id="last_name" class="form-input" value="{{ old('last_name') }}" required>
                    @error('last_name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-input" value="{{ old('email') }}">
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="form-label">Phone</label>
                    <input type="text" name="phone" id="phone" class="form-input" value="{{ old('phone') }}">
                    @error('phone')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <x-nepali-date-input 
                        name="date_of_birth"
                        label="Date of Birth (जन्म मिति)"
                        :value="old('date_of_birth')"
                        :maxDate="date('Y-m-d')"
                        help="Select date of birth in Nepali calendar"
                    />
                </div>

                <div>
                    <label for="gender" class="form-label">Gender</label>
                    <select name="gender" id="gender" class="form-input">
                        <option value="">Select Gender</option>
                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                        <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('gender')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('patients.index') }}" class="btn-secondary">Cancel</a>
                <button type="submit" class="btn-primary">Create Patient</button>
            </div>
        </form>
    </div>
</div>
@endsection