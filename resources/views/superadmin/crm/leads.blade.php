@extends('layouts.app')

@section('content')
<!-- Header -->
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-12">
    <div>
        <h1 class="text-4xl font-black text-slate-900 tracking-tight flex items-center gap-4">
            <div class="w-12 h-12 bg-white border border-slate-100 rounded-2xl flex items-center justify-center shadow-sm">
                <i class="fas fa-funnel-dollar text-blue-500"></i>
            </div>
            Lead Management
        </h1>
        <p class="text-slate-500 font-medium mt-2 flex items-center gap-2 italic">
            <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
            Track and manage your potential customers and clinic leads.
        </p>
    </div>
    <div class="flex items-center gap-4">
        <a href="{{ route('superadmin.crm.create-lead', ['iframe' => 1]) }}" data-modal-url="{{ route('superadmin.crm.create-lead', ['iframe' => 1]) }}" data-modal-title="Add" class="btn-premium-primary !py-4 !px-8 !rounded-2xl !text-xs !bg-slate-900 !text-white hover:!bg-blue-600 shadow-xl shadow-slate-900/10 group">
            <i class="fas fa-plus mr-2 text-blue-400 group-hover:text-white transition-all"></i>
            Add New Lead
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
                    <i class="fas fa-database text-xl"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Total Leads</p>
                    <h3 class="text-3xl font-black text-slate-900 tracking-tight">{{ $stats['total_leads'] }}</h3>
                    <p class="text-[8px] font-black text-blue-500 uppercase tracking-widest mt-1 italic">Total leads in system</p>
                </div>
            </div>
        </div>

        <div class="stat-card group">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-amber-50/50 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-700"></div>
            <div class="relative z-10 flex flex-col gap-6">
                <div class="w-14 h-14 bg-amber-50 text-amber-600 rounded-[1.25rem] flex items-center justify-center shadow-inner group-hover:rotate-12 transition-all">
                    <i class="fas fa-bolt text-xl"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">New Leads</p>
                    <h3 class="text-3xl font-black text-slate-900 tracking-tight">{{ $stats['new_leads'] }}</h3>
                    <p class="text-[8px] font-black text-amber-500 uppercase tracking-widest mt-1 italic">Leads added this month</p>
                </div>
            </div>
        </div>

        <div class="stat-card group">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-emerald-50/50 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-700"></div>
            <div class="relative z-10 flex flex-col gap-6">
                <div class="w-14 h-14 bg-emerald-50 text-emerald-600 rounded-[1.25rem] flex items-center justify-center shadow-inner group-hover:rotate-12 transition-all">
                    <i class="fas fa-check-double text-xl"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Qualified</p>
                    <h3 class="text-3xl font-black text-slate-900 tracking-tight">{{ $stats['qualified_leads'] }}</h3>
                    <p class="text-[8px] font-black text-emerald-500 uppercase tracking-widest mt-1 italic">Interested and verified</p>
                </div>
            </div>
        </div>

        <div class="stat-card group">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-purple-50/50 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-700"></div>
            <div class="relative z-10 flex flex-col gap-6">
                <div class="w-14 h-14 bg-purple-50 text-purple-600 rounded-[1.25rem] flex items-center justify-center shadow-inner group-hover:rotate-12 transition-all">
                    <i class="fas fa-arrow-right-from-bracket text-xl rotate-180"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Converted</p>
                    <h3 class="text-3xl font-black text-slate-900 tracking-tight">{{ $stats['converted_leads'] }}</h3>
                    <p class="text-[8px] font-black text-purple-500 uppercase tracking-widest mt-1 italic">Successfully joined clinics</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Leads Registry -->
    <div class="relative">
        <div class="premium-table-container">
            <div class="px-10 py-8 border-b border-slate-50 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center shadow-sm">
                        <i class="fas fa-users text-xs"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-black text-slate-900 tracking-tight">Leads List</h2>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5 italic">View and manage all your current leads here.</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-search text-slate-300 text-[10px]"></i>
                        </div>
                        <input type="text" placeholder="Search leads..." class="bg-slate-50 border-none rounded-2xl pl-10 pr-4 py-2.5 text-[10px] font-black uppercase tracking-widest w-64 focus:ring-4 focus:ring-blue-500/10 focus:bg-white transition-all text-slate-900 placeholder-slate-400">
                    </div>
                    <button class="btn-premium-outline !py-2.5 !px-5 !rounded-2xl !text-[10px] !bg-white group">
                        <i class="fas fa-filter mr-2 text-slate-400 group-hover:text-slate-900 transition-colors"></i>
                        Filter List
                    </button>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="premium-table">
                    <thead>
                        <tr>
                            <th>Lead Name</th>
                            <th>Contact Details</th>
                            <th>Status</th>
                            <th>Source</th>
                            <th>Lead Value</th>
                            <th>Assigned</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($leads as $lead)
                            <tr class="group">
                                <td>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-black text-slate-900 tracking-tight group-hover:text-blue-600 transition-colors">{{ $lead->name }}</span>
                                        @if($lead->company)
                                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest italic">{{ $lead->company }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="flex flex-col gap-1">
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-envelope text-[10px] text-slate-300"></i>
                                            <span class="text-xs font-bold text-slate-600">{{ $lead->email }}</span>
                                        </div>
                                        @if($lead->phone)
                                            <div class="flex items-center gap-2">
                                                <i class="fas fa-phone text-[10px] text-slate-300"></i>
                                                <span class="text-[10px] font-bold text-slate-400">{{ $lead->phone }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <span class="chip
                                        @if($lead->status === 'new') chip-info
                                        @elseif($lead->status === 'contacted') chip-warning
                                        @elseif($lead->status === 'qualified') bg-purple-50 text-purple-600 border-purple-100
                                        @elseif($lead->status === 'converted') chip-success
                                        @else chip-danger
                                        @endif">
                                        <span class="w-1.5 h-1.5 rounded-full
                                            @if($lead->status === 'new') bg-blue-500
                                            @elseif($lead->status === 'contacted') bg-amber-500
                                            @elseif($lead->status === 'qualified') bg-purple-500
                                            @elseif($lead->status === 'converted') bg-emerald-500
                                            @else bg-rose-500
                                            @endif"></span>
                                        {{ ucfirst($lead->status) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest bg-slate-50 px-2 py-1 rounded-md border border-slate-100">
                                        {{ $lead->source ?? 'Direct' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-black text-slate-900 tracking-tight">NPR {{ number_format($lead->value ?? 0) }}</span>
                                        <span class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">Est. Value</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center">
                                            <i class="fas fa-user text-[8px] text-slate-400"></i>
                                        </div>
                                        <span class="text-[11px] font-bold text-slate-600 tracking-tight">{{ $lead->assigned_to ?? 'Unassigned' }}</span>
                                    </div>
                                </td>
                                <td class="text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button class="btn-premium-ghost !p-2">
                                            <i class="fas fa-eye text-[10px]"></i>
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
                                <td colspan="7" class="py-24 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-24 h-24 bg-slate-50 rounded-[2.5rem] flex items-center justify-center mb-6 shadow-inner border border-slate-100 group">
                                            <i class="fas fa-user-plus text-4xl text-slate-200 group-hover:scale-110 group-hover:text-blue-500/20 transition-all duration-700"></i>
                                        </div>
                                        <h3 class="text-2xl font-black text-slate-900 tracking-tight">No Leads Found</h3>
                                        <p class="text-slate-500 max-w-sm mx-auto text-sm font-medium mt-2 italic text-center">You haven't added any clinical leads yet.</p>
                                        <a href="{{ route('superadmin.crm.create-lead', ['iframe' => 1]) }}" data-modal-url="{{ route('superadmin.crm.create-lead') }}" class="btn-premium-primary !py-4 !px-8 !rounded-2xl !text-xs !bg-slate-900 !text-white hover:!bg-blue-600 shadow-xl shadow-slate-900/10 group">
            <i class="fas fa-plus mr-2 text-blue-400 group-hover:text-white transition-all"></i>
            Add New Lead
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
                    <i class="fas fa-database text-xl"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Total Leads</p>
                    <h3 class="text-3xl font-black text-slate-900 tracking-tight">{{ $stats['total_leads'] }}</h3>
                    <p class="text-[8px] font-black text-blue-500 uppercase tracking-widest mt-1 italic">Total leads in system</p>
                </div>
            </div>
        </div>

        <div class="stat-card group">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-amber-50/50 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-700"></div>
            <div class="relative z-10 flex flex-col gap-6">
                <div class="w-14 h-14 bg-amber-50 text-amber-600 rounded-[1.25rem] flex items-center justify-center shadow-inner group-hover:rotate-12 transition-all">
                    <i class="fas fa-bolt text-xl"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">New Leads</p>
                    <h3 class="text-3xl font-black text-slate-900 tracking-tight">{{ $stats['new_leads'] }}</h3>
                    <p class="text-[8px] font-black text-amber-500 uppercase tracking-widest mt-1 italic">Leads added this month</p>
                </div>
            </div>
        </div>

        <div class="stat-card group">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-emerald-50/50 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-700"></div>
            <div class="relative z-10 flex flex-col gap-6">
                <div class="w-14 h-14 bg-emerald-50 text-emerald-600 rounded-[1.25rem] flex items-center justify-center shadow-inner group-hover:rotate-12 transition-all">
                    <i class="fas fa-check-double text-xl"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Qualified</p>
                    <h3 class="text-3xl font-black text-slate-900 tracking-tight">{{ $stats['qualified_leads'] }}</h3>
                    <p class="text-[8px] font-black text-emerald-500 uppercase tracking-widest mt-1 italic">Interested and verified</p>
                </div>
            </div>
        </div>

        <div class="stat-card group">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-purple-50/50 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-700"></div>
            <div class="relative z-10 flex flex-col gap-6">
                <div class="w-14 h-14 bg-purple-50 text-purple-600 rounded-[1.25rem] flex items-center justify-center shadow-inner group-hover:rotate-12 transition-all">
                    <i class="fas fa-arrow-right-from-bracket text-xl rotate-180"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Converted</p>
                    <h3 class="text-3xl font-black text-slate-900 tracking-tight">{{ $stats['converted_leads'] }}</h3>
                    <p class="text-[8px] font-black text-purple-500 uppercase tracking-widest mt-1 italic">Successfully joined clinics</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Leads Registry -->
    <div class="relative">
        <div class="premium-table-container">
            <div class="px-10 py-8 border-b border-slate-50 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center shadow-sm">
                        <i class="fas fa-users text-xs"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-black text-slate-900 tracking-tight">Leads List</h2>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5 italic">View and manage all your current leads here.</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-search text-slate-300 text-[10px]"></i>
                        </div>
                        <input type="text" placeholder="Search leads..." class="bg-slate-50 border-none rounded-2xl pl-10 pr-4 py-2.5 text-[10px] font-black uppercase tracking-widest w-64 focus:ring-4 focus:ring-blue-500/10 focus:bg-white transition-all text-slate-900 placeholder-slate-400">
                    </div>
                    <button class="btn-premium-outline !py-2.5 !px-5 !rounded-2xl !text-[10px] !bg-white group">
                        <i class="fas fa-filter mr-2 text-slate-400 group-hover:text-slate-900 transition-colors"></i>
                        Filter List
                    </button>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="premium-table">
                    <thead>
                        <tr>
                            <th>Lead Name</th>
                            <th>Contact Details</th>
                            <th>Status</th>
                            <th>Source</th>
                            <th>Lead Value</th>
                            <th>Assigned</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($leads as $lead)
                            <tr class="group">
                                <td>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-black text-slate-900 tracking-tight group-hover:text-blue-600 transition-colors">{{ $lead->name }}</span>
                                        @if($lead->company)
                                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest italic">{{ $lead->company }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="flex flex-col gap-1">
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-envelope text-[10px] text-slate-300"></i>
                                            <span class="text-xs font-bold text-slate-600">{{ $lead->email }}</span>
                                        </div>
                                        @if($lead->phone)
                                            <div class="flex items-center gap-2">
                                                <i class="fas fa-phone text-[10px] text-slate-300"></i>
                                                <span class="text-[10px] font-bold text-slate-400">{{ $lead->phone }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <span class="chip
                                        @if($lead->status === 'new') chip-info
                                        @elseif($lead->status === 'contacted') chip-warning
                                        @elseif($lead->status === 'qualified') bg-purple-50 text-purple-600 border-purple-100
                                        @elseif($lead->status === 'converted') chip-success
                                        @else chip-danger
                                        @endif">
                                        <span class="w-1.5 h-1.5 rounded-full
                                            @if($lead->status === 'new') bg-blue-500
                                            @elseif($lead->status === 'contacted') bg-amber-500
                                            @elseif($lead->status === 'qualified') bg-purple-500
                                            @elseif($lead->status === 'converted') bg-emerald-500
                                            @else bg-rose-500
                                            @endif"></span>
                                        {{ ucfirst($lead->status) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest bg-slate-50 px-2 py-1 rounded-md border border-slate-100">
                                        {{ $lead->source ?? 'Direct' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-black text-slate-900 tracking-tight">NPR {{ number_format($lead->value ?? 0) }}</span>
                                        <span class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">Est. Value</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center">
                                            <i class="fas fa-user text-[8px] text-slate-400"></i>
                                        </div>
                                        <span class="text-[11px] font-bold text-slate-600 tracking-tight">{{ $lead->assigned_to ?? 'Unassigned' }}</span>
                                    </div>
                                </td>
                                <td class="text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button class="btn-premium-ghost !p-2">
                                            <i class="fas fa-eye text-[10px]"></i>
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
                                <td colspan="7" class="py-24 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-24 h-24 bg-slate-50 rounded-[2.5rem] flex items-center justify-center mb-6 shadow-inner border border-slate-100 group">
                                            <i class="fas fa-user-plus text-4xl text-slate-200 group-hover:scale-110 group-hover:text-blue-500/20 transition-all duration-700"></i>
                                        </div>
                                        <h3 class="text-2xl font-black text-slate-900 tracking-tight">No Leads Found</h3>
                                        <p class="text-slate-500 max-w-sm mx-auto text-sm font-medium mt-2 italic text-center">You haven't added any clinical leads yet.</p>
                                        <a href="{{ route('superadmin.crm.create-lead', ['iframe' => 1], 'iframe' => 1) }}" data-modal-url="{{ route('superadmin.crm.create-lead', ['iframe' => 1], 'iframe' => 1) }}" data-modal-title="Add New Lead" data-modal-title="Add New Lead" class="mt-8 btn-premium-primary !rounded-2xl">
                                            Add New Lead
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($leads->hasPages())
                <div class="px-10 py-6 border-t border-slate-50">
                    {{ $leads->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection