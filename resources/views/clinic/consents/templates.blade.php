@extends('layouts.app')

@section('title', 'Consent Templates')
@section('page-title', 'Consent Templates')

@section('content')
<div class="space-y-6">
    <!-- Add Template -->
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 p-6 shadow-sm">
        <h2 class="text-sm font-semibold text-gray-700 dark:text-slate-300 mb-4">New Consent Template</h2>
        <form method="POST" action="{{ route('clinic.consents.templates.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-slate-400 mb-1">Title</label>
                <input type="text" name="title" required
                       class="w-full rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-2.5 text-sm text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-purple-500"
                       placeholder="e.g. General Treatment Consent">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-slate-400 mb-1">Content</label>
                <textarea name="content" rows="6" required
                          class="w-full rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-2.5 text-sm text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-purple-500"
                          placeholder="Enter consent form text..."></textarea>
            </div>
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-purple-600 hover:bg-purple-700 text-white text-sm font-semibold transition-colors">
                Save Template
            </button>
        </form>
    </div>

    <!-- Templates List -->
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-slate-800">
            <h2 class="text-sm font-semibold text-gray-700 dark:text-slate-300">All Templates ({{ $templates->count() }})</h2>
        </div>
        @forelse($templates as $template)
            <div class="px-6 py-4 border-b border-gray-50 dark:border-slate-800/50 last:border-0">
                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $template->title }}</p>
                <p class="mt-1 text-xs text-gray-500 dark:text-slate-400 line-clamp-2">{{ $template->content }}</p>
                <p class="mt-1 text-[10px] text-gray-400 dark:text-slate-600">Added {{ $template->created_at->diffForHumans() }}</p>
            </div>
        @empty
            <div class="px-6 py-12 text-center">
                <p class="text-sm text-gray-400 dark:text-slate-500">No consent templates yet. Create one above.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
