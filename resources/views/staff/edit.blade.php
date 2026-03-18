@extends(request()->has('iframe') ? 'layouts.iframe' : 'layouts.app')

@section('page-title', 'Refine Team Profile')

@section('content')
<div class="max-w-3xl mx-auto page-fade-in">
    <!-- Breadcrumb & Title -->
    <div class="flex items-center space-x-4 mb-8">
        <a href="{{ route('clinic.staff.index') }}" class="p-2 bg-white border border-slate-200 rounded-xl text-slate-400 hover:text-indigo-600 hover:border-indigo-100 transition-all shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Refine Team Profile</h1>
            <p class="text-sm text-slate-500 font-medium">Updating access and identity details for <span class="text-indigo-600 font-bold">{{ $staff->name }}</span>.</p>
        </div>
    </div>

    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-200 overflow-hidden">
        <form action="{{ route('clinic.staff.update', $staff) }}" method="POST" class="p-8 lg:p-12">
    @if(request()->has('iframe'))
        <input type="hidden" name="iframe" value="1">
    @endif

            @csrf
            @method('PUT')
            
            <div class="space-y-8">
                <!-- Profile Identity -->
                <div class="space-y-6">
                    <div class="flex items-center space-x-3 pb-2 border-b border-slate-100">
                        <div class="w-8 h-8 bg-indigo-50 rounded-lg flex items-center justify-center text-indigo-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <h3 class="text-sm font-black text-slate-900 uppercase tracking-widest">Identity Details</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="name" class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Full Legal Name</label>
                            <input type="text" id="name" name="name" value="{{ old('name', $staff->name) }}" required
                                   class="w-full px-5 py-4 bg-slate-50 border-slate-200 rounded-2xl text-slate-900 font-bold focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all outline-none">
                            @error('name') <p class="text-rose-500 text-[10px] font-bold mt-1 ml-1 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="email" class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Professional Email</label>
                            <input type="email" id="email" name="email" value="{{ old('email', $staff->email) }}" required
                                   class="w-full px-5 py-4 bg-slate-50 border-slate-200 rounded-2xl text-slate-900 font-bold focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all outline-none">
                            @error('email') <p class="text-rose-500 text-[10px] font-bold mt-1 ml-1 uppercase">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Access & Permissions -->
                <div class="space-y-6">
                    <div class="flex items-center space-x-3 pb-2 border-b border-slate-100">
                        <div class="w-8 h-8 bg-emerald-50 rounded-lg flex items-center justify-center text-emerald-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                        </div>
                        <h3 class="text-sm font-black text-slate-900 uppercase tracking-widest">Access Control</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="role" class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-1">System Role</label>
                            <select id="role" name="role" required
                                    class="w-full px-5 py-4 bg-slate-50 border-slate-200 rounded-2xl text-slate-900 font-bold focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all outline-none appearance-none">
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}" 
                                        {{ old('role', $staff->roles->first()?->name) === $role->name ? 'selected' : '' }}>
                                        {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role') <p class="text-rose-500 text-[10px] font-bold mt-1 ml-1 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div class="flex items-center pt-8">
                            <label class="relative inline-flex items-center cursor-pointer group">
                                <input type="checkbox" name="is_active" value="1" 
                                       {{ old('is_active', $staff->is_active) ? 'checked' : '' }}
                                       class="sr-only peer">
                                <div class="w-14 h-8 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-indigo-600"></div>
                                <span class="ml-3 text-sm font-black text-slate-700 uppercase tracking-widest group-hover:text-indigo-600 transition-colors">Account Active</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-4 pt-4 border-t border-slate-100">
                    <button type="submit" class="flex-1 px-8 py-4 bg-indigo-600 text-white font-black rounded-2xl shadow-xl shadow-indigo-600/20 hover:bg-indigo-700 transition-all hover:scale-[1.02] active:scale-95">
                        Synchronize Changes
                    </button>
                    <a href="{{ route('clinic.staff.index') }}" class="px-8 py-4 bg-slate-100 text-slate-600 font-bold rounded-2xl hover:bg-slate-200 transition-all text-center">
                        Cancel
                    </a>
                </div>
            </div>
        </form>
    </div>
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
