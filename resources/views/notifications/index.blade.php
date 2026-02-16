@extends('layouts.app')

@section('title', 'Notifications - ' . config('app.name'))

@section('page-title', 'Message Center')

@section('content')
<div class="max-w-4xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-center gap-6 bg-white p-8 rounded-4xl shadow-sm border border-slate-100">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-3xl bg-blue-50 flex items-center justify-center text-blue-600 shadow-inner">
                <i class="fas fa-bell text-2xl"></i>
            </div>
            <div>
                <h2 class="text-2xl font-black text-slate-900 tracking-tight">Center Hub</h2>
                <p class="text-sm text-slate-400 font-medium">Keep track of clinical updates and system alerts</p>
            </div>
        </div>
        <button onclick="markAllAsRead()" class="px-6 py-4 bg-slate-900 text-white rounded-2xl text-[11px] font-black uppercase tracking-widest hover:bg-slate-800 transition-all shadow-xl shadow-slate-200">
            <i class="fas fa-check-double mr-2"></i> Mark All as Read
        </button>
    </div>

    <!-- Notification List -->
    <div class="bg-white rounded-4xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="divide-y divide-slate-50">
            @forelse($notifications as $notification)
                <div class="p-8 group transition-all duration-300 {{ !$notification->read_at ? 'bg-blue-50/30' : 'bg-white' }}">
                    <div class="flex items-start gap-6">
                        <!-- Icon Column -->
                        <div class="flex-shrink-0">
                            @php
                                $typeStyles = [
                                    'email' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-600', 'icon' => 'fa-envelope'],
                                    'system' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-600', 'icon' => 'fa-server'],
                                    'appointment' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-600', 'icon' => 'fa-calendar-check'],
                                    'billing' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-600', 'icon' => 'fa-file-invoice-dollar'],
                                ];
                                $style = $typeStyles[$notification->type] ?? ['bg' => 'bg-slate-100', 'text' => 'text-slate-600', 'icon' => 'fa-bell'];
                            @endphp
                            <div class="w-12 h-12 rounded-2xl {{ $style['bg'] }} {{ $style['text'] }} flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                                <i class="fas {{ $style['icon'] }} text-lg"></i>
                            </div>
                        </div>

                        <!-- Content Column -->
                        <div class="flex-1 space-y-2">
                            <div class="flex justify-between items-center">
                                <h4 class="text-sm font-black text-slate-900 uppercase tracking-tight">{{ $notification->title }}</h4>
                                <span class="text-[10px] font-bold text-slate-400 uppercase">{{ $notification->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-sm text-slate-600 leading-relaxed">{{ $notification->message }}</p>
                            
                            @if(!$notification->read_at)
                                <div class="pt-2 flex items-center gap-4">
                                    <button onclick="markAsRead({{ $notification->id }})" class="text-[10px] font-black text-blue-600 uppercase tracking-widest hover:text-blue-800 transition-colors">
                                        Mark as Done
                                    </button>
                                    <div class="w-1.5 h-1.5 rounded-full bg-blue-600 animate-pulse"></div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="py-24 text-center">
                    <div class="w-20 h-20 bg-slate-50 rounded-3xl flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-moon text-3xl text-slate-200"></i>
                    </div>
                    <h3 class="text-sm font-black text-slate-900 uppercase tracking-widest">Quiet in the hub</h3>
                    <p class="text-xs text-slate-400 mt-1 font-medium italic">There are no new notifications for you right now.</p>
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

<script>
function markAsRead(id) {
    fetch(`/notifications/${id}/read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}

function markAllAsRead() {
    fetch('/notifications/mark-all-read', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}
</script>

<style>
    .rounded-4xl { border-radius: 2.5rem; }
    .pagination { @apply flex items-center gap-2; }
    .pagination .active { @apply px-3 py-1 bg-slate-900 text-white rounded-lg text-[10px] font-black; }
    .pagination a { @apply px-3 py-1 bg-white text-slate-600 border border-slate-200 rounded-lg text-[10px] font-bold hover:bg-slate-50; }
</style>
@endsection