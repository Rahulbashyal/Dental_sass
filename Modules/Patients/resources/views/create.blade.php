@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-semibold text-slate-800">Register New Patient</h2>
            <p class="text-sm text-slate-500">Capture essential patient details for clinical records.</p>
        </div>
        <a href="{{ route('clinic.patients.index') }}" class="text-sm font-medium text-slate-500 hover:text-slate-700 flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to List
        </a>
    </div>

    @if ($errors->any())
        <div class="mb-6 rounded-lg bg-red-50 border border-red-100 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Pre-registration errors found:</h3>
                    <div class="mt-2 text-sm text-red-700">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <form action="{{ route('clinic.patients.store') }}" method="POST" class="p-8">
            @csrf
            <div class="space-y-8">
                <!-- Basic Information -->
                <div>
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-widest mb-6 flex items-center">
                        <span class="w-8 h-8 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center mr-3 text-xs">01</span>
                        Basic Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-slate-700">First Name *</label>
                            <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" required class="mt-1 block w-full rounded-lg border-slate-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-slate-700">Last Name *</label>
                            <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" required class="mt-1 block w-full rounded-lg border-slate-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                        <div>
                            <label for="date_of_birth" class="block text-sm font-medium text-slate-700">Date of Birth</label>
                            <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth') }}" class="mt-1 block w-full rounded-lg border-slate-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                        <div>
                            <label for="gender" class="block text-sm font-medium text-slate-700">Gender</label>
                            <select name="gender" id="gender" class="mt-1 block w-full rounded-lg border-slate-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">Select Gender</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Contact Details -->
                <div class="pt-8 border-t border-slate-100">
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-widest mb-6 flex items-center">
                        <span class="w-8 h-8 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center mr-3 text-xs">02</span>
                        Contact Details
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="email" class="block text-sm font-medium text-slate-700">Email Address</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" class="mt-1 block w-full rounded-lg border-slate-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                        <div>
                            <label for="phone" class="block text-sm font-medium text-slate-700">Phone Number</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone') }}" class="mt-1 block w-full rounded-lg border-slate-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                        <div class="col-span-2">
                            <label for="address" class="block text-sm font-medium text-slate-700">Residential Address</label>
                            <textarea name="address" id="address" rows="3" class="mt-1 block w-full rounded-lg border-slate-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('address') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Medical History (Encrypted) -->
                <div class="pt-8 border-t border-slate-100">
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-widest mb-6 flex items-center">
                        <span class="w-8 h-8 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center mr-3 text-xs">03</span>
                        Medical Records
                        <span class="ml-2 text-[10px] font-bold text-green-600 bg-green-50 px-2 py-0.5 rounded border border-green-100">SECURE ENCRYPTED</span>
                    </h3>
                    <div class="space-y-6">
                        <div>
                            <label for="medical_history" class="block text-sm font-medium text-slate-700">General Medical History</label>
                            <textarea name="medical_history" id="medical_history" rows="4" placeholder="Any existing conditions, previous surgeries, etc." class="mt-1 block w-full rounded-lg border-slate-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('medical_history') }}</textarea>
                        </div>
                        <div>
                            <label for="allergies" class="block text-sm font-medium text-slate-700">Allergies</label>
                            <textarea name="allergies" id="allergies" rows="2" placeholder="Medication or food allergies." class="mt-1 block w-full rounded-lg border-slate-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('allergies') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-12 pt-8 border-t border-slate-100 flex justify-end space-x-3">
                <button type="reset" class="px-6 py-2 border border-slate-200 text-sm font-medium rounded-lg text-slate-600 hover:bg-slate-50 transition-colors">
                    Reset Form
                </button>
                <button type="submit" class="px-8 py-2 bg-indigo-600 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                    Register Patient
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
