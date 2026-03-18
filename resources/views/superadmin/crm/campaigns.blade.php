@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <!-- Header -->
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-12">
    <div>
        <h1 class="text-4xl font-black text-slate-900 tracking-tight flex items-center gap-4">
            <div class="w-12 h-12 bg-white border border-slate-100 rounded-2xl flex items-center justify-center shadow-sm">
                <i class="fas fa-bullhorn text-blue-500"></i>
            </div>
            Marketing Campaigns
        </h1>
        <p class="text-slate-500 font-medium mt-2 flex items-center gap-2 italic">
            <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
            Manage and track your marketing efforts across all channels.
        </p>
    </div>
    <div class="flex items-center gap-4">
        <a href="{{ route('superadmin.crm.create-campaign', ['iframe' => 1]) }}" data-modal-url="{{ route('superadmin.crm.create-campaign', ['iframe' => 1]) }}" data-modal-title="Start Campaign
        
    



    
    
        
            
            
                
                    
                
                
                    Total Campaigns
                    {{ $stats['total_campaigns'] }}
                    All created campaigns
                
            
        

        
            
            
                
                    
                
                
                    Active Now
                    {{ $stats['active_campaigns'] }}
                    Campaigns currently running
                
            
        

        
            
            
                
                    
                
                
                    Messages Sent
                    {{ number_format($stats['total_sent'] / 1000, 1) }}K
                    Total messages delivered
                
            
        

        
            
            
                
                    
                
                
                    Total Opened
                    {{ number_format($stats['total_opened']) }}
                    Total user engagement
                
            
        
    

    
    
        
            
                
                    
                        
                    
                    
                        Campaign List
                        View and manage all your current marketing campaigns.
                    
                
            
            
            
                
                    
                        
                            Campaign Name
                            Type
                            Status
                            Budget
                            Engagement
                            Actions
                        
                    
                    
                        @forelse($campaigns as $campaign)
                            
                                
                                    
                                        {{ $campaign->name }}
                                        {{ Str::limit($campaign->description, 50) }}
                                    
                                
                                
                                    
                                        {{ str_replace('_', ' ', $campaign->type) }}
                                    
                                
                                
                                    status === 'active') chip-success
                                        @elseif($campaign->status === 'draft') bg-slate-100 text-slate-600 border-slate-200
                                        @elseif($campaign->status === 'paused') chip-warning
                                        @else chip-info
                                        @endif">
                                        status === 'active') bg-emerald-500
                                            @elseif($campaign->status === 'draft') bg-slate-400
                                            @elseif($campaign->status === 'paused') bg-amber-500
                                            @else bg-blue-500
                                            @endif">
                                        {{ ucfirst($campaign->status) }}
                                    
                                
                                
                                    
                                        
                                            @if($campaign->budget)
                                                NPR {{ number_format($campaign->budget) }}
                                            @else
                                                N/A
                                            @endif
                                        
                                        Budget
                                    
                                
                                
                                    
                                        
                                            {{ number_format($campaign->total_sent) }}
                                            Sent
                                        
                                        
                                            {{ number_format($campaign->total_opened) }}
                                            Opened
                                        
                                    
                                
                                
                                    
                                        
                                            
                                        
                                        
                                            
                                        
                                        
                                            
                                        
                                    
                                
                            
                        @empty
                            
                                
                                    
                                        
                                            
                                        
                                        No Campaigns Found
                                        You haven't created any marketing campaigns yet.
                                        
                                            Create New Campaign" class="btn-premium-primary !py-4 !px-8 !rounded-2xl !text-xs !bg-slate-900 !text-white hover:!bg-blue-600 shadow-xl shadow-slate-900/10 group">
            <i class="fas fa-rocket mr-2 text-blue-400 group-hover:text-white transition-all"></i>
            Start Campaign
        </a>
    </div>
</div>

<div class="space-y-12">
    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 stagger-in">
        <div class="stat-card group">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-blue-50/50 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-700"></div>
            <div class="relative z-10 flex flex-col gap-6">
                <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-[1.25rem] flex items-center justify-center shadow-inner group-hover:rotate-12 transition-all">
                    <i class="fas fa-microchip text-xl"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Total Campaigns</p>
                    <h3 class="text-3xl font-black text-slate-900 tracking-tight">{{ $stats['total_campaigns'] }}</h3>
                    <p class="text-[8px] font-black text-blue-500 uppercase tracking-widest mt-1 italic">All created campaigns</p>
                </div>
            </div>
        </div>

        <div class="stat-card group">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-emerald-50/50 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-700"></div>
            <div class="relative z-10 flex flex-col gap-6">
                <div class="w-14 h-14 bg-emerald-50 text-emerald-600 rounded-[1.25rem] flex items-center justify-center shadow-inner group-hover:rotate-12 transition-all">
                    <i class="fas fa-satellite-dish text-xl"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Active Now</p>
                    <h3 class="text-3xl font-black text-slate-900 tracking-tight">{{ $stats['active_campaigns'] }}</h3>
                    <p class="text-[8px] font-black text-emerald-500 uppercase tracking-widest mt-1 italic">Campaigns currently running</p>
                </div>
            </div>
        </div>

        <div class="stat-card group">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-purple-50/50 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-700"></div>
            <div class="relative z-10 flex flex-col gap-6">
                <div class="w-14 h-14 bg-purple-50 text-purple-600 rounded-[1.25rem] flex items-center justify-center shadow-inner group-hover:rotate-12 transition-all">
                    <i class="fas fa-paper-plane text-xl"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Messages Sent</p>
                    <h3 class="text-3xl font-black text-slate-900 tracking-tight">{{ number_format($stats['total_sent'] / 1000, 1) }}K</h3>
                    <p class="text-[8px] font-black text-purple-500 uppercase tracking-widest mt-1 italic">Total messages delivered</p>
                </div>
            </div>
        </div>

        <div class="stat-card group">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-amber-50/50 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-700"></div>
            <div class="relative z-10 flex flex-col gap-6">
                <div class="w-14 h-14 bg-amber-50 text-amber-600 rounded-[1.25rem] flex items-center justify-center shadow-inner group-hover:rotate-12 transition-all">
                    <i class="fas fa-eye text-xl"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Total Opened</p>
                    <h3 class="text-3xl font-black text-slate-900 tracking-tight">{{ number_format($stats['total_opened']) }}</h3>
                    <p class="text-[8px] font-black text-amber-500 uppercase tracking-widest mt-1 italic">Total user engagement</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Campaigns Registry -->
    <div class="relative">
        <div class="premium-table-container">
            <div class="px-10 py-8 border-b border-slate-50 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center shadow-sm">
                        <i class="fas fa-project-diagram text-xs"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-black text-slate-900 tracking-tight">Campaign List</h2>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5 italic">View and manage all your current marketing campaigns.</p>
                    </div>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="premium-table">
                    <thead>
                        <tr>
                            <th>Campaign Name</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Budget</th>
                            <th>Engagement</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($campaigns as $campaign)
                            <tr class="group">
                                <td>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-black text-slate-900 tracking-tight group-hover:text-blue-600 transition-colors">{{ $campaign->name }}</span>
                                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest italic truncate max-w-xs">{{ Str::limit($campaign->description, 50) }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest bg-slate-50 px-2 py-1 rounded-md border border-slate-100">
                                        {{ str_replace('_', ' ', $campaign->type) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="chip
                                        @if($campaign->status === 'active') chip-success
                                        @elseif($campaign->status === 'draft') bg-slate-100 text-slate-600 border-slate-200
                                        @elseif($campaign->status === 'paused') chip-warning
                                        @else chip-info
                                        @endif">
                                        <span class="w-1.5 h-1.5 rounded-full
                                            @if($campaign->status === 'active') bg-emerald-500
                                            @elseif($campaign->status === 'draft') bg-slate-400
                                            @elseif($campaign->status === 'paused') bg-amber-500
                                            @else bg-blue-500
                                            @endif"></span>
                                        {{ ucfirst($campaign->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-black text-slate-900 tracking-tight">
                                            @if($campaign->budget)
                                                NPR {{ number_format($campaign->budget) }}
                                            @else
                                                N/A
                                            @endif
                                        </span>
                                        <span class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">Budget</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex items-center gap-6">
                                        <div class="flex flex-col">
                                            <span class="text-[11px] font-black text-slate-900 tracking-tight">{{ number_format($campaign->total_sent) }}</span>
                                            <span class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">Sent</span>
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-[11px] font-black text-blue-600 tracking-tight">{{ number_format($campaign->total_opened) }}</span>
                                            <span class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">Opened</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button class="btn-premium-ghost !p-2">
                                            <i class="fas fa-chart-bar text-[10px]"></i>
                                        </button>
                                        <button class="btn-premium-ghost !p-2">
                                            <i class="fas fa-pen text-[10px]"></i>
                                        </button>
                                        <button class="btn-premium-ghost !p-2 !text-rose-400 hover:!text-rose-600 hover:!bg-rose-50">
                                            <i class="fas fa-trash-alt text-[10px]"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-24 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-24 h-24 bg-slate-50 rounded-[2.5rem] flex items-center justify-center mb-6 shadow-inner border border-slate-100 group">
                                            <i class="fas fa-bullhorn text-4xl text-slate-200 group-hover:scale-110 group-hover:text-blue-500/20 transition-all duration-700"></i>
                                        </div>
                                        <h3 class="text-2xl font-black text-slate-900 tracking-tight">No Campaigns Found</h3>
                                        <p class="text-slate-500 max-w-sm mx-auto text-sm font-medium mt-2 italic text-center">You haven't created any marketing campaigns yet.</p>
                                        <a href="{{ route('superadmin.crm.create-campaign', ['iframe' => 1]) }}" data-modal-url="{{ route('superadmin.crm.create-campaign', ['iframe' => 1]) }}" data-modal-title="Create New Campaign" class="mt-8 btn-premium-primary !rounded-2xl">
                                            Create New Campaign
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($campaigns->hasPages())
                <div class="px-10 py-6 border-t border-slate-50">
                    {{ $campaigns->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
</div>
@endsection