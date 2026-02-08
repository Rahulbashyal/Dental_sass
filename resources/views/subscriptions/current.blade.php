@extends('layouts.app')

@section('page-title', 'Subscription Management')

@section('content')
<div class="space-y-8">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-gray-900 to-gray-700 bg-clip-text text-transparent">Subscription Management</h1>
            <p class="text-gray-600 mt-2">Manage your clinic's subscription plan and usage</p>
        </div>
        @if($subscription)
            <div class="flex items-center space-x-3">
                <div class="flex items-center space-x-2 px-4 py-2 bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg border border-green-200">
                    <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                    <span class="text-green-800 font-medium text-sm">Active Subscription</span>
                </div>
            </div>
        @endif
    </div>

    @if($subscription)
        <!-- Current Plan Overview -->
        <div class="stat-card group">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 bg-gradient-to-br {{ $color[0] }} {{ $color[1] }} rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                        @if($subscription->plan->slug === 'basic')
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        @elseif($subscription->plan->slug === 'professional')
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                        @else
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                        @endif
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ $subscription->plan->name }} Plan</h2>
                        <p class="text-gray-600">{{ $subscription->plan->description }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-full {{ $subscription->status === 'active' ? 'bg-green-100 text-green-800' : ($subscription->status === 'trial' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800') }}">
                        @if($subscription->status === 'active')
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        @elseif($subscription->status === 'trial')
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        @endif
                        {{ ucfirst($subscription->status) }}
                    </span>
                </div>
            </div>

            <div class="grid md:grid-cols-3 gap-6">
                <div class="text-center p-6 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl border border-blue-100">
                    <div class="text-3xl font-bold bg-gradient-to-r {{ $color[0] }} {{ $color[1] }} bg-clip-text text-transparent mb-2">
                        NPR {{ number_format($subscription->plan->price, 0) }}
                    </div>
                    <div class="text-gray-600 font-medium">Monthly Cost</div>
                </div>
                <div class="text-center p-6 bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl border border-green-100">
                    <div class="text-2xl font-bold text-gray-900 mb-2">
                        {{ $subscription->ends_at->format('M d, Y') }}
                    </div>
                    <div class="text-gray-600 font-medium">Next Billing Date</div>
                </div>
                <div class="text-center p-6 bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl border border-purple-100">
                    <div class="text-2xl font-bold text-gray-900 mb-2">
                        {{ $subscription->created_at->diffInDays(now()) }} days
                    </div>
                    <div class="text-gray-600 font-medium">Subscription Age</div>
                </div>
            </div>

            @if($subscription->isOnTrial())
                <div class="mt-6 p-6 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl border border-blue-200">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-blue-900">Free Trial Active</h4>
                            <p class="text-blue-800">Your trial ends on {{ $subscription->trial_ends_at->format('M d, Y') }} ({{ $subscription->trial_ends_at->diffInDays(now()) }} days remaining)</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Usage Statistics -->
        <div class="grid lg:grid-cols-2 gap-8">
            <!-- Usage Limits -->
            <div class="card">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900">Usage Statistics</h3>
                    <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                </div>
                
                <div class="space-y-6">
                    
                    <div class="p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-100">
                        <div class="flex justify-between items-center mb-3">
                            <div class="flex items-center space-x-2">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                <span class="font-medium text-gray-900">Staff Members</span>
                            </div>
                            <span class="text-lg font-bold text-blue-600">{{ $staffCount }} / {{ $maxUsers === -1 ? '∞' : $maxUsers }}</span>
                        </div>
                        @if($maxUsers !== -1)
                            <div class="w-full bg-blue-200 rounded-full h-3">
                                <div class="bg-gradient-to-r from-blue-500 to-indigo-600 h-3 rounded-full transition-all duration-500" style="width: {{ $staffPercentage }}%"></div>
                            </div>
                            <div class="text-sm text-blue-700 mt-2">{{ number_format($staffPercentage, 1) }}% used</div>
                        @else
                            <div class="text-sm text-blue-700">Unlimited staff members</div>
                        @endif
                    </div>

                    <div class="p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl border border-green-100">
                        <div class="flex justify-between items-center mb-3">
                            <div class="flex items-center space-x-2">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                <span class="font-medium text-gray-900">Patients</span>
                            </div>
                            <span class="text-lg font-bold text-green-600">{{ $patientCount }} / {{ $maxPatients === -1 ? '∞' : $maxPatients }}</span>
                        </div>
                        @if($maxPatients !== -1)
                            <div class="w-full bg-green-200 rounded-full h-3">
                                <div class="bg-gradient-to-r from-green-500 to-emerald-600 h-3 rounded-full transition-all duration-500" style="width: {{ $patientPercentage }}%"></div>
                            </div>
                            <div class="text-sm text-green-700 mt-2">{{ number_format($patientPercentage, 1) }}% used</div>
                        @else
                            <div class="text-sm text-green-700">Unlimited patients</div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Plan Features -->
            <div class="card">
                <h3 class="text-xl font-bold text-gray-900 mb-6">Plan Features</h3>
                <div class="grid gap-3">
                    @foreach($subscription->plan->features as $feature)
                        <div class="flex items-center space-x-3 p-3 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl hover:from-green-50 hover:to-emerald-50 transition-all duration-300 group">
                            <div class="w-6 h-6 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <span class="text-gray-700 font-medium group-hover:text-green-700 transition-colors">{{ $feature }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="grid md:grid-cols-2 gap-6">
            <a href="{{ route('subscriptions.plans') }}" class="group flex items-center justify-center p-6 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-2xl hover:from-blue-600 hover:to-indigo-700 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                <div class="text-center">
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path></svg>
                    </div>
                    <h4 class="font-bold text-lg mb-1">Upgrade Plan</h4>
                    <p class="text-blue-100 text-sm">Get more features and higher limits</p>
                </div>
            </a>
            
            <button class="group flex items-center justify-center p-6 bg-gradient-to-r from-red-500 to-rose-600 text-white rounded-2xl hover:from-red-600 hover:to-rose-700 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                <div class="text-center">
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </div>
                    <h4 class="font-bold text-lg mb-1">Cancel Subscription</h4>
                    <p class="text-red-100 text-sm">End your subscription anytime</p>
                </div>
            </button>
        </div>
    @else
        <!-- No Subscription State -->
        <div class="card text-center py-16">
            <div class="w-24 h-24 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-3xl flex items-center justify-center mx-auto mb-6">
                <svg class="w-12 h-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 mb-4">No Active Subscription</h2>
            <p class="text-gray-600 mb-8 max-w-md mx-auto">Choose a plan to unlock all features and start managing your dental practice efficiently.</p>
            <a href="{{ route('subscriptions.plans') }}" class="btn-primary inline-flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                <span>View Available Plans</span>
            </a>
        </div>
    @endif
</div>
@endsection