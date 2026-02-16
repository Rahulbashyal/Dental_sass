@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-semibold text-slate-800">Clinic Branches</h2>
            <p class="text-sm text-slate-500">Manage multiple locations for your clinic.</p>
        </div>
        <button type="button" onclick="openCreateModal()" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded shadow">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add Branch
        </button>
    </div>

    @if(session('status'))
        <div class="mb-4 rounded-md bg-green-50 border border-green-100 p-3 text-sm text-green-700">
            {{ session('status') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($branches as $branch)
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden flex flex-col">
                <div class="p-6 flex-1">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 bg-indigo-50 rounded-lg">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-slate-900">{{ $branch->name }}</h3>
                                @if($branch->is_main_branch)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-100 text-indigo-700 border border-indigo-200 uppercase tracking-tight">Main Branch</span>
                                @endif
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            @if($branch->is_active)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Active</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800">Inactive</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="space-y-2 text-sm text-slate-600">
                        <div class="flex items-start">
                            <svg class="h-4 w-4 mr-2 text-slate-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span>{{ $branch->address ?? 'No address provided' }}</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="h-4 w-4 mr-2 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            <span>{{ $branch->phone ?? 'No phone' }}</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="h-4 w-4 mr-2 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <span>{{ $branch->email ?? 'No email' }}</span>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex items-center justify-between">
                    <button type="button" onclick="editBranch({{ json_encode($branch) }})" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800">
                        Edit Settings
                    </button>
                    
                    @if(!$branch->is_main_branch)
                        <form action="{{ route('tenant.branches.destroy', $branch) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this branch?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-sm font-semibold text-red-600 hover:text-red-800">Delete</button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-xl shadow-sm border border-slate-200 p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-slate-900">No branches found</h3>
                <p class="mt-1 text-sm text-slate-500">Every clinic needs at least one branch. Add one to get started.</p>
                <button type="button" onclick="openCreateModal()" class="mt-6 inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                    Add First Branch
                </button>
            </div>
        @endforelse
    </div>
</div>

<!-- Modal -->
<div id="branch-modal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-slate-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
            <form id="branch-form" method="POST">
                @csrf
                <div id="method-container"></div>
                <div>
                    <h3 class="text-xl leading-6 font-bold text-slate-900" id="modal-title">Add New Branch</h3>
                    <div class="mt-6 space-y-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-slate-700">Branch Name *</label>
                            <input type="text" name="name" id="name" required class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                        <div>
                            <label for="address" class="block text-sm font-medium text-slate-700">Address</label>
                            <textarea name="address" id="address" rows="3" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="phone" class="block text-sm font-medium text-slate-700">Phone</label>
                                <input type="text" name="phone" id="phone" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium text-slate-700">Email</label>
                                <input type="email" name="email" id="email" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                        </div>
                        <div class="flex items-center space-x-6 pt-2">
                            <label class="flex items-center">
                                <input type="checkbox" name="is_active" id="is_active" value="1" checked class="rounded border-slate-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-slate-600">Active</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="is_main_branch" id="is_main_branch" value="1" class="rounded border-slate-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-slate-600">Set as Main Branch</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="mt-8 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:col-start-2 sm:text-sm">
                        Save Branch
                    </button>
                    <button type="button" onclick="closeModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-slate-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:col-start-1 sm:text-sm">
                        Cancel
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
    
    form.action = "{{ route('tenant.branches.store') }}";
    methodContainer.innerHTML = '';
    title.innerText = 'Add New Branch';
    
    // Reset form
    form.reset();
    document.getElementById('is_active').checked = true;
    
    modal.classList.remove('hidden');
}

function editBranch(branch) {
    const modal = document.getElementById('branch-modal');
    const form = document.getElementById('branch-form');
    const title = document.getElementById('modal-title');
    const methodContainer = document.getElementById('method-container');
    
    form.action = `/branches/${branch.id}`;
    methodContainer.innerHTML = '@method("PUT")';
    title.innerText = 'Edit Branch: ' + branch.name;
    
    document.getElementById('name').value = branch.name;
    document.getElementById('address').value = branch.address || '';
    document.getElementById('phone').value = branch.phone || '';
    document.getElementById('email').value = branch.email || '';
    document.getElementById('is_active').checked = branch.is_active;
    document.getElementById('is_main_branch').checked = branch.is_main_branch;
    
    modal.classList.remove('hidden');
}

function closeModal() {
    document.getElementById('branch-modal').classList.add('hidden');
}
</script>
@endsection
