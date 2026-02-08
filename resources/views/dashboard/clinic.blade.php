@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Clinic Dashboard</h1>
        <p class="text-gray-600">{{ $clinic ? $clinic->name : 'No clinic assigned' }}</p>
    </div>

    <!-- Nepali Date Widget -->
    <div class="mb-6">
        @include('components.nepali-date-widget')
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="stat-card group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Total Patients</p>
                    <p class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">{{ $stats['total_patients'] }}</p>
                    <p class="text-xs text-green-600 mt-1">↗ +12% from last month</p>
                </div>
                <div class="p-4 rounded-2xl bg-gradient-to-br from-blue-100 to-indigo-100 group-hover:from-blue-200 group-hover:to-indigo-200 transition-all duration-300">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="stat-card group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Today's Appointments</p>
                    <p class="text-3xl font-bold bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent">{{ $stats['todays_appointments'] }}</p>
                    <p class="text-xs text-green-600 mt-1">{{ $stats['todays_appointments'] > 0 ? 'Active schedule' : 'No appointments' }}</p>
                </div>
                <div class="p-4 rounded-2xl bg-gradient-to-br from-green-100 to-emerald-100 group-hover:from-green-200 group-hover:to-emerald-200 transition-all duration-300">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="stat-card group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Pending Appointments</p>
                    <p class="text-3xl font-bold bg-gradient-to-r from-yellow-600 to-orange-600 bg-clip-text text-transparent">{{ $stats['pending_appointments'] }}</p>
                    <p class="text-xs text-yellow-600 mt-1">{{ $stats['pending_appointments'] > 0 ? 'Needs attention' : 'All clear' }}</p>
                </div>
                <div class="p-4 rounded-2xl bg-gradient-to-br from-yellow-100 to-orange-100 group-hover:from-yellow-200 group-hover:to-orange-200 transition-all duration-300">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="stat-card group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Monthly Revenue</p>
                    <p class="text-3xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">NPR {{ number_format($stats['monthly_revenue'], 0) }}</p>
                    <p class="text-xs text-purple-600 mt-1">{{ $stats['monthly_revenue'] > 0 ? '↗ Growing' : 'Start earning' }}</p>
                </div>
                <div class="p-4 rounded-2xl bg-gradient-to-br from-purple-100 to-pink-100 group-hover:from-purple-200 group-hover:to-pink-200 transition-all duration-300">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="card">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-900">Quick Actions</h2>
                <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
            </div>
            @if($clinic)
                <div class="grid grid-cols-2 gap-4">
                    <a href="{{ route('patients.create') }}" class="group flex items-center justify-center p-4 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl border border-blue-100 hover:from-blue-100 hover:to-indigo-100 transition-all duration-300 transform hover:scale-105">
                        <div class="text-center">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-lg flex items-center justify-center mx-auto mb-2 group-hover:scale-110 transition-transform">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            </div>
                            <span class="text-sm font-medium text-gray-700 group-hover:text-blue-700">Add Patient</span>
                        </div>
                    </a>
                    <a href="{{ route('clinic.appointments.create') }}" class="group flex items-center justify-center p-4 bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl border border-green-100 hover:from-green-100 hover:to-emerald-100 transition-all duration-300 transform hover:scale-105">
                        <div class="text-center">
                            <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-500 rounded-lg flex items-center justify-center mx-auto mb-2 group-hover:scale-110 transition-transform">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <span class="text-sm font-medium text-gray-700 group-hover:text-green-700">New Appointment</span>
                        </div>
                    </a>
                    <a href="{{ route('treatment-plans.create') }}" class="group flex items-center justify-center p-4 bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl border border-purple-100 hover:from-purple-100 hover:to-pink-100 transition-all duration-300 transform hover:scale-105">
                        <div class="text-center">
                            <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-500 rounded-lg flex items-center justify-center mx-auto mb-2 group-hover:scale-110 transition-transform">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                            </div>
                            <span class="text-sm font-medium text-gray-700 group-hover:text-purple-700">Treatment Plan</span>
                        </div>
                    </a>
                    <a href="{{ route('invoices.create') }}" class="group flex items-center justify-center p-4 bg-gradient-to-br from-yellow-50 to-orange-50 rounded-xl border border-yellow-100 hover:from-yellow-100 hover:to-orange-100 transition-all duration-300 transform hover:scale-105">
                        <div class="text-center">
                            <div class="w-10 h-10 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-lg flex items-center justify-center mx-auto mb-2 group-hover:scale-110 transition-transform">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                            </div>
                            <span class="text-sm font-medium text-gray-700 group-hover:text-yellow-700">Create Invoice</span>
                        </div>
                    </a>
                    <a href="{{ route('staff.create') }}" class="group flex items-center justify-center p-4 bg-gradient-to-br from-indigo-50 to-blue-50 rounded-xl border border-indigo-100 hover:from-indigo-100 hover:to-blue-100 transition-all duration-300 transform hover:scale-105">
                        <div class="text-center">
                            <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-blue-500 rounded-lg flex items-center justify-center mx-auto mb-2 group-hover:scale-110 transition-transform">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            </div>
                            <span class="text-sm font-medium text-gray-700 group-hover:text-indigo-700">Add Staff</span>
                        </div>
                    </a>
                    <a href="{{ route('emails.compose') }}" class="group flex items-center justify-center p-4 bg-gradient-to-br from-teal-50 to-cyan-50 rounded-xl border border-teal-100 hover:from-teal-100 hover:to-cyan-100 transition-all duration-300 transform hover:scale-105">
                        <div class="text-center">
                            <div class="w-10 h-10 bg-gradient-to-br from-teal-500 to-cyan-500 rounded-lg flex items-center justify-center mx-auto mb-2 group-hover:scale-110 transition-transform">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            </div>
                            <span class="text-sm font-medium text-gray-700 group-hover:text-teal-700">Send Email</span>
                        </div>
                    </a>
                    <a href="{{ route('reports.index') }}" class="group flex items-center justify-center p-4 bg-gradient-to-br from-gray-50 to-slate-50 rounded-xl border border-gray-100 hover:from-gray-100 hover:to-slate-100 transition-all duration-300 transform hover:scale-105">
                        <div class="text-center">
                            <div class="w-10 h-10 bg-gradient-to-br from-gray-500 to-slate-500 rounded-lg flex items-center justify-center mx-auto mb-2 group-hover:scale-110 transition-transform">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                            </div>
                            <span class="text-sm font-medium text-gray-700 group-hover:text-gray-700">View Reports</span>
                        </div>
                    </a>
                </div>
            @else
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.268 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>
                    </div>
                    <p class="text-gray-500 text-sm">No actions available. Contact administrator to assign a clinic to your account.</p>
                </div>
            @endif
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Nepal Market Features</h2>
            <div class="space-y-3">
                <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                    <span class="text-sm font-medium text-green-800">NPR Currency Support</span>
                    <span class="text-xs bg-green-200 text-green-800 px-2 py-1 rounded-full">Active</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                    <span class="text-sm font-medium text-blue-800">SMS Notifications</span>
                    <span class="text-xs bg-blue-200 text-blue-800 px-2 py-1 rounded-full">Ready</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-purple-50 rounded-lg">
                    <span class="text-sm font-medium text-purple-800">Local Billing</span>
                    <span class="text-xs bg-purple-200 text-purple-800 px-2 py-1 rounded-full">Enabled</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg">
                    <span class="text-sm font-medium text-yellow-800">Multi-language Support</span>
                    <span class="text-xs bg-yellow-200 text-yellow-800 px-2 py-1 rounded-full">Coming Soon</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection