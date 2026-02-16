<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Patient Portal') - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/css/animations.css'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, h4, h5, .font-display, .Outfit { font-family: 'Outfit', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="h-full antialiased">
    @php 
    $patient = Auth::guard('patient')->user();
    $clinic = $patient ? $patient->clinic : null;
    $clinicName = $clinic ? $clinic->name : config('app.name', 'Dental Care');
    $clinicLogo = $clinic && $clinic->logo ? asset('storage/' . $clinic->logo) : null;
    $clinicColor = $clinic && $clinic->primary_color ? $clinic->primary_color : '#0ea5e9';
    @endphp

    <div x-data="{ mobileMenuOpen: false, userMenuOpen: false }" class="min-h-screen flex flex-col">
        
        <!-- Top Navbar - White Background -->
        <header class="bg-white border-b border-slate-200 sticky top-0 z-50 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <!-- Logo & Clinic Name -->
                    <div class="flex items-center space-x-3">
                        @if($clinicLogo)
                            <img src="{{ $clinicLogo }}" alt="{{ $clinicName }}" class="h-10 w-10 object-contain rounded-lg">
                        @else
                            <div class="h-10 w-10 bg-slate-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-tooth text-slate-600 text-xl"></i>
                            </div>
                        @endif
                        <div>
                            <h1 class="text-lg font-black tracking-tight text-slate-800 Outfit leading-none">{{ $clinicName }}</h1>
                            <p class="text-[10px] text-slate-500 font-medium tracking-widest opacity-70 uppercase">Patient Portal</p>
                        </div>
                    </div>

                    <!-- Desktop Navigation Links -->
                    <nav class="hidden md:flex items-center space-x-1">
                        <a href="{{ route('patient.dashboard') }}" class="px-4 py-2 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('patient.dashboard') ? 'bg-slate-100 text-slate-800' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-800' }}">
                            <i class="fas fa-home mr-2"></i>Overview
                        </a>
                        <a href="{{ route('patient.appointments') }}" class="px-4 py-2 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('patient.appointments*') ? 'bg-slate-100 text-slate-800' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-800' }}">
                            <i class="fas fa-calendar-check mr-2"></i>Appointments
                        </a>
                        <a href="{{ route('patient.invoices') }}" class="px-4 py-2 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('patient.invoices*') ? 'bg-slate-100 text-slate-800' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-800' }}">
                            <i class="fas fa-wallet mr-2"></i>Billing
                        </a>
                    </nav>

                    <!-- Right Side - Profile & Logout -->
                    <div class="flex items-center space-x-4">
                        <!-- Notification Center -->
                        <div x-data="{ open: false, count: 0 }" 
                             x-init="fetch('/notifications/unread-count').then(r => r.json()).then(d => count = d.count)"
                             class="relative">
                            <button @click="open = !open" type="button" class="p-2 text-slate-400 hover:text-slate-600 relative transition-colors">
                                <i class="fa-solid fa-bell text-xl"></i>
                                <template x-if="count > 0">
                                    <span class="absolute top-1 right-1 block h-2.5 w-2.5 rounded-full bg-rose-500 ring-2 ring-white"></span>
                                </template>
                            </button>

                            <div x-show="open" 
                                 @click.away="open = false"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 translate-y-1"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 class="absolute right-0 z-50 mt-4 w-80 origin-top-right rounded-3xl bg-white p-4 shadow-2xl ring-1 ring-slate-900/5 focus:outline-none"
                                 x-cloak>
                                <div class="flex items-center justify-between mb-4 px-2">
                                    <h3 class="text-xs font-black text-slate-900 uppercase tracking-widest">Clinical Alerts</h3>
                                    <a href="{{ route('patient.consents') }}" class="text-[10px] font-black text-blue-600 uppercase tracking-widest hover:text-blue-800">Inbox</a>
                                </div>
                                <div class="max-h-80 overflow-y-auto no-scrollbar space-y-2">
                                    @php $topNotifications = $patient->notifications()->latest()->take(5)->get(); @endphp
                                    @forelse($topNotifications as $notification)
                                        <div class="group relative flex gap-x-3 rounded-2xl p-2.5 hover:bg-slate-50 transition-all border border-transparent {{ $notification->read_at ? 'opacity-50' : '' }}">
                                            <div class="mt-1 flex h-8 w-8 flex-none items-center justify-center rounded-lg bg-slate-100 group-hover:bg-white shadow-sm transition-all text-slate-400">
                                                <i class="fa-solid fa-xs {{ $notification->type === 'payment' ? 'fa-file-invoice-dollar' : 'fa-bell' }}"></i>
                                            </div>
                                            <div class="flex-auto">
                                                <p class="text-xs font-black text-slate-900 leading-tight">{{ $notification->title }}</p>
                                                <p class="mt-0.5 text-[10px] text-slate-500 line-clamp-2">{{ $notification->message }}</p>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="py-6 text-center">
                                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Secure & Connected</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <div class="h-6 w-px bg-slate-200 mx-2"></div>
                        <!-- Profile Dropdown -->
                        <div class="relative" x-data>
                            <button @click="userMenuOpen = !userMenuOpen" class="flex items-center space-x-2 bg-slate-50 hover:bg-slate-100 rounded-xl px-3 py-2 transition-colors">
                                <div class="w-8 h-8 rounded-full text-white flex items-center justify-center font-bold text-sm" style="background-color: {{ $clinicColor }}">
                                    {{ $patient ? substr($patient->first_name ?? 'P', 0, 1) : 'P' }}
                                </div>
                                <span class="hidden sm:block text-sm font-medium text-slate-700">{{ $patient ? ($patient->first_name ?? 'Patient') : 'Patient' }}</span>
                                <i class="fas fa-chevron-down text-slate-400 text-xs"></i>
                            </button>
                            
                            <!-- Dropdown Menu -->
                            <div x-show="userMenuOpen" @click.away="userMenuOpen = false" class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg py-1 z-50 border border-slate-100" style="display: none;">
                                <div class="px-4 py-3 border-b border-slate-100">
                                    <p class="text-sm font-bold text-slate-800">{{ $patient ? ($patient->full_name ?? 'Patient') : 'Patient' }}</p>
                                    <p class="text-xs text-slate-500">{{ $patient ? ($patient->patient_id ?? '') : '' }}</p>
                                </div>
                                <a href="{{ route('patient.profile') }}" class="flex items-center px-4 py-3 text-sm text-slate-700 hover:bg-slate-50 transition-colors">
                                    <div class="w-8 flex justify-center">
                                        <i class="fas fa-user-circle text-slate-400"></i>
                                    </div>
                                    <span class="ml-2 font-medium">My Profile</span>
                                </a>
                                <a href="{{ route('patient.consents') }}" class="flex items-center px-4 py-3 text-sm text-slate-700 hover:bg-slate-50 transition-colors">
                                    <div class="w-8 flex justify-center">
                                        <i class="fas fa-file-signature text-slate-400"></i>
                                    </div>
                                    <span class="ml-2 font-medium">Documents</span>
                                </a>
                                <div class="border-t border-slate-100"></div>
                                <form method="POST" action="{{ route('patient.logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center px-4 py-3 text-sm text-rose-600 hover:bg-rose-50 transition-colors text-left">
                                        <div class="w-8 flex justify-center">
                                            <i class="fas fa-sign-out-alt"></i>
                                        </div>
                                        <span class="ml-2 font-black uppercase tracking-widest text-[10px]">Logout Identity</span>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Mobile Menu Button -->
                        <button @click="mobileMenuOpen = true" class="md:hidden p-2 text-slate-600">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile Menu Overlay -->
            <div x-show="mobileMenuOpen" class="md:hidden fixed inset-0 z-50" style="display: none;">
                <div class="fixed inset-0 bg-black/50" @click="mobileMenuOpen = false"></div>
                <div class="fixed top-0 right-0 bottom-0 w-72 bg-white text-slate-800 shadow-2xl p-6">
                    <div class="flex justify-between items-center mb-6">
                        <div class="flex items-center space-x-3">
                            @if($clinicLogo)
                                <img src="{{ $clinicLogo }}" alt="{{ $clinicName }}" class="h-8 w-8 object-contain rounded-lg">
                            @else
                                <div class="h-8 w-8 bg-slate-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-tooth text-slate-600"></i>
                                </div>
                            @endif
                            <span class="font-bold">{{ $clinicName }}</span>
                        </div>
                        <button @click="mobileMenuOpen = false" class="p-2">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                    <nav class="space-y-2">
                        <a href="{{ route('patient.dashboard') }}" class="flex items-center px-4 py-3 rounded-xl transition-colors {{ request()->routeIs('patient.dashboard') ? 'bg-slate-100' : 'hover:bg-slate-50' }}">
                            <i class="fas fa-home w-6"></i><span class="font-medium">Overview</span>
                        </a>
                        <a href="{{ route('patient.appointments') }}" class="flex items-center px-4 py-3 rounded-xl transition-colors {{ request()->routeIs('patient.appointments*') ? 'bg-slate-100' : 'hover:bg-slate-50' }}">
                            <i class="fas fa-calendar-check w-6"></i><span class="font-medium">Appointments</span>
                        </a>
                        <a href="{{ route('patient.recurring-appointments') }}" class="flex items-center px-4 py-3 rounded-xl transition-colors {{ request()->routeIs('patient.recurring-appointments*') ? 'bg-slate-100' : 'hover:bg-slate-50' }}">
                            <i class="fas fa-sync w-6"></i><span class="font-medium">Recurring</span>
                        </a>
                        <a href="{{ route('patient.consents') }}" class="flex items-center px-4 py-3 rounded-xl transition-colors {{ request()->routeIs('patient.consents*') ? 'bg-slate-100' : 'hover:bg-slate-50' }}">
                            <i class="fas fa-file-signature w-6"></i><span class="font-medium">Documents</span>
                        </a>
                        <a href="{{ route('patient.payment-plans') }}" class="flex items-center px-4 py-3 rounded-xl transition-colors {{ request()->routeIs('patient.payment-plans*') ? 'bg-slate-100' : 'hover:bg-slate-50' }}">
                            <i class="fas fa-credit-card w-6"></i><span class="font-medium">Payments</span>
                        </a>
                        <a href="{{ route('patient.invoices') }}" class="flex items-center px-4 py-3 rounded-xl transition-colors {{ request()->routeIs('patient.invoices*') ? 'bg-slate-100' : 'hover:bg-slate-50' }}">
                            <i class="fas fa-wallet w-6"></i><span class="font-medium">Billing</span>
                        </a>
                        <a href="{{ route('patient.profile') }}" class="flex items-center px-4 py-3 rounded-xl transition-colors {{ request()->routeIs('patient.profile*') ? 'bg-slate-100' : 'hover:bg-slate-50' }}">
                            <i class="fas fa-user-circle w-6"></i><span class="font-medium">Profile</span>
                        </a>
                    </nav>
                </div>
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="flex-1 bg-slate-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <!-- Status Notifications -->
                @if(session('success'))
                    <div class="mb-6 rounded-2xl bg-emerald-50 border border-emerald-100 p-4 shadow-sm">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center mr-4">
                                <i class="fas fa-check-circle text-lg"></i>
                            </div>
                            <p class="text-sm font-bold text-emerald-800">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 rounded-2xl bg-rose-50 border border-rose-100 p-4 shadow-sm">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-rose-100 text-rose-600 rounded-xl flex items-center justify-center mr-4">
                                <i class="fas fa-exclamation-circle text-lg"></i>
                            </div>
                            <p class="text-sm font-bold text-rose-800">{{ session('error') }}</p>
                        </div>
                    </div>
                @endif

                @yield('content')
            </div>

            <!-- Global Footer -->
            <footer class="max-w-7xl mx-auto px-8 py-8 mt-12 border-t border-slate-200">
                <div class="flex flex-col md:flex-row justify-between items-center text-slate-400 gap-4">
                    <p class="text-xs font-bold uppercase tracking-widest">© {{ date('Y') }} {{ $clinicName }}</p>
                    <div class="flex items-center space-x-6">
                        <a href="#" class="text-xs font-black uppercase tracking-widest hover:text-slate-600 transition-colors">Privacy</a>
                        <a href="#" class="text-xs font-black uppercase tracking-widest hover:text-slate-600 transition-colors">Support</a>
                    </div>
                </div>
            </footer>
        </main>

        <!-- Mobile Bottom Nav Bar -->
        <div class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-slate-200 px-4 py-2 flex justify-between items-center z-40 shadow-[0_-5px_20px_rgba(0,0,0,0.05)]">
            <a href="{{ route('patient.dashboard') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('patient.dashboard') ? 'text-slate-800' : 'text-slate-400' }}">
                <i class="fas fa-home text-lg"></i>
                <span class="text-[10px] font-black uppercase">Home</span>
            </a>
            <a href="{{ route('patient.appointments') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('patient.appointments*') ? 'text-slate-800' : 'text-slate-400' }}">
                <i class="fas fa-calendar-alt text-lg"></i>
                <span class="text-[10px] font-black uppercase">Visits</span>
            </a>
            <div class="relative -top-5">
                <a href="{{ route('patient.appointments') }}" class="w-12 h-12 text-white rounded-full flex items-center justify-center shadow-xl shadow-slate-200 border-4 border-white" style="background-color: {{ $clinicColor }}">
                    <i class="fas fa-plus text-lg"></i>
                </a>
            </div>
            <a href="{{ route('patient.invoices') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('patient.invoices*') ? 'text-slate-800' : 'text-slate-400' }}">
                <i class="fas fa-wallet text-lg"></i>
                <span class="text-[10px] font-black uppercase">Bills</span>
            </a>
            <a href="{{ route('patient.profile') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('patient.profile*') ? 'text-slate-800' : 'text-slate-400' }}">
                <i class="fas fa-user text-lg"></i>
                <span class="text-[10px] font-black uppercase">Me</span>
            </a>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
