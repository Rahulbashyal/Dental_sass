<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/css/animations.css'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-50">
    <!-- Mobile Header -->
    <header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50">
        <div class="flex items-center justify-between px-4 py-3">
            <button id="mobile-menu-btn" class="p-2 rounded-md text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
            <h1 class="text-lg font-semibold text-gray-900">@yield('title', 'Dashboard')</h1>
            <div class="w-8"></div>
        </div>
    </header>

    <!-- Mobile Navigation -->
    <nav id="mobile-nav" class="fixed inset-0 z-40 bg-black bg-opacity-50 hidden">
        <div class="bg-white w-64 h-full shadow-lg">
            <div class="p-4 border-b">
                <h2 class="text-lg font-semibold">{{ auth()->user()->clinic->name ?? 'Menu' }}</h2>
            </div>
            <div class="py-2">
                <a href="{{ route('dashboard') }}" class="block px-4 py-3 text-gray-700 hover:bg-gray-100">Dashboard</a>
                <a href="{{ route('clinic.appointments.index') }}" class="block px-4 py-3 text-gray-700 hover:bg-gray-100">Appointments</a>
                <a href="{{ route('clinic.patients.index') }}" class="block px-4 py-3 text-gray-700 hover:bg-gray-100">Patients</a>
                <a href="{{ route('clinic.analytics.dashboard') }}" class="block px-4 py-3 text-gray-700 hover:bg-gray-100">Analytics</a>
                <a href="{{ route('clinic.recurring-appointments.index') }}" class="block px-4 py-3 text-gray-700 hover:bg-gray-100">Recurring</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="pb-16">
        @yield('content')
    </main>

    <!-- Mobile Bottom Navigation -->
    <nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200">
        <div class="flex justify-around py-2">
            <a href="{{ route('dashboard') }}" class="flex flex-col items-center py-2 px-3 text-xs text-gray-600">
                <svg class="w-5 h-5 mb-1" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                </svg>
                Home
            </a>
            <a href="{{ route('clinic.appointments.index') }}" class="flex flex-col items-center py-2 px-3 text-xs text-gray-600">
                <svg class="w-5 h-5 mb-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                </svg>
                Appointments
            </a>
            <a href="{{ route('clinic.patients.index') }}" class="flex flex-col items-center py-2 px-3 text-xs text-gray-600">
                <svg class="w-5 h-5 mb-1" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                </svg>
                Patients
            </a>
            <a href="{{ route('clinic.analytics.dashboard') }}" class="flex flex-col items-center py-2 px-3 text-xs text-gray-600">
                <svg class="w-5 h-5 mb-1" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"></path>
                </svg>
                Analytics
            </a>
        </div>
    </nav>

    <script>
        document.getElementById('mobile-menu-btn').addEventListener('click', function() {
            document.getElementById('mobile-nav').classList.toggle('hidden');
        });
        
        document.getElementById('mobile-nav').addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
            }
        });
    </script>
</body>
</html>