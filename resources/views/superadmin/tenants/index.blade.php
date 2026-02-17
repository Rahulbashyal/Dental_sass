@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-semibold text-slate-800">Tenants</h2>
            <p class="text-sm text-slate-500">Manage tenant clinics, domains and per-tenant settings.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('superadmin.tenants.create') }}" class="inline-flex items-center px-4 py-2 bg-sky-600 hover:bg-sky-700 text-black text-sm font-medium rounded shadow">Create Tenant</a>
        </div>
    </div>

    @if(session('status'))
        <div class="mb-4 rounded-md bg-green-50 border border-green-100 p-3 text-sm text-green-700">{{ session('status') }}</div>
    @section('content')
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold">Tenants</h1>
            <a href="{{ route('superadmin.tenants.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded">Create Tenant</a>
        </div>

        <div class="bg-white rounded shadow overflow-hidden">
            <div class="p-4 border-b">
                <form method="GET" action="" class="flex gap-2">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Search tenants..." class="border rounded px-3 py-2 w-full" />
                    <button class="px-4 py-2 bg-gray-100 rounded">Search</button>
                </form>
            </div>

            <table class="min-w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-6 py-3 text-left">ID</th>
                        <th class="px-6 py-3 text-left">Name</th>
                        <th class="px-6 py-3 text-left">Domains</th>
                        <th class="px-6 py-3 text-left">Status</th>
                        <th class="px-6 py-3 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($tenants as $tenant)
                    <tr class="border-t">
                        <td class="px-6 py-4 align-top">{{ $tenant->id }}</td>
                        <td class="px-6 py-4 align-top">{{ data_get($tenant->data, 'name', '-') }}</td>
                        <td class="px-6 py-4 align-top">
                            @foreach(\Stancl\Tenancy\Database\Models\Domain::where('tenant_id', $tenant->id)->get() as $d)
                                <span class="inline-block bg-gray-100 px-2 py-1 rounded mr-1">{{ $d->domain }}</span>
                            @endforeach
                        </td>
                        <td class="px-6 py-4 align-top">
                            @php($status = data_get($tenant->data, 'provision_status', 'unknown'))
                            @if($status === 'in_progress' || $status === 'pending')
                                <span class="text-yellow-600">{{ $status }}</span>
                            @elseif($status === 'completed')
                                <span class="text-green-600">{{ $status }}</span>
                            @else
                                <span class="text-gray-600">{{ $status }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 align-top">
                            <a href="{{ route('superadmin.tenants.edit', $tenant->id) }}" class="text-blue-600">Edit</a>
                            <form action="{{ route('superadmin.tenants.destroy', $tenant->id) }}" method="POST" style="display:inline">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600 ml-2" onclick="return confirm('Delete tenant?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-8 text-center text-gray-500">No tenants found. <a href="{{ route('superadmin.tenants.create') }}" class="text-blue-600">Create one</a>.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $tenants->withQueryString()->links() }}
        </div>
    </div>
    @endsection
