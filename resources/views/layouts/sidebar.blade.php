<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>
    
    <!-- Nepali Date Picker CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nepali-date-picker@2.0.5/dist/nepaliDatePicker.min.css">
    <script src="https://cdn.jsdelivr.net/npm/nepali-date-picker@2.0.5/dist/nepaliDatePicker.min.js"></script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .nav-link {
            @apply flex items-center px-4 py-3 text-sm font-medium rounded-xl text-slate-300 hover:text-white hover:bg-slate-700/50 transition-all duration-200 group;
        }
        .nav-link.active {
            @apply bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg;
        }
        .nav-link:hover svg {
            @apply text-white;
        }
        .nav-link.active svg {
            @apply text-white;
        }
    </style>
</head>
<body class="bg-gray-50 antialiased">
    <div class="flex h-screen bg-gray-50">
        <!-- Mobile menu overlay -->
        <div id="mobile-menu-overlay" class="fixed inset-0 z-40 bg-gray-600 bg-opacity-75 hidden md:hidden"></div>
        
        <!-- Sidebar -->
        <div id="sidebar" class="fixed inset-y-0 left-0 z-50 w-72 transform -translate-x-full transition-transform duration-300 ease-in-out md:translate-x-0 md:relative md:flex md:w-72 md:flex-col md:flex-shrink-0">
            <div class="flex flex-col h-full bg-gradient-to-b from-slate-900 via-slate-800 to-slate-900 text-gray-100 border-r border-slate-700/50 overflow-y-auto">
                <!-- Logo Section -->
                <div class="flex items-center flex-shrink-0 px-6 py-6 border-b border-slate-700/50">
                    @if(auth()->user()->hasRole('superadmin'))
                        <a href="{{ route('dashboard') }}" class="flex items-center">
                            <img class="h-10 w-auto" src="/logo.png" alt="{{ config('app.name') }}">
                            <div class="ml-4">
                                <h1 class="text-xl font-bold text-white tracking-tight">{{ config('app.name') }}</h1>
                                <p class="text-xs text-slate-400 font-medium">Professional Care</p>
                            </div>
                        </a>
                    @else
                        @php
                            $clinic = auth()->user()->clinic;
                        @endphp
                        <a href="/clinic/{{ $clinic->slug ?? 'default' }}" target="_blank" class="flex items-center hover:opacity-80 transition-opacity">
                            @if($clinic && $clinic->logo)
                                <img class="h-10 w-10 rounded-full object-cover" src="{{ Storage::url($clinic->logo) }}" alt="{{ $clinic->name }}">
                            @else
                                <div class="h-10 w-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                                    <span class="text-white font-bold text-lg">{{ $clinic ? substr($clinic->name, 0, 1) : 'C' }}</span>
                                </div>
                            @endif
                            <div class="ml-4">
                                <h1 class="text-xl font-bold text-white tracking-tight">{{ $clinic->name ?? config('app.name') }}</h1>
                                <p class="text-xs text-slate-400 font-medium">{{ $clinic->tagline ?? 'Professional Care' }}</p>
                            </div>
                        </a>
                    @endif
                </div>
                
                <!-- Navigation -->
                <nav class="mt-8 flex-1 px-4 space-y-2">
                    @if(auth()->user()->hasRole('superadmin'))
                        <!-- Superadmin Menu -->
                        <div class="space-y-2">
                            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                                <svg class="mr-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                </svg>
                                <span class="truncate font-semibold">Dashboard</span>
                            </a>
                        </div>

                        <div class="pt-4">
                            <h3 class="px-4 text-xs font-bold text-slate-400 uppercase tracking-widest">System Management</h3>
                            <div class="mt-3 space-y-2">
                                <a href="{{ route('admin.roles.index') }}" class="nav-link">
                                    <svg class="mr-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    <span class="truncate">Role Management</span>
                                </a>
                                <a href="{{ route('clinics.index') }}" class="nav-link {{ request()->routeIs('clinics.*') ? 'active' : '' }}">
                                    <svg class="mr-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                    <span class="truncate">Clinics</span>
                                </a>
                                <a href="{{ route('superadmin.users') }}" class="nav-link {{ request()->routeIs('superadmin.users') ? 'active' : '' }}">
                                    <svg class="mr-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                    </svg>
                                    <span class="truncate">Users</span>
                                </a>
                                <a href="{{ route('superadmin.analytics') }}" class="nav-link {{ request()->routeIs('superadmin.analytics') ? 'active' : '' }}">
                                    <svg class="mr-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                    <span class="truncate">Analytics</span>
                                </a>
                                <a href="{{ route('medications.index') }}" class="nav-link {{ request()->routeIs('medications.*') ? 'active' : '' }}">
                                    <svg class="mr-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                    <span class="truncate">Medications Database</span>
                                </a>
                            </div>
                        </div>

                        <div class="pt-4">
                            <h3 class="px-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Content</h3>
                            <div class="mt-3 space-y-2">
                                <a href="{{ route('superadmin.content.landing') }}" class="nav-link {{ request()->routeIs('superadmin.content.landing*') ? 'active' : '' }}">
                                    <svg class="mr-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                    </svg>
                                    <span class="truncate">Landing Page</span>
                                </a>
                                <a href="{{ route('superadmin.content.blog') }}" class="nav-link {{ request()->routeIs('superadmin.content.blog*') ? 'active' : '' }}">
                                    <svg class="mr-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                                    </svg>
                                    <span class="truncate">Blog Posts</span>
                                </a>
                                <a href="{{ route('superadmin.content.testimonials') }}" class="nav-link {{ request()->routeIs('superadmin.content.testimonials*') ? 'active' : '' }}">
                                    <svg class="mr-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                    </svg>
                                    <span class="truncate">Testimonials</span>
                                </a>
                            </div>
                        </div>

                        <div class="pt-4">
                            <h3 class="px-4 text-xs font-bold text-slate-400 uppercase tracking-widest">CRM</h3>
                            <div class="mt-3 space-y-2">
                                <a href="{{ route('superadmin.crm.leads') }}" class="nav-link {{ request()->routeIs('superadmin.crm.leads*') ? 'active' : '' }}">
                                    <svg class="mr-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    <span class="truncate">Leads</span>
                                </a>
                                <a href="{{ route('superadmin.crm.campaigns') }}" class="nav-link {{ request()->routeIs('superadmin.crm.campaigns*') ? 'active' : '' }}">
                                    <svg class="mr-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                    </svg>
                                    <span class="truncate">Campaigns</span>
                                </a>
                                <a href="{{ route('superadmin.crm.email-logs') }}" class="nav-link {{ request()->routeIs('superadmin.crm.email-logs*') ? 'active' : '' }}">
                                    <svg class="mr-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="truncate">Email Logs</span>
                                </a>
                            </div>
                        </div>

                        <div class="pt-4">
                            <h3 class="px-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Communication</h3>
                            <div class="mt-3 space-y-2">
                                <a href="{{ route('emails.index') }}" class="nav-link {{ request()->routeIs('emails.*') ? 'active' : '' }}">
                                    <svg class="mr-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="truncate">Emails</span>
                                </a>
                                <a href="{{ route('global.notifications.index') }}" class="nav-link {{ request()->routeIs('global.notifications.*') ? 'active' : '' }}">
                                    <svg class="mr-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.343 12.344l1.414 1.414L9 10.414V3a1 1 0 011-1h4a1 1 0 011 1v7.414l3.243 3.243 1.414-1.414L15 8.586V3a3 3 0 00-3-3H8a3 3 0 00-3 3v5.586l-4.657 4.657z"></path>
                                    </svg>
                                    <span class="truncate">Notifications</span>
                                </a>
                            </div>
                        </div>
                    @elseif(auth()->user()->hasRole('dentist'))
                        <!-- Dentist Menu -->
                        <div class="space-y-2">
                            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                                <svg class="mr-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                </svg>
                                <span class="truncate font-semibold">Dashboard</span>
                            </a>
                        </div>

                        <div class="pt-4">
                            <h3 class="px-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Patient Care</h3>
                            <div class="mt-3 space-y-2">
                                <a href="{{ route('patients.index') }}" class="nav-link {{ request()->routeIs('patients.*') ? 'active' : '' }}">
                                    <svg class="mr-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <span class="truncate">My Patients</span>
                                </a>
                                <a href="{{ route('clinic.appointments.index') }}" class="nav-link {{ request()->routeIs('clinic.appointments.*') ? 'active' : '' }}">
                                    <svg class="mr-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="truncate">My Schedule</span>
                                </a>
                                <a href="{{ route('treatment-plans.index') }}" class="nav-link {{ request()->routeIs('treatment-plans.*') ? 'active' : '' }}">
                                    <svg class="mr-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                    <span class="truncate">Treatment Plans</span>
                                </a>
                                <a href="{{ route('prescriptions.index') }}" class="nav-link {{ request()->routeIs('prescriptions.*') ? 'active' : '' }}">
                                    <svg class="mr-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <span class="truncate">Prescriptions</span>
                                </a>
                            </div>
                        </div>

                        <div class="pt-4">
                            <h3 class="px-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Communication</h3>
                            <div class="mt-3 space-y-2">
                                <a href="{{ route('emails.index') }}" class="nav-link {{ request()->routeIs('emails.*') ? 'active' : '' }}">
                                    <svg class="mr-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="truncate">Emails</span>
                                </a>
                                <a href="{{ route('global.notifications.index') }}" class="nav-link {{ request()->routeIs('global.notifications.*') ? 'active' : '' }}">
                                    <svg class="mr-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.343 12.344l1.414 1.414L9 10.414V3a1 1 0 011-1h4a1 1 0 011 1v7.414l3.243 3.243 1.414-1.414L15 8.586V3a3 3 0 00-3-3H8a3 3 0 00-3 3v5.586l-4.657 4.657z"></path>
                                    </svg>
                                    <span class="truncate">Notifications</span>
                                </a>
                            </div>
                        </div>

                        <div class="pt-4">
                            <h3 class="px-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Settings</h3>
                            <div class="mt-3 space-y-2">
                                <a href="{{ route('settings.index') }}" class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                                    <svg class="mr-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span class="truncate">Settings</span>
                                </a>
                            </div>
                        </div>

                    @elseif(auth()->user()->hasRole('receptionist'))
                        <!-- Receptionist Menu -->
                        <div class="space-y-2">
                            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                                <svg class="mr-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                </svg>
                                <span class="truncate font-semibold">Dashboard</span>
                            </a>
                        </div>

                        <div class="pt-4">
                            <h3 class="px-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Patient Management</h3>
                            <div class="mt-3 space-y-2">
                                <a href="{{ route('patients.index') }}" class="nav-link {{ request()->routeIs('patients.*') ? 'active' : '' }}">
                                    <svg class="mr-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <span class="truncate">Patients</span>
                                </a>
                                <a href="{{ route('clinic.appointments.index') }}" class="nav-link {{ request()->routeIs('clinic.appointments.*') ? 'active' : '' }}">
                                    <svg class="mr-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="truncate">Appointments</span>
                                </a>
                                <a href="{{ route('waitlist.index') }}" class="nav-link {{ request()->routeIs('waitlist.*') ? 'active' : '' }}">
                                    <svg class="mr-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="truncate">Waitlist</span>
                                </a>
                            </div>
                        </div>

                        <div class="pt-4">
                            <h3 class="px-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Communication</h3>
                            <div class="mt-3 space-y-2">
                                <a href="{{ route('emails.index') }}" class="nav-link {{ request()->routeIs('emails.*') ? 'active' : '' }}">
                                    <svg class="mr-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="truncate">Emails</span>
                                </a>
                                <a href="{{ route('global.notifications.index') }}" class="nav-link {{ request()->routeIs('global.notifications.*') ? 'active' : '' }}">
                                    <svg class="mr-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.343 12.344l1.414 1.414L9 10.414V3a1 1 0 011-1h4a1 1 0 011 1v7.414l3.243 3.243 1.414-1.414L15 8.586V3a3 3 0 00-3-3H8a3 3 0 00-3 3v5.586l-4.657 4.657z"></path>
                                    </svg>
                                    <span class="truncate">Notifications</span>
                                </a>
                            </div>
                        </div>

                        <div class="pt-4">
                            <h3 class="px-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Settings</h3>
                            <div class="mt-3 space-y-2">
                                <a href="{{ route('settings.index') }}" class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                                    <svg class="mr-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span class="truncate">Settings</span>
                                </a>
                            </div>
                        </div>

                    @elseif(auth()->user()->hasRole('accountant'))
                        <!-- Accountant Menu -->
                        <div class="space-y-2">
                            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                                <svg class="mr-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                </svg>
                                <span class="truncate font-semibold">Dashboard</span>
                            </a>
                        </div>

                        <div class="pt-4">
                            <h3 class="px-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Financial Management</h3>
                            <div class="mt-3 space-y-2">
                                <a href="{{ route('invoices.index') }}" class="nav-link {{ request()->routeIs('invoices.*') ? 'active' : '' }}">
                                    <svg class="mr-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="truncate">Invoices</span>
                                </a>
                                <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                                    <svg class="mr-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                    <span class="truncate">Financial Reports</span>
                                </a>
                                <a href="{{ route('analytics.dashboard') }}" class="nav-link {{ request()->routeIs('analytics.*') ? 'active' : '' }}">
                                    <svg class="mr-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="truncate">Analytics</span>
                                </a>
                            </div>
                        </div>

                        <div class="pt-4">
                            <h3 class="px-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Accounting</h3>
                            <div class="mt-3 space-y-2">
                                <a href="{{ route('journal-entries') }}" class="nav-link {{ request()->routeIs('journal-entries*') ? 'active' : '' }}">
                                    <svg class="mr-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                    <span class="truncate">Journal Entries</span>
                                </a>
                                <a href="{{ route('ledger') }}" class="nav-link {{ request()->routeIs('ledger') ? 'active' : '' }}">
                                    <svg class="mr-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                    </svg>
                                    <span class="truncate">General Ledger</span>
                                </a>
                                <a href="{{ route('chart-of-accounts') }}" class="nav-link {{ request()->routeIs('chart-of-accounts') ? 'active' : '' }}">
                                    <svg class="mr-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v14l-5-3-5 3V5z"></path>
                                    </svg>
                                    <span class="truncate">Chart of Accounts</span>
                                </a>
                            </div>
                        </div>

                        <div class="pt-4">
                            <h3 class="px-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Communication</h3>
                            <div class="mt-3 space-y-2">
                                <a href="{{ route('emails.index') }}" class="nav-link {{ request()->routeIs('emails.*') ? 'active' : '' }}">
                                    <svg class="mr-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="truncate">Emails</span>
                                </a>
                                <a href="{{ route('global.notifications.index') }}" class="nav-link {{ request()->routeIs('global.notifications.*') ? 'active' : '' }}">
                                    <svg class="mr-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.343 12.344l1.414 1.414L9 10.414V3a1 1 0 011-1h4a1 1 0 011 1v7.414l3.243 3.243 1.414-1.414L15 8.586V3a3 3 0 00-3-3H8a3 3 0 00-3 3v5.586l-4.657 4.657z"></path>
                                    </svg>
                                    <span class="truncate">Notifications</span>
                                </a>
                            </div>
                        </div>

                        <div class="pt-4">
                            <h3 class="px-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Settings</h3>
                            <div class="mt-3 space-y-2">
                                <a href="{{ route('settings.index') }}" class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                                    <svg class="mr-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span class="truncate">Settings</span>
                                </a>
                            </div>
                        </div>

                    @elseif(auth()->user()->hasRole('clinic_admin'))
                        <!-- Clinic Admin Menu -->
                        <div class="space-y-2">
                            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                                <svg class="mr-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                </svg>
                                <span class="truncate font-semibold">Dashboard</span>
                            </a>
                        </div>

                        @if(auth()->user()->clinic->hasModule('crm'))
                        <div class="pt-4">
                            <h3 class="px-4 text-xs font-bold text-slate-400 uppercase tracking-widest">CRM & Marketing</h3>
                            <div class="mt-3 space-y-2">
                                <a href="{{ route('clinic.crm.leads.index') }}" class="nav-link {{ request()->routeIs('clinic.crm.leads.*') ? 'active' : '' }}">
                                    <svg class="mr-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    <span class="truncate">Leads</span>
                                </a>
                                <a href="{{ route('landing-page-manager') }}" class="nav-link {{ request()->routeIs('landing-page-manager*') ? 'active' : '' }}">
                                    <svg class="mr-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                    </svg>
                                    <span class="truncate">Landing Page CMS</span>
                                </a>
                                @if(auth()->user()->clinic->hasFeature('has_email_system'))
                                <a href="{{ route('emails.index') }}" class="nav-link {{ request()->routeIs('emails.*') ? 'active' : '' }}">
                                    <svg class="mr-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="truncate">Email Marketing</span>
                                </a>
                                @endif
                            </div>
                        </div>
                        @endif

                        @if(auth()->user()->clinic->hasModule('patients'))
                        <div class="pt-4">
                            <h3 class="px-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Patient Management</h3>
                            <div class="mt-3 space-y-2">
                                <a href="{{ route('patients.index') }}" class="nav-link {{ request()->routeIs('patients.*') ? 'active' : '' }}">
                                    <svg class="mr-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <span class="truncate">Patients</span>
                                </a>
                                @if(auth()->user()->clinic->hasModule('appointments'))
                                <a href="{{ route('clinic.appointments.index') }}" class="nav-link {{ request()->routeIs('clinic.appointments.*') ? 'active' : '' }}">
                                    <svg class="mr-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="truncate">Appointments</span>
                                </a>
                                @endif
                                <a href="{{ route('treatment-plans.index') }}" class="nav-link {{ request()->routeIs('treatment-plans.*') ? 'active' : '' }}">
                                    <svg class="mr-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                    <span class="truncate">Treatment Plans</span>
                                </a>
                                <a href="{{ route('waitlist.index') }}" class="nav-link {{ request()->routeIs('waitlist.*') ? 'active' : '' }}">
                                    <svg class="mr-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="truncate">Waitlist</span>
                                </a>
                            </div>
                        </div>
                        @endif

                        <div class="pt-4">
                            <h3 class="px-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Website CMS</h3>
                            <div class="mt-3 space-y-2">
                                <a href="{{ route('admin.cms.services.index') }}" class="nav-link {{ request()->routeIs('admin.cms.services.*') ? 'active' : '' }}">
                                    <svg class="mr-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                    <span class="truncate">Services</span>
                                </a>
                                <a href="{{ route('admin.cms.team.index') }}" class="nav-link {{ request()->routeIs('admin.cms.team.*') ? 'active' : '' }}">
                                    <svg class="mr-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    <span class="truncate">Team</span>
                                </a>
                            </div>
                        </div>

                        @if(auth()->user()->clinic->hasModule('invoicing'))
                        <div class="pt-4">
                            <h3 class="px-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Financial Management</h3>
                            <div class="mt-3 space-y-2">
                                <a href="{{ route('invoices.index') }}" class="nav-link {{ request()->routeIs('invoices.*') ? 'active' : '' }}">
                                    <svg class="mr-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="truncate">Invoices & Billing</span>
                                </a>
                                @if(auth()->user()->clinic->hasModule('reports'))
                                <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                                    <svg class="mr-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                    <span class="truncate">Financial Reports</span>
                                </a>
                                @endif
                                @if(auth()->user()->clinic->hasFeature('has_analytics'))
                                <a href="{{ route('analytics.dashboard') }}" class="nav-link {{ request()->routeIs('analytics.*') ? 'active' : '' }}">
                                    <svg class="mr-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="truncate">Analytics</span>
                                </a>
                                @endif
                            </div>
                        </div>
                        @endif

                        <div class="pt-4">
                            <h3 class="px-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Operations</h3>
                            <div class="mt-3 space-y-2">
                                <a href="{{ route('staff.index') }}" class="nav-link {{ request()->routeIs('staff.*') ? 'active' : '' }}">
                                    <svg class="mr-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    <span class="truncate">Staff Management</span>
                                </a>
                                @if(auth()->user()->clinic->hasModule('appointments'))
                                <a href="{{ route('recurring-appointments.index') }}" class="nav-link {{ request()->routeIs('recurring-appointments.*') ? 'active' : '' }}">
                                    <svg class="mr-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                    <span class="truncate">Recurring Appointments</span>
                                </a>
                                @endif
                                @if(auth()->user()->clinic->hasFeature('has_notifications'))
                                <a href="{{ route('global.notifications.index') }}" class="nav-link {{ request()->routeIs('global.notifications.*') ? 'active' : '' }}">
                                    <svg class="mr-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.343 12.344l1.414 1.414L9 10.414V3a1 1 0 011-1h4a1 1 0 011 1v7.414l3.243 3.243 1.414-1.414L15 8.586V3a3 3 0 00-3-3H8a3 3 0 00-3 3v5.586l-4.657 4.657z"></path>
                                    </svg>
                                    <span class="truncate">Notifications</span>
                                </a>
                                @endif
                            </div>
                        </div>

                        <div class="pt-4">
                            <h3 class="px-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Settings & Subscription</h3>
                            <div class="mt-3 space-y-2">
                                <a href="{{ route('settings.index') }}" class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                                    <svg class="mr-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span class="truncate">Clinic Settings</span>
                                </a>
                                <a href="{{ route('subscriptions.current') }}" class="nav-link {{ request()->routeIs('subscriptions.*') ? 'active' : '' }}">
                                    <svg class="mr-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                    </svg>
                                    <span class="truncate">Subscription & Billing</span>
                                </a>
                            </div>
                        </div>
                    @endif
                </nav>

                <!-- User Profile & Date -->
                <div class="flex-shrink-0 border-t border-slate-700/50 p-4 mt-auto">
                    <div class="flex-shrink-0 w-full">
                        <div class="flex items-center">
                            <div class="inline-flex h-11 w-11 rounded-full bg-gradient-to-br from-cyan-400 to-blue-600 items-center justify-center shadow-lg">
                                <span class="text-sm font-bold text-white">{{ substr(auth()->user()->name, 0, 1) }}</span>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-semibold text-white">{{ auth()->user()->name }}</p>
                                <p class="text-xs font-medium text-slate-400">{{ ucfirst(auth()->user()->getRoleNames()->first()) }}</p>
                            </div>
                        </div>
                        <div class="mt-4 bg-gradient-to-br from-slate-700 to-slate-800 rounded-xl p-3.5 text-center border border-slate-600/50 shadow-lg">
                            <div class="flex items-center justify-center space-x-2 mb-2">
                                <div class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-pulse shadow-lg shadow-emerald-400/50"></div>
                                <p class="text-xs font-bold text-slate-300">आज</p>
                            </div>
                            @php
                                $nepaliDate = \App\Helpers\NepaliDateHelper::getCurrentNepaliDate();
                            @endphp
                            <p class="text-sm font-bold text-white">{{ $nepaliDate['formatted'] ?? '१ मंसिर २०८२' }}</p>
                            <p class="text-xs text-slate-400 font-medium">{{ $nepaliDate['day_of_week'] ? \App\Services\NepaliCalendarService::getDayName($nepaliDate['day_of_week']) : 'सोमबार' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex flex-col flex-1 min-w-0 overflow-hidden">
            <!-- Top Header -->
            <div class="sticky top-0 z-10 flex items-center justify-between px-6 py-4 bg-white border-b border-gray-200 shadow-sm">
                <div class="flex items-center">
                    <!-- Mobile menu button -->
                    <button id="mobile-menu-button" class="md:hidden -ml-0.5 -mt-0.5 h-12 w-12 inline-flex items-center justify-center rounded-md text-gray-500 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">
                        <span class="sr-only">Open sidebar</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <h1 class="ml-2 md:ml-0 text-2xl font-bold text-gray-900">@yield('page-title', 'Dashboard')</h1>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-4 py-2.5 border border-gray-200 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 hover:border-gray-300 focus:outline-none transition-all duration-150 shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        Sign out
                    </button>
                </form>
            </div>
            
            <main class="flex-1 p-6 overflow-y-auto">
                @if(session('success'))
                    <div class="mb-6 rounded-md bg-green-50 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" fill="none" viewBox="0 0 20 20" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="mb-6 rounded-md bg-red-50 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" fill="none" viewBox="0 0 20 20" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                            </div>
                        </div>
                    </div>
                @endif
                
                @yield('content')
            </main>
        </div>
    </div>
    <script>
        // Mobile menu functionality
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('mobile-menu-overlay');
        
        function openMobileMenu() {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }
        
        function closeMobileMenu() {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
        
        mobileMenuButton.addEventListener('click', openMobileMenu);
        overlay.addEventListener('click', closeMobileMenu);
        
        // Close menu on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeMobileMenu();
            }
        });
        
        // Close menu when clicking on nav links (mobile only)
        const navLinks = sidebar.querySelectorAll('a');
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth < 768) {
                    closeMobileMenu();
                }
            });
        });
    </script>
</body>
</html>