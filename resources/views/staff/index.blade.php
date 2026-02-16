@extends('layouts.app')

@section('page-title', 'Staff Management')

@section('content')
<div class="space-y-8 page-fade-in">
    <!-- Premium Header Section -->
    <div class="relative overflow-hidden bg-white rounded-3xl p-8 shadow-sm border border-slate-200">
        <div class="absolute top-0 right-0 -mt-20 -mr-20 w-64 h-64 bg-blue-500/5 rounded-full blur-3xl"></div>
        <div class="relative flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
            <div>
                <div class="flex items-center space-x-3 mb-2">
                    <div class="p-2 bg-blue-100 rounded-lg text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                    <h1 class="text-3xl font-black text-slate-900 tracking-tight">Staff Directory</h1>
                </div>
                <p class="text-slate-500 font-medium">Manage your clinical and administrative team roles and access permissions.</p>
            </div>
            <a href="{{ route('clinic.staff.create') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-2xl shadow-lg shadow-blue-600/20 transition-all hover:-translate-y-0.5 active:scale-95">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                </svg>
                Deploy New Staff
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="rounded-2xl bg-blue-50 border border-blue-200 p-4 flex items-center text-blue-800 shadow-sm animate-bounce-in">
            <svg class="w-5 h-5 mr-3 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
            <span class="font-bold">{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 stagger-in">
        @forelse($staff as $member)
            <div class="relative group bg-white rounded-3xl border border-slate-200 shadow-sm transition-all duration-300 hover:shadow-2xl hover:-translate-y-2 overflow-hidden flex flex-col">
                <div class="p-8 pb-4 flex-1">
                    <div class="flex items-start justify-between mb-6">
                        <div class="relative">
                            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-500 to-blue-600 flex items-center justify-center text-white text-2xl font-black shadow-lg group-hover:rotate-6 transition-transform">
                                {{ substr($member->name, 0, 1) }}
                            </div>
                            @if($member->is_active)
                                <span class="absolute -bottom-1 -right-1 w-5 h-5 bg-blue-500 border-4 border-white rounded-full"></span>
                            @else
                                <span class="absolute -bottom-1 -right-1 w-5 h-5 bg-slate-300 border-4 border-white rounded-full"></span>
                            @endif
                        </div>
                        <div class="flex flex-col items-end">
                            @php
                                $role = $member->roles->first()?->name ?? 'no_role';
                                $roleColors = [
                                    'clinic_admin' => 'bg-blue-50 text-blue-700 border-blue-100',
                                    'dentist' => 'bg-sky-50 text-sky-700 border-sky-100',
                                    'receptionist' => 'bg-indigo-50 text-indigo-700 border-indigo-100',
                                    'accountant' => 'bg-cyan-50 text-cyan-700 border-cyan-100',
                                    'no_role' => 'bg-slate-50 text-slate-500 border-slate-100',
                                ];
                                $roleColor = $roleColors[$role] ?? $roleColors['no_role'];
                            @endphp
                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border {{ $roleColor }}">
                                {{ ucfirst(str_replace('_', ' ', $role)) }}
                            </span>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-xl font-black text-slate-900 mb-1 truncate">{{ $member->name }}</h3>
                        <p class="text-sm font-medium text-slate-500 truncate">{{ $member->email }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-3 mb-6">
                        <div class="bg-slate-50 p-3 rounded-2xl border border-slate-100">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-tighter mb-1">Status</p>
                            <p class="text-xs font-bold {{ $member->is_active ? 'text-blue-600' : 'text-slate-500' }}">
                                {{ $member->is_active ? 'Available' : 'On Leave' }}
                            </p>
                        </div>
                        <div class="bg-slate-50 p-3 rounded-2xl border border-slate-100">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-tighter mb-1">Joined</p>
                            <p class="text-xs font-bold text-slate-700">{{ $member->created_at->format('M Y') }}</p>
                        </div>
                    </div>
                </div>

                <div class="px-8 py-6 bg-slate-50 border-t border-slate-200 flex items-center justify-between group-hover:bg-indigo-50/30 transition-colors">
                    <a href="{{ route('clinic.staff.edit', $member) }}" class="flex items-center text-sm font-bold text-indigo-600 hover:text-indigo-800 transition-colors">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        Edit Profile
                    </a>
                    
                    <form action="{{ route('clinic.staff.destroy', $member) }}" method="POST" onsubmit="return confirm('Secure verify: Terminate staff access?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-2 text-rose-500 hover:bg-rose-50 rounded-xl transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full py-24 bg-white rounded-3xl border border-dashed border-slate-300 text-center">
                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="h-10 w-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-black text-slate-900 mb-2">Build your team</h3>
                <p class="text-slate-500 font-medium mb-8">No staff members have been registered to this clinic yet.</p>
                <a href="{{ route('clinic.staff.create') }}" class="inline-flex items-center px-8 py-4 bg-blue-600 text-white rounded-2xl font-bold shadow-lg shadow-blue-600/20 hover:scale-105 transition-transform">
                    Add First Team Member
                </a>
            </div>
        @endforelse
    </div>
</div>
@endsection