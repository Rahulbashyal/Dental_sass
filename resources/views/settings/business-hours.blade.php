@extends('layouts.app')

@section('page-title', 'Business Hours')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Business Hours</h1>
            <p class="text-gray-600 mt-1">Configure your clinic's operating schedule</p>
        </div>
        <a href="{{ route('clinic.settings.index') }}"
           class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm font-medium">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Settings
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center">
            <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow-md rounded-xl p-6">
        <form action="{{ route('clinic.settings.update-business-hours') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                {{-- Operating Hours --}}
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Operating Hours</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Opening Time</label>
                            <input type="time"
                                   name="working_hours_start"
                                   value="{{ old('working_hours_start', $clinic->working_hours_start ?? '09:00') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                   required>
                            @error('working_hours_start')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Closing Time</label>
                            <input type="time"
                                   name="working_hours_end"
                                   value="{{ old('working_hours_end', $clinic->working_hours_end ?? '18:00') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                   required>
                            @error('working_hours_end')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Working Days --}}
                <div class="border-t pt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Working Days</h3>
                    @error('working_days')
                        <p class="text-red-500 text-sm mb-3">{{ $message }}</p>
                    @enderror
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        @foreach($days as $value => $label)
                            <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-indigo-50 hover:border-indigo-300 cursor-pointer transition-colors">
                                <input type="checkbox"
                                       name="working_days[]"
                                       value="{{ $value }}"
                                       {{ in_array($value, $workingDays) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <span class="ml-2 text-sm font-medium text-gray-700">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="flex justify-end space-x-4 pt-4 border-t">
                    <a href="{{ route('clinic.settings.index') }}"
                       class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                        Cancel
                    </a>
                    <button type="submit"
                            class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium">
                        Save Business Hours
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
