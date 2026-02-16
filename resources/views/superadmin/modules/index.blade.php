@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-semibold text-slate-800">System Modules</h2>
            <p class="text-sm text-slate-500">Enable or disable core features across the entire SaaS platform.</p>
        </div>
    </div>

    @if(session('status'))
        <div class="mb-4 rounded-md bg-green-50 border border-green-100 p-3 text-sm text-green-700">
            {{ session('status') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="mb-4 rounded-md bg-red-50 border border-red-100 p-3 text-sm text-red-700">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($modules as $module)
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden flex flex-col">
                <div class="p-6 flex-1">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 bg-indigo-50 rounded-lg">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 011-1h1a2 2 0 100-4H7a1 1 0 01-1-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-slate-900">{{ $module->display_name }}</h3>
                                <span class="text-xs font-mono text-slate-400">{{ $module->name }}</span>
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            @if($module->is_active)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Active
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800">
                                    Disabled
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <p class="text-sm text-slate-600 mb-4 h-12 overflow-hidden">
                        {{ $module->description ?? 'No description provided for this module.' }}
                    </p>

                    @if($module->dependencies)
                        <div class="mt-4">
                            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block mb-2">Dependencies</span>
                            <div class="flex flex-wrap gap-1">
                                @foreach($module->dependencies as $dep)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-slate-100 text-slate-700 border border-slate-200">
                                        {{ $dep }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex items-center justify-between">
                    <form action="{{ route('superadmin.modules.toggle', $module) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" @if($module->is_core) disabled @endif
                            class="text-sm font-semibold {{ $module->is_active ? 'text-red-600 hover:text-red-800' : 'text-green-600 hover:text-green-800' }} {{ $module->is_core ? 'opacity-50 cursor-not-allowed' : '' }}">
                            {{ $module->is_active ? 'Disable Globally' : 'Enable Globally' }}
                        </button>
                    </form>
                    
                    <button type="button" onclick="editModule('{{ $module->id }}', '{{ $module->display_name }}')" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800">
                        Settings
                    </button>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-xl shadow-sm border border-slate-200 p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-slate-900">No modules discovered</h3>
                <p class="mt-1 text-sm text-slate-500">Ensure your modules are located in the /Modules directory.</p>
            </div>
        @endforelse
    </div>
</div>

<!-- Simple Modal for Editing -->
<div id="edit-modal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-slate-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
            <form id="edit-form" method="POST">
                @csrf
                @method('PUT')
                <div>
                    <h3 class="text-lg leading-6 font-medium text-slate-900" id="modal-title">Module Settings</h3>
                    <div class="mt-4">
                        <label for="display_name" class="block text-sm font-medium text-slate-700">Display Name</label>
                        <input type="text" name="display_name" id="display_name" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                </div>
                <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:col-start-2 sm:text-sm">
                        Save
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
function editModule(id, name) {
    const modal = document.getElementById('edit-modal');
    const form = document.getElementById('edit-form');
    const input = document.getElementById('display_name');
    
    form.action = `/superadmin/modules/${id}`;
    input.value = name;
    
    modal.classList.remove('hidden');
}

function closeModal() {
    document.getElementById('edit-modal').classList.add('hidden');
}
</script>
@endsection
