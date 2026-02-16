@extends('layouts.app')

@section('page-title', 'Clinic Settings')

@section('content')
<div class="space-y-8 page-fade-in">
    <!-- Premium Header Section -->
    <div class="relative overflow-hidden bg-white rounded-3xl p-8 shadow-sm border border-slate-200">
        <div class="absolute top-0 right-0 -mt-20 -mr-20 w-64 h-64 bg-blue-500/5 rounded-full blur-3xl"></div>
        <div class="relative flex flex-col xl:flex-row xl:items-center xl:justify-between gap-8">
            <div class="flex-1">
                <div class="flex items-center space-x-3 mb-2">
                    <div class="p-2 bg-blue-100 rounded-lg text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <h1 class="text-3xl font-black text-slate-900 tracking-tight">Clinic Settings</h1>
                </div>
                <p class="text-slate-500 font-medium">Configure your clinic's global parameters, localization, and operational rules.</p>
            </div>
            
            <div class="w-full xl:w-auto">
                @include('components.nepali-date-widget')
            </div>
        </div>
    </div>

    <form action="{{ route('clinic.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 stagger-in">
            <!-- Left Panel: Navigation & Quick Info -->
            <div class="lg:col-span-4 space-y-6">
                <!-- Status Card -->
                <div class="bg-gradient-to-br from-slate-900 to-slate-800 rounded-3xl p-8 shadow-2xl border border-white/5 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                        <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.64.304 1.24.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    </div>
                    <div class="relative">
                        <h3 class="text-white font-black text-xl mb-4">Clinic Status</h3>
                        <div class="flex items-center space-x-3 mb-6">
                            <span class="w-3 h-3 bg-emerald-500 rounded-full animate-pulse"></span>
                            <span class="text-emerald-400 font-black text-sm uppercase tracking-widest">Active System</span>
                        </div>
                        <p class="text-slate-400 text-sm font-medium mb-6">Your clinic is currently active and processing appointments.</p>
                        <div class="p-4 bg-white/5 rounded-2xl border border-white/10 text-center">
                            <p class="text-xs text-slate-500 font-bold uppercase mb-1">Clinic ID</p>
                            <p class="text-white font-mono font-bold">{{ $clinic->uuid ?? 'SaaS-'.str_pad($clinic->id, 5, '0', STR_PAD_LEFT) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Guidance section -->
                <div class="bg-indigo-50 rounded-3xl p-8 border border-indigo-100">
                    <h4 class="text-indigo-900 font-black mb-3">Settings Impact</h4>
                    <p class="text-indigo-700/70 text-sm leading-relaxed mb-4">
                        Changes to working hours and duration will immediately affect your real-time booking availability.
                    </p>
                    <ul class="space-y-3 text-sm font-bold text-indigo-900">
                        <li class="flex items-center"><svg class="w-4 h-4 mr-2 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg> Live Updates</li>
                        <li class="flex items-center"><svg class="w-4 h-4 mr-2 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg> Multi-device Sync</li>
                        <li class="flex items-center"><svg class="w-4 h-4 mr-2 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg> Auto Backup</li>
                    </ul>
                </div>
            </div>

            <!-- Right Panel: Settings Forms -->
            <div class="lg:col-span-8 space-y-8">
                <!-- Basic Info Card -->
                <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-200">
                    <div class="flex items-center justify-between mb-8">
                        <h3 class="text-xl font-black text-slate-900 tracking-tight">Basic Information</h3>
                        <span class="px-3 py-1 bg-slate-100 text-slate-500 text-[10px] font-black uppercase tracking-widest rounded-full">Section 01</span>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Clinic Name</label>
                            <input type="text" name="name" value="{{ old('name', $clinic->name) }}" required
                                   class="w-full px-5 py-4 bg-slate-50 border-slate-200 rounded-2xl text-slate-900 font-bold focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none">
                            @error('name') <p class="text-rose-500 text-[10px] font-bold mt-1 ml-1 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Primary Email</label>
                            <input type="email" name="email" value="{{ old('email', $clinic->email) }}" required
                                   class="w-full px-5 py-4 bg-slate-50 border-slate-200 rounded-2xl text-slate-900 font-bold focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none">
                            @error('email') <p class="text-rose-500 text-[10px] font-bold mt-1 ml-1 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Contact Phone</label>
                            <input type="text" name="phone" value="{{ old('phone', $clinic->phone) }}" required
                                   class="w-full px-5 py-4 bg-slate-50 border-slate-200 rounded-2xl text-slate-900 font-bold focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none">
                            @error('phone') <p class="text-rose-500 text-[10px] font-bold mt-1 ml-1 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Website URL</label>
                            <input type="url" name="website" value="{{ old('website', $clinic->website) }}" 
                                   class="w-full px-5 py-4 bg-slate-50 border-slate-200 rounded-2xl text-slate-900 font-bold focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none">
                        </div>
                    </div>

                    <div class="mt-8 space-y-2">
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Physical Address</label>
                        <textarea name="address" rows="3" required
                                  class="w-full px-5 py-4 bg-slate-50 border-slate-200 rounded-2xl text-slate-900 font-bold focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none">{{ old('address', $clinic->address) }}</textarea>
                    </div>
                </div>

                <!-- Localization & Compliance Card -->
                <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-200">
                    <div class="flex items-center justify-between mb-8">
                        <h3 class="text-xl font-black text-slate-900 tracking-tight">Localization & Compliance</h3>
                        <span class="px-3 py-1 bg-slate-100 text-slate-500 text-[10px] font-black uppercase tracking-widest rounded-full">Section 02</span>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Operating Currency</label>
                            <select name="currency" class="w-full px-5 py-4 bg-slate-50 border-slate-200 rounded-2xl text-slate-900 font-bold focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none appearance-none">
                                <option value="NPR" {{ old('currency', $clinic->currency ?? 'NPR') === 'NPR' ? 'selected' : '' }}>Nepali Rupee (रु)</option>
                                <option value="USD" {{ old('currency', $clinic->currency) === 'USD' ? 'selected' : '' }}>US Dollar ($)</option>
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Timezone</label>
                            <select name="timezone" class="w-full px-5 py-4 bg-slate-50 border-slate-200 rounded-2xl text-slate-900 font-bold focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none appearance-none">
                                <option value="Asia/Kathmandu">Asia/Kathmandu (GMT+5:45)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Operational Logic Card -->
                <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-200">
                    <div class="flex items-center justify-between mb-8">
                        <h3 class="text-xl font-black text-slate-900 tracking-tight">Operational Logic</h3>
                        <span class="px-3 py-1 bg-slate-100 text-slate-500 text-[10px] font-black uppercase tracking-widest rounded-full">Section 03</span>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="space-y-2">
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Visit Slot (min)</label>
                            <input type="number" name="appointment_duration" value="{{ old('appointment_duration', $clinic->appointment_duration ?? 30) }}" 
                                   class="w-full px-5 py-4 bg-slate-50 border-slate-200 rounded-2xl text-slate-900 font-bold focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none">
                        </div>

                        <div class="space-y-2">
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Opening Time</label>
                            <input type="time" name="working_hours_start" value="{{ old('working_hours_start', $clinic->working_hours_start ?? '09:00') }}" 
                                   class="w-full px-5 py-4 bg-slate-50 border-slate-200 rounded-2xl text-slate-900 font-bold focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none">
                        </div>

                        <div class="space-y-2">
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Closing Time</label>
                            <input type="time" name="working_hours_end" value="{{ old('working_hours_end', $clinic->working_hours_end ?? '18:00') }}" 
                                   class="w-full px-5 py-4 bg-slate-50 border-slate-200 rounded-2xl text-slate-900 font-bold focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none">
                        </div>
                    </div>

                    <div class="space-y-4">
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Active Working Days</label>
                        <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-7 gap-3">
                            @foreach($days as $value => $label)
                                <label class="relative flex flex-col items-center justify-center p-4 rounded-2xl border-2 transition-all cursor-pointer group
                                    {{ in_array($value, $workingDays) ? 'bg-blue-50 border-blue-200' : 'bg-slate-50 border-slate-100 grayscale opacity-70' }}">
                                    <input type="checkbox" name="working_days[]" value="{{ $value }}" 
                                           {{ in_array($value, $workingDays) ? 'checked' : '' }}
                                           class="peer sr-only">
                                    <span class="text-[10px] font-black uppercase tracking-tighter mb-1 select-none {{ in_array($value, $workingDays) ? 'text-blue-700' : 'text-slate-500' }}">Day</span>
                                    <span class="text-sm font-black select-none {{ in_array($value, $workingDays) ? 'text-blue-900' : 'text-slate-700' }}">{{ substr($label, 0, 3) }}</span>
                                    
                                    <div class="absolute inset-0 rounded-2xl border-2 border-transparent peer-checked:border-blue-500 transition-all"></div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Submission Actions -->
                <div class="flex items-center justify-between p-8 bg-slate-900 rounded-3xl shadow-xl shadow-slate-900/20">
                    <div class="hidden sm:block">
                        <p class="text-white font-black">Ready to sync changes?</p>
                        <p class="text-slate-400 text-xs font-medium">System will refresh after successful update.</p>
                    </div>
                    <button type="submit" class="w-full sm:w-auto px-8 py-4 bg-blue-600 text-white font-black rounded-2xl hover:bg-blue-700 transition-all hover:scale-105 active:scale-95 shadow-lg shadow-blue-600/20">
                        Commit Changes
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection