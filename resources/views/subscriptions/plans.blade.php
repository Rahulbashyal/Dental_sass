@extends('layouts.app')

@section('page-title', 'Subscription Plans')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50 -m-6 p-6">
    <div class="max-w-7xl mx-auto space-y-12">
        <!-- Header Section -->
        <div class="text-center space-y-6">
            <div class="inline-flex items-center px-6 py-3 bg-white/80 backdrop-blur-sm rounded-full shadow-lg border border-gray-200">
                <span class="mr-3 text-2xl">💎</span>
                <span class="font-semibold text-gray-800">Premium Plans for Nepal</span>
                <div class="ml-3 w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
            </div>
            
            <h1 class="text-5xl md:text-6xl font-bold text-gray-900 leading-tight">
                <span class="block">Choose Your</span>
                <span class="bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent block">Perfect Plan</span>
            </h1>
            
            <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                Transparent pricing designed specifically for Nepal's dental practices. All plans include NPR billing, Nepali calendar, and local support.
            </p>
        </div>

        <!-- Pricing Cards -->
        <div class="grid md:grid-cols-3 gap-8 max-w-6xl mx-auto">
            @foreach($plans as $index => $plan)
                @php
                    $isPopular = $index === 1;
                    $color = $colors[$index] ?? $colors[0];
                @endphp
                
                <div class="group relative {{ $isPopular ? 'transform scale-105 z-10' : '' }}">
                    @if($isPopular)
                        <div class="absolute -top-6 left-1/2 transform -translate-x-1/2 z-20">
                            <div class="bg-gradient-to-r {{ $color[0] }} {{ $color[1] }} text-white px-8 py-3 rounded-full shadow-xl">
                                <div class="flex items-center space-x-2">
                                    <span class="text-lg">🏆</span>
                                    <span class="font-bold text-sm">MOST POPULAR</span>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="bg-white/90 backdrop-blur-sm rounded-3xl shadow-xl hover:shadow-2xl transition-all duration-500 p-8 border-2 {{ $isPopular ? $color[4] : 'border-gray-100' }} group-hover:border-opacity-50 {{ $isPopular ? 'mt-6' : '' }}">
                        <!-- Plan Header -->
                        <div class="text-center mb-8">
                            <div class="w-20 h-20 bg-gradient-to-br {{ $color[0] }} {{ $color[1] }} rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg group-hover:scale-110 transition-transform duration-300">
                                @if($plan->slug === 'basic')
                                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                @elseif($plan->slug === 'professional')
                                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                                @else
                                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                                @endif
                            </div>
                            
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $plan->name }}</h3>
                            <p class="text-gray-600 mb-6">{{ $plan->description }}</p>
                            
                            <div class="space-y-2">
                                <div class="text-5xl font-bold bg-gradient-to-r {{ $color[0] }} {{ $color[1] }} bg-clip-text text-transparent">
                                    NPR {{ number_format($plan->price, 0) }}
                                </div>
                                <div class="text-gray-600 text-lg">/month</div>
                                @if($plan->slug === 'professional')
                                    <div class="inline-flex items-center px-3 py-1 {{ $color[2] }} {{ $color[3] }} rounded-full text-sm font-medium">
                                        Save 20% annually
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Features List -->
                        <div class="space-y-4 mb-8">
                            @foreach($features as $feature)
                                <div class="flex items-center space-x-3 p-3 bg-gray-50/50 rounded-xl hover:bg-gray-50 transition-colors">
                                    <div class="w-6 h-6 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    <span class="text-gray-700 font-medium">{{ $feature }}</span>
                                </div>
                            @endforeach
                        </div>

                        <!-- CTA Button -->
                        <form action="{{ route('subscriptions.upgrade', $plan->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full group relative overflow-hidden {{ $isPopular ? 'bg-gradient-to-r ' . $color[0] . ' ' . $color[1] . ' text-white shadow-xl hover:shadow-2xl' : 'bg-gray-900 text-white hover:bg-gray-800' }} py-4 px-6 rounded-2xl font-semibold transition-all duration-300 transform hover:scale-105">
                                <div class="flex items-center justify-center space-x-2">
                                    @if($isPopular)
                                        <span class="text-lg">🚀</span>
                                    @endif
                                    <span>Choose {{ $plan->name }}</span>
                                </div>
                                <div class="absolute inset-0 bg-white/20 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></div>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Trust Indicators -->
        <div class="bg-white/60 backdrop-blur-sm rounded-3xl shadow-xl p-8 border border-gray-200">
            <div class="grid md:grid-cols-4 gap-8 text-center">
                <div class="space-y-3">
                    <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center mx-auto">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <h4 class="font-bold text-gray-900">14-Day Free Trial</h4>
                    <p class="text-gray-600 text-sm">No credit card required</p>
                </div>
                <div class="space-y-3">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center mx-auto">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h4 class="font-bold text-gray-900">24/7 Support</h4>
                    <p class="text-gray-600 text-sm">In Nepali language</p>
                </div>
                <div class="space-y-3">
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center mx-auto">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h4 class="font-bold text-gray-900">Cancel Anytime</h4>
                    <p class="text-gray-600 text-sm">No long-term contracts</p>
                </div>
                <div class="space-y-3">
                    <div class="w-16 h-16 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-2xl flex items-center justify-center mx-auto">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <h4 class="font-bold text-gray-900">Secure & Compliant</h4>
                    <p class="text-gray-600 text-sm">Bank-level security</p>
                </div>
            </div>
        </div>

        <!-- FAQ Section -->
        <div class="text-center space-y-6">
            <h3 class="text-2xl font-bold text-gray-900">Frequently Asked Questions</h3>
            <div class="grid md:grid-cols-2 gap-6 max-w-4xl mx-auto text-left">
                <div class="bg-white/60 backdrop-blur-sm rounded-2xl p-6 border border-gray-200">
                    <h4 class="font-semibold text-gray-900 mb-2">Can I change plans anytime?</h4>
                    <p class="text-gray-600 text-sm">Yes, you can upgrade or downgrade your plan at any time. Changes take effect immediately.</p>
                </div>
                <div class="bg-white/60 backdrop-blur-sm rounded-2xl p-6 border border-gray-200">
                    <h4 class="font-semibold text-gray-900 mb-2">Is my data secure?</h4>
                    <p class="text-gray-600 text-sm">Absolutely. We use bank-level encryption and daily backups to keep your data safe.</p>
                </div>
                <div class="bg-white/60 backdrop-blur-sm rounded-2xl p-6 border border-gray-200">
                    <h4 class="font-semibold text-gray-900 mb-2">Do you offer training?</h4>
                    <p class="text-gray-600 text-sm">Yes, we provide free onboarding and training for all staff members in Nepali.</p>
                </div>
                <div class="bg-white/60 backdrop-blur-sm rounded-2xl p-6 border border-gray-200">
                    <h4 class="font-semibold text-gray-900 mb-2">What payment methods do you accept?</h4>
                    <p class="text-gray-600 text-sm">We accept all major Nepali banks, eSewa, Khalti, and international cards.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection