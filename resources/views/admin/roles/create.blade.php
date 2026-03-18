@extends(request()->has('iframe') ? 'layouts.iframe' : 'layouts.app')

@section('page-title', 'Create Role')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
<!-- Header -->
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-12">
    <div>
        <h1 class="text-4xl font-black text-slate-900 tracking-tight flex items-center gap-4">
            <div class="w-12 h-12 bg-white border border-slate-100 rounded-2xl flex items-center justify-center shadow-sm">
                <i class="fas fa-plus text-indigo-500"></i>
            </div>
            Create New Authority
        </h1>
        <p class="text-slate-500 font-medium mt-2 flex items-center gap-2 italic">
            <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
            Define a new operational protocol and its permissions.
        </p>
    </div>
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.roles.index') }}" class="btn-premium-outline !py-3 !px-6 !rounded-2xl !text-xs !bg-white group">
            <i class="fas fa-arrow-left mr-2 text-slate-400 group-hover:text-slate-900 transition-colors"></i>
            Back to Registry
        </a>
    </div>
</div>

<form action="{{ route('admin.roles.store') }}" method="POST" class="space-y-10">
    @if(request()->has('iframe'))
        <input type="hidden" name="iframe" value="1">
    @endif

    @csrf

    <!-- Role Identity Card -->
    <div class="relative bg-white rounded-[3rem] border border-slate-100 p-10 hover:shadow-2xl transition-all duration-700 overflow-hidden">
        <div class="absolute -top-24 -right-24 w-64 h-64 bg-indigo-50/30 rounded-full blur-[100px] pointer-events-none"></div>
        
        <div class="relative z-10 flex items-center gap-4 mb-10 pb-6 border-b border-slate-50">
            <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center shadow-sm">
                <i class="fas fa-id-card text-xs"></i>
            </div>
            <div>
                <h3 class="text-lg font-black text-slate-900 tracking-tight">Identity & Labeling</h3>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5 italic">Base Configuration</p>
            </div>
        </div>

        <div class="relative z-10 grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-3">
                <label for="name" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">
                    Protocol Identifier <span class="text-rose-500">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                        <i class="fas fa-terminal text-slate-400 group-focus-within:text-indigo-500 transition-colors"></i>
                    </div>
                    <input type="text" name="name" id="name" required value="{{ old('name') }}"
                           class="w-full pl-12 pr-4 py-4 bg-slate-50/50 border border-slate-100 rounded-2xl text-[11px] font-bold text-slate-900 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500/30 transition-all placeholder-slate-400"
                           placeholder="e.g. branch_supervisor">
                </div>
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest ml-1 italic">Internal system key (lowercase/underscores)</p>
            </div>

            <div class="space-y-3">
                <label for="display_name" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">
                    Interface Label <span class="text-rose-500">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                        <i class="fas fa-heading text-slate-400 group-focus-within:text-indigo-500 transition-colors"></i>
                    </div>
                    <input type="text" name="display_name" id="display_name" required value="{{ old('display_name') }}"
                           class="w-full pl-12 pr-4 py-4 bg-slate-50/50 border border-slate-100 rounded-2xl text-[11px] font-bold text-slate-900 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500/30 transition-all placeholder-slate-400"
                           placeholder="e.g. Branch Supervisor">
                </div>
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest ml-1 italic">The name users will see in the UI</p>
            </div>

            <div class="col-span-full space-y-3">
                <label for="description" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">
                    Functional Summary
                </label>
                <textarea name="description" id="description" rows="3"
                          class="w-full p-5 bg-slate-50/50 border border-slate-100 rounded-3xl text-[11px] font-bold text-slate-900 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500/30 transition-all placeholder-slate-400"
                          placeholder="Outline the operational bounds for this identity node...">{{ old('description') }}</textarea>
            </div>
        </div>
    </div>

    <!-- Capabilities Registry -->
    <div class="relative bg-white rounded-[3rem] border border-slate-100 overflow-hidden hover:shadow-2xl transition-all duration-700">
        <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-violet-50/30 rounded-full blur-[100px] pointer-events-none"></div>

        <div class="relative z-10 px-10 py-8 border-b border-slate-50 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 bg-violet-50 text-violet-600 rounded-xl flex items-center justify-center shadow-sm">
                    <i class="fas fa-key text-xs"></i>
                </div>
                <div>
                    <h3 class="text-lg font-black text-slate-900 tracking-tight">Authority Schema</h3>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5 italic">Selective Grant Mapping</p>
                </div>
            </div>
            <div class="px-5 py-2 bg-slate-900 rounded-2xl text-[9px] font-black text-white uppercase tracking-[0.2em] shadow-xl shadow-slate-900/10 flex items-center gap-3">
                <i class="fas fa-shield-alt text-indigo-400"></i>
                Integrity Shield Active
            </div>
        </div>
        
        <div class="relative z-10 divide-y divide-slate-50">
            @foreach($permissions as $category => $categoryPermissions)
            <div class="p-10 hover:bg-slate-50/[0.3] transition-colors group/module">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-10 h-10 rounded-2xl bg-white border border-slate-100 flex items-center justify-center text-[11px] font-black text-indigo-500 shadow-sm transition-transform group-hover/module:scale-110 duration-500">
                        {{ substr($category, 0, 2) }}
                    </div>
                    <div class="flex-1">
                        <h4 class="text-sm font-black text-slate-900 uppercase tracking-widest">{{ str_replace('_', ' ', $category) }} Protocol</h4>
                        <div class="flex items-center gap-3 mt-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest italic">{{ count($categoryPermissions) }} ATOMIC GRANTS AVAILABLE</span>
                        </div>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($categoryPermissions as $permission)
                    <label for="perm_{{ $permission->id }}" 
                           class="flex items-center gap-4 p-5 rounded-2xl bg-slate-50/50 border border-slate-100 cursor-pointer hover:bg-white hover:border-indigo-200 hover:shadow-xl hover:shadow-indigo-500/5 transition-all duration-300 group/perm">
                        <div class="relative flex items-center justify-center">
                            <input id="perm_{{ $permission->id }}" name="permissions[]" value="{{ $permission->id }}" type="checkbox" 
                                   class="w-6 h-6 rounded-lg border-2 border-slate-200 text-indigo-600 focus:ring-indigo-500/20 focus:ring-offset-0 cursor-pointer transition-all checked:border-indigo-600">
                        </div>
                        <div class="flex-1">
                            <span class="text-[11px] font-black text-slate-700 group-hover/perm:text-indigo-600 transition-colors truncate block">
                                {{ str_replace('_', ' ', ucfirst($permission->name)) }}
                            </span>
                            <span class="text-[8px] font-bold text-slate-400 uppercase tracking-tighter block mt-0.5">CAPABILITY GRANT</span>
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Actions -->
    <div class="flex items-center justify-end gap-6 pt-6 mb-12">
        <a href="{{ route('admin.roles.index') }}" class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] hover:text-slate-900 transition-colors px-6">
            Cancel Task
        </a>
        <button type="submit" class="btn-premium-primary !py-5 !px-12 !rounded-[2rem] !text-xs !bg-slate-900 !text-white flex items-center gap-4 hover:!bg-indigo-600 transition-all shadow-2xl shadow-slate-900/10 hover:shadow-indigo-500/30 group">
            <i class="fas fa-fingerprint text-indigo-400 group-hover:text-white transition-all"></i>
            Initialize Authority Node
        </button>
    </div>
</form>
</div>
@endsection

{{-- Auto-close modal script on success --}}
@if(session('success') && request()->has('iframe'))
    <script>
        setTimeout(() => {
            window.parent.location.reload();
        }, 1500);
    </script>
@endif
