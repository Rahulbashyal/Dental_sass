@extends('layouts.app')

@section('content')
<!-- Premium Welcome Section -->
<!-- Clean Light Premium Welcome Section -->
<div class="mb-10 page-fade-in">
    <div class="relative overflow-hidden bg-white rounded-[2.5rem] p-10 lg:p-12 shadow-sm border border-slate-200">
        <!-- Subtle Decorative Blobs -->
        <div class="absolute top-0 right-0 -mt-24 -mr-24 w-96 h-96 bg-blue-50/50 rounded-full blur-[100px]"></div>
        <div class="absolute bottom-0 left-0 -mb-24 -ml-24 w-96 h-96 bg-indigo-50/30 rounded-full blur-[100px]"></div>
        
        <div class="relative flex flex-col lg:flex-row items-center justify-between gap-12">
            <!-- Left: Identity & Authority -->
            <div class="flex flex-col md:flex-row items-center gap-10">
                <div class="relative group">
                    <div class="w-24 h-24 rounded-3xl bg-gradient-to-tr from-blue-600 to-indigo-600 p-1 shadow-xl transition-all duration-500 group-hover:scale-105">
                        <img class="w-full h-full rounded-[1.4rem] object-cover border-4 border-white" 
                             src="{{ auth()->user()->photo ? asset('storage/' . auth()->user()->photo) : 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&color=FFFFFF&background=3B82F6' }}" 
                             alt="">
                    </div>
                    <div class="absolute -bottom-1 -right-1 bg-emerald-500 w-7 h-7 rounded-2xl border-4 border-white shadow-lg flex items-center justify-center">
                        <div class="w-1.5 h-1.5 bg-white rounded-full animate-pulse"></div>
                    </div>
                </div>
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <span class="px-4 py-1.5 bg-blue-50 text-blue-600 text-[11px] font-black uppercase tracking-[0.2em] rounded-xl border border-blue-100 shadow-sm">
                            Super Administration
                        </span>
                        <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest bg-slate-50 px-3 py-1.5 rounded-xl border border-slate-200">
                            v0.1.0 stable
                        </span>
                    </div>
                    <h1 class="text-3xl lg:text-5xl font-extrabold text-slate-900 mb-4 tracking-tight leading-tight">
                        Good Morning, <span class="text-blue-600">{{ explode(' ', auth()->user()->name)[0] }}</span>
                    </h1>
                    <p class="text-slate-600 text-lg font-medium max-w-xl leading-relaxed">
                        Platform status is <span class="text-blue-600 font-bold underline decoration-blue-200 underline-offset-4">optimal</span>. 
                        Managing <span class="text-slate-900 font-bold">{{ \App\Models\Clinic::count() }} active clinic nodes</span> across the ecosystem.
                    </p>
                </div>
            </div>

            <!-- Right: Elegant Utility -->
            <div class="flex flex-col sm:flex-row items-center gap-6">
                <!-- Platform Time Card -->
                <div class="flex-1 w-full relative overflow-hidden bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm group hover:shadow-xl transition-all duration-500 min-w-[280px]">
                    <!-- Subtle Decorative Background -->
                    <div class="absolute -top-10 -right-10 w-32 h-32 bg-blue-50/30 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
                    
                    <div class="relative flex items-center gap-6">
                        <div class="flex-shrink-0 w-16 h-16 bg-gradient-to-br from-blue-50,to-blue-100/50 rounded-2xl flex items-center justify-center text-blue-600 shadow-sm border border-blue-100 group-hover:scale-110 group-hover:rotate-3 transition-all duration-500">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>

                        <div class="space-y-1.5 flex-1 min-w-0">
                            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-[0.2em] leading-none">Platform Time</p>
                            <h3 id="live-time" class="text-3xl font-black text-slate-900 tracking-tight whitespace-nowrap tabular-nums leading-none">
                                {{ now('Asia/Kathmandu')->format('h:i A') }}
                            </h3>
                            <div class="flex items-center gap-1.5 text-[10px] font-black text-blue-600 uppercase tracking-widest bg-blue-50 px-2 py-1 rounded-lg w-fit mt-1.5 border border-blue-100">
                                <span class="relative flex h-1.5 w-1.5">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-blue-500"></span>
                                </span>
                                Good Day
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Nepali Segment Card -->
                <div class="flex-1 w-full relative overflow-hidden bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm group hover:shadow-xl transition-all duration-500 min-w-[280px]">
                    <!-- Subtle Decorative Background -->
                    <div class="absolute -top-10 -right-10 w-32 h-32 bg-indigo-50/30 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>

                    @php $nepaliDate = \App\Helpers\NepaliDateHelper::getCurrentNepaliDate(); @endphp
                    <div class="relative flex items-center gap-6">
                        <div class="flex-shrink-0 w-16 h-16 bg-gradient-to-br from-indigo-50 to-indigo-100/50 rounded-2xl flex items-center justify-center text-indigo-600 shadow-sm border border-indigo-100 group-hover:scale-110 group-hover:-rotate-3 transition-all duration-500">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>

                        <div class="space-y-1.5 flex-1 min-w-0">
                            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-[0.2em] leading-none">Platform Date</p>
                            <h3 class="text-xl font-black text-slate-900 tracking-tight leading-none">
                                {{ $nepaliDate['formatted'] ?? '२६ फाल्गुन २०८२' }}
                            </h3>
                            <div class="flex items-center gap-1.5 text-[10px] font-black text-indigo-600 uppercase tracking-widest bg-indigo-50 px-2 py-1 rounded-lg w-fit mt-1.5 border border-indigo-100">
                                <i class="fas fa-calendar-day text-[8px]"></i>
                                {{ $nepaliDate['day'] ?? 'सोमबार' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function updateLiveTime() {
        const now = new Date();
        const options = {
            timeZone: 'Asia/Kathmandu',
            hour: '2-digit',
            minute: '2-digit',
            hour12: true
        };
        const formatter = new Intl.DateTimeFormat('en-US', options);
        const parts = formatter.formatToParts(now);
        
        const h = parts.find(p => p.type === 'hour').value;
        const m = parts.find(p => p.type === 'minute').value;
        const p = parts.find(p => p.type === 'dayPeriod').value;
        
        const timeElem = document.getElementById('live-time');
        if (timeElem) {
            // Simplified display: hh:mm AM/PM without line breaks
            timeElem.textContent = `${h}:${m} ${p.toUpperCase()}`;
        }
    }
    updateLiveTime();
    setInterval(updateLiveTime, 1000);
</script>

<!-- Stats Perspective Section -->
<div class="mb-12">
    <div class="flex items-center gap-3 mb-8 px-2">
        <div class="w-1.5 h-8 bg-blue-600 rounded-full shadow-lg shadow-blue-500/50"></div>
        <h2 class="text-2xl font-black text-slate-900 tracking-tight uppercase text-[11px] tracking-[0.3em]">Platform Landscape</h2>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 stagger-in">
        <!-- Clinics Node -->
        <div class="relative group bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm transition-all duration-500 hover:shadow-2xl hover:-translate-y-2 hover:border-blue-200 overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-blue-50 -mr-16 -mt-16 rounded-full transition-transform duration-700 group-hover:scale-150"></div>
            <div class="relative flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-3">Health Centers</p>
                    <p class="text-5xl font-black text-slate-900 leading-none mb-3">{{ \App\Models\Clinic::count() }}</p>
                    <div class="flex items-center text-[11px] font-black text-blue-600 uppercase">
                        <i class="fas fa-arrow-up mr-1 text-[8px]"></i> 12% Uptrend
                    </div>
                </div>
                <div class="p-5 bg-gradient-to-br from-blue-500 to-blue-700 rounded-[1.8rem] shadow-xl shadow-blue-500/30 text-white transform group-hover:rotate-6 transition-transform">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Global Users -->
        <div class="relative group bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm transition-all duration-500 hover:shadow-2xl hover:-translate-y-2 hover:border-emerald-200 overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-50 -mr-16 -mt-16 rounded-full transition-transform duration-700 group-hover:scale-150"></div>
            <div class="relative flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-3">Total Identities</p>
                    <p class="text-5xl font-black text-slate-900 leading-none mb-3">{{ \App\Models\User::count() }}</p>
                    <div class="flex items-center text-[11px] font-black text-emerald-600 uppercase">
                        <i class="fas fa-shield-alt mr-1 text-[8px]"></i> 100% Authorized
                    </div>
                </div>
                <div class="p-5 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-[1.8rem] shadow-xl shadow-emerald-500/30 text-white transform group-hover:rotate-6 transition-transform">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Platform Yield -->
        <div class="relative group bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm transition-all duration-500 hover:shadow-2xl hover:-translate-y-2 hover:border-amber-200 overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-amber-50 -mr-16 -mt-16 rounded-full transition-transform duration-700 group-hover:scale-150"></div>
            <div class="relative flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3">Monthly Yield</p>
                    <p class="text-3xl font-black text-slate-900 leading-none mb-3">NPR {{ number_format(\App\Models\Subscription::where('status', 'active')->sum('amount'), 0) }}</p>
                    <div class="flex items-center text-[10px] font-black text-amber-600 uppercase">
                        <i class="fas fa-chart-line mr-1 text-[8px]"></i> 24% Growth
                    </div>
                </div>
                <div class="p-5 bg-gradient-to-br from-amber-500 to-orange-600 rounded-[1.8rem] shadow-xl shadow-amber-500/30 text-white transform group-hover:rotate-6 transition-transform">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Active Licenses -->
        <div class="relative group bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm transition-all duration-500 hover:shadow-2xl hover:-translate-y-2 hover:border-indigo-200 overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-50 -mr-16 -mt-16 rounded-full transition-transform duration-700 group-hover:scale-150"></div>
            <div class="relative flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3">Active Licenses</p>
                    <p class="text-5xl font-black text-slate-900 leading-none mb-3">{{ \App\Models\Subscription::where('status', 'active')->count() }}</p>
                    <div class="flex items-center text-[10px] font-black text-indigo-600 uppercase">
                        <i class="fas fa-star mr-1 text-[8px]"></i> Premium Tier
                    </div>
                </div>
                <div class="p-5 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-[1.8rem] shadow-xl shadow-indigo-500/30 text-white transform group-hover:rotate-6 transition-transform">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section with Date Filter -->
<div class="mb-12">
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-10 px-2">
        <div class="flex items-center gap-3">
            <div class="w-1.5 h-8 bg-indigo-600 rounded-full shadow-lg shadow-indigo-500/50"></div>
            <h2 class="text-2xl font-black text-slate-900 tracking-tight uppercase text-xs tracking-[0.3em]">Analytics & Trends</h2>
        </div>
        <div class="relative mt-4 md:mt-0">
            <select id="dateFilter" class="appearance-none bg-white border border-slate-100 rounded-2xl px-6 py-3 pr-12 text-[10px] font-black uppercase tracking-widest text-slate-700 hover:border-blue-200 focus:outline-none focus:ring-4 focus:ring-blue-500/5 shadow-sm transition-all cursor-pointer">
                <option value="7">Last 7 days</option>
                <option value="30">Last 30 days</option>
                <option value="90">Last 3 months</option>
                <option value="180" selected>Last 6 months</option>
                <option value="365">Last year</option>
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-blue-500">
                <i class="fas fa-chevron-down text-[10px]"></i>
            </div>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
        <!-- Growth Chart -->
        <div class="relative group bg-white p-10 rounded-[3rem] border border-slate-100 shadow-sm transition-all duration-500 hover:shadow-2xl overflow-hidden">
            <div class="absolute -top-12 -right-12 w-48 h-48 bg-blue-50/50 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-700"></div>
            
            <div class="relative flex items-center justify-between mb-8">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-blue-50 text-blue-500 rounded-2xl flex items-center justify-center border border-blue-100 shadow-sm">
                        <i class="fas fa-chart-area"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-slate-900 tracking-tight">Growth Trends</h3>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Ecosystem Expansion</p>
                    </div>
                </div>
                <div class="flex items-center gap-6">
                    <div class="flex items-center gap-2">
                        <div class="w-1.5 h-1.5 bg-blue-500 rounded-full"></div>
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Clinics</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-1.5 h-1.5 bg-sky-400 rounded-full"></div>
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Users</span>
                    </div>
                </div>
            </div>
            
            <div class="h-80 relative z-10">
                <canvas id="growthChart"></canvas>
            </div>
        </div>

        <!-- Revenue Chart -->
        <div class="relative group bg-white p-10 rounded-[3rem] border border-slate-100 shadow-sm transition-all duration-500 hover:shadow-2xl overflow-hidden">
            <div class="absolute -bottom-12 -right-12 w-48 h-48 bg-indigo-50/50 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-700"></div>
            
            <div class="relative flex items-center justify-between mb-8">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-indigo-50 text-indigo-500 rounded-2xl flex items-center justify-center border border-indigo-100 shadow-sm">
                        <i class="fas fa-hand-holding-usd"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-slate-900 tracking-tight">Revenue Trends</h3>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Fiscal Performance</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-1.5 h-1.5 bg-indigo-600 rounded-full"></div>
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Revenue (NPR)</span>
                </div>
            </div>
            
            <div class="h-80 relative z-10">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Main Content Grid -->
<div class="space-y-8">
    <!-- Two Column Layout for Stats and Actions -->
    <div class="grid grid-cols-1 xl:grid-cols-4 gap-8">
        <!-- Quick Actions (Spans 3 columns) -->
        <div class="xl:col-span-3">
            <h2 class="text-2xl font-black text-slate-900 mb-6 flex items-center gap-3 uppercase text-[11px] tracking-[0.3em]">
                <i class="fas fa-bolt text-amber-500"></i>
                Operational Hub
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- CRM & Pipeline -->
                <div class="relative group bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm transition-all duration-500 hover:shadow-2xl hover:-translate-y-2 hover:border-blue-200 overflow-hidden">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-blue-50 -mr-12 -mt-12 rounded-full transition-transform duration-700 group-hover:scale-150"></div>
                    <div class="relative flex items-center justify-between">
                        <div>
                            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-2">Platform CRM</p>
                            <h3 class="text-2xl font-black text-slate-900 leading-tight mb-4">Pipeline Hub</h3>
                            <a href="{{ route('superadmin.crm.leads') }}" class="btn-premium-ghost py-2 !rounded-xl !text-[11px]">
                                Open CRM <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                            </a>
                        </div>
                        <div class="w-16 h-16 rounded-2xl shadow-xl flex items-center justify-center text-white transform group-hover:rotate-6 transition-transform" style="background: linear-gradient(135deg, #2563eb, #4f46e5);">
                            <svg class="w-8 h-8" fill="none" stroke="white" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Content Management -->
                <div class="relative group bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm transition-all duration-500 hover:shadow-2xl hover:-translate-y-2 hover:border-purple-200 overflow-hidden">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-purple-50 -mr-12 -mt-12 rounded-full transition-transform duration-700 group-hover:scale-150"></div>
                    <div class="relative flex items-center justify-between">
                        <div>
                            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-2">World System</p>
                            <h3 class="text-2xl font-black text-slate-900 leading-tight mb-4">Content Kit</h3>
                            <a href="{{ route('superadmin.content.landing') }}" class="btn-premium-outline py-2 !rounded-xl !text-[11px] !bg-white">
                                Edit Web <i class="fas fa-edit ml-2"></i>
                            </a>
                        </div>
                        <div class="w-16 h-16 rounded-2xl shadow-xl flex items-center justify-center text-white transform group-hover:-rotate-6 transition-transform" style="background: linear-gradient(135deg, #9333ea, #db2777);">
                            <svg class="w-8 h-8" fill="none" stroke="white" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Platform Core Management -->
                <div class="relative group bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm transition-all duration-500 hover:shadow-2xl hover:-translate-y-2 hover:border-sky-200 overflow-hidden">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-sky-50/50 -mr-12 -mt-12 rounded-full transition-transform duration-700 group-hover:scale-150"></div>
                    <div class="relative flex items-center justify-between">
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Cloud Infrastructure</p>
                            <h3 class="text-xl font-black text-slate-900 leading-tight mb-4">Core Nodes</h3>
                            <a href="{{ route('clinics.index') }}" class="btn-premium-outline py-2 !rounded-xl !text-[10px] !bg-white">
                                Global Config <i class="fas fa-server ml-2"></i>
                            </a>
                        </div>
                        <div class="w-16 h-16 rounded-2xl shadow-xl flex items-center justify-center text-white transform group-hover:scale-110 transition-transform" style="background: linear-gradient(135deg, #0ea5e9, #2563eb);">
                            <svg class="w-8 h-8" fill="none" stroke="white" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Intelligence -->
                <div class="relative group bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm transition-all duration-500 hover:shadow-2xl hover:-translate-y-2 hover:border-emerald-200 overflow-hidden">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-emerald-50/50 -mr-12 -mt-12 rounded-full transition-transform duration-700 group-hover:scale-150"></div>
                    <div class="relative flex items-center justify-between">
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Analytical Kernel</p>
                            <h3 class="text-xl font-black text-slate-900 leading-tight mb-4">Data Stream</h3>
                            <a href="{{ route('superadmin.analytics') }}" class="btn-premium-outline py-2 !rounded-xl !text-[10px] !bg-white">
                                Audit Stats <i class="fas fa-chart-line ml-2"></i>
                            </a>
                        </div>
                        <div class="w-16 h-16 rounded-2xl shadow-xl flex items-center justify-center text-white transform group-hover:-rotate-12 transition-transform" style="background: linear-gradient(135deg, #10b981, #059669);">
                            <svg class="w-8 h-8" fill="none" stroke="white" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- CRM Snapshot (Side column on XL, Full width below) -->
        <div class="xl:col-span-1">
            <div class="flex items-center gap-3 mb-6 px-2">
                <div class="w-1 h-6 bg-blue-600 rounded-full"></div>
                <h2 class="text-2xl font-black text-slate-900 tracking-tight uppercase text-[10px] tracking-[0.3em]">CRM Snapshot</h2>
            </div>
            
            <div class="space-y-6">
                <!-- Total Leads -->
                <div class="relative group bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm transition-all duration-500 hover:shadow-xl overflow-hidden">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-blue-50 -mr-12 -mt-12 rounded-full transition-transform duration-700 group-hover:scale-150"></div>
                    <div class="relative flex items-center justify-between">
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Incoming Leads</p>
                            <h4 class="text-3xl font-black text-slate-900 leading-none">{{ \App\Models\Lead::count() ?? 0 }}</h4>
                            <div class="mt-2 text-[10px] font-black text-blue-600 uppercase">
                                <span class="px-2 py-0.5 bg-blue-50 rounded-lg">Realtime Feed</span>
                            </div>
                        </div>
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl shadow-lg flex items-center justify-center text-white transform group-hover:rotate-12 transition-transform">
                            <i class="fas fa-user-plus text-lg"></i>
                        </div>
                    </div>
                </div>

                <!-- Conversion -->
                <div class="relative group bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm transition-all duration-500 hover:shadow-xl overflow-hidden">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-emerald-50 -mr-12 -mt-12 rounded-full transition-transform duration-700 group-hover:scale-150"></div>
                    <div class="relative flex items-center justify-between">
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Yield Performance</p>
                            <h4 class="text-3xl font-black text-slate-900 leading-none">
                                {{ \App\Models\Lead::count() > 0 ? round((\App\Models\Lead::where('status', 'converted')->count() / \App\Models\Lead::count()) * 100, 1) : 0 }}%
                            </h4>
                            <div class="mt-2 text-[10px] font-black text-emerald-600 uppercase">
                                <span class="px-2 py-0.5 bg-emerald-50 rounded-lg">High Conversion</span>
                            </div>
                        </div>
                        <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl shadow-lg flex items-center justify-center text-white transform group-hover:-rotate-12 transition-transform">
                            <i class="fas fa-bullseye text-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity - Moved to Bottom -->
    <div class="mt-12">
        <div class="flex items-end justify-between mb-8 px-2">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-1.5 h-8 bg-indigo-600 rounded-full shadow-lg shadow-indigo-500/50"></div>
                    <h2 class="text-2xl font-black text-slate-900 tracking-tight uppercase text-xs tracking-[0.3em]">Network Pulse</h2>
                </div>
                <p class="text-sm text-slate-500 font-medium">Real-time ecosystem updates</p>
            </div>
            <button class="btn-premium-ghost !rounded-2xl !px-6">Archive Explorer <i class="fas fa-history ml-2"></i></button>
        </div>
        
        <div class="bg-white rounded-[2.5rem] border border-slate-200 overflow-hidden shadow-sm">
            <div class="p-8">
                <div class="flow-root">
                    <ul role="list" class="-mb-8">
                        @forelse($recentClinics as $clinic)
                        <li>
                            <div class="relative pb-8">
                                @if(!$loop->last)
                                <span class="absolute left-6 top-6 -ml-px h-full w-0.5 bg-slate-100" aria-hidden="true"></span>
                                @endif
                                <div class="relative flex items-start space-x-4">
                                    <div class="relative">
                                        <div class="h-12 w-12 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center ring-4 ring-white shadow-sm overflow-hidden">
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($clinic->name) }}&color=3B82F6&background=DBEAFE" alt="">
                                        </div>
                                        <span class="absolute -bottom-1 -right-1 bg-white rounded-full p-1 shadow-sm">
                                            <div class="w-3 h-3 bg-emerald-500 rounded-full border-2 border-white"></div>
                                        </span>
                                    </div>
                                    <div class="min-w-0 flex-1 pt-1">
                                        <div class="flex justify-between">
                                            <div>
                                                <p class="text-sm font-black text-slate-900">{{ $clinic->name }}</p>
                                                <p class="text-xs font-bold text-slate-400 mt-0.5 uppercase tracking-wider">New Site Provisioned • {{ $clinic->city ?? 'Unknown' }}</p>
                                            </div>
                                            <time class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $clinic->created_at->diffForHumans() }}</time>
                                        </div>
                                        <div class="mt-3 p-5 bg-slate-50 rounded-2xl border border-slate-100/50">
                                            <p class="text-base text-slate-600 font-medium leading-relaxed">
                                                Physical site established at <span class="text-slate-900 font-bold">{{ $clinic->address }}</span>. 
                                                Initial licensing and multi-tenancy nodes have been initialized successfully.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        @empty
                        <div class="py-12 text-center">
                            <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-slate-100">
                                <i class="fas fa-satellite-dish text-slate-300"></i>
                            </div>
                            <p class="text-sm font-bold text-slate-400 uppercase tracking-widest">No recent platform pings</p>
                        </div>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Chart data from backend
let chartData = @json($chartData);
let growthChart, revenueChart;

// Initialize charts
function initializeCharts() {
    // Growth Chart
    const growthCtx = document.getElementById('growthChart').getContext('2d');
    growthChart = new Chart(growthCtx, {
        type: 'line',
        data: {
            labels: chartData.map(d => d.month),
            datasets: [{
                label: 'New Clinics',
                data: chartData.map(d => d.clinics),
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                fill: true,
                borderWidth: 3,
                pointBackgroundColor: 'rgb(59, 130, 246)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 6,
                pointHoverRadius: 8
            }, {
                label: 'New Users',
                data: chartData.map(d => d.users),
                borderColor: 'rgb(14, 165, 233)',
                backgroundColor: 'rgba(14, 165, 233, 0.1)',
                tension: 0.4,
                fill: true,
                borderWidth: 3,
                pointBackgroundColor: 'rgb(14, 165, 233)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 6,
                pointHoverRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    borderColor: 'rgba(255, 255, 255, 0.1)',
                    borderWidth: 1,
                    cornerRadius: 8,
                    displayColors: true
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(148, 163, 184, 0.1)',
                        drawBorder: false
                    },
                    ticks: {
                        color: 'rgb(100, 116, 139)',
                        font: {
                            size: 12
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: 'rgb(100, 116, 139)',
                        font: {
                            size: 12
                        }
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            }
        }
    });

    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    const revenueValues = chartData.map(d => d.revenue);
    const hasRevenueData = revenueValues.some(v => v > 0);

    revenueChart = new Chart(revenueCtx, {
        type: 'bar',
        data: {
            labels: chartData.map(d => d.month),
            datasets: [{
                label: 'Revenue (NPR)',
                data: revenueValues,
                backgroundColor: 'rgba(79, 70, 229, 0.8)',
                borderColor: 'rgb(79, 70, 229)',
                borderWidth: 2,
                borderRadius: 8,
                borderSkipped: false,
                hoverBackgroundColor: 'rgba(79, 70, 229, 0.9)',
                hoverBorderColor: 'rgb(79, 70, 229)'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    enabled: hasRevenueData,
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    borderColor: 'rgba(255, 255, 255, 0.1)',
                    borderWidth: 1,
                    cornerRadius: 8,
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return 'Revenue: NPR ' + context.parsed.y.toLocaleString();
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    suggestedMax: 1000,
                    grid: {
                        color: 'rgba(148, 163, 184, 0.1)',
                        drawBorder: false
                    },
                    ticks: {
                        color: 'rgb(100, 116, 139)',
                        font: {
                            size: 12
                        },
                        callback: function(value) {
                            return 'NPR ' + value.toLocaleString();
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: 'rgb(100, 116, 139)',
                        font: {
                            size: 12
                        }
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            }
        }
    });

    if (!hasRevenueData) {
        // Add a "No Data" message overlay if chart is empty
        revenueCtx.font = "14px Inter, sans-serif";
        revenueCtx.fillStyle = "#94a3b8";
        revenueCtx.textAlign = "center";
        revenueCtx.fillText("No subscription revenue data recorded for this period", revenueCtx.canvas.width/2, revenueCtx.canvas.height/2);
    }
}

// Date filter functionality
document.getElementById('dateFilter').addEventListener('change', function() {
    const days = parseInt(this.value);
    fetchChartData(days);
});

// Fetch chart data via AJAX
function fetchChartData(days) {
    fetch(`/superadmin/chart-data?days=${days}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        updateCharts(data);
    })
    .catch(error => {
        console.error('Error fetching chart data:', error);
    });
}

// Function to update charts with new data
function updateCharts(newData) {
    chartData = newData;
    
    // Update growth chart
    growthChart.data.labels = chartData.map(d => d.month);
    growthChart.data.datasets[0].data = chartData.map(d => d.clinics);
    growthChart.data.datasets[1].data = chartData.map(d => d.users);
    growthChart.update('active');
    
    // Update revenue chart
    revenueChart.data.labels = chartData.map(d => d.month);
    revenueChart.data.datasets[0].data = chartData.map(d => d.revenue);
    revenueChart.update('active');
}

// Initialize charts when page loads
document.addEventListener('DOMContentLoaded', function() {
    initializeCharts();
});
</script>
@endsection