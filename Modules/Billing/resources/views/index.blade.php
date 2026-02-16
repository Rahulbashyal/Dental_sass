@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-slate-900">Subscription & Billing</h2>
        <p class="text-sm text-slate-500">Manage your clinic's SaaS plan, usage limits, and payment history.</p>
    </div>

    @if($activeSubscription)
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- Current Plan Card -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-8 flex flex-col md:flex-row md:items-center justify-between bg-slate-50/50 border-b border-slate-100">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Current Active Plan</p>
                    <h3 class="text-2xl font-black text-slate-900">{{ $activeSubscription->plan->name }}</h3>
                    <div class="flex items-center mt-2 group">
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider ring-1 {{ $activeSubscription->status === 'active' ? 'bg-green-50 text-green-700 ring-green-100' : 'bg-amber-50 text-amber-700 ring-amber-100' }}">
                            {{ $activeSubscription->status }}
                        </span>
                        <span class="ml-3 text-xs text-slate-500 italic">Renews on {{ $activeSubscription->ends_at->format('M d, Y') }}</span>
                    </div>
                </div>
                <div class="mt-4 md:mt-0">
                    <a href="{{ route('billing.upgrade') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-indigo-100 transition-all">
                        Upgrade Plan
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </a>
                </div>
            </div>
            
            <div class="p-8">
                <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Plan Inclusions</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($activeSubscription->plan->features as $feature)
                    <div class="flex items-center text-sm text-slate-600">
                        <svg class="w-4 h-4 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        {{ $feature }}
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Usage Stats -->
        <div class="bg-indigo-900 rounded-2xl shadow-xl shadow-indigo-100 p-8 text-white relative overflow-hidden">
            <div class="relative z-10">
                <h4 class="text-[10px] font-bold text-indigo-300 uppercase tracking-widest mb-6">Clinic Resource Usage</h4>
                
                <div class="mb-6">
                    <div class="flex justify-between text-xs mb-2">
                        <span class="font-bold">Staff Accounts</span>
                        <span class="font-medium">Limited by Plan</span>
                    </div>
                    <div class="w-full bg-indigo-950 rounded-full h-1.5">
                        <div class="bg-white h-1.5 rounded-full" style="width: 45%"></div>
                    </div>
                </div>

                <div class="mb-8">
                    <div class="flex justify-between text-xs mb-2">
                        <span class="font-bold">Managed Patients</span>
                        <span class="font-medium">Limited by Plan</span>
                    </div>
                    <div class="w-full bg-indigo-950 rounded-full h-1.5">
                        <div class="bg-indigo-400 h-1.5 rounded-full" style="width: 70%"></div>
                    </div>
                </div>

                <div class="pt-6 border-t border-indigo-800">
                    <p class="text-[10px] text-indigo-300">Need more resources?</p>
                    <a href="{{ route('billing.upgrade') }}" class="text-xs font-bold text-white hover:underline mt-1 inline-block">View Enterprise Plans &rarr;</a>
                </div>
            </div>
            <!-- Decorative SVG -->
            <svg class="absolute bottom-0 right-0 w-32 h-32 text-indigo-800 opacity-20 -mb-8 -mr-8" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/>
            </svg>
        </div>
    </div>
    @else
    <div class="bg-white p-12 text-center rounded-2xl border border-slate-200 shadow-sm">
        <div class="h-20 w-20 bg-indigo-50 text-indigo-600 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V5a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
        </div>
        <h3 class="text-xl font-black text-slate-900 mb-2">No Active Subscription</h3>
        <p class="text-slate-500 mb-8 max-w-md mx-auto">Your clinic is currently on a limited access mode. To unlock the full power of the "Dental Sass Engine", please choose a plan.</p>
        <a href="{{ route('billing.upgrade') }}" class="inline-flex items-center px-8 py-3 bg-indigo-600 text-white font-bold rounded-xl shadow-xl shadow-indigo-100 hover:bg-indigo-700 transition-all">
            Choose a Plan
        </a>
    </div>
    @endif
</div>
@endsection
