@extends('layouts.app')

@section('content')
<!-- Welcome Section - Full Width -->
<div class="mb-8">
    <div class="bg-gradient-to-r from-blue-50 via-indigo-50 to-purple-50 rounded-2xl p-8 border border-blue-100 shadow-sm">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
            <div class="mb-4 lg:mb-0">
                <h1 class="text-3xl lg:text-4xl font-bold text-slate-900 mb-2">
                    Good morning, {{ auth()->user()->name }} 👋
                </h1>
                <p class="text-slate-600 text-lg">Here's what's happening with your platform today.</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-4">
                <div class="flex items-center space-x-2 px-4 py-2 bg-blue-100 rounded-full border border-blue-200">
                    <div class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></div>
                    <span class="text-blue-700 font-medium text-sm">All systems operational</span>
                </div>
                <div class="flex items-center space-x-2 px-4 py-2 bg-blue-100 rounded-full border border-blue-200">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    @php
                        $nepaliDate = \App\Helpers\NepaliDateHelper::getCurrentNepaliDate();
                    @endphp
                    <span class="text-blue-700 font-medium text-sm">{{ $nepaliDate['formatted'] ?? '१ मंसिर २०८२' }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Platform Overview - Full Width -->
<div class="mb-8">
    <h2 class="text-2xl font-bold text-slate-900 mb-6">Platform Overview</h2>
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 stagger-in">
        <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-slate-200 animate-card-hover hover:border-blue-300">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-slate-500 truncate">Total Clinics</dt>
                            <dd class="text-3xl font-bold text-slate-900">{{ \App\Models\Clinic::count() }}</dd>
                            <dd class="flex items-baseline text-sm font-semibold text-blue-600 mt-1">
                                <svg class="self-center flex-shrink-0 h-4 w-4 text-blue-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                                +12% from last month
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-slate-200 hover:shadow-lg transition-all duration-200 hover:border-emerald-300">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-14 h-14 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-slate-500 truncate">Total Users</dt>
                            <dd class="text-3xl font-bold text-slate-900">{{ \App\Models\User::count() }}</dd>
                            <dd class="flex items-baseline text-sm font-semibold text-sky-600 mt-1">
                                <svg class="self-center flex-shrink-0 h-4 w-4 text-sky-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                                +18% from last month
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-slate-200 hover:shadow-lg transition-all duration-200 hover:border-amber-300">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-14 h-14 bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-slate-500 truncate">Monthly Revenue</dt>
                            <dd class="text-3xl font-bold text-slate-900">NPR {{ number_format(\App\Models\Subscription::where('status', 'active')->sum('amount'), 0) }}</dd>
                            <dd class="flex items-baseline text-sm font-semibold text-cyan-600 mt-1">
                                <svg class="self-center flex-shrink-0 h-4 w-4 text-cyan-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                                +24% from last month
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-slate-200 hover:shadow-lg transition-all duration-200 hover:border-purple-300">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-slate-500 truncate">Active Subscriptions</dt>
                            <dd class="text-3xl font-bold text-slate-900">{{ \App\Models\Subscription::where('status', 'active')->count() }}</dd>
                            <dd class="flex items-baseline text-sm font-semibold text-indigo-600 mt-1">
                                <svg class="self-center flex-shrink-0 h-4 w-4 text-indigo-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                                +8% from last month
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section with Date Filter -->
<div class="mb-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <h2 class="text-2xl font-bold text-slate-900 mb-4 sm:mb-0">Analytics & Trends</h2>
        <div class="relative">
            <select id="dateFilter" class="appearance-none bg-white border border-slate-300 rounded-lg px-4 py-2 pr-8 text-sm font-medium text-slate-700 hover:border-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="7">Last 7 days</option>
                <option value="30">Last 30 days</option>
                <option value="90">Last 3 months</option>
                <option value="180" selected>Last 6 months</option>
                <option value="365">Last year</option>
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-slate-700">
                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                </svg>
            </div>
        </div>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Growth Chart -->
        <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-200 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-semibold text-slate-900">Growth Trends</h3>
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                    <span class="text-sm text-slate-600">Clinics</span>
                    <div class="w-3 h-3 bg-sky-500 rounded-full ml-4"></div>
                    <span class="text-sm text-slate-600">Users</span>
                </div>
            </div>
            <div class="h-80">
                <canvas id="growthChart"></canvas>
            </div>
        </div>

        <!-- Revenue Chart -->
        <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-200 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-semibold text-slate-900">Revenue Trends</h3>
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-indigo-500 rounded-full"></div>
                    <span class="text-sm text-slate-600">Revenue (NPR)</span>
                </div>
            </div>
            <div class="h-80">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Two Column Layout -->
<div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
    <!-- Left Column - Quick Actions -->
    <div class="xl:col-span-2 space-y-8">

        <!-- Quick Actions -->
        <div>
            <h2 class="text-2xl font-bold text-slate-900 mb-6">Quick Actions</h2>
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <!-- CRM Management -->
                <div class="relative group bg-white p-8 rounded-2xl shadow-sm border border-slate-200 hover:shadow-lg hover:border-blue-300 transition-all duration-300 cursor-pointer">
                    <a href="{{ route('superadmin.crm.leads') }}" class="absolute inset-0"></a>
                    <div class="flex items-center space-x-5">
                        <div class="flex-shrink-0">
                            <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-sky-600 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300 shadow-lg">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-xl font-semibold text-slate-900 group-hover:text-blue-600 transition-colors">CRM & Leads</h3>
                            <p class="text-slate-600 mt-2">Manage leads, sales pipeline, and customer relationships</p>
                        </div>
                    </div>
                    <div class="mt-6">
                        <span class="inline-flex items-center text-blue-600 font-medium group-hover:text-blue-700">
                            Manage CRM 
                            <svg class="ml-2 w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </span>
                    </div>
                </div>

                <!-- Content Management -->
                <div class="relative group bg-white p-8 rounded-2xl shadow-sm border border-slate-200 hover:shadow-lg hover:border-blue-300 transition-all duration-300 cursor-pointer">
                    <a href="{{ route('superadmin.content.landing') }}" class="absolute inset-0"></a>
                    <div class="flex items-center space-x-5">
                        <div class="flex-shrink-0">
                            <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300 shadow-lg">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-xl font-semibold text-slate-900 group-hover:text-blue-600 transition-colors">Content Management</h3>
                            <p class="text-slate-600 mt-2">Manage landing page, blog posts, and testimonials</p>
                        </div>
                    </div>
                    <div class="mt-6">
                        <span class="inline-flex items-center text-blue-600 font-medium group-hover:text-blue-700">
                            Manage content 
                            <svg class="ml-2 w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </span>
                    </div>
                </div>

                <!-- Platform Management -->
                <div class="relative group bg-white p-8 rounded-2xl shadow-sm border border-slate-200 hover:shadow-lg hover:border-sky-300 transition-all duration-300 cursor-pointer">
                    <a href="{{ route('clinics.index') }}" class="absolute inset-0"></a>
                    <div class="flex items-center space-x-5">
                        <div class="flex-shrink-0">
                            <div class="w-16 h-16 bg-gradient-to-br from-sky-500 to-sky-600 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300 shadow-lg">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-xl font-semibold text-slate-900 group-hover:text-sky-600 transition-colors">Platform Management</h3>
                            <p class="text-slate-600 mt-2">Manage clinics, users, and system settings</p>
                        </div>
                    </div>
                    <div class="mt-6">
                        <span class="inline-flex items-center text-sky-600 font-medium group-hover:text-sky-700">
                            Manage platform 
                            <svg class="ml-2 w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </span>
                    </div>
                </div>

                <!-- Analytics & Reports -->
                <div class="relative group bg-white p-8 rounded-2xl shadow-sm border border-slate-200 hover:shadow-lg hover:border-indigo-300 transition-all duration-300 cursor-pointer">
                    <a href="{{ route('superadmin.analytics') }}" class="absolute inset-0"></a>
                    <div class="flex items-center space-x-5">
                        <div class="flex-shrink-0">
                            <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300 shadow-lg">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-xl font-semibold text-slate-900 group-hover:text-indigo-600 transition-colors">Analytics & Reports</h3>
                            <p class="text-slate-600 mt-2">View detailed analytics and generate reports</p>
                        </div>
                    </div>
                    <div class="mt-6">
                        <span class="inline-flex items-center text-indigo-600 font-medium group-hover:text-indigo-700">
                            View analytics 
                            <svg class="ml-2 w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column - CRM Stats and Recent Activity -->
    <div class="space-y-8">
        <!-- CRM Overview -->
        <div>
            <h2 class="text-2xl font-bold text-slate-900 mb-6">CRM Overview</h2>
            <div class="space-y-4">
                <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-slate-200 hover:shadow-md transition-shadow duration-200">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-14 h-14 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-slate-500 truncate">Total Leads</dt>
                                    <dd class="text-3xl font-bold text-slate-900">{{ \App\Models\Lead::count() ?? 0 }}</dd>
                                    <dd class="text-sm text-slate-600 mt-1">+5 this week</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-slate-200 hover:shadow-md transition-shadow duration-200">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-sky-600 rounded-xl flex items-center justify-center shadow-lg">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-slate-500 truncate">Converted Leads</dt>
                                    <dd class="text-3xl font-bold text-slate-900">{{ \App\Models\Lead::where('status', 'converted')->count() ?? 0 }}</dd>
                                    <dd class="text-sm text-slate-600 mt-1">{{ \App\Models\Lead::count() > 0 ? round((\App\Models\Lead::where('status', 'converted')->count() / \App\Models\Lead::count()) * 100, 1) : 0 }}% conversion rate</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-slate-200 hover:shadow-md transition-shadow duration-200">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-14 h-14 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-slate-500 truncate">Active Campaigns</dt>
                                    <dd class="text-3xl font-bold text-slate-900">{{ \App\Models\Campaign::where('status', 'active')->count() ?? 0 }}</dd>
                                    <dd class="text-sm text-slate-600 mt-1">Running campaigns</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-slate-200 hover:shadow-md transition-shadow duration-200">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-14 h-14 bg-gradient-to-br from-cyan-500 to-sky-600 rounded-xl flex items-center justify-center shadow-lg">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-slate-500 truncate">Emails Sent</dt>
                                    <dd class="text-3xl font-bold text-slate-900">{{ \App\Models\EmailLog::count() ?? 0 }}</dd>
                                    <dd class="text-sm text-slate-600 mt-1">Total sent</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white shadow-sm rounded-xl border border-slate-200 hover:shadow-md transition-shadow duration-200">
            <div class="px-6 py-5 border-b border-slate-200">
                <h3 class="text-xl font-semibold text-slate-900">Recent Activity</h3>
                <p class="text-sm text-slate-600 mt-1">Latest updates from your platform</p>
            </div>
            <div class="p-4 lg:p-6">
                <div class="flow-root">
                    <ul role="list" class="-mb-8">
                        @forelse($recentClinics as $clinic)
                        <li>
                            <div class="relative pb-8">
                                @if(!$loop->last)
                                <span class="absolute left-5 top-5 -ml-px h-full w-0.5 bg-slate-200" aria-hidden="true"></span>
                                @endif
                                <div class="relative flex items-start space-x-3">
                                    <div class="relative">
                                        <img class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-100 ring-8 ring-white" src="https://ui-avatars.com/api/?name={{ urlencode($clinic->name) }}&color=3B82F6&background=DBEAFE" alt="">
                                        <span class="absolute -bottom-0.5 -right-1 bg-white rounded-tl px-0.5 py-px">
                                            <svg class="h-3 w-3 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.236 4.53L7.53 10.53a.75.75 0 00-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div>
                                            <div class="text-sm">
                                                <a href="#" class="font-medium text-slate-900 hover:text-blue-600">{{ $clinic->name }}</a>
                                            </div>
                                            <p class="mt-0.5 text-sm text-slate-500">New clinic registered</p>
                                        </div>
                                        <div class="mt-2 text-sm text-slate-700">
                                            <p>{{ $clinic->address }}</p>
                                        </div>
                                    </div>
                                    <div class="flex-shrink-0 self-center">
                                        <time class="text-sm text-slate-500" datetime="{{ $clinic->created_at->toISOString() }}">{{ $clinic->created_at->diffForHumans() }}</time>
                                    </div>
                                </div>
                            </div>
                        </li>
                        @empty
                        <li class="text-center py-8">
                            <div class="text-slate-400">
                                <svg class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-slate-900">No recent activity</h3>
                                <p class="mt-1 text-sm text-slate-500">Get started by adding your first clinic.</p>
                            </div>
                        </li>
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
    revenueChart = new Chart(revenueCtx, {
        type: 'bar',
        data: {
            labels: chartData.map(d => d.month),
            datasets: [{
                label: 'Revenue (NPR)',
                data: chartData.map(d => d.revenue),
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