@props([
    'name',
    'label' => null,
    'value' => null,
    'required' => false,
    'minDate' => null,
    'maxDate' => null,
    'error' => null,
    'help' => null,
    'disabled' => false,
])

@php
    $inputId = $name . '_input';
    $errorMessage = $error ?? $errors->first($name);
    
    // Convert AD value to BS for display
    $bsDisplay = '';
    if ($value) {
        try {
            $bsDate = \App\Helpers\NepaliDateHelper::convertADtoBS($value);
            if ($bsDate) {
                $bsDisplay = \App\Services\NepaliCalendarService::formatNepaliDate(
                    $bsDate['year'], 
                    $bsDate['month'], 
                    $bsDate['day']
                );
            }
        } catch (\Exception $e) {
            // Silently handle conversion errors
        }
    }
@endphp

<div data-nepali-date class="nepali-datepicker-container">
    @if($label)
        <label for="{{ $inputId }}" class="block text-sm font-medium text-gray-700 mb-2">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif
    
    <div class="relative">
        {{-- Display Input (shows BS date) - clickable to open picker --}}
        <input 
            type="text" 
            id="{{ $inputId }}_display"
            class="nepali-date-display w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 {{ $errorMessage ? 'border-red-500' : '' }} cursor-pointer"
            value="{{ $bsDisplay }}"
            placeholder="Click to select date"
            {{ $disabled ? 'disabled' : '' }}
            readonly
            onclick="document.getElementById('{{ $inputId }}').showPicker()"
        >
        
        {{-- Hidden Date Input (native HTML5 date picker) --}}
        <input 
            type="date" 
            id="{{ $inputId }}"
            name="{{ $name }}"
            class="nepali-date-value"
            style="position: absolute; opacity: 0; width: 100%; height: 100%; top: 0; left: 0; cursor: pointer;"
            value="{{ old($name, $value) }}"
            @if($minDate) min="{{ $minDate }}" @endif
            @if($maxDate) max="{{ $maxDate }}" @endif
            {{ $required ? 'required' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            onchange="document.getElementById('{{ $inputId }}_display').value = this.value"
        >
        
        {{-- Calendar Icon --}}
        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
        </div>
    </div>
    
    {{-- Dual Date Display (when value exists) --}}
    @if(!$errorMessage && $value)
        <div class="mt-1 text-xs text-gray-500">
            <span class="font-medium">BS:</span> {{ $bsDisplay }} | 
            <span class="font-medium">AD:</span> {{ \Carbon\Carbon::parse($value)->format('d M Y') }}
        </div>
    @endif
    
    {{-- Help Text --}}
    @if($help && !$errorMessage)
        <p class="mt-1 text-sm text-gray-500">{{ $help }}</p>
    @endif
    
    {{-- Error Message --}}
    @if($errorMessage)
        <p class="mt-1 text-sm text-red-600">{{ $errorMessage }}</p>
    @endif
</div>
