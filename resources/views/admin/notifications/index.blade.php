@extends('layouts.app')

@section('page-title', 'Clinic Notifications')

@section('content')
<div class="space-y-6">
    <!-- Header Card -->
    <div class="bg-white rounded-[2rem] shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <h2 class="text-2xl font-black text-slate-900 tracking-tight">Notification Center</h2>
                    <p class="text-sm text-slate-500 font-medium mt-1">Manage system alerts, appointment updates, and clinic communication.</p>
                </div>
                <div class="flex items-center gap-3">
                    <form action="{{ route('clinic.notifications.send-reminders') }}" method="POST">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white text-xs font-black uppercase tracking-widest rounded-2xl hover:bg-indigo-700 transition shadow-lg active:scale-95">
                            <i class="fas fa-paper-plane mr-2"></i> Send Reminders
                        </button>
                    </form>
                    <form action="{{ route('notifications.mark-all-read') }}" method="POST">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-6 py-3 bg-slate-100 text-slate-700 text-xs font-black uppercase tracking-widest rounded-2xl hover:bg-slate-200 transition active:scale-95">
                            Mark All as Read
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="bg-white rounded-[2rem] shadow-sm border border-slate-200 overflow-hidden">
        <div class="divide-y divide-slate-100">
            @forelse($notifications as $notification)
                <div class="p-6 hover:bg-slate-50 transition-colors {{ !$notification->read_at ? 'bg-indigo-50/30' : '' }}">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 mt-1">
                            @php
                                $type = $notification->data['type'] ?? 'info';
                                $icon = match($type) {
                                    'appointment' => 'fa-calendar-check',
                                    'payment' => 'fa-file-invoice-dollar',
                                    'system' => 'fa-cog',
                                    default => 'fa-bell',
                                };
                                $color = match($type) {
                                    'appointment' => 'blue',
                                    'payment' => 'emerald',
                                    'system' => 'amber',
                                    default => 'indigo',
                                };
                            @endphp
                            <div class="w-10 h-10 rounded-xl bg-{{ $color }}-100 text-{{ $color }}-600 flex items-center justify-center">
                                <i class="fas {{ $icon }}"></i>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-1">
                                <h3 class="text-sm font-black text-slate-900 truncate">
                                    {{ $notification->data['title'] ?? 'System Notification' }}
                                </h3>
                                <time class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">
                                    {{ $notification->created_at->diffForHumans() }}
                                </time>
                            </div>
                            <p class="text-sm text-slate-600 leading-relaxed">{{ $notification->data['message'] ?? 'No message content available.' }}</p>
                            
                            @if(!$notification->read_at)
                                <div class="mt-4 flex items-center gap-3">
                                    <form action="{{ route('notifications.read', $notification->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-[10px] font-black text-indigo-600 hover:text-indigo-800 uppercase tracking-widest">
                                            Mark as Read
                                        </button>
                                    </form>
                                    @if(isset($notification->data['action_url']))
                                        <a href="{{ $notification->data['action_url'] }}" class="text-[10px] font-black text-slate-500 hover:text-slate-900 uppercase tracking-widest flex items-center">
                                            View Details <i class="fas fa-chevron-right ml-1"></i>
                                        </a>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-20 text-center">
                    <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-bell-slash text-3xl text-slate-300"></i>
                    </div>
                    <h3 class="text-lg font-black text-slate-900">Quiet for now</h3>
                    <p class="text-sm text-slate-500 mt-2">When you get updates, they'll appear here.</p>
                </div>
            @endforelse
        </div>
        
        @if($notifications->hasPages())
            <div class="px-8 py-6 bg-slate-50 border-t border-slate-100">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
