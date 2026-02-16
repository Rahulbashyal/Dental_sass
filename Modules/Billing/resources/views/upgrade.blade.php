@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="text-center mb-16">
        <h2 class="text-3xl font-black text-slate-900 mb-4">Elevate Your Practice</h2>
        <p class="text-slate-500 max-w-2xl mx-auto">Choose a plan that scales with your dental clinic. From single-chair practices to multi-branch enterprises.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 items-stretch">
        @foreach($plans as $plan)
        <div class="bg-white rounded-3xl border {{ $currentPlanId == $plan->id ? 'border-indigo-600 ring-4 ring-indigo-50 shadow-2xl' : 'border-slate-200 shadow-sm' }} overflow-hidden flex flex-col relative transition-all hover:shadow-xl">
            @if($plan->slug === 'pro')
            <div class="absolute top-0 right-0 mt-6 mr-6">
                <span class="bg-indigo-600 text-white text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-widest shadow-lg shadow-indigo-100 italic">Recommended</span>
            </div>
            @endif

            <div class="p-8 pb-0">
                <h3 class="text-xl font-black text-slate-900 mb-2">{{ $plan->name }}</h3>
                <p class="text-sm text-slate-500 h-10 overflow-hidden">{{ $plan->description }}</p>
                
                <div class="mt-8 mb-8 flex items-baseline">
                    <span class="text-4xl font-black text-slate-900">{{ tenant()->clinic->currency ?? '$' }}{{ number_format($plan->price, 0) }}</span>
                    <span class="text-slate-400 text-sm ml-2">/ month</span>
                </div>
            </div>

            <div class="p-8 pt-0 flex-grow">
                <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6">What's Included</h4>
                <div class="space-y-4">
                    @foreach($plan->features as $feature)
                    <div class="flex items-start text-sm text-slate-600">
                        <svg class="w-4 h-4 text-indigo-500 mt-0.5 flex-shrink-0 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>{{ $feature }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="p-8 bg-slate-50/50 border-t border-slate-100">
                @if($currentPlanId == $plan->id)
                    <button disabled class="w-full py-4 bg-slate-200 text-slate-500 text-sm font-bold rounded-2xl cursor-not-allowed">
                        Current Active Plan
                    </button>
                @else
                    <form action="{{ route('billing.subscribe', $plan) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-black rounded-2xl shadow-xl shadow-indigo-100 transition-all flex items-center justify-center">
                            @if($plan->price > 0)
                                Subscribe Now
                            @else
                                Select Free Tier
                            @endif
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </button>
                    </form>
                @endif
            </div>
        </div>
        @endforeach

        <!-- Enterprise / Contact Card -->
        <div class="bg-indigo-900 rounded-3xl p-8 flex flex-col justify-between text-white shadow-2xl shadow-indigo-100 relative overflow-hidden">
            <div class="relative z-10">
                <h3 class="text-xl font-black mb-2">Enterprise</h3>
                <p class="text-sm text-indigo-300 mb-8">Customized solutions for massive hospital networks and university dental clinics.</p>
                
                <ul class="space-y-4 mb-12">
                     <li class="flex items-center text-sm">
                        <svg class="w-4 h-4 text-indigo-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Multi-tenant hierarchy (Chains)
                    </li>
                    <li class="flex items-center text-sm">
                         <svg class="w-4 h-4 text-indigo-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Dedicated account manager
                    </li>
                    <li class="flex items-center text-sm">
                         <svg class="w-4 h-4 text-indigo-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        24/7 Priority SLA support
                    </li>
                </ul>
            </div>

            <div class="relative z-10">
                <a href="#" class="w-full py-4 bg-white text-indigo-900 text-sm font-black rounded-2xl shadow-xl flex items-center justify-center hover:bg-indigo-50 transition-all">
                    Contact Sales
                </a>
            </div>

            <!-- Decorative SVG -->
            <svg class="absolute top-0 right-0 w-48 h-48 text-indigo-800 opacity-20 -mt-16 -mr-16" fill="currentColor" viewBox="0 0 24 24">
                <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z"/>
            </svg>
        </div>
    </div>
</div>
@endsection
