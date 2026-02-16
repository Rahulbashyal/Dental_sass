@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">Treatment Plans</h2>
            <p class="text-sm text-slate-500">Proposed and ongoing dental treatments for patients.</p>
        </div>
        <a href="{{ route('clinic.treatment-plans.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow-sm">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Create Plan
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm mb-6 flex items-center justify-between">
        <div class="flex items-center space-x-2">
            <a href="{{ route('clinic.treatment-plans.index') }}" class="px-3 py-1.5 rounded-lg text-xs font-bold {{ !$status ? 'bg-indigo-600 text-white' : 'text-slate-500 hover:bg-slate-50' }}">All Plans</a>
            <a href="{{ route('clinic.treatment-plans.index', ['status' => 'proposed']) }}" class="px-3 py-1.5 rounded-lg text-xs font-bold {{ $status == 'proposed' ? 'bg-blue-600 text-white' : 'text-slate-500 hover:bg-slate-50' }}">Proposed</a>
            <a href="{{ route('clinic.treatment-plans.index', ['status' => 'accepted']) }}" class="px-3 py-1.5 rounded-lg text-xs font-bold {{ $status == 'accepted' ? 'bg-green-600 text-white' : 'text-slate-500 hover:bg-slate-50' }}">Accepted</a>
            <a href="{{ route('clinic.treatment-plans.index', ['status' => 'in_progress']) }}" class="px-3 py-1.5 rounded-lg text-xs font-bold {{ $status == 'in_progress' ? 'bg-amber-600 text-white' : 'text-slate-500 hover:bg-slate-50' }}">In Progress</a>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead>
                    <tr class="bg-slate-50">
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Patient</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Plan Title</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Est. Cost</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Priority</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-200">
                @forelse($treatmentPlans as $plan)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <span class="text-sm font-bold text-slate-900">{{ $plan->patient->full_name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-slate-900">{{ $plan->title }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-black text-slate-900">{{ tenant()->clinic->currency ?? '$' }}{{ number_format($plan->estimated_cost, 2) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $priorityClass = match($plan->priority) {
                                    'high' => 'bg-red-50 text-red-700 ring-red-200',
                                    'medium' => 'bg-amber-50 text-amber-700 ring-amber-200',
                                    'low' => 'bg-blue-50 text-blue-700 ring-blue-200',
                                };
                            @endphp
                            <span class="px-2 py-1 rounded text-[10px] font-bold uppercase tracking-wider ring-1 {{ $priorityClass }}">{{ $plan->priority }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                             @php
                                $statusClass = match($plan->status) {
                                    'proposed' => 'bg-slate-100 text-slate-700',
                                    'accepted' => 'bg-green-50 text-green-700',
                                    'rejected' => 'bg-red-50 text-red-700',
                                    'in_progress' => 'bg-amber-50 text-amber-700',
                                    'completed' => 'bg-indigo-50 text-indigo-700',
                                };
                            @endphp
                            <span class="px-2 py-1 rounded text-[10px] font-bold uppercase tracking-wider {{ $statusClass }}">{{ str_replace('_', ' ', $plan->status) }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                            <a href="{{ route('clinic.treatment-plans.show', $plan) }}" class="p-1.5 text-slate-400 hover:text-indigo-600 transition-colors">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-500 italic">No treatment plans found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if($treatmentPlans->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/30">
                {{ $treatmentPlans->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
