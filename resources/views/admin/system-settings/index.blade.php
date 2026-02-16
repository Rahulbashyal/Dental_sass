@extends('layouts.app')
@php
    $isSetup = request()->routeIs('clinic.settings.*');
@endphp

@section('title', 'System Settings')
@section('page-title', $isSetup ? 'Clinic Setup' : 'Core System Logic')

@section('header')
@endsection

@php
    $allTabs = [
        'general' => ['icon' => 'cog', 'label' => 'General', 'description' => 'Clinic information'],
        'branding' => ['icon' => 'palette', 'label' => 'Branding', 'description' => 'Logo, colors, theme'],
        'landing-page' => ['icon' => 'layout', 'label' => 'Landing Page', 'description' => 'Hero, about, services'],
        'navigation' => ['icon' => 'menu', 'label' => 'Navigation', 'description' => 'Menu & links'],
        'seo' => ['icon' => 'search', 'label' => 'SEO', 'description' => 'Meta tags, analytics'],
        'business-hours' => ['icon' => 'clock', 'label' => 'Business Hours', 'description' => 'Schedule & timing'],
        'features' => ['icon' => 'toggle-on', 'label' => 'Features', 'description' => 'Modules & features'],
    ];

    $setupTabs = ['general', 'branding', 'business-hours'];
    $logicTabs = ['features', 'landing-page', 'navigation', 'seo'];
    
    $tabs = $isSetup 
        ? array_intersect_key($allTabs, array_flip($setupTabs)) 
        : array_intersect_key($allTabs, array_flip($logicTabs));
        
    $viewTitle = $isSetup ? 'Clinic Control Center' : 'System Engine';
    $viewTag = $isSetup ? 'Setup' : 'Logic';
    $viewDesc = $isSetup 
        ? 'Identity, branding, and operational schedule configuration.'
        : 'Module orchestration, public node content, and system intelligence settings.';
@endphp

@section('content')
    <!-- Combined Header & Dashboard Information -->
    <div class="mb-8 p-6 bg-white rounded-2xl border border-slate-200 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight flex items-center">
                    {{ $viewTitle }}
                    <span class="ml-3 px-2 py-0.5 bg-blue-100 text-blue-600 text-[10px] uppercase font-black rounded-md tracking-widest">{{ $viewTag }}</span>
                </h1>
                <p class="mt-1 text-sm text-slate-500 font-medium">{{ $viewDesc }}</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('clinic.system-settings.preview') }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition shadow-sm">
                    <i class="fas fa-external-link-alt mr-2"></i>
                    Preview Landing Page
                </a>
                <form action="{{ route('clinic.system-settings.toggle-status') }}" method="POST">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-4 py-2 {{ $landingContent->is_active ? 'bg-green-600 hover:bg-green-700' : 'bg-gray-600 hover:bg-gray-700' }} text-white rounded-lg transition shadow-sm">
                        <span class="w-2 h-2 mr-2 rounded-full {{ $landingContent->is_active ? 'bg-white' : 'bg-gray-400' }}"></span>
                        {{ $landingContent->is_active ? 'Live' : 'Offline' }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="flex flex-col lg:flex-row gap-6">
        <!-- Sidebar Tabs -->
        <div class="lg:w-64 flex-shrink-0">
            <nav class="bg-white rounded-xl shadow-sm border border-gray-200 p-2">
                @foreach($tabs as $key => $tab)
                    <a href="{{ route('clinic.system-settings.index', ['tab' => $key]) }}" 
                       class="flex items-center px-4 py-3 rounded-lg mb-1 transition-all duration-200
                       {{ $activeTab === $key 
                           ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-md' 
                           : 'text-gray-600 hover:bg-gray-50' }}">
                        <span class="flex items-center justify-center w-8 h-8 rounded-lg {{ $activeTab === $key ? 'bg-white/20' : 'bg-gray-100' }}">
                            @switch($tab['icon'])
                                @case('cog')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    @break
                                @case('palette')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path></svg>
                                    @break
                                @case('layout')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path></svg>
                                    @break
                                @case('menu')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                                    @break
                                @case('search')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                    @break
                                @case('clock')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    @break
                                @case('toggle-on')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    @break
                            @endswitch
                        </span>
                        <div class="ml-3">
                            <p class="font-medium text-sm">{{ $tab['label'] }}</p>
                            <p class="text-xs {{ $activeTab === $key ? 'text-indigo-100' : 'text-gray-400' }}">{{ $tab['description'] }}</p>
                        </div>
                    </a>
                @endforeach
            </nav>

            <!-- Quick Stats -->
            <div class="mt-4 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl p-4 text-white">
                <h3 class="font-semibold text-sm">Landing Page Status</h3>
                <div class="mt-3 space-y-2">
                    <div class="flex justify-between items-center">
                        <span class="text-xs text-indigo-100">Status</span>
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $landingContent->is_active ? 'bg-green-400' : 'bg-gray-400' }}">
                            {{ $landingContent->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-xs text-indigo-100">Theme</span>
                        <span class="text-xs font-medium">{{ $landingContent->theme_template ?? 'Default' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="flex-1">
            <!-- General Settings -->
            @if($activeTab === 'general')
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <form action="{{ route('clinic.system-settings.update-general') }}" method="POST" class="p-6">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="clinic_id" value="{{ $clinic->id }}">
                        
                        <h2 class="text-lg font-semibold text-gray-900 mb-6">General Clinic Information</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Clinic Name *</label>
                                <input type="text" name="name" value="{{ old('name', $clinic->name) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent" required>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                                <input type="email" name="email" value="{{ old('email', $clinic->email) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent" required>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                                <input type="text" name="phone" value="{{ old('phone', $clinic->phone) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Website</label>
                                <input type="url" name="website" value="{{ old('website', $clinic->website) }}" placeholder="https://example.com" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            </div>
                            
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                                <input type="text" name="address" value="{{ old('address', $clinic->address) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">City</label>
                                <input type="text" name="city" value="{{ old('city', $clinic->city) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">State/Province</label>
                                <input type="text" name="state" value="{{ old('state', $clinic->state) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Country</label>
                                <input type="text" name="country" value="{{ old('country', $clinic->country ?? 'Nepal') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Postal Code</label>
                                <input type="text" name="postal_code" value="{{ old('postal_code', $clinic->postal_code) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Timezone</label>
                                <select name="timezone" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                    <option value="Asia/Kathmandu" {{ old('timezone', $clinic->timezone) === 'Asia/Kathmandu' ? 'selected' : '' }}>Asia/Kathmandu (NPT)</option>
                                    <option value="Asia/Kolkata" {{ old('timezone', $clinic->timezone) === 'Asia/Kolkata' ? 'selected' : '' }}>Asia/Kolkata (IST)</option>
                                    <option value="UTC" {{ old('timezone', $clinic->timezone) === 'UTC' ? 'selected' : '' }}>UTC</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Currency</label>
                                <select name="currency" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                    <option value="NPR" {{ old('currency', $clinic->currency) === 'NPR' ? 'selected' : '' }}>NPR (Nepalese Rupee)</option>
                                    <option value="USD" {{ old('currency', $clinic->currency) === 'USD' ? 'selected' : '' }}>USD (US Dollar)</option>
                                </select>
                            </div>
                            
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tagline</label>
                                <input type="text" name="tagline" value="{{ old('tagline', $clinic->tagline) }}" placeholder="Your clinic's tagline" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            </div>
                            
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                                <textarea name="description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">{{ old('description', $clinic->description) }}</textarea>
                            </div>

                            <!-- Operational Settings -->
                            <div class="md:col-span-2 mt-4 pt-6 border-t">
                                <h3 class="text-md font-semibold text-gray-900 mb-4">Operational Parameters</h3>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Visit Slot (Minutes)</label>
                                        <input type="number" name="appointment_duration" value="{{ old('appointment_duration', $clinic->appointment_duration ?? 30) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Standard Start Time</label>
                                        <input type="time" name="working_hours_start" value="{{ old('working_hours_start', $clinic->working_hours_start ?? '09:00') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Standard End Time</label>
                                        <input type="time" name="working_hours_end" value="{{ old('working_hours_end', $clinic->working_hours_end ?? '18:00') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                    </div>
                                </div>
                            </div>

                            <div class="md:col-span-2 mt-4">
                                <label class="block text-sm font-medium text-gray-700 mb-4">Weekly Schedule (Active Days)</label>
                                @php
                                    $allDays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                                    $activeDays = is_array($clinic->working_days) ? $clinic->working_days : json_decode($clinic->working_days ?? '[]', true) ?? ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
                                @endphp
                                <div class="flex flex-wrap gap-3">
                                    @foreach($allDays as $day)
                                        <label class="flex items-center px-4 py-2 bg-gray-50 border rounded-xl cursor-pointer hover:bg-indigo-50 transition group">
                                            <input type="checkbox" name="working_days[]" value="{{ $day }}" {{ in_array($day, $activeDays) ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 rounded">
                                            <span class="ml-2 text-sm font-semibold capitalize group-hover:text-indigo-600 transition">{{ substr($day, 0, 3) }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Social Links -->
                            <div class="md:col-span-2 mt-6 pt-6 border-t">
                                <h3 class="text-md font-semibold text-gray-900 mb-4">Social Media Channels</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Facebook URL</label>
                                        <div class="flex">
                                            <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm"><i class="fab fa-facebook-f"></i></span>
                                            <input type="url" name="facebook_url" value="{{ old('facebook_url', $clinic->facebook_url) }}" class="flex-1 px-4 py-2 border border-gray-300 rounded-r-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Instagram URL</label>
                                        <div class="flex">
                                            <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm"><i class="fab fa-instagram"></i></span>
                                            <input type="url" name="instagram_url" value="{{ old('instagram_url', $clinic->instagram_url) }}" class="flex-1 px-4 py-2 border border-gray-300 rounded-r-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">LinkedIn URL</label>
                                        <div class="flex">
                                            <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm"><i class="fab fa-linkedin-in"></i></span>
                                            <input type="url" name="linkedin_url" value="{{ old('linkedin_url', $clinic->linkedin_url) }}" class="flex-1 px-4 py-2 border border-gray-300 rounded-r-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">YouTube URL</label>
                                        <div class="flex">
                                            <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm"><i class="fab fa-youtube"></i></span>
                                            <input type="url" name="youtube_url" value="{{ old('youtube_url', $clinic->youtube_url) }}" class="flex-1 px-4 py-2 border border-gray-300 rounded-r-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-6 flex justify-between items-center">
                            <div class="flex items-center space-x-3">
                                <label class="flex items-center cursor-pointer">
                                    <div class="relative">
                                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $clinic->is_active ?? true) ? 'checked' : '' }} class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                                    </div>
                                    <span class="ml-3 text-sm font-medium text-gray-700">Clinic Status (Online/Offline)</span>
                                </label>
                            </div>
                            <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            <!-- Branding Settings -->
            @if($activeTab === 'branding')
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <form action="{{ route('clinic.system-settings.update-branding') }}" method="POST" enctype="multipart/form-data" class="p-6">
                        @csrf
                        @method('PUT')
                        
                        <h2 class="text-lg font-semibold text-gray-900 mb-6">Branding & Visual Identity</h2>
                        
                        <!-- Logo Upload -->
                        <div class="mb-8">
                            <h3 class="text-sm font-medium text-gray-700 mb-4">Logo</h3>
                            <div class="flex items-start space-x-6">
                                <div class="flex-shrink-0">
                                    @if($clinic->logo)
                                        <img src="{{ Storage::url($clinic->logo) }}" alt="Current Logo" class="w-24 h-24 object-contain border border-gray-200 rounded-lg bg-white p-2">
                                    @else
                                        <div class="w-24 h-24 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center">
                                            <span class="text-gray-400 text-sm">No Logo</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Upload New Logo</label>
                                    <input type="file" name="logo" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                    <p class="mt-1 text-xs text-gray-500">PNG, JPG, GIF up to 2MB. Recommended: 200x60px</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Favicon Upload -->
                        <div class="mb-8">
                            <h3 class="text-sm font-medium text-gray-700 mb-4">Favicon</h3>
                            <div class="flex items-start space-x-6">
                                <div class="flex-shrink-0">
                                    @if($clinic->favicon)
                                        <img src="{{ Storage::url($clinic->favicon) }}" alt="Current Favicon" class="w-16 h-16 object-contain border border-gray-200 rounded-lg bg-white p-1">
                                    @else
                                        <div class="w-16 h-16 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center">
                                            <span class="text-gray-400 text-xs">None</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Upload Favicon</label>
                                    <input type="file" name="favicon" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                    <p class="mt-1 text-xs text-gray-500">ICO, PNG up to 512KB. Recommended: 32x32px or 64x64px</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Color Scheme -->
                        <div class="mb-8">
                            <h3 class="text-sm font-medium text-gray-700 mb-4">Color Scheme</h3>
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Primary Color</label>
                                    <div class="flex items-center space-x-2">
                                        <input type="color" name="primary_color" value="{{ old('primary_color', $clinic->primary_color ?? '#3b82f6') }}" class="w-12 h-10 border border-gray-300 rounded cursor-pointer">
                                        <input type="text" name="primary_color_text" value="{{ old('primary_color', $clinic->primary_color ?? '#3b82f6') }}" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Secondary Color</label>
                                    <div class="flex items-center space-x-2">
                                        <input type="color" name="secondary_color" value="{{ old('secondary_color', $clinic->secondary_color ?? '#06b6d4') }}" class="w-12 h-10 border border-gray-300 rounded cursor-pointer">
                                        <input type="text" name="secondary_color_text" value="{{ old('secondary_color', $clinic->secondary_color ?? '#06b6d4') }}" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Accent Color</label>
                                    <div class="flex items-center space-x-2">
                                        <input type="color" name="accent_color" value="{{ old('accent_color', $clinic->accent_color ?? '#8b5cf6') }}" class="w-12 h-10 border border-gray-300 rounded cursor-pointer">
                                        <input type="text" name="accent_color_text" value="{{ old('accent_color', $clinic->accent_color ?? '#8b5cf6') }}" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Theme Color</label>
                                    <div class="flex items-center space-x-2">
                                        <input type="color" name="theme_color" value="{{ old('theme_color', $clinic->theme_color ?? '#1e293b') }}" class="w-12 h-10 border border-gray-300 rounded cursor-pointer">
                                        <input type="text" name="theme_color_text" value="{{ old('theme_color', $clinic->theme_color ?? '#1e293b') }}" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Preview -->
                        <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                            <h3 class="text-sm font-medium text-gray-700 mb-3">Preview</h3>
                            <div class="flex items-center space-x-4">
                                <div class="px-4 py-2 rounded-lg text-white text-sm font-medium" style="background-color: {{ $clinic->primary_color ?? '#3b82f6' }}">
                                    Primary Button
                                </div>
                                <div class="px-4 py-2 rounded-lg text-white text-sm font-medium" style="background-color: {{ $clinic->secondary_color ?? '#06b6d4' }}">
                                    Secondary Button
                                </div>
                                <div class="px-4 py-2 rounded-lg text-white text-sm font-medium" style="background-color: {{ $clinic->accent_color ?? '#8b5cf6' }}">
                                    Accent Button
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-6 flex justify-end">
                            <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                                Save Branding
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            <!-- Landing Page Settings -->
            @if($activeTab === 'landing-page')
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <form action="{{ route('clinic.system-settings.update-landing-page') }}" method="POST" enctype="multipart/form-data" class="p-6">
                        @csrf
                        @method('PUT')
                        
                        <h2 class="text-lg font-semibold text-gray-900 mb-6 font-bold tracking-tight uppercase border-b pb-2">Landing Page Content</h2>

                        @if($errors->any())
                            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-lg shadow-sm">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-exclamation-circle mt-0.5"></i>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-bold uppercase tracking-wider">Storage & Validation Errors</h3>
                                        <div class="mt-2 text-xs font-medium">
                                            <ul class="list-disc pl-5 space-y-1">
                                                @foreach($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Section Visibility -->
                        <div class="mb-8 bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-md font-medium text-gray-800 mb-4">Section Visibility</h3>
                            <p class="text-sm text-gray-600 mb-4">Toggle which sections to display on your landing page</p>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                <label class="flex items-center space-x-3">
                                    <input type="checkbox" name="show_hero" value="1" {{ old('show_hero', $landingContent->show_hero ?? true) ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                    <span class="text-sm font-medium text-gray-700">Hero Section</span>
                                </label>
                                <label class="flex items-center space-x-3">
                                    <input type="checkbox" name="show_stats" value="1" {{ old('show_stats', $landingContent->show_stats ?? true) ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                    <span class="text-sm font-medium text-gray-700">Stats Section</span>
                                </label>
                                <label class="flex items-center space-x-3">
                                    <input type="checkbox" name="show_about" value="1" {{ old('show_about', $landingContent->show_about ?? true) ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                    <span class="text-sm font-medium text-gray-700">About Section</span>
                                </label>
                                <label class="flex items-center space-x-3">
                                    <input type="checkbox" name="show_services" value="1" {{ old('show_services', $landingContent->show_services ?? true) ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                    <span class="text-sm font-medium text-gray-700">Services Section</span>
                                </label>
                                <label class="flex items-center space-x-3">
                                    <input type="checkbox" name="show_gallery" value="1" {{ old('show_gallery', $landingContent->show_gallery ?? true) ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                    <span class="text-sm font-medium text-gray-700">Gallery Section</span>
                                </label>
                                <label class="flex items-center space-x-3">
                                    <input type="checkbox" name="show_testimonials" value="1" {{ old('show_testimonials', $landingContent->show_testimonials ?? true) ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                    <span class="text-sm font-medium text-gray-700">Testimonials</span>
                                </label>
                                <label class="flex items-center space-x-3">
                                    <input type="checkbox" name="show_faq" value="1" {{ old('show_faq', $landingContent->show_faq ?? true) ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                    <span class="text-sm font-medium text-gray-700">FAQ Section</span>
                                </label>
                                <label class="flex items-center space-x-3">
                                    <input type="checkbox" name="show_contact" value="1" {{ old('show_contact', $landingContent->show_contact ?? true) ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                    <span class="text-sm font-medium text-gray-700">Contact Section</span>
                                </label>
                                <label class="flex items-center space-x-3">
                                    <input type="checkbox" name="show_footer" value="1" {{ old('show_footer', $landingContent->show_footer ?? true) ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                    <span class="text-sm font-medium text-gray-700">Footer</span>
                                </label>
                            </div>
                        </div>
                        
                        <!-- Hero Section -->
                        <div class="mb-8">
                            <h3 class="text-md font-medium text-gray-800 mb-4 pb-2 border-b">Header & Navigation</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Navbar Tagline</label>
                                    <input type="text" name="navbar_tagline" value="{{ old('navbar_tagline', $landingContent->navbar_tagline) }}" placeholder="Professional Dental Care" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Booking CTA Button</label>
                                    <input type="text" name="nav_booking_cta" value="{{ old('nav_booking_cta', $landingContent->nav_booking_cta) }}" placeholder="Book Appointment" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Home Label</label>
                                    <input type="text" name="nav_home_label" value="{{ old('nav_home_label', $landingContent->nav_home_label) }}" placeholder="Home" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">About Label</label>
                                    <input type="text" name="nav_about_label" value="{{ old('nav_about_label', $landingContent->nav_about_label) }}" placeholder="About" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Services Label</label>
                                    <input type="text" name="nav_services_label" value="{{ old('nav_services_label', $landingContent->nav_services_label) }}" placeholder="Services" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Gallery Label</label>
                                    <input type="text" name="nav_gallery_label" value="{{ old('nav_gallery_label', $landingContent->nav_gallery_label) }}" placeholder="Gallery" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Reviews Label</label>
                                    <input type="text" name="nav_testimonials_label" value="{{ old('nav_testimonials_label', $landingContent->nav_testimonials_label) }}" placeholder="Reviews" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">FAQ Label</label>
                                    <input type="text" name="nav_faq_label" value="{{ old('nav_faq_label', $landingContent->nav_faq_label) }}" placeholder="FAQ" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Contact Label</label>
                                    <input type="text" name="nav_contact_label" value="{{ old('nav_contact_label', $landingContent->nav_contact_label) }}" placeholder="Contact" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Stats Section -->
                        <div class="mb-8">
                            <h3 class="text-md font-medium text-gray-800 mb-4 pb-2 border-b">Stats Section (Trust Badges)</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Years Experience Value</label>
                                    <input type="text" name="stats_experience" value="{{ old('stats_experience', $landingContent->stats_experience ?? '15+') }}" placeholder="15+" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Years Experience Label</label>
                                    <input type="text" name="stats_experience_label" value="{{ old('stats_experience_label', $landingContent->stats_experience_label ?? 'Years Experience') }}" placeholder="Years Experience" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Happy Patients Value</label>
                                    <input type="text" name="stats_patients" value="{{ old('stats_patients', $landingContent->stats_patients ?? '2500+') }}" placeholder="2500+" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Happy Patients Label</label>
                                    <input type="text" name="stats_patients_label" value="{{ old('stats_patients_label', $landingContent->stats_patients_label ?? 'Happy Patients') }}" placeholder="Happy Patients" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Success Rate Value</label>
                                    <input type="text" name="stats_success_rate" value="{{ old('stats_success_rate', $landingContent->stats_success_rate ?? '98%') }}" placeholder="98%" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Success Rate Label</label>
                                    <input type="text" name="stats_success_rate_label" value="{{ old('stats_success_rate_label', $landingContent->stats_success_rate_label ?? 'Success Rate') }}" placeholder="Success Rate" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Emergency Value</label>
                                    <input type="text" name="stats_emergency" value="{{ old('stats_emergency', $landingContent->stats_emergency ?? '24/7') }}" placeholder="24/7" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Emergency Label</label>
                                    <input type="text" name="stats_emergency_label" value="{{ old('stats_emergency_label', $landingContent->stats_emergency_label ?? 'Emergency Care') }}" placeholder="Emergency Care" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Hero Section Content -->
                        <div class="mb-8">
                            <h3 class="text-md font-medium text-gray-800 mb-4 pb-2 border-b">Hero Section</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Hero Title</label>
                                    <input type="text" name="hero_title" value="{{ old('hero_title', $landingContent->hero_title) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                </div>
                                
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Hero Subtitle</label>
                                    <textarea name="hero_subtitle" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">{{ old('hero_subtitle', $landingContent->hero_subtitle) }}</textarea>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Primary CTA Text</label>
                                    <input type="text" name="hero_cta_primary" value="{{ old('hero_cta_primary', $landingContent->hero_cta_primary) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Secondary CTA Text</label>
                                    <input type="text" name="hero_cta_secondary" value="{{ old('hero_cta_secondary', $landingContent->hero_cta_secondary) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                </div>
                                
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Hero Image</label>
                                    @if($landingContent->hero_image)
                                        <img src="{{ $landingContent->getImageUrl('hero_image') }}" alt="Hero Image" class="w-full max-w-md h-48 object-cover rounded-lg mb-2">
                                    @endif
                                    <input type="file" name="hero_image" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                </div>
                                
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Hero Carousel Images (Multiple)</label>
                                    <p class="text-xs text-gray-500 mb-2">Upload multiple images for the hero carousel</p>
                                    @if($landingContent->hero_carousel_images && count($landingContent->hero_carousel_images) > 0)
                                        <div class="grid grid-cols-4 gap-2 mb-2">
                                            @foreach($landingContent->hero_carousel_images as $img)
                                                <div class="relative group">
                                                    <img src="{{ $landingContent->getGalleryImageUrl($img) }}" class="w-full h-32 md:h-20 object-cover rounded-lg border shadow-sm group-hover:border-red-400 transition-all">
                                                    <button type="button" 
                                                        onclick="deleteLandingImage('hero_carousel_images', '{{ $img }}')"
                                                        class="absolute top-1 right-1 bg-red-500 hover:bg-red-600 text-white w-6 h-6 rounded-full opacity-0 group-hover:opacity-100 transition-all flex items-center justify-center shadow-md">
                                                        <i class="fas fa-times text-[10px]"></i>
                                                    </button>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                     <input type="file" name="hero_carousel_images[]" 
                                         multiple 
                                         accept="image/jpeg,image/png,image/jpg,image/webp" 
                                         onchange="updateFileCount(this, 'hero-count')"
                                         class="block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-6 file:rounded-xl file:border-0 file:text-sm file:font-black file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 transition-all cursor-pointer shadow-md">
                                     <div id="hero-count" class="mt-3 p-3 bg-indigo-50 border border-indigo-100 rounded-xl text-xs text-indigo-700 font-bold hidden animate-pulse"></div>
                                 </div>
                            </div>
                        </div>
                        
                        <!-- About Section -->
                        <div class="mb-8">
                            <h3 class="text-md font-medium text-gray-800 mb-4 pb-2 border-b">About Section</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">About Title</label>
                                    <input type="text" name="about_title" value="{{ old('about_title', $landingContent->about_title) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                </div>
                                
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">About Description</label>
                                    <textarea name="about_description" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">{{ old('about_description', $landingContent->about_description) }}</textarea>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Doctor/Clinic Name</label>
                                    <input type="text" name="about_doctor_name" value="{{ old('about_doctor_name', $landingContent->about_doctor_name) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Years of Experience</label>
                                    <input type="number" name="about_years_experience" value="{{ old('about_years_experience', $landingContent->about_years_experience) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                </div>
                                
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">About Image</label>
                                    @if($landingContent->about_image)
                                        <img src="{{ $landingContent->getImageUrl('about_image') }}" alt="About Image" class="w-full max-w-md h-48 object-cover rounded-lg mb-2">
                                    @endif
                                    <input type="file" name="about_image" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Services Section -->
                        <div class="mb-8">
                            <div class="flex items-center justify-between mb-4 pb-2 border-b">
                                <h3 class="text-md font-medium text-gray-800">Services Section</h3>
                                <button type="button" onclick="addService()" class="text-xs px-3 py-1 bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-100 transition font-bold">
                                    <i class="fas fa-plus mr-1"></i> Add Service
                                </button>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Services Title</label>
                                    <input type="text" name="services_title" value="{{ old('services_title', $landingContent->services_title) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Services Description</label>
                                    <textarea name="services_description" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">{{ old('services_description', $landingContent->services_description) }}</textarea>
                                </div>
                            </div>
                            <div id="services-list" class="space-y-3">
                                @php
                                    $services = is_array($landingContent->services_data) ? $landingContent->services_data : (is_string($landingContent->services_data) ? json_decode($landingContent->services_data, true) : []);
                                @endphp
                                @foreach($services as $index => $service)
                                    <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg border border-gray-200 group">
                                        <div class="grid grid-cols-1 md:grid-cols-4 gap-3 flex-1">
                                            <input type="text" name="services[{{ $index }}][name]" value="{{ $service['name'] ?? '' }}" placeholder="Service Name" class="px-3 py-2 text-sm border border-gray-300 rounded-lg">
                                            <input type="text" name="services[{{ $index }}][description]" value="{{ $service['description'] ?? '' }}" placeholder="Description" class="px-3 py-2 text-sm border border-gray-300 rounded-lg col-span-2">
                                            <input type="text" name="services[{{ $index }}][icon]" value="{{ $service['icon'] ?? '' }}" placeholder="Icon (e.g. 🦷)" class="px-3 py-2 text-sm border border-gray-300 rounded-lg">
                                        </div>
                                        <button type="button" onclick="this.parentElement.remove()" class="text-gray-400 hover:text-red-500 transition-colors p-2">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        
                        <!-- Gallery Section -->
                        <div class="mb-8">
                            <h3 class="text-md font-medium text-gray-800 mb-4 pb-2 border-b">Gallery Section</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Gallery Title</label>
                                    <input type="text" name="gallery_title" value="{{ old('gallery_title', $landingContent->gallery_title) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Gallery Description</label>
                                    <textarea name="gallery_description" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">{{ old('gallery_description', $landingContent->gallery_description) }}</textarea>
                                </div>
                                
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Gallery Images (Multiple)</label>
                                    @if($landingContent->gallery_images && count($landingContent->gallery_images) > 0)
                                        <div class="grid grid-cols-4 gap-2 mb-2">
                                            @foreach($landingContent->gallery_images as $index => $item)
                                                @php
                                                    $path = is_array($item) ? ($item['path'] ?? '') : $item;
                                                    $description = is_array($item) ? ($item['description'] ?? '') : '';
                                                @endphp
                                                <div class="relative group border rounded-xl p-2 bg-white shadow-sm">
                                                    <div class="relative">
                                                        <img src="{{ $landingContent->getGalleryImageUrl($path) }}" class="w-full h-24 object-cover rounded-lg mb-2">
                                                        <button type="button" 
                                                            onclick="deleteLandingImage('gallery_images', '{{ $path }}')"
                                                            class="absolute top-1 right-1 bg-red-500 hover:bg-red-600 text-white w-6 h-6 rounded-full opacity-0 group-hover:opacity-100 transition-all flex items-center justify-center shadow-md">
                                                            <i class="fas fa-times text-[10px]"></i>
                                                        </button>
                                                    </div>
                                                    <input type="text" 
                                                           name="gallery_meta[{{ $path }}]" 
                                                           value="{{ $description }}" 
                                                           placeholder="Add description..."
                                                           class="w-full text-[10px] px-2 py-1 border border-gray-200 rounded-md focus:ring-1 focus:ring-indigo-500">
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                     <input type="file" name="gallery_images[]" 
                                         multiple 
                                         accept="image/jpeg,image/png,image/jpg,image/webp" 
                                         onchange="updateFileCount(this, 'gallery-count')"
                                         class="block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-6 file:rounded-xl file:border-0 file:text-sm file:font-black file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 transition-all cursor-pointer shadow-sm">
                                     <div id="gallery-count" class="mt-3 p-3 bg-indigo-50 border border-indigo-100 rounded-xl text-xs text-indigo-700 font-bold hidden animate-pulse"></div>
                                 </div>
                            </div>
                        </div>
                        
                        <!-- Testimonials Section -->
                        <div class="mb-8">
                            <div class="flex items-center justify-between mb-4 pb-2 border-b">
                                <h3 class="text-md font-medium text-gray-800">Testimonials Section</h3>
                                <button type="button" onclick="addTestimonial()" class="text-xs px-3 py-1 bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-100 transition font-bold">
                                    <i class="fas fa-plus mr-1"></i> Add Testimonial
                                </button>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Testimonials Title</label>
                                    <input type="text" name="testimonials_title" value="{{ old('testimonials_title', $landingContent->testimonials_title) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Testimonials Description</label>
                                    <textarea name="testimonials_description" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">{{ old('testimonials_description', $landingContent->testimonials_description) }}</textarea>
                                </div>
                            </div>
                            <div id="testimonials-list" class="space-y-3">
                                @php
                                    $testimonials = is_array($landingContent->testimonials_data) ? $landingContent->testimonials_data : (is_string($landingContent->testimonials_data) ? json_decode($landingContent->testimonials_data, true) : []);
                                @endphp
                                @foreach($testimonials as $index => $testimonial)
                                    <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg border border-gray-200 group">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 flex-1">
                                            <input type="text" name="testimonials[{{ $index }}][name]" value="{{ $testimonial['name'] ?? '' }}" placeholder="Patient Name" class="px-3 py-2 text-sm border border-gray-300 rounded-lg">
                                            <input type="text" name="testimonials[{{ $index }}][designation]" value="{{ $testimonial['designation'] ?? '' }}" placeholder="Position/Tagline" class="px-3 py-2 text-sm border border-gray-300 rounded-lg">
                                            <textarea name="testimonials[{{ $index }}][review]" rows="2" placeholder="Testimonial content" class="px-3 py-2 text-sm border border-gray-300 rounded-lg col-span-2">{{ $testimonial['review'] ?? '' }}</textarea>
                                        </div>
                                        <button type="button" onclick="this.parentElement.remove()" class="text-gray-400 hover:text-red-500 transition-colors p-2">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        
                        <!-- FAQ Section -->
                        <div class="mb-8">
                            <div class="flex items-center justify-between mb-4 pb-2 border-b">
                                <h3 class="text-md font-medium text-gray-800">FAQ Section</h3>
                                <button type="button" onclick="addFaq()" class="text-xs px-3 py-1 bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-100 transition font-bold">
                                    <i class="fas fa-plus mr-1"></i> Add FAQ
                                </button>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">FAQ Title</label>
                                    <input type="text" name="faq_title" value="{{ old('faq_title', $landingContent->faq_title) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">FAQ Description</label>
                                    <textarea name="faq_description" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">{{ old('faq_description', $landingContent->faq_description) }}</textarea>
                                </div>
                            </div>
                            <div id="faq-list" class="space-y-3">
                                @php
                                    $faqs = is_array($landingContent->faq_data) ? $landingContent->faq_data : (is_string($landingContent->faq_data) ? json_decode($landingContent->faq_data, true) : []);
                                @endphp
                                @foreach($faqs as $index => $faq)
                                    <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg border border-gray-200 group">
                                        <div class="grid grid-cols-1 gap-3 flex-1">
                                            <input type="text" name="faq[{{ $index }}][question]" value="{{ $faq['question'] ?? '' }}" placeholder="Question" class="px-3 py-2 text-sm border border-gray-300 rounded-lg w-full">
                                            <textarea name="faq[{{ $index }}][answer]" rows="2" placeholder="Answer" class="px-3 py-2 text-sm border border-gray-300 rounded-lg w-full">{{ $faq['answer'] ?? '' }}</textarea>
                                        </div>
                                        <button type="button" onclick="this.parentElement.remove()" class="text-gray-400 hover:text-red-500 transition-colors p-2">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        
                        <!-- Contact Section -->
                        <div class="mb-8">
                            <h3 class="text-md font-medium text-gray-800 mb-4 pb-2 border-b">Contact Section</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Contact Title</label>
                                    <input type="text" name="contact_title" value="{{ old('contact_title', $landingContent->contact_title) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Contact Subtitle</label>
                                    <input type="text" name="contact_subtitle" value="{{ old('contact_subtitle', $landingContent->contact_subtitle) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Get in Touch Title</label>
                                    <input type="text" name="contact_get_in_touch_title" value="{{ old('contact_get_in_touch_title', $landingContent->contact_get_in_touch_title) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Send Message Title</label>
                                    <input type="text" name="contact_send_message_title" value="{{ old('contact_send_message_title', $landingContent->contact_send_message_title) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Contact Phone</label>
                                    <input type="text" name="contact_phone" value="{{ old('contact_phone', $landingContent->contact_phone ?? $clinic->phone) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Contact Email</label>
                                    <input type="email" name="contact_email" value="{{ old('contact_email', $landingContent->contact_email ?? $clinic->email) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                </div>
                                
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Contact Address</label>
                                    <textarea name="contact_address" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">{{ old('contact_address', $landingContent->contact_address ?? $clinic->address) }}</textarea>
                                </div>
                                
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Google Maps URL</label>
                                    <input type="url" name="google_maps_url" value="{{ old('google_maps_url', $landingContent->google_maps_url) }}" placeholder="https://maps.google.com/..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                </div>
                                
                                <div>
                                    <label class="flex items-center space-x-3">
                                        <input type="checkbox" name="contact_form_enabled" value="1" {{ old('contact_form_enabled', $landingContent->contact_form_enabled) ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                        <span class="text-sm font-medium text-gray-700">Enable Contact Form</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Footer Section -->
                        <div class="mb-8">
                            <h3 class="text-md font-medium text-gray-800 mb-4 pb-2 border-b">Footer</h3>
                            <div class="grid grid-cols-1 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Footer Tagline</label>
                                    <input type="text" name="footer_tagline" value="{{ old('footer_tagline', $landingContent->footer_tagline) }}" placeholder="Professional Dental Care" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Footer Description</label>
                                    <textarea name="footer_description" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">{{ old('footer_description', $landingContent->footer_description) }}</textarea>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Copyright Text</label>
                                    <input type="text" name="footer_copyright" value="{{ old('footer_copyright', $landingContent->footer_copyright) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-6 flex justify-end">
                            <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                                Save Landing Page
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            <!-- Navigation Settings -->
            @if($activeTab === 'navigation')
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <form action="{{ route('clinic.system-settings.update-navigation') }}" method="POST" class="p-6">
                        @csrf
                        @method('PUT')
                        
                        <h2 class="text-lg font-semibold text-gray-900 mb-6">Navigation & Menu Settings</h2>
                        
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-sm font-medium text-gray-700 mb-4">Menu Visibility</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <label class="flex items-center space-x-3 p-4 border rounded-lg hover:bg-gray-50">
                                        <input type="checkbox" name="show_in_landing_menu" value="1" {{ $clinic->show_in_landing_menu ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                        <span class="text-sm font-medium text-gray-700">Home</span>
                                    </label>
                                    <label class="flex items-center space-x-3 p-4 border rounded-lg hover:bg-gray-50">
                                        <input type="checkbox" name="show_services_menu" value="1" {{ $clinic->show_services_menu ?? true ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                        <span class="text-sm font-medium text-gray-700">Services</span>
                                    </label>
                                    <label class="flex items-center space-x-3 p-4 border rounded-lg hover:bg-gray-50">
                                        <input type="checkbox" name="show_team_menu" value="1" {{ $clinic->show_team_menu ?? true ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                        <span class="text-sm font-medium text-gray-700">Team</span>
                                    </label>
                                    <label class="flex items-center space-x-3 p-4 border rounded-lg hover:bg-gray-50">
                                        <input type="checkbox" name="show_gallery_menu" value="1" {{ $clinic->show_gallery_menu ?? true ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                        <span class="text-sm font-medium text-gray-700">Gallery</span>
                                    </label>
                                    <label class="flex items-center space-x-3 p-4 border rounded-lg hover:bg-gray-50">
                                        <input type="checkbox" name="show_contact_menu" value="1" {{ $clinic->show_contact_menu ?? true ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                        <span class="text-sm font-medium text-gray-700">Contact</span>
                                    </label>
                                </div>
                            </div>
                            
                            <div>
                                <h3 class="text-sm font-medium text-gray-700 mb-4">Booking Button</h3>
                                <div class="space-y-4">
                                    <label class="flex items-center space-x-3">
                                        <input type="checkbox" name="show_booking_button" value="1" {{ $clinic->show_booking_button ?? true ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                        <span class="text-sm font-medium text-gray-700">Show Booking Button in Navigation</span>
                                    </label>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 ml-7">
                                        <div>
                                            <label class="block text-sm text-gray-600 mb-1">Button Text</label>
                                            <input type="text" name="booking_button_text" value="{{ old('booking_button_text', $clinic->booking_button_text ?? 'Book Appointment') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-sm text-gray-600 mb-1">Button Style</label>
                                            <select name="booking_button_style" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                                <option value="primary" {{ ($clinic->booking_button_style ?? 'primary') === 'primary' ? 'selected' : '' }}>Primary (Solid)</option>
                                                <option value="secondary" {{ ($clinic->booking_button_style ?? 'primary') === 'secondary' ? 'selected' : '' }}>Secondary</option>
                                                <option value="outline" {{ ($clinic->booking_button_style ?? 'primary') === 'outline' ? 'selected' : '' }}>Outline</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-6 flex justify-end">
                            <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                                Save Navigation
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            <!-- SEO Settings -->
            @if($activeTab === 'seo')
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <form action="{{ route('clinic.system-settings.update-seo') }}" method="POST" enctype="multipart/form-data" class="p-6">
                        @csrf
                        @method('PUT')
                        
                        <h2 class="text-lg font-semibold text-gray-900 mb-6">SEO & Analytics Settings</h2>
                        
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-sm font-medium text-gray-700 mb-4">Meta Information</h3>
                                <div class="grid grid-cols-1 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Meta Title</label>
                                        <input type="text" name="meta_title" value="{{ old('meta_title', $landingContent->meta_title) }}" placeholder="{{ $clinic->name }} - Your tagline here" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                        <p class="mt-1 text-xs text-gray-500">Recommended: 50-60 characters</p>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Meta Description</label>
                                        <textarea name="meta_description" rows="3" placeholder="Describe your clinic..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">{{ old('meta_description', $landingContent->meta_description) }}</textarea>
                                        <p class="mt-1 text-xs text-gray-500">Recommended: 150-160 characters</p>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Meta Keywords</label>
                                        <input type="text" name="meta_keywords" value="{{ old('meta_keywords', $landingContent->meta_keywords) }}" placeholder="dental, clinic, healthcare, nepal" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                        <p class="mt-1 text-xs text-gray-500">Separate keywords with commas</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <h3 class="text-sm font-medium text-gray-700 mb-4">Analytics & Tracking</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Google Analytics ID</label>
                                        <input type="text" name="google_analytics_id" value="{{ old('google_analytics_id', $landingContent->google_analytics_id) }}" placeholder="G-XXXXXXXXXX" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Facebook Pixel ID</label>
                                        <input type="text" name="facebook_pixel_id" value="{{ old('facebook_pixel_id', $landingContent->facebook_pixel_id) }}" placeholder="1234567890" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <h3 class="text-sm font-medium text-gray-700 mb-4">Social Sharing Image (OG Image)</h3>
                                <div class="flex items-start space-x-4">
                                    <div class="flex-shrink-0">
                                        @if($landingContent->og_image)
                                            <img src="{{ $landingContent->getImageUrl('og_image') }}" alt="OG Image" class="w-32 h-32 object-cover border border-gray-200 rounded-lg">
                                        @else
                                            <div class="w-32 h-32 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center">
                                                <span class="text-gray-400 text-xs">No Image</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Upload OG Image</label>
                                        <input type="file" name="og_image" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                        <p class="mt-1 text-xs text-gray-500">Recommended: 1200x630px (1.91:1 ratio)</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-6 flex justify-end">
                            <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                                Save SEO Settings
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            <!-- Business Hours Settings -->
            @if($activeTab === 'business-hours')
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <form action="{{ route('clinic.system-settings.update-business-hours') }}" method="POST" class="p-6">
                        @csrf
                        @method('PUT')
                        
                        <h2 class="text-lg font-semibold text-gray-900 mb-6">Business Hours</h2>
                        
                        @php
                            $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                            $businessHours = is_array($landingContent->business_hours) ? $landingContent->business_hours : [];
                        @endphp
                        
                        <div class="space-y-4">
                            @foreach($days as $day)
                                @php
                                    $dayHours = collect($businessHours)->firstWhere('day', $day);
                                    $isClosed = $dayHours['is_closed'] ?? false;
                                    $openTime = $dayHours['open'] ?? '09:00';
                                    $closeTime = $dayHours['close'] ?? '17:00';
                                @endphp
                                <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-lg">
                                    <div class="w-28">
                                        <span class="text-sm font-medium text-gray-700 capitalize">{{ $day }}</span>
                                    </div>
                                    <div class="flex-1 flex items-center space-x-4">
                                        <label class="flex items-center">
                                            <input type="checkbox" name="business_hours[{{ $loop->index }}][is_closed]" value="1" {{ $isClosed ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 day-toggle" data-day="{{ $day }}">
                                            <span class="ml-2 text-sm text-gray-600">Closed</span>
                                        </label>
                                        <div class="flex items-center space-x-2 time-fields" data-day="{{ $day }}">
                                            <input type="time" name="business_hours[{{ $loop->index }}][open]" value="{{ $openTime }}" class="w-32 px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                            <span class="text-gray-400">to</span>
                                            <input type="time" name="business_hours[{{ $loop->index }}][close]" value="{{ $closeTime }}" class="w-32 px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                        </div>
                                        <input type="hidden" name="business_hours[{{ $loop->index }}][day]" value="{{ $day }}">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-6 flex justify-end">
                            <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                                Save Business Hours
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            <!-- Features & Modules -->
            @if($activeTab === 'features')
                <div class="space-y-6 stagger-in">
                    <form action="{{ route('clinic.system-settings.update-features') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <!-- Core Platform Modules -->
                        <div class="bg-white rounded-[2rem] shadow-sm border border-slate-200 overflow-hidden mb-6">
                            <div class="p-8">
                                <div class="flex items-center justify-between mb-8">
                                    <div>
                                        <h2 class="text-xl font-black text-slate-900 tracking-tight">Platform Core Engine</h2>
                                        <p class="text-xs text-slate-500 font-medium mt-1">Foundational services available for all clinic tiers.</p>
                                    </div>
                                    <span class="px-3 py-1 bg-slate-100 text-slate-500 text-[10px] font-black uppercase tracking-widest rounded-full">Core Toggles</span>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                    {{-- Landing Page --}}
                                    <label class="group flex items-center justify-between p-5 border-2 border-slate-100 rounded-2xl hover:border-indigo-200 hover:bg-indigo-50/30 transition cursor-pointer">
                                        <div class="flex items-center space-x-4">
                                            <div class="w-10 h-10 bg-indigo-100 rounded-xl text-indigo-600 flex items-center justify-center transition group-hover:scale-110">
                                                <i class="fas fa-desktop text-sm"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-black text-slate-900">Web Portal</p>
                                                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-tighter">Public Website</p>
                                            </div>
                                        </div>
                                        <div class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" name="has_landing_page" value="1" {{ $clinic->has_landing_page ? 'checked' : '' }} class="sr-only peer">
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                                        </div>
                                    </label>

                                    {{-- CRM --}}
                                    <label class="group flex items-center justify-between p-5 border-2 border-slate-100 rounded-2xl hover:border-emerald-200 hover:bg-emerald-50/30 transition cursor-pointer">
                                        <div class="flex items-center space-x-4">
                                            <div class="w-10 h-10 bg-emerald-100 rounded-xl text-emerald-600 flex items-center justify-center transition group-hover:scale-110">
                                                <i class="fas fa-user-tag text-sm"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-black text-slate-900">CRM Engine</p>
                                                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-tighter">Lead Management</p>
                                            </div>
                                        </div>
                                        <div class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" name="has_crm" value="1" {{ $clinic->has_crm ? 'checked' : '' }} class="sr-only peer">
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                                        </div>
                                    </label>

                                    {{-- Patient Portal --}}
                                    <label class="group flex items-center justify-between p-5 border-2 border-slate-100 rounded-2xl hover:border-purple-200 hover:bg-purple-50/30 transition cursor-pointer">
                                        <div class="flex items-center space-x-4">
                                            <div class="w-10 h-10 bg-purple-100 rounded-xl text-purple-600 flex items-center justify-center transition group-hover:scale-110">
                                                <i class="fas fa-user-circle text-sm"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-black text-slate-900">Patient Hub</p>
                                                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-tighter">Self-Service</p>
                                            </div>
                                        </div>
                                        <div class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" name="has_patient_portal" value="1" {{ $clinic->has_patient_portal ? 'checked' : '' }} class="sr-only peer">
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                                        </div>
                                    </label>

                                    {{-- Emails --}}
                                    <label class="group flex items-center justify-between p-5 border-2 border-slate-100 rounded-2xl hover:border-amber-200 hover:bg-amber-50/30 transition cursor-pointer">
                                        <div class="flex items-center space-x-4">
                                            <div class="w-10 h-10 bg-amber-100 rounded-xl text-amber-600 flex items-center justify-center transition group-hover:scale-110">
                                                <i class="fas fa-envelope text-sm"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-black text-slate-900">Smart Emails</p>
                                                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-tighter">Auto-Communication</p>
                                            </div>
                                        </div>
                                        <div class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" name="has_email_system" value="1" {{ $clinic->has_email_system ? 'checked' : '' }} class="sr-only peer">
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-amber-600"></div>
                                        </div>
                                    </label>

                                    {{-- Notifications --}}
                                    <label class="group flex items-center justify-between p-5 border-2 border-slate-100 rounded-2xl hover:border-blue-200 hover:bg-blue-50/30 transition cursor-pointer">
                                        <div class="flex items-center space-x-4">
                                            <div class="w-10 h-10 bg-blue-100 rounded-xl text-blue-600 flex items-center justify-center transition group-hover:scale-110">
                                                <i class="fas fa-bell text-sm"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-black text-slate-900">Push Notifications</p>
                                                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-tighter">Real-time alerts</p>
                                            </div>
                                        </div>
                                        <div class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" name="has_notifications" value="1" {{ $clinic->has_notifications ? 'checked' : '' }} class="sr-only peer">
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                        </div>
                                    </label>

                                    {{-- Analytics --}}
                                    <label class="group flex items-center justify-between p-5 border-2 border-slate-100 rounded-2xl hover:border-rose-200 hover:bg-rose-50/30 transition cursor-pointer">
                                        <div class="flex items-center space-x-4">
                                            <div class="w-10 h-10 bg-rose-100 rounded-xl text-rose-600 flex items-center justify-center transition group-hover:scale-110">
                                                <i class="fas fa-chart-line text-sm"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-black text-slate-900">Advanced Analytics</p>
                                                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-tighter">Growth Insights</p>
                                            </div>
                                        </div>
                                        <div class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" name="has_analytics" value="1" {{ $clinic->has_analytics ? 'checked' : '' }} class="sr-only peer">
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-rose-600"></div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Module Lifecycle Hub -->
                        <div class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 rounded-[2.5rem] p-10 border border-white/5 shadow-2xl relative overflow-hidden group">
                            <div class="absolute top-0 right-0 p-12 opacity-5 group-hover:opacity-10 transition-all transform group-hover:scale-110 duration-700">
                                <i class="fas fa-cubes text-[15rem] text-white"></i>
                            </div>
                            <div class="relative z-10">
                                <div class="flex items-center space-x-3 mb-2">
                                    <div class="p-2 bg-indigo-500/20 rounded-lg text-indigo-400">
                                        <i class="fas fa-bolt text-xs"></i>
                                    </div>
                                    <h2 class="text-3xl font-black text-white tracking-tight">Industrial Extension Hub</h2>
                                </div>
                                <p class="text-slate-400 text-sm mb-12 font-medium max-w-2xl leading-relaxed">Scale your operation with high-performance modules designed for the modern dental chain.</p>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    @php
                                        $modules = [
                                            ['key' => 'has_inventory', 'label' => 'Inventory Matrix', 'desc' => 'Precision stock tracking & supplier logs.', 'icon' => 'fa-boxes-stacked', 'color' => 'blue'],
                                            ['key' => 'has_accounting', 'label' => 'Advanced Finance', 'desc' => 'Dual-entry ledger system & VAT logs.', 'icon' => 'fa-file-invoice-dollar', 'color' => 'emerald'],
                                            ['key' => 'has_lab_integration', 'label' => 'Lab Nexus', 'desc' => 'Direct laboratory integration & case tracking.', 'icon' => 'fa-microscope', 'color' => 'purple'],
                                            ['key' => 'has_telemedicine', 'label' => 'Tele-Healthcare', 'desc' => 'HD video consults with real-time charting.', 'icon' => 'fa-video', 'color' => 'amber'],
                                        ];
                                    @endphp
                                    @foreach($modules as $mod)
                                        <div class="bg-white/5 border border-white/10 p-6 rounded-3xl flex items-center justify-between hover:bg-white/[0.08] transition-all duration-300 group/mod hover:border-white/20">
                                            <div class="flex items-center space-x-5">
                                                <div class="w-14 h-14 rounded-2xl bg-{{ $mod['color'] }}-500/10 text-{{ $mod['color'] }}-400 flex items-center justify-center text-2xl shadow-2xl ring-1 ring-white/10 transition-transform group-hover/mod:scale-110">
                                                    <i class="fas {{ $mod['icon'] }}"></i>
                                                </div>
                                                <div>
                                                    <p class="text-white font-black text-base tracking-wide">{{ $mod['label'] }}</p>
                                                    <p class="text-slate-500 text-[10px] font-bold mt-1">{{ $mod['desc'] }}</p>
                                                </div>
                                            </div>
                                            <div class="flex flex-col items-end gap-3">
                                                <div class="relative inline-flex items-center cursor-pointer">
                                                    <input type="checkbox" name="{{ $mod['key'] }}" value="1" {{ $clinic->{$mod['key']} ? 'checked' : '' }} class="sr-only peer">
                                                    <div class="w-11 h-6 bg-white/10 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white/50 after:border-white/10 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-{{ $mod['color'] }}-500"></div>
                                                </div>
                                                @if($clinic->{$mod['key']})
                                                    <span class="text-[9px] font-black text-emerald-400 uppercase tracking-widest">Active</span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                
                                <div class="mt-12 pt-8 border-t border-white/5 flex flex-col sm:flex-row items-center justify-between gap-6">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></div>
                                        <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest">Global Lifecycle Sync: Active</p>
                                    </div>
                                    <button type="submit" class="px-10 py-4 bg-white text-slate-900 text-xs font-black uppercase tracking-widest rounded-2xl hover:bg-indigo-50 transition shadow-2xl active:scale-95">
                                        Save All Configurations
                                    </button>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="clinic_id" value="{{ $clinic->id }}">
                    </form>
                </div>
            @endif
        </div>
    </div>
    
    <script>
        // Toggle business hours time fields based on closed checkbox
        document.addEventListener('DOMContentLoaded', function() {
            const toggles = document.querySelectorAll('.day-toggle');
            toggles.forEach(toggle => {
                toggle.addEventListener('change', function() {
                    const day = this.dataset.day;
                    const timeFields = document.querySelector(`.time-fields[data-day="${day}"]`);
                    if (timeFields) {
                        timeFields.style.opacity = this.checked ? '0.5' : '1';
                        timeFields.querySelectorAll('input').forEach(input => {
                            input.disabled = this.checked;
                        });
                    }
                });
                
                // Initialize
                if (toggle.checked) {
                    const day = toggle.dataset.day;
                    const timeFields = document.querySelector(`.time-fields[data-day="${day}"]`);
                    if (timeFields) {
                        timeFields.style.opacity = '0.5';
                        timeFields.querySelectorAll('input').forEach(input => {
                            input.disabled = true;
                        });
                    }
                }
            });
        });

        function addService() {
            const list = document.getElementById('services-list');
            const index = list.children.length;
            const div = document.createElement('div');
            div.className = 'flex items-start space-x-3 p-3 bg-gray-50 rounded-lg border border-gray-200 group animate-fade-in-down';
            div.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-4 gap-3 flex-1">
                    <input type="text" name="services[${index}][name]" placeholder="Service Name" class="px-3 py-2 text-sm border border-gray-300 rounded-lg">
                    <input type="text" name="services[${index}][description]" placeholder="Description" class="px-3 py-2 text-sm border border-gray-300 rounded-lg col-span-2">
                    <input type="text" name="services[${index}][icon]" placeholder="Icon (e.g. 🦷)" class="px-3 py-2 text-sm border border-gray-300 rounded-lg">
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-gray-400 hover:text-red-500 transition-colors p-2">
                    <i class="fas fa-times"></i>
                </button>
            `;
            list.appendChild(div);
        }

        function addTestimonial() {
            const list = document.getElementById('testimonials-list');
            const index = list.children.length;
            const div = document.createElement('div');
            div.className = 'flex items-start space-x-3 p-3 bg-gray-50 rounded-lg border border-gray-200 group animate-fade-in-down';
            div.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 flex-1">
                    <input type="text" name="testimonials[${index}][name]" placeholder="Patient Name" class="px-3 py-2 text-sm border border-gray-300 rounded-lg">
                    <input type="text" name="testimonials[${index}][designation]" placeholder="Position/Tagline" class="px-3 py-2 text-sm border border-gray-300 rounded-lg">
                    <textarea name="testimonials[${index}][review]" rows="2" placeholder="Testimonial content" class="px-3 py-2 text-sm border border-gray-300 rounded-lg col-span-2"></textarea>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-gray-400 hover:text-red-500 transition-colors p-2">
                    <i class="fas fa-times"></i>
                </button>
            `;
            list.appendChild(div);
        }

        function addFaq() {
            const list = document.getElementById('faq-list');
            const index = list.children.length;
            const div = document.createElement('div');
            div.className = 'flex items-start space-x-3 p-3 bg-gray-50 rounded-lg border border-gray-200 group animate-fade-in-down';
            div.innerHTML = `
                <div class="grid grid-cols-1 gap-3 flex-1">
                    <input type="text" name="faq[${index}][question]" placeholder="Question" class="px-3 py-2 text-sm border border-gray-300 rounded-lg w-full">
                    <textarea name="faq[${index}][answer]" rows="2" placeholder="Answer" class="px-3 py-2 text-sm border border-gray-300 rounded-lg w-full"></textarea>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-gray-400 hover:text-red-500 transition-colors p-2">
                    <i class="fas fa-times"></i>
                </button>
            `;
            list.appendChild(div);
        }
    </script>
    <form id="delete-image-form" action="{{ route('clinic.system-settings.delete-landing-image') }}" method="POST" class="hidden">
        @csrf
        @method('DELETE')
        <input type="hidden" name="type" id="delete-image-type">
        <input type="hidden" name="path" id="delete-image-path">
    </form>
    
    <script>
        function deleteLandingImage(type, path) {
            if (confirm('Are you sure you want to remove this image? This action cannot be undone.')) {
                document.getElementById('delete-image-type').value = type;
                document.getElementById('delete-image-path').value = path;
                document.getElementById('delete-image-form').submit();
            }
        }

        function updateFileCount(input, feedbackId) {
            const feedback = document.getElementById(feedbackId);
            const count = input.files.length;
            if (feedback) {
                if (count > 0) {
                    let fileNames = Array.from(input.files).map(f => f.name).join(', ');
                    if (fileNames.length > 50) fileNames = fileNames.substring(0, 47) + '...';
                    feedback.innerHTML = `
                        <div class="flex items-center">
                            <span class="mr-2">🚀</span>
                            <span><strong>${count} images ready!</strong> (${fileNames})</span>
                        </div>
                    `;
                    feedback.classList.remove('hidden');
                } else {
                    feedback.classList.add('hidden');
                }
            }
        }
    </script>
@endsection
