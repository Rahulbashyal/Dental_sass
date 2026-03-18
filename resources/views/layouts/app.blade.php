<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.9/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/css/animations.css'])
    <script src="{{ asset('slide-modal.js') }}"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        [x-cloak] { display: none !important; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        /* Brainwave Sidebar Design System */
        :root {
            --sidebar-bg: #14141c;
            --sidebar-item-hover: rgba(255, 255, 255, 0.05);
            --sidebar-accent: #AC6AFF;
        }

        .sidebar-premium {
            background: var(--sidebar-bg) !important;
            border-right: 1px solid rgba(255, 255, 255, 0.05) !important;
        }

        /* Nav link reset */
        .nav-link {
            display: flex !important;
            align-items: center !important;
            gap: 0.5rem !important;           /* 8px — tight icon↔text */
            padding: 0.45rem 0.75rem !important;
            border-radius: 0.625rem !important;
            color: #94a3b8 !important;        /* slate-400 */
            font-size: 0.8125rem !important;  /* 13px */
            font-weight: 450 !important;
            letter-spacing: 0.008em !important;
            line-height: 1.4 !important;
            border: 1px solid transparent !important;
            transition: background 150ms, color 150ms !important;
            margin-bottom: 1px !important;
            white-space: nowrap !important;
        }

        .nav-link:hover {
            background: #1e293b !important;   /* slate-800 solid */
            color: #e2e8f0 !important;
        }

        /* Active state — solid dark pill, accent text */
        .nav-link.active {
            background: #1e293b !important;   /* slate-800 solid */
            color: #c084fc !important;        /* purple-400 */
            border-color: #334155 !important; /* slate-700 solid */
            font-weight: 600 !important;
        }

        .nav-link i {
            font-size: 0.875rem !important;   /* 14px */
            flex-shrink: 0 !important;
            width: 1.125rem !important;       /* 18px — icon cell */
            display: flex !important;
            justify-content: center !important;
            opacity: 0.75;
            transition: opacity 150ms !important;
        }

        .nav-link:hover i   { opacity: 1 !important; }
        .nav-link.active i  { opacity: 1 !important; color: var(--sidebar-accent) !important; }

        /* Section headers — horizontal rule + label */
        .nav-section-title {
            display: flex !important;
            align-items: center !important;
            gap: 0.5rem !important;
            padding: 0 0.75rem !important;
            margin-top: 1.25rem !important;
            margin-bottom: 0.375rem !important;
            font-size: 0.625rem !important;   /* 10px */
            font-weight: 700 !important;
            letter-spacing: 0.1em !important;
            text-transform: uppercase !important;
            color: #475569 !important;        /* slate-600 */
        }

        .nav-section-title::before {
            content: '';
            display: block;
            width: 1rem;
            height: 1px;
            background: #334155;             /* slate-700 */
            flex-shrink: 0;
        }

        /* Profile Card */
        .profile-card {
            @apply mt-auto mx-2 p-3 rounded-2xl bg-slate-800/40 border border-white/5 flex items-center gap-3 transition-all hover:bg-slate-800/60;
        }

        /* Pro Badge */
        .pro-badge {
            @apply px-1.5 py-0.5 rounded-md bg-[#AC6AFF] text-[8px] font-black text-white uppercase tracking-tighter;
        }

        /* Hide scrollbar everywhere in sidebar */
        .sidebar-premium *::-webkit-scrollbar { display: none; }
        .sidebar-premium * { scrollbar-width: none; -ms-overflow-style: none; }

        /* Premium Typography - Plus Jakarta Sans */
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>
<body class="h-full page-fade-in transition-colors duration-300 bg-slate-100">
    <div class="min-h-full flex flex-col">
        <!-- Mobile Sidebar (Off-canvas) -->
        <div x-show="mobileMenuOpen" 
             class="relative z-50 lg:hidden" 
             x-description="Off-canvas menu for mobile, show/hide based on off-canvas menu state." 
             role="dialog" 
             aria-modal="true"
             x-cloak>
            
            <div x-show="mobileMenuOpen"
                 x-transition:enter="transition-opacity ease-linear duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity ease-linear duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-gray-900/80" 
                 @click="mobileMenuOpen = false"></div>

            <div class="fixed inset-0 flex">
                <div x-show="mobileMenuOpen"
                     x-transition:enter="transition ease-in-out duration-300 transform"
                     x-transition:enter-start="-translate-x-full"
                     x-transition:enter-end="translate-x-0"
                     x-transition:leave="transition ease-in-out duration-300 transform"
                     x-transition:leave-start="translate-x-0"
                     x-transition:leave-end="-translate-x-full"
                     class="relative mr-16 flex w-full max-w-xs flex-1"
                     @click.away="mobileMenuOpen = false">
                    
                    <div class="absolute left-full top-0 flex w-16 justify-center pt-5">
                        <button type="button" class="-m-2.5 p-2.5" @click="mobileMenuOpen = false">
                            <span class="sr-only">Close sidebar</span>
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Mobile Sidebar Content -->
                    <div class="flex grow flex-col gap-y-5 overflow-y-auto sidebar-premium px-4 pb-4 no-scrollbar">
                        <div class="flex h-16 shrink-0 items-center border-b border-white/5 pb-4 mt-4">
                            <img class="h-8 w-auto" src="/logo.png" alt="{{ config('app.name') }}">
                            <span class="ml-3 text-lg font-bold text-white tracking-tight">{{ config('app.name') }}</span>
                        </div>
                        <nav class="flex flex-1 flex-col">
                            <ul role="list" class="flex flex-1 flex-col">
                                @include('layouts.navigation-items')
                            </ul>
                        </nav>
                        
                        <!-- Mobile User Info -->
                        <div class="mt-auto py-4 border-t border-white/5">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-full bg-gradient-to-tr from-blue-500 to-purple-500 p-0.5">
                                    <img class="h-full w-full rounded-full border-2 border-slate-900 object-cover" 
                                         src="{{ auth()->user()->photo ? asset('storage/' . auth()->user()->photo) : 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&color=7F9CF5&background=EBF4FF' }}" 
                                         alt="">
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-white">{{ auth()->user()->name }}</p>
                                    <p class="text-[10px] text-slate-500">{{ auth()->user()->email }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Static Sidebar for Desktop -->
        <div class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-72 lg:flex-col">
            <div class="flex h-full flex-col sidebar-premium">
                <!-- Logo (pinned) -->
                <div class="flex h-16 shrink-0 items-center border-b border-white/5 px-6 mt-4 pb-4">
                    <img class="h-8 w-auto" src="/logo.png" alt="{{ config('app.name') }}">
                    <div class="ml-3">
                        <h1 class="text-lg font-bold text-white tracking-tight">{{ config('app.name') }}</h1>
                    </div>
                </div>
                
                <!-- Navigation (scrollable) -->
                <nav class="flex-1 overflow-y-auto px-4 pb-4 no-scrollbar">
                    <ul role="list" class="flex flex-col">
                        @include('layouts.navigation-items')
                    </ul>
                </nav>
            </div>
        </div>

        <!-- Main content area -->
        <div class="lg:pl-72 flex flex-col flex-1 w-full min-h-screen">
            <!-- Top bar -->
            <div class="sticky top-0 z-40 flex h-16 shrink-0 items-center gap-x-4 border-b border-slate-200 bg-white px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8 transition-colors duration-300">
                <button type="button" class="-m-2.5 p-2.5 text-slate-700 lg:hidden" @click="mobileMenuOpen = true">
                    <span class="sr-only">Open sidebar</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>

                <div class="h-6 w-px bg-slate-200 lg:hidden" aria-hidden="true"></div>

                <div class="flex flex-1 gap-x-4 self-stretch lg:gap-x-6">
                    <div class="flex items-center gap-x-4 lg:gap-x-6">
                        <h1 class="text-xl font-semibold leading-7 text-slate-900">@yield('page-title', 'Dashboard')</h1>
                        <div class="flex items-center gap-x-2">
                            <div class="h-2 w-2 bg-green-400 rounded-full"></div>
                            <span class="text-xs text-slate-500">Live</span>
                        </div>
                    </div>
                    
                    <div class="ml-auto flex items-center gap-x-4 lg:gap-x-6">
                        <!-- Theme Toggle Removed - Using fixed theme -->

                        <!-- Notification Center -->
                        <div x-data="{ open: false, count: 0 }" 
                             x-init="fetch('/notifications/unread-count').then(r => r.json()).then(d => count = d.count)"
                             class="relative">
                            <button @click="open = !open" type="button" class="-m-2.5 p-2.5 text-slate-400 hover:text-slate-600 relative transition-colors">
                                <span class="sr-only">View notifications</span>
                                <i class="fa-solid fa-bell text-xl"></i>
                                <template x-if="count > 0">
                                    <span class="absolute top-2 right-2 block h-2.5 w-2.5 rounded-full bg-rose-500 ring-2 ring-white border-2 border-white"></span>
                                </template>
                            </button>

                            <div x-show="open" 
                                 @click.away="open = false"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 translate-y-1"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 class="absolute right-0 z-50 mt-4 w-96 origin-top-right rounded-3xl bg-white p-4 shadow-2xl ring-1 ring-slate-900/5 focus:outline-none border border-slate-100"
                                 x-cloak>
                                <div class="flex items-center justify-between mb-4 px-2">
                                    <h3 class="text-xs font-black text-slate-900 uppercase tracking-widest">Notifications</h3>
                                    <a href="{{ route('notifications.index') }}" class="text-[10px] font-black text-blue-600 uppercase tracking-widest hover:text-blue-800">View All</a>
                                </div>
                                <div class="max-h-96 overflow-y-auto no-scrollbar space-y-2">
                                    @php $topNotifications = Auth::user()->notifications()->latest()->take(5)->get(); @endphp
                                    @forelse($topNotifications as $notification)
                                        <div class="group relative flex gap-x-4 rounded-2xl p-3 hover:bg-slate-50 transition-all border border-transparent hover:border-slate-100 {{ $notification->read_at ? 'opacity-60' : '' }}">
                                            <div class="mt-1 flex h-10 w-10 flex-none items-center justify-center rounded-xl bg-slate-50 group-hover:bg-white shadow-sm transition-all text-slate-400">
                                                <i class="fa-solid {{ $notification->type === 'email' ? 'fa-envelope' : ($notification->type === 'appointment' ? 'fa-calendar-check' : 'fa-info-circle') }}"></i>
                                            </div>
                                            <div class="flex-auto">
                                                <p class="text-sm font-black text-slate-900 leading-tight line-clamp-1">{{ $notification->title }}</p>
                                                <p class="mt-1 text-xs text-slate-500 line-clamp-2 leading-relaxed">{{ $notification->message }}</p>
                                                <p class="mt-1 text-[10px] font-bold text-slate-400 uppercase tracking-tighter">{{ $notification->created_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="py-10 text-center">
                                            <i class="fa-solid fa-bell-slash text-slate-300 text-3xl mb-3"></i>
                                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">No New Alerts</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <div class="h-6 w-px bg-slate-200" aria-hidden="true"></div>

                        <!-- User Profile Dropdown -->
                        <div x-data="{ userOpen: false }" class="relative">
                            <button @click="userOpen = !userOpen" class="flex items-center gap-3 p-1.5 rounded-2xl hover:bg-slate-50 transition-all border border-transparent hover:border-slate-200">
                                <div class="h-9 w-9 rounded-full bg-gradient-to-tr from-blue-500 to-purple-500 p-0.5 shadow-sm">
                                    <img class="h-full w-full rounded-full border-2 border-white object-cover" 
                                         src="{{ auth()->user()->photo ? asset('storage/' . auth()->user()->photo) : 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&color=7F9CF5&background=EBF4FF' }}" 
                                         alt="">
                                </div>
                                <div class="hidden lg:block text-left mr-2">
                                    <p class="text-xs font-black text-slate-900 leading-none mb-1">{{ auth()->user()->name }}</p>
                                    <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest">{{ auth()->user()->roles->first()->name ?? 'Member' }}</p>
                                </div>
                                <i class="fa-solid fa-chevron-down text-[10px] text-slate-400 transition-transform duration-200" :class="{ 'rotate-180': userOpen }"></i>
                            </button>

                            <div x-show="userOpen" 
                                 @click.away="userOpen = false"
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 class="absolute right-0 z-50 mt-3 w-64 origin-top-right rounded-3xl bg-white border border-slate-100 shadow-2xl p-2"
                                 x-cloak>
                                <div class="px-4 py-3 border-b border-slate-100 mb-2">
                                    <p class="text-xs font-black text-slate-900">{{ auth()->user()->email }}</p>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Status: Online</p>
                                </div>
                                <a href="{{ route('profile.edit') }}" class="flex items-center gap-4 px-4 py-3 text-sm font-bold text-slate-700 rounded-2xl hover:bg-slate-50 transition-all">
                                    <div class="h-8 w-8 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600">
                                        <i class="fa-solid fa-user-circle"></i>
                                    </div>
                                    My details
                                </a>
                                <div class="my-1 border-t border-slate-100"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left flex items-center gap-4 px-4 py-3 text-sm font-bold text-rose-600 rounded-2xl hover:bg-rose-50 transition-all">
                                        <div class="h-8 w-8 rounded-xl bg-rose-50 flex items-center justify-center">
                                            <i class="fa-solid fa-power-off"></i>
                                        </div>
                                        Log Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <main class="py-10">
                <div class="px-4 sm:px-6 lg:px-8">
                    @if(session('success'))
                        <div class="rounded-md bg-green-50 p-4 mb-6">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.236 4.53L7.53 10.53a.75.75 0 00-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="rounded-md bg-red-50 p-4 mb-6">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Global Slide-in Modal Container -->
    @stack('modals')
</body>
</html>