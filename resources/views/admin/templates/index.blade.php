@extends('layouts.app')

@section('page-title', 'UI Themes (Lego)')

@section('content')
<div x-data="{ addThemeOpen: false }">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-12 page-fade-in">
        <div>
        <h1 class="text-4xl font-black text-slate-900 tracking-tight flex items-center gap-4">
            <div class="w-12 h-12 bg-white border border-slate-100 rounded-2xl flex items-center justify-center shadow-sm">
                <i class="fas fa-layer-group text-blue-500"></i>
            </div>
            Modular UI Themes
        </h1>
        <p class="text-slate-500 font-medium mt-2 flex items-center gap-2 italic">
            <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
            Manage and sync your landing page templates like Lego blocks.
        </p>
    </div>
    <div class="flex items-center gap-4">
        <form action="{{ route('superadmin.templates.sync') }}" method="POST">
            @csrf
            <button type="submit" class="btn-premium flex items-center gap-2">
                <i class="fas fa-sync-alt"></i>
                Sync Templates
            </button>
        </form>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 stagger-in">
    @foreach($templates as $template)
        <div class="relative group bg-white rounded-[2.5rem] border border-slate-100 shadow-sm transition-all duration-500 hover:shadow-2xl overflow-hidden hover:-translate-y-2">
            <!-- Preview Image Place holder or real image -->
            <div class="aspect-video bg-slate-100 relative overflow-hidden">
                @if($template->preview_image)
                    <img src="{{ asset('storage/templates/' . $template->slug . '/' . $template->preview_image) }}" 
                         onerror="this.onerror=null; this.src='https://placehold.co/600x400/3b82f6/FFFFFF?text={{ urlencode($template->name) }}'"
                         alt="{{ $template->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                @else
                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-slate-50 to-slate-100 text-slate-300">
                        <i class="fas fa-image text-4xl"></i>
                    </div>
                @endif
                
                <div class="absolute top-4 right-4">
                    <span class="px-4 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg {{ $template->is_active ? 'bg-emerald-500 text-white' : 'bg-slate-500 text-white' }}">
                        {{ $template->is_active ? 'Active' : 'Disabled' }}
                    </span>
                </div>
            </div>

            <div class="p-8">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-xl font-black text-slate-900 tracking-tight">{{ $template->name }}</h3>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-1">{{ $template->slug }}</p>
                    </div>
                </div>

                <p class="text-slate-600 text-sm font-medium mb-8 leading-relaxed line-clamp-2">
                    {{ $template->description ?? 'No description provided for this template.' }}
                </p>

                <div class="flex items-center justify-between border-t border-slate-50 pt-6">
                    <form action="{{ route('superadmin.templates.toggle', $template->id) }}" method="POST">
                        @csrf
                        @if($template->slug === 'default')
                            <button type="button" disabled class="px-6 py-2 bg-slate-100 text-slate-400 rounded-xl text-xs font-black uppercase tracking-widest cursor-not-allowed">
                                Default Node
                            </button>
                        @else
                            <button type="submit" class="inline-flex items-center gap-2 px-6 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all {{ $template->is_active ? 'bg-amber-50 text-amber-600 hover:bg-amber-100' : 'bg-emerald-50 text-emerald-600 hover:bg-emerald-100' }}">
                                <i class="fas {{ $template->is_active ? 'fa-pause' : 'fa-play' }}"></i>
                                {{ $template->is_active ? 'Disable' : 'Enable' }}
                            </button>
                        @endif
                    </form>

                    <div class="flex items-center gap-2">
                         <a href="{{ route('superadmin.templates.preview', $template->slug) }}" target="_blank" class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-blue-500 hover:bg-blue-600 hover:text-white transition-all group/info relative" title="Live Preview">
                            <i class="fas fa-eye text-xs"></i>
                            <div class="absolute bottom-full mb-4 left-1/2 -translate-x-1/2 w-48 p-4 bg-slate-900 text-white text-[10px] rounded-2xl opacity-0 group-hover/info:opacity-100 transition-all pointer-events-none shadow-2xl z-50">
                                <div class="font-black mb-1 uppercase tracking-widest text-blue-400">View Template</div>
                                <p>Click to see a live preview of this theme with demo data.</p>
                                <hr class="my-2 border-slate-700">
                                <div class="font-black mb-1 uppercase tracking-widest text-slate-400">Config:</div>
                                <pre class="whitespace-pre-wrap">{{ json_encode($template->config, JSON_PRETTY_PRINT) }}</pre>
                            </div>
                         </a>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <!-- Add New Theme Card (triggers modal) -->
    <div @click="addThemeOpen = true"
         class="relative group bg-slate-50 rounded-[2.5rem] border-2 border-dashed border-slate-200 p-8 flex flex-col items-center justify-center text-center hover:border-indigo-300 hover:bg-white transition-all duration-500 cursor-pointer">
        <div class="w-20 h-20 bg-white rounded-[2rem] shadow-sm border border-slate-100 flex items-center justify-center mb-6 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500">
            <i class="fas fa-plus text-slate-300 group-hover:text-indigo-500 text-2xl"></i>
        </div>
        <h3 class="text-lg font-black text-slate-400 group-hover:text-slate-900 transition-colors">Add New Theme</h3>
        <p class="text-xs font-medium text-slate-400 mt-2">Click to see how to add a new theme block</p>
    </div>
</div>

{{-- ══════════ Add Theme Modal ══════════ --}}
<div x-show="addThemeOpen"
     x-cloak
     class="fixed inset-0 z-50 overflow-y-auto"
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0">
    <div class="flex min-h-full items-center justify-center p-4">
        {{-- Backdrop --}}
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="addThemeOpen = false"></div>

        {{-- Modal panel --}}
        <div class="relative w-full max-w-2xl bg-white rounded-[2.5rem] shadow-2xl z-10 overflow-hidden"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0">

            {{-- Header --}}
            <div class="px-10 pt-10 pb-6 border-b border-slate-100">
                <div class="flex items-start justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-indigo-50 rounded-2xl flex items-center justify-center">
                            <i class="fas fa-file-archive text-indigo-500 text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-black text-slate-900 tracking-tight">Upload a New Theme</h2>
                            <p class="text-sm text-slate-500 mt-0.5">Upload a .ZIP file containing your Lego block theme.</p>
                        </div>
                    </div>
                    <button @click="addThemeOpen = false" class="w-9 h-9 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-500 transition-colors">
                        <i class="fas fa-times text-sm"></i>
                    </button>
                </div>
            </div>

            {{-- Form & Instructions --}}
            <form action="{{ route('superadmin.templates.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="px-10 py-8 space-y-6">
                    
                    {{-- File Input --}}
                    <div class="p-8 border-2 border-dashed border-indigo-200 rounded-2xl text-center bg-indigo-50/50 hover:bg-indigo-50 transition-colors">
                        <input type="file" name="theme_zip" id="theme_zip" accept=".zip" class="hidden" onchange="document.getElementById('file-chosen').textContent = this.files[0] ? this.files[0].name : 'No file chosen'">
                        <label for="theme_zip" class="cursor-pointer flex flex-col items-center justify-center">
                            <div class="w-16 h-16 rounded-full bg-white shadow-sm flex items-center justify-center mb-4 text-indigo-500">
                                <i class="fas fa-cloud-upload-alt text-2xl"></i>
                            </div>
                            <span class="text-sm font-black text-indigo-600 px-4 py-2 bg-white rounded-lg shadow-sm border border-indigo-100 hover:bg-indigo-50 transition-colors">Browse for .ZIP file</span>
                            <span id="file-chosen" class="mt-3 text-xs font-medium text-slate-500">No file chosen</span>
                        </label>
                        @error('theme_zip')
                            <p class="mt-2 text-xs text-red-500 font-bold">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Documentation --}}
                    <div class="bg-slate-50 rounded-2xl p-6 border border-slate-100">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-sm font-black text-slate-900 flex items-center gap-2">
                                <i class="fas fa-book-open text-blue-500"></i> Theme Development Guide
                            </h4>
                            <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Strict Constraints</span>
                        </div>
                        
                        <p class="text-[11px] text-slate-500 mb-6 leading-relaxed">
                            Your ZIP file must contain the following structural files at its root. <b>No external CSS/JS processing is run on these files</b>. Ensure your theme relies solely on CDN resources (like Tailwind via CDN) or existing compiled assets (`app.css`, `app.js`).
                        </p>
                        
                        <div class="space-y-6">
                            {{-- Manifest --}}
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-file-code text-indigo-500 text-xs"></i>
                                </div>
                                <div class="w-full">
                                    <p class="text-xs font-bold text-slate-800">manifest.json <span class="bg-red-100 text-red-600 px-1.5 py-0.5 rounded text-[9px] uppercase tracking-wider ml-1">Required</span></p>
                                    <p class="text-[10px] text-slate-500 mt-1 mb-2">Defines your theme's metadata. Create exactly this:</p>
                                    <pre class="px-3 py-2 bg-slate-900 border border-slate-800 text-emerald-400 text-[10px] rounded-lg font-mono overflow-x-auto">
{
  "name": "My Epic Theme",
  "description": "A high converting landing page.",
  "preview_image": "preview.png",
  "config": { "primary": "#3b82f6" }
}</pre>
                                </div>
                            </div>
                            
                            {{-- Index Blade --}}
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center flex-shrink-0">
                                    <i class="fab fa-php text-indigo-500 text-xs"></i>
                                </div>
                                <div class="w-full">
                                    <p class="text-xs font-bold text-slate-800">index.blade.php <span class="bg-red-100 text-red-600 px-1.5 py-0.5 rounded text-[9px] uppercase tracking-wider ml-1">Required</span></p>
                                    <p class="text-[10px] text-slate-500 mt-1 mb-2">
                                        The main layout. The backend automatically injects the following variables into this view:
                                    </p>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mt-2">
                                        <div class="bg-white border border-slate-200 p-2 rounded-lg">
                                            <p class="text-[10px] font-bold text-indigo-600 mb-1">$clinic</p>
                                            <p class="text-[9px] text-slate-600 leading-tight">Access `id`, `name`, `slug`, `email`, `phone`, `address`, `logo` (Storage::url).</p>
                                        </div>
                                        <div class="bg-white border border-slate-200 p-2 rounded-lg">
                                            <p class="text-[10px] font-bold text-indigo-600 mb-1">$content</p>
                                            <p class="text-[9px] text-slate-600 leading-tight">Access `hero_title`, `about_description`, `theme_primary_color`, `meta_title`, etc.</p>
                                        </div>
                                        <div class="bg-white border border-slate-200 p-2 rounded-lg">
                                            <p class="text-[10px] font-bold text-indigo-600 mb-1">$services / $testimonials</p>
                                            <p class="text-[9px] text-slate-600 leading-tight">Iterable arrays of service and review data.</p>
                                        </div>
                                    </div>
                                    <p class="text-[10px] text-slate-500 mt-3 font-semibold">Example Usage:</p>
                                    <pre class="mt-1 px-3 py-2 bg-slate-900 border border-slate-800 text-blue-300 text-[10px] rounded-lg font-mono overflow-x-auto">
@verbatim&lt;h1&gt;{{ $content->hero_title ?? $clinic->name }}&lt;/h1&gt;
&lt;a href="{{ route('clinic.book', $clinic->slug) }}"&gt;Book Now&lt;/a&gt;@endverbatim</pre>
                                </div>
                            </div>

                            {{-- Preview Image --}}
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-image text-indigo-500 text-xs"></i>
                                </div>
                                <div class="w-full">
                                    <p class="text-xs font-bold text-slate-800">preview.png <span class="bg-slate-200 text-slate-600 px-1.5 py-0.5 rounded text-[9px] uppercase tracking-wider ml-1">Optional</span></p>
                                    <p class="text-[10px] text-slate-500 mt-1">Provide an 800x600 preview screenshot showing what your theme looks like. If omitted, a generic placeholder will be rendered.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="border-t border-slate-100 pt-6 flex items-center justify-between">
                        <a href="{{ route('superadmin.templates.download-sample') }}" class="px-4 py-2 text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 text-xs font-bold rounded-lg transition-colors flex items-center gap-2">
                            <i class="fas fa-download"></i> Download Sample ZIP
                        </a>
                        <div class="flex items-center gap-3">
                            <button type="button" @click="addThemeOpen = false" class="px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-bold rounded-xl transition-colors">
                                Cancel
                            </button>
                            <button type="submit" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-black uppercase tracking-widest rounded-xl transition-all shadow-lg shadow-indigo-200 flex items-center gap-2">
                                <i class="fas fa-upload"></i> Upload & Install
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
@endsection
