@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="mb-8 flex items-center justify-between no-print">
        <h2 class="text-2xl font-bold text-slate-900 line-clamp-1">Plan Details: {{ $treatmentPlan->title }}</h2>
        <div class="flex items-center space-x-3">
             <form action="{{ route('clinic.treatment-plans.status.update', $treatmentPlan) }}" method="POST" class="flex items-center space-x-2">
                @csrf
                @method('PATCH')
                <select name="status" onchange="this.form.submit()" class="rounded-lg border-slate-200 text-xs font-bold uppercase tracking-wider focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="proposed" {{ $treatmentPlan->status == 'proposed' ? 'selected' : '' }}>Proposed</option>
                    <option value="accepted" {{ $treatmentPlan->status == 'accepted' ? 'selected' : '' }}>Accepted</option>
                    <option value="rejected" {{ $treatmentPlan->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    <option value="in_progress" {{ $treatmentPlan->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="completed" {{ $treatmentPlan->status == 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </form>
            <button onclick="window.print()" class="inline-flex items-center px-4 py-2 bg-white border border-slate-200 text-slate-700 text-sm font-semibold rounded-lg hover:bg-slate-50 transition-colors shadow-sm">
                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                Print
            </button>
        </div>
    </div>

    <!-- Professional Plan Layout -->
    <div class="bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden print:shadow-none print:border-none">
        <!-- Header -->
        <div class="px-10 py-12 border-b border-slate-100 bg-slate-50/50 flex flex-col md:flex-row justify-between items-start">
            <div>
                 <h1 class="text-2xl font-black text-indigo-600 mb-2">{{ tenant()->clinic->name }}</h1>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Treatment Proposal</p>
                <h2 class="text-2xl font-bold text-slate-900 mt-2">{{ $treatmentPlan->title }}</h2>
            </div>
            <div class="mt-8 md:mt-0 text-right">
                <div class="inline-flex items-center px-4 py-2 bg-indigo-900 text-white rounded-xl shadow-lg">
                    <div class="text-right">
                        <p class="text-[10px] font-bold text-indigo-300 uppercase tracking-widest">Est. Cost</p>
                        <p class="text-xl font-black">{{ tenant()->clinic->currency ?? '$' }}{{ number_format($treatmentPlan->estimated_cost, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Details -->
        <div class="px-10 py-10 grid grid-cols-2 md:grid-cols-3 gap-8 border-b border-slate-50">
            <div>
                <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Patient:</h4>
                <p class="text-base font-bold text-slate-900">{{ $treatmentPlan->patient->full_name }}</p>
                <p class="text-xs text-slate-500 font-medium mt-1">ID: {{ $treatmentPlan->patient->patient_id }}</p>
            </div>
            <div>
                <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Estimated Duration:</h4>
                <p class="text-sm font-bold text-slate-900">{{ $treatmentPlan->estimated_duration ?? 'Not Specified' }}</p>
            </div>
            <div>
                <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Priority Level:</h4>
                <span class="text-sm font-bold uppercase {{ $treatmentPlan->priority == 'high' ? 'text-red-600' : ($treatmentPlan->priority == 'medium' ? 'text-amber-600' : 'text-blue-600') }}">
                    {{ $treatmentPlan->priority }}
                </span>
            </div>
        </div>

        <!-- Description -->
        <div class="px-10 py-12">
            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-6">Clinical Pathway & Description</h4>
            <div class="prose prose-slate max-w-none text-slate-700 leading-relaxed italic border-l-4 border-indigo-100 pl-6">
                {!! nl2br(e($treatmentPlan->description)) !!}
            </div>
        </div>

        <!-- Disclaimers -->
        <div class="px-10 py-8 bg-slate-50 border-t border-slate-100">
            <p class="text-[10px] text-slate-400 font-medium leading-relaxed">
                * This document is a proposal and actual costs or durations may vary based on clinical findings during the procedure.<br>
                * Patient consent is required before proceeding with the outlined treatment pathway.
            </p>
        </div>
    </div>
</div>

<style>
@media print {
    body { background: white !important; }
    .no-print { display: none !important; }
}
</style>
@endsection
