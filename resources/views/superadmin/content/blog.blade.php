@extends('layouts.app')

@section('content')
<div class="space-y-6 page-fade-in">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">Platform Knowledge Base</h1>
            <p class="text-slate-500 font-medium">Manage and publish blog content for all clinics and landing pages.</p>
        </div>
        <button onclick="openCreateModal()" class="flex items-center gap-2 bg-blue-600 text-white px-6 py-3 rounded-2xl font-bold shadow-lg shadow-blue-100 hover:bg-blue-700 transition-all transform hover:scale-105">
            <i class="fas fa-plus"></i>
            New Post
        </button>
    </div>

    <!-- Create Post Modal -->
    <div id="createModal" class="fixed inset-0 bg-slate-900/60 hidden z-50 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-[2.5rem] shadow-2xl p-10 w-full max-w-2xl transform transition-all duration-300">
            <div class="flex items-center mb-8">
                <div class="w-14 h-14 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center mr-5 shadow-inner">
                    <i class="fas fa-edit text-2xl"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-black text-slate-900">Draft New Insight</h3>
                    <p class="text-slate-400 font-medium text-sm">Prepare content for platform-wide distribution.</p>
                </div>
            </div>
            
            <form method="POST" action="{{ route('superadmin.content.blog.store') }}" class="space-y-6">
                @csrf
                <div class="space-y-4">
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1">Article Title</label>
                        <input type="text" name="title" placeholder="e.g. The Future of Digital Dentistry in Nepal" class="w-full bg-slate-50 border-2 border-transparent focus:bg-white focus:border-blue-100 rounded-2xl px-6 py-4 font-bold text-slate-900 outline-none transition-all" required>
                    </div>
                    
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1">Rich Content</label>
                        <textarea name="content" rows="6" placeholder="Deep dive into your topic..." class="w-full bg-slate-50 border-2 border-transparent focus:bg-white focus:border-blue-100 rounded-[2rem] px-6 py-4 font-medium text-slate-900 outline-none transition-all resize-none" required></textarea>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1">Publication Status</label>
                            <select name="status" class="w-full bg-slate-50 border-2 border-transparent focus:bg-white focus:border-blue-100 rounded-2xl px-6 py-4 font-bold text-slate-900 outline-none transition-all appearance-none cursor-pointer">
                                <option value="draft">📁 Internal Draft</option>
                                <option value="published">🌐 Live Broadcast</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="pt-6 flex gap-4">
                    <button type="button" onclick="closeCreateModal()" class="flex-1 py-4 bg-slate-50 text-slate-400 rounded-2xl font-bold hover:bg-slate-100 hover:text-slate-600 transition-all">
                        Discard
                    </button>
                    <button type="submit" class="flex-1 py-4 bg-blue-600 text-white rounded-2xl font-bold shadow-xl shadow-blue-100 hover:bg-blue-700 transition-all">
                        Secure Post
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Posts Grid/Table -->
    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden stagger-in">
        <table class="w-full text-left">
            <thead class="bg-slate-50/50">
                <tr class="text-slate-400 text-[10px] font-black uppercase tracking-widest border-b border-slate-100">
                    <th class="px-8 py-5">Article Overview</th>
                    <th class="px-8 py-5">Current Status</th>
                    <th class="px-8 py-5">Timestamp</th>
                    <th class="px-8 py-5 text-right">Interactions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($posts as $post)
                <tr class="hover:bg-slate-50/50 transition-colors group">
                    <td class="px-8 py-6">
                        <div class="text-sm font-black text-slate-900 group-hover:text-blue-600 transition-colors">{{ $post->title }}</div>
                        <div class="text-[11px] font-medium text-slate-400 truncate max-w-sm">{{ Str::limit($post->content, 60) }}</div>
                    </td>
                    <td class="px-8 py-6">
                        <span class="px-3 py-1 text-[10px] font-black uppercase rounded-full border
                            {{ $post->status === 'published' ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : 'bg-amber-50 text-amber-700 border-amber-100' }}">
                            {{ $post->status }}
                        </span>
                    </td>
                    <td class="px-8 py-6">
                        <div class="text-[11px] font-bold text-slate-400">{{ $post->created_at->format('M d, Y') }}</div>
                    </td>
                    <td class="px-8 py-6 text-right">
                        <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <button class="w-9 h-9 bg-white border border-slate-100 rounded-xl flex items-center justify-center text-slate-400 hover:text-blue-600 hover:border-blue-200 shadow-sm">
                                <i class="fas fa-edit text-xs"></i>
                            </button>
                            <button class="w-9 h-9 bg-white border border-slate-100 rounded-xl flex items-center justify-center text-slate-400 hover:text-rose-600 hover:border-rose-200 shadow-sm">
                                <i class="fas fa-trash-alt text-xs"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-8 py-20 text-center">
                        <div class="max-w-xs mx-auto">
                            <i class="fas fa-newspaper text-5xl text-slate-100 mb-4"></i>
                            <h3 class="text-lg font-black text-slate-900 uppercase tracking-tight">Zero Articles Found</h3>
                            <p class="text-xs text-slate-400 font-medium">The platform knowledge base is currently empty. Start drafting to educate your users.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @if($posts->hasPages())
        <div class="px-8 py-5 bg-slate-50/50 border-t border-slate-100">
            {{ $posts->links() }}
        </div>
        @endif
    </div>
</div>

<script>
function openCreateModal() {
    document.getElementById('createModal').classList.remove('hidden');
}

function closeCreateModal() {
    document.getElementById('createModal').classList.add('hidden');
}
</script>
@endsection