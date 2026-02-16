@extends('layouts.app')

@section('content')
<div class="space-y-8 page-fade-in">
    <!-- Premium Header Section -->
    <div class="relative overflow-hidden bg-white rounded-3xl p-8 shadow-sm border border-slate-200">
        <div class="absolute top-0 right-0 -mt-20 -mr-20 w-64 h-64 bg-indigo-500/5 rounded-full blur-3xl"></div>
        <div class="relative flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
            <div>
                <div class="flex items-center space-x-3 mb-2">
                    <div class="p-2 bg-indigo-100 rounded-lg text-indigo-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <h1 class="text-3xl font-black text-slate-900 tracking-tight">Clinic Branches</h1>
                </div>
                <p class="text-slate-500 font-medium">Manage and optimize multiple locations for your dental practice.</p>
            </div>
            <button type="button" onclick="openCreateModal()" class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-2xl shadow-lg shadow-indigo-600/20 transition-all hover:-translate-y-0.5 active:scale-95">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add New Branch
            </button>
        </div>
    </div>

    @if(session('status'))
        <div class="rounded-2xl bg-emerald-50 border border-emerald-200 p-4 flex items-center text-emerald-800 shadow-sm animate-bounce-in">
            <svg class="w-5 h-5 mr-3 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
            <span class="font-bold">{{ session('status') }}</span>
        </div>
    @endif
    
    @if(session('error'))
        <div class="rounded-2xl bg-rose-50 border border-rose-200 p-4 flex items-center text-rose-800 shadow-sm">
            <svg class="w-5 h-5 mr-3 text-rose-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
            <span class="font-bold">{{ session('error') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 stagger-in">
        @forelse($branches as $branch)
            <div class="relative group bg-white rounded-3xl border border-slate-200 shadow-sm transition-all duration-300 hover:shadow-2xl hover:-translate-y-2 overflow-hidden flex flex-col">
                <!-- Status Badge Overlay -->
                <div class="absolute top-4 right-4 z-10">
                    @if($branch->is_active)
                        <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-[10px] font-black uppercase tracking-widest rounded-full border border-emerald-200 shadow-sm">Active</span>
                    @else
                        <span class="px-3 py-1 bg-slate-100 text-slate-500 text-[10px] font-black uppercase tracking-widest rounded-full border border-slate-200">Inactive</span>
                    @endif
                </div>

                <div class="p-8 pb-4 flex-1">
                    <div class="mb-6">
                        @if($branch->is_main_branch)
                        <div class="mb-2 inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-black bg-indigo-600 text-white uppercase tracking-tighter">Main HQ</div>
                        @endif
                        <h3 class="text-2xl font-black text-slate-900 group-hover:text-indigo-600 transition-colors">{{ $branch->name }}</h3>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="flex items-start bg-slate-50 p-3 rounded-2xl border border-slate-100 group-hover:bg-indigo-50/50 transition-colors">
                            <div class="p-2 bg-white rounded-xl shadow-sm mr-3 text-slate-400 group-hover:text-indigo-500">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-slate-700 leading-relaxed">{{ $branch->address ?? 'No address provided' }}</span>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-3">
                            <div class="flex items-center bg-slate-50 p-3 rounded-2xl border border-slate-100 transition-colors">
                                <svg class="h-4 w-4 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                <span class="text-xs font-bold text-slate-700 truncate">{{ $branch->phone ?? 'No phone' }}</span>
                            </div>
                            <div class="flex items-center bg-slate-50 p-3 rounded-2xl border border-slate-100 transition-colors">
                                <svg class="h-4 w-4 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                <span class="text-xs font-bold text-slate-700 truncate">{{ $branch->email ?? 'No email' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-6 pt-2 flex items-center justify-between">
                    <button type="button" onclick="editBranch({{ json_encode($branch) }})" class="flex-1 mr-2 inline-flex justify-center items-center px-4 py-2.5 bg-slate-900 text-white text-xs font-black uppercase tracking-widest rounded-2xl hover:bg-slate-800 transition-colors">
                        Edit Settings
                    </button>
                    
                    @if(!$branch->is_main_branch)
                        <form action="{{ route('clinic.branches.destroy', $branch) }}" method="POST" onsubmit="return confirm('Secure action: Confirm deletion?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2.5 text-rose-500 bg-rose-50 rounded-2xl border border-rose-100 hover:bg-rose-100 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full py-24 bg-white rounded-3xl border border-dashed border-slate-300 text-center">
                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="h-10 w-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-black text-slate-900 mb-2">Grow your network</h3>
                <p class="text-slate-500 font-medium mb-8">You haven't added any branch locations yet.</p>
                <button type="button" onclick="openCreateModal()" class="inline-flex items-center px-8 py-4 bg-indigo-600 text-white rounded-2xl font-bold shadow-lg shadow-indigo-600/20 hover:scale-105 transition-transform">
                    Initialize First Branch
                </button>
            </div>
        @endforelse
    </div>
</div>

<!-- Premium Modal Interface -->
<div id="branch-modal" class="fixed inset-0 z-[60] hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="closeModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-[2rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-200">
            <div class="absolute top-0 right-0 p-6">
                <button onclick="closeModal()" class="text-slate-400 hover:text-slate-900 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form id="branch-form" method="POST" class="p-10">
                @csrf
                <div id="method-container"></div>
                
                <div class="mb-10 text-center">
                    <div class="w-16 h-16 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600 mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900" id="modal-title">Branch Configuration</h3>
                    <p class="text-slate-500 font-medium text-sm mt-1">Configure your clinic's location details.</p>
                </div>

                <div class="space-y-6">
                    <div>
                        <label for="name" class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2">Branch Name *</label>
                        <input type="text" name="name" id="name" required placeholder="e.g. Kathmandu Downtown Clinic" class="block w-full px-5 py-4 rounded-2xl bg-slate-50 border-slate-200 text-slate-900 font-bold focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all sm:text-sm">
                    </div>
                    <div>
                        <label for="address" class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2">Physical Address</label>
                        <textarea name="address" id="address" rows="3" placeholder="Full address including landmarks..." class="block w-full px-5 py-4 rounded-2xl bg-slate-50 border-slate-200 text-slate-900 font-bold focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all sm:text-sm"></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="phone" class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2">Phone</label>
                            <input type="text" name="phone" id="phone" placeholder="+977-..." class="block w-full px-5 py-4 rounded-2xl bg-slate-50 border-slate-200 text-slate-900 font-bold focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all sm:text-sm">
                        </div>
                        <div>
                            <label for="email" class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2">Email</label>
                            <input type="email" name="email" id="email" placeholder="branch@example.com" class="block w-full px-5 py-4 rounded-2xl bg-slate-50 border-slate-200 text-slate-900 font-bold focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all sm:text-sm">
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-8 p-6 bg-slate-50 rounded-2xl border border-slate-200">
                        <label class="flex items-center group cursor-pointer">
                            <div class="relative">
                                <input type="checkbox" name="is_active" id="is_active" value="1" checked class="peer sr-only">
                                <div class="w-11 h-6 bg-slate-300 rounded-full peer peer-checked:bg-emerald-500 transition-colors"></div>
                                <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full peer-checked:translate-x-5 transition-transform"></div>
                            </div>
                            <span class="ml-3 text-sm font-black text-slate-700">Active</span>
                        </label>
                        <label class="flex items-center group cursor-pointer">
                            <div class="relative">
                                <input type="checkbox" name="is_main_branch" id="is_main_branch" value="1" class="peer sr-only">
                                <div class="w-11 h-6 bg-slate-300 rounded-full peer peer-checked:bg-indigo-600 transition-colors"></div>
                                <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full peer-checked:translate-x-5 transition-transform"></div>
                            </div>
                            <span class="ml-3 text-sm font-black text-slate-700">Set HQ</span>
                        </label>
                    </div>
                </div>

                <div class="mt-10 flex flex-col sm:flex-row gap-4">
                    <button type="submit" class="flex-1 px-8 py-4 bg-indigo-600 text-white font-black rounded-2xl shadow-xl shadow-indigo-600/20 hover:bg-indigo-700 transition-all">
                        Save Configuration
                    </button>
                    <button type="button" onclick="closeModal()" class="px-8 py-4 bg-slate-100 text-slate-600 font-bold rounded-2xl hover:bg-slate-200 transition-all">
                        Discard
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openCreateModal() {
    const modal = document.getElementById('branch-modal');
    const form = document.getElementById('branch-form');
    const title = document.getElementById('modal-title');
    const methodContainer = document.getElementById('method-container');
    
    form.action = "{{ route('clinic.branches.store') }}";
    methodContainer.innerHTML = '';
    title.innerText = 'Initialize Branch';
    
    // Reset form
    form.reset();
    document.getElementById('is_active').checked = true;
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function editBranch(branch) {
    const modal = document.getElementById('branch-modal');
    const form = document.getElementById('branch-form');
    const title = document.getElementById('modal-title');
    const methodContainer = document.getElementById('method-container');
    
    form.action = `/clinic/branches/${branch.id}`;
    methodContainer.innerHTML = '@method("PUT")';
    title.innerText = 'Update Branch';
    
    document.getElementById('name').value = branch.name;
    document.getElementById('address').value = branch.address || '';
    document.getElementById('phone').value = branch.phone || '';
    document.getElementById('email').value = branch.email || '';
    document.getElementById('is_active').checked = branch.is_active;
    document.getElementById('is_main_branch').checked = branch.is_main_branch;
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeModal() {
    const modal = document.getElementById('branch-modal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}
</script>
@endsection
