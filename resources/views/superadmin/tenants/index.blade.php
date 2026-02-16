@extends('layouts.app')

@section('page-title', 'Clinics & Tenants')

@section('content')
<div class="space-y-8 page-fade-in">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight flex items-center gap-3">
                Clinics & Tenants
                <span class="text-sm font-medium px-2 py-0.5 bg-blue-50 text-blue-600 rounded-md border border-blue-100 italic">
                    {{ number_format($tenants->total()) }} Registered
                </span>
            </h1>
            <p class="text-slate-500 mt-1">Manage infrastructure, domains, and global provisioning across all clinics</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('superadmin.tenants.create') }}" class="btn-secondary flex items-center gap-2">
                <i class="fas fa-plus text-slate-400"></i>
                Manual Tenant
            </a>
            <a href="{{ route('clinics.create') }}" class="btn-primary flex items-center gap-2">
                <i class="fas fa-rocket"></i>
                Deploy New Clinic
            </a>
        </div>
    </div>

    @if(session('status'))
        <div class="rounded-xl bg-emerald-50 border border-emerald-100 p-4 flex items-center gap-3 text-emerald-800 shadow-sm animate-card-hover">
            <div class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-600">
                <i class="fas fa-check-circle"></i>
            </div>
            <p class="text-sm font-bold">{{ session('status') }}</p>
        </div>
    @endif

    <div class="card p-0 overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50">
            <form method="GET" action="" class="flex gap-4">
                <div class="relative flex-1 group">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 group-focus-within:text-blue-500 transition-colors">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Search by ID, Clinic Name or Email..." class="form-input pl-11 h-12" />
                </div>
                <button class="btn-secondary h-12 flex items-center gap-2">
                    Search
                </button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead>
                    <tr class="bg-slate-50">
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-widest">Infrastructure</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-widest">Clinic Identity</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-widest hidden md:table-cell">Global Domains</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-widest">Provisioning</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-widest">Management</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                @forelse($tenants as $tenant)
                    <tr class="group hover:bg-slate-50/80 transition-all duration-200">
                        <td class="px-6 py-5 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-slate-100 text-slate-600 rounded-xl flex items-center justify-center font-black mr-3 shadow-inner">
                                    {{ substr($tenant->id, 0, 2) }}
                                </div>
                                <div class="text-sm font-black text-slate-900">{{ $tenant->id }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <div class="text-sm font-extrabold text-slate-900">{{ data_get($tenant->data, 'name', 'Unnamed Clinic') }}</div>
                            <div class="text-xs text-slate-500 font-medium flex items-center mt-1">
                                <i class="fas fa-at mr-1 text-slate-300"></i>
                                {{ data_get($tenant->data, 'email', 'No email mapped') }}
                            </div>
                        </td>
                        <td class="px-6 py-5 hidden md:table-cell">
                            <div class="flex flex-wrap gap-1.5">
                                @foreach(\Stancl\Tenancy\Database\Models\Domain::where('tenant_id', $tenant->id)->get() as $d)
                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-[10px] font-black uppercase tracking-tighter bg-blue-50 text-blue-700 border border-blue-100 shadow-sm">
                                        <i class="fas fa-globe mr-1 opacity-50"></i>
                                        {{ $d->domain }}
                                    </span>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap">
                            @php($status = data_get($tenant->data, 'provision_status', 'unknown'))
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest
                                @if($status === 'completed') bg-emerald-50 text-emerald-700
                                @elseif($status === 'in_progress') bg-blue-50 text-blue-700 animate-pulse
                                @elseif($status === 'failed') bg-rose-50 text-rose-700
                                @else bg-slate-50 text-slate-700
                                @endif shadow-sm border
                                @if($status === 'completed') border-emerald-100
                                @elseif($status === 'in_progress') border-blue-100
                                @elseif($status === 'failed') border-rose-100
                                @else border-slate-100
                                @endif">
                                <span class="w-1.5 h-1.5 rounded-full mr-2
                                    @if($status === 'completed') bg-emerald-500
                                    @elseif($status === 'in_progress') bg-blue-500
                                    @elseif($status === 'failed') bg-rose-500
                                    @else bg-slate-500
                                    @endif"></span>
                                {{ $status === 'in_progress' ? 'Provisioning' : $status }}
                            </span>
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap text-right">
                            <div class="flex justify-end items-center gap-2">
                                <a href="{{ route('superadmin.tenants.logs', $tenant->id) }}" class="w-9 h-9 rounded-lg bg-slate-50 text-slate-400 hover:bg-slate-900 hover:text-white flex items-center justify-center transition-all duration-200" title="Execution Logs">
                                    <i class="fas fa-terminal text-sm"></i>
                                </a>
                                <a href="{{ route('superadmin.tenants.edit', $tenant->id) }}" class="w-9 h-9 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white flex items-center justify-center transition-all duration-200" title="Edit Configuration">
                                    <i class="fas fa-cog text-sm"></i>
                                </a>
                                <form action="{{ route('superadmin.tenants.destroy', $tenant->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="w-9 h-9 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white flex items-center justify-center transition-all duration-200" onclick="return confirm('CRITICAL: Permanent deletion?')" title="Delete Record">
                                        <i class="fas fa-trash-alt text-sm"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-24 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-20 h-20 bg-slate-50 text-slate-200 rounded-3xl flex items-center justify-center mb-6 shadow-inner">
                                    <i class="fas fa-hospital-user text-4xl"></i>
                                </div>
                                <h3 class="text-xl font-black text-slate-900 mb-2">Initialize your first clinic</h3>
                                <p class="text-slate-500 max-w-sm mx-auto mb-8 font-medium">Automatic provisioning will handle database creation, domain mapping, and local storage initialization.</p>
                                <a href="{{ route('clinics.create') }}" class="btn-primary flex items-center gap-2">
                                    <i class="fas fa-rocket"></i>
                                    Launch Deployment
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if($tenants->hasPages())
        <div class="px-6 py-5 border-t border-slate-100 bg-slate-50/50 font-bold">
            {{ $tenants->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
