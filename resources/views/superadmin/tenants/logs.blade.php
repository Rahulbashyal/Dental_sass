@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-xl font-semibold text-slate-800">Provisioning Logs: {{ $tenant->id }}</h2>
                <p class="text-sm text-slate-500">History of provisioning steps and errors for this tenant.</p>
            </div>
            <a href="{{ route('superadmin.tenants.edit', $tenant->id) }}" class="text-sm text-slate-600 hover:underline">Back to Tenant</a>
        </div>

        <div class="space-y-4">
            @foreach($logs as $log)
                <div class="border p-3 rounded">
                    <div class="flex justify-between">
                        <div class="text-sm font-medium text-slate-700">{{ $log->level }}</div>
                        <div class="text-xs text-slate-500">{{ $log->created_at->format('Y-m-d H:i:s') }}</div>
                    </div>
                    <div class="mt-2 text-sm text-slate-800">{{ $log->message }}</div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $logs->links() }}
        </div>
    </div>
</div>
@endsection
