<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/css/animations.css'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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

        .nav-link {
            @apply flex items-center gap-x-4 px-4 py-3 text-slate-400 rounded-2xl transition-all duration-200 border border-transparent mb-1;
            font-size: 0.875rem !important;
        }

        .nav-link:hover {
            @apply bg-white/5 text-slate-200;
        }

        .nav-link.active {
            @apply bg-slate-800/80 text-white shadow-none translate-x-0 !important;
            border-color: rgba(255, 255, 255, 0.05) !important;
        }

        .nav-link i {
            @apply text-lg transition-all duration-300 shrink-0 w-6 flex justify-center !important;
        }

        .nav-link.active i {
            color: var(--sidebar-accent) !important;
        }

        .nav-section-title {
            @apply px-4 text-[10px] font-bold text-slate-600 uppercase tracking-widest mt-6 mb-2 flex items-center gap-2 !important;
        }

        /* Profile Card */
        .profile-card {
            @apply mt-auto mx-2 p-3 rounded-2xl bg-slate-800/40 border border-white/5 flex items-center gap-4 transition-all hover:bg-slate-800/60;
        }
        
        /* Pro Badge */
        .pro-badge {
            @apply px-1.5 py-0.5 rounded-md bg-[#AC6AFF] text-[8px] font-black text-white uppercase tracking-tighter;
        }
    </style>
</head>
<body class="h-full page-fade-in transition-colors duration-300" 
      x-data="{ 
        mobileMenuOpen: false, 
        darkMode: localStorage.getItem('theme') === 'dark' 
      }" 
      x-init="$watch('darkMode', val => { 
          localStorage.setItem('theme', val ? 'dark' : 'light');
          if (val) document.documentElement.classList.add('dark');
          else document.documentElement.classList.remove('dark');
      }); if (darkMode) document.documentElement.classList.add('dark');"
      :class="{ 'dark': darkMode, 'bg-gray-50': !darkMode, 'bg-slate-950': darkMode }">
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
                    <div class="flex grow flex-col gap-y-5 overflow-y-auto sidebar-premium px-6 pb-4 no-scrollbar">
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
                                         src="{{ auth()->user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&color=7F9CF5&background=EBF4FF' }}" 
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
            <div class="flex grow flex-col gap-y-5 overflow-y-auto sidebar-premium px-6 pb-4">
                <!-- Logo -->
                <div class="flex h-16 shrink-0 items-center border-b border-white/5 pb-4 mt-4">
                    <img class="h-8 w-auto" src="/logo.png" alt="{{ config('app.name') }}">
                    <div class="ml-3">
                        <h1 class="text-lg font-bold text-white tracking-tight">{{ config('app.name') }}</h1>
                    </div>
                </div>
                
                <!-- Navigation -->
                <nav class="flex flex-1 flex-col">
                    <ul role="list" class="flex flex-1 flex-col">
                        @include('layouts.navigation-items')
                    </ul>
                </nav>
            </div>
        </div>

        <!-- Main content area -->
        <div class="lg:pl-72 flex flex-col flex-1 w-full min-h-screen">
            <!-- Top bar -->
            <div class="sticky top-0 z-40 flex h-16 shrink-0 items-center gap-x-4 border-b border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-900 px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8 transition-colors duration-300">
                <button type="button" class="-m-2.5 p-2.5 text-gray-700 dark:text-gray-200 lg:hidden" @click="mobileMenuOpen = true">
                    <span class="sr-only">Open sidebar</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>

                <div class="h-6 w-px bg-gray-200 dark:bg-slate-800 lg:hidden" aria-hidden="true"></div>

                <div class="flex flex-1 gap-x-4 self-stretch lg:gap-x-6">
                    <div class="flex items-center gap-x-4 lg:gap-x-6">
                        <h1 class="text-xl font-semibold leading-7 text-gray-900 dark:text-white">@yield('page-title', 'Dashboard')</h1>
                        <div class="flex items-center gap-x-2">
                            <div class="h-2 w-2 bg-green-400 rounded-full"></div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Live</span>
                        </div>
                    </div>
                    
                    <div class="ml-auto flex items-center gap-x-4 lg:gap-x-6">
                        <!-- Theme Toggle -->
                        <div class="flex items-center bg-gray-100 dark:bg-slate-800 p-1 rounded-xl transition-colors">
                            <button @click="darkMode = false" 
                                    :class="{ 'bg-white text-blue-600 shadow-sm': !darkMode, 'text-slate-400': darkMode }"
                                    class="p-1.5 rounded-lg transition-all duration-200">
                                <i class="fa-solid fa-sun text-sm"></i>
                            </button>
                            <button @click="darkMode = true" 
                                    :class="{ 'bg-slate-700 text-amber-400 shadow-sm': darkMode, 'text-slate-400': !darkMode }"
                                    class="p-1.5 rounded-lg transition-all duration-200">
                                <i class="fa-solid fa-moon text-sm"></i>
                            </button>
                        </div>

                        <!-- Notification Center -->
                        <div x-data="{ open: false, count: 0 }" 
                             x-init="fetch('/notifications/unread-count').then(r => r.json()).then(d => count = d.count)"
                             class="relative">
                            <button @click="open = !open" type="button" class="-m-2.5 p-2.5 text-gray-400 hover:text-gray-500 dark:text-slate-400 dark:hover:text-white relative transition-colors">
                                <span class="sr-only">View notifications</span>
                                <i class="fa-solid fa-bell text-xl"></i>
                                <template x-if="count > 0">
                                    <span class="absolute top-2 right-2 block h-2.5 w-2.5 rounded-full bg-rose-500 ring-2 ring-white dark:ring-slate-900 border-2 border-white dark:border-slate-900"></span>
                                </template>
                            </button>

                            <div x-show="open" 
                                 @click.away="open = false"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 translate-y-1"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 class="absolute right-0 z-50 mt-4 w-96 origin-top-right rounded-3xl bg-white dark:bg-slate-900 p-4 shadow-2xl ring-1 ring-slate-900/5 focus:outline-none border dark:border-slate-800"
                                 x-cloak>
                                <div class="flex items-center justify-between mb-4 px-2">
                                    <h3 class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-widest">Notifications</h3>
                                    <a href="{{ route('notifications.index') }}" class="text-[10px] font-black text-blue-600 dark:text-blue-400 uppercase tracking-widest hover:text-blue-800 dark:hover:text-blue-300">View All</a>
                                </div>
                                <div class="max-h-96 overflow-y-auto no-scrollbar space-y-2">
                                    @php $topNotifications = Auth::user()->notifications()->latest()->take(5)->get(); @endphp
                                    @forelse($topNotifications as $notification)
                                        <div class="group relative flex gap-x-4 rounded-2xl p-3 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-all border border-transparent hover:border-slate-100 dark:hover:border-slate-800 {{ $notification->read_at ? 'opacity-60' : '' }}">
                                            <div class="mt-1 flex h-10 w-10 flex-none items-center justify-center rounded-xl bg-slate-50 dark:bg-slate-800 group-hover:bg-white dark:group-hover:bg-slate-700 shadow-sm transition-all text-slate-400">
                                                <i class="fa-solid {{ $notification->type === 'email' ? 'fa-envelope' : ($notification->type === 'appointment' ? 'fa-calendar-check' : 'fa-info-circle') }}"></i>
                                            </div>
                                            <div class="flex-auto">
                                                <p class="text-sm font-black text-slate-900 dark:text-white leading-tight line-clamp-1">{{ $notification->title }}</p>
                                                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400 line-clamp-2 leading-relaxed">{{ $notification->message }}</p>
                                                <p class="mt-1 text-[10px] font-bold text-slate-400 uppercase tracking-tighter">{{ $notification->created_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="py-10 text-center">
                                            <i class="fa-solid fa-bell-slash text-slate-200 dark:text-slate-800 text-3xl mb-3"></i>
                                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">No New Alerts</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <div class="h-6 w-px bg-gray-200 dark:bg-slate-800" aria-hidden="true"></div>

                        <!-- User Profile Dropdown -->
                        <div x-data="{ userOpen: false }" class="relative">
                            <button @click="userOpen = !userOpen" class="flex items-center gap-3 p-1.5 rounded-2xl hover:bg-gray-50 dark:hover:bg-slate-800 transition-all border border-transparent hover:border-gray-100 dark:hover:border-slate-700">
                                <div class="h-9 w-9 rounded-full bg-gradient-to-tr from-blue-500 to-purple-500 p-0.5 shadow-sm">
                                    <img class="h-full w-full rounded-full border-2 border-white dark:border-slate-900 object-cover" 
                                         src="{{ auth()->user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&color=7F9CF5&background=EBF4FF' }}" 
                                         alt="">
                                </div>
                                <div class="hidden lg:block text-left mr-2">
                                    <p class="text-xs font-black text-slate-900 dark:text-white leading-none mb-1">{{ auth()->user()->name }}</p>
                                    <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest">{{ auth()->user()->roles->first()->name ?? 'Member' }}</p>
                                </div>
                                <i class="fa-solid fa-chevron-down text-[10px] text-slate-400 transition-transform duration-200" :class="{ 'rotate-180': userOpen }"></i>
                            </button>

                            <div x-show="userOpen" 
                                 @click.away="userOpen = false"
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 class="absolute right-0 z-50 mt-3 w-64 origin-top-right rounded-3xl bg-white dark:bg-slate-900 border dark:border-slate-800 shadow-2xl p-2"
                                 x-cloak>
                                <div class="px-4 py-3 border-b border-gray-100 dark:border-slate-800 mb-2">
                                    <p class="text-xs font-black text-slate-900 dark:text-white">{{ auth()->user()->email }}</p>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Status: Online</p>
                                </div>
                                <a href="{{ route('profile.edit') }}" class="flex items-center gap-4 px-4 py-3 text-sm font-bold text-slate-700 dark:text-slate-300 rounded-2xl hover:bg-gray-50 dark:hover:bg-slate-800 transition-all">
                                    <div class="h-8 w-8 rounded-xl bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center text-blue-600 dark:text-blue-400">
                                        <i class="fa-solid fa-user-circle"></i>
                                    </div>
                                    My details
                                </a>
                                <div class="my-1 border-t border-gray-100 dark:border-slate-800"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left flex items-center gap-4 px-4 py-3 text-sm font-bold text-rose-600 rounded-2xl hover:bg-rose-50 dark:hover:bg-rose-900/20 transition-all">
                                        <div class="h-8 w-8 rounded-xl bg-rose-50 dark:bg-rose-900/20 flex items-center justify-center">
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
</body>
</html>