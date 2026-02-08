<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment - {{ $clinic->name }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <div class="min-h-screen py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Book an Appointment</h1>
                <p class="text-gray-600 mt-2">{{ $clinic->name }}</p>
                <a href="{{ route('clinic.landing', $clinic->slug) }}" class="text-blue-600 hover:text-blue-700 text-sm">← Back to clinic</a>
            </div>

            <!-- Booking Form -->
            <div class="bg-white shadow-lg rounded-lg p-8">
                <form method="POST" action="{{ route('clinic.book', $clinic) }}" class="space-y-6">
                    @csrf
                    
                    <!-- Personal Information -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Personal Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                                <input type="text" name="first_name" id="first_name" required 
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                       value="{{ old('first_name') }}">
                                @error('first_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                                <input type="text" name="last_name" id="last_name" required 
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                       value="{{ old('last_name') }}">
                                @error('last_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" name="email" id="email" required 
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                       value="{{ old('email') }}">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                                <input type="tel" name="phone" id="phone" required 
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                       value="{{ old('phone') }}">
                                @error('phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Appointment Details -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Appointment Details</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-nepali-date-input 
                                    name="appointment_date"
                                    label="Preferred Date (रोजाइएको मिति)"
                                    :value="old('appointment_date')"
                                    required
                                    :minDate="date('Y-m-d', strtotime('+1 day'))"
                                    help="Select your preferred appointment date"
                                />
                            </div>
                            
                            <div>
                                <label for="appointment_time" class="block text-sm font-medium text-gray-700">Preferred Time</label>
                                <select name="appointment_time" id="appointment_time" required 
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Select time</option>
                                    <option value="09:00" {{ old('appointment_time') == '09:00' ? 'selected' : '' }}>9:00 AM</option>
                                    <option value="10:00" {{ old('appointment_time') == '10:00' ? 'selected' : '' }}>10:00 AM</option>
                                    <option value="11:00" {{ old('appointment_time') == '11:00' ? 'selected' : '' }}>11:00 AM</option>
                                    <option value="14:00" {{ old('appointment_time') == '14:00' ? 'selected' : '' }}>2:00 PM</option>
                                    <option value="15:00" {{ old('appointment_time') == '15:00' ? 'selected' : '' }}>3:00 PM</option>
                                    <option value="16:00" {{ old('appointment_time') == '16:00' ? 'selected' : '' }}>4:00 PM</option>
                                </select>
                                @error('appointment_time')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            @if($dentists->count() > 0)
                            <div>
                                <label for="dentist_id" class="block text-sm font-medium text-gray-700">Preferred Dentist</label>
                                <select name="dentist_id" id="dentist_id" required 
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Select dentist</option>
                                    @foreach($dentists as $dentist)
                                        <option value="{{ $dentist->id }}" {{ old('dentist_id') == $dentist->id ? 'selected' : '' }}>
                                            Dr. {{ $dentist->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('dentist_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            @endif
                            
                            <div>
                                <label for="type" class="block text-sm font-medium text-gray-700">Appointment Type</label>
                                <select name="type" id="type" required 
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Select type</option>
                                    <option value="consultation" {{ old('type') == 'consultation' ? 'selected' : '' }}>Consultation</option>
                                    <option value="cleaning" {{ old('type') == 'cleaning' ? 'selected' : '' }}>Cleaning</option>
                                    <option value="filling" {{ old('type') == 'filling' ? 'selected' : '' }}>Filling</option>
                                    <option value="extraction" {{ old('type') == 'extraction' ? 'selected' : '' }}>Extraction</option>
                                    <option value="checkup" {{ old('type') == 'checkup' ? 'selected' : '' }}>Regular Checkup</option>
                                    <option value="emergency" {{ old('type') == 'emergency' ? 'selected' : '' }}>Emergency</option>
                                </select>
                                @error('type')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    @if ($errors->any())
                        <div class="rounded-md bg-red-50 p-4">
                            <div class="text-sm text-red-700">
                                <ul class="list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    <!-- Submit Button -->
                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('clinic.landing', $clinic->slug) }}" 
                           class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            Book Appointment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>