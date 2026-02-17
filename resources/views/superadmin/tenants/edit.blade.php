@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex items-start justify-between mb-4">
            <div>
                <h2 class="text-xl font-semibold text-slate-800">Edit Tenant: {{ $tenant->id }}</h2>
                <p class="text-sm text-slate-500">Update domain and SMTP configuration for this tenant.</p>
            </div>
            <a href="{{ route('superadmin.tenants.index') }}" class="text-sm text-slate-600 hover:underline">Back to Tenants</a>
        </div>

        @if ($errors->any())
            <div class="mb-4 rounded-md bg-red-50 border border-red-100 p-3 text-sm text-red-700">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @php
            $domain = optional(\Stancl\Tenancy\Database\Models\Domain::where('tenant_id', $tenant->id)->first())->domain;
            $config = $tenant->data['config'] ?? [];
            $mail = $config['mail'] ?? [];
            $mailer = $mail['mailers']['smtp'] ?? [];
        @endphp

        <form method="POST" action="{{ route('superadmin.tenants.update', $tenant->id) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-slate-700">Domain</label>
                <input name="domain" required value="{{ old('domain', $domain) }}" class="mt-1 block w-full rounded-md border border-slate-200 bg-white text-slate-800 placeholder-slate-400 px-3 py-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition duration-150" />
            </div>

            <div class="border-t pt-4">
                <h3 class="text-sm font-semibold text-slate-800 mb-2">SMTP (optional)</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700">SMTP Host</label>
                        <input name="smtp_host" value="{{ old('smtp_host', $mailer['host'] ?? '') }}" class="mt-1 block w-full rounded-md border border-slate-200 bg-white text-slate-800 placeholder-slate-400 px-3 py-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition duration-150" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Port</label>
                        <input name="smtp_port" value="{{ old('smtp_port', $mailer['port'] ?? '') }}" class="mt-1 block w-full rounded-md border border-slate-200 bg-white text-slate-800 placeholder-slate-400 px-3 py-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition duration-150" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Encryption</label>
                        <input name="smtp_encryption" value="{{ old('smtp_encryption', $mailer['encryption'] ?? '') }}" class="mt-1 block w-full rounded-md border border-slate-200 bg-white text-slate-800 placeholder-slate-400 px-3 py-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition duration-150" />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700">SMTP Username</label>
                        <input name="smtp_username" value="{{ old('smtp_username', $mailer['username'] ?? '') }}" class="mt-1 block w-full rounded-md border border-slate-200 bg-white text-slate-800 placeholder-slate-400 px-3 py-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition duration-150" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">SMTP Password</label>
                        <input name="smtp_password" value="" class="mt-1 block w-full rounded-md border border-slate-200 bg-white text-slate-800 placeholder-slate-400 px-3 py-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition duration-150" />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700">From Address</label>
                        <input name="from_address" value="{{ old('from_address', $mail['from']['address'] ?? '') }}" class="mt-1 block w-full rounded-md border border-slate-200 bg-white text-slate-800 placeholder-slate-400 px-3 py-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition duration-150" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">From Name</label>
                        <input name="from_name" value="{{ old('from_name', $mail['from']['name'] ?? '') }}" class="mt-1 block w-full rounded-md border border-slate-200 bg-white text-slate-800 placeholder-slate-400 px-3 py-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition duration-150" />
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('superadmin.tenants.index') }}" class="inline-flex items-center px-4 py-2 border rounded text-sm text-slate-700 hover:bg-slate-50">Cancel</a>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white text-sm font-medium rounded">Save</button>
            </div>
        </form>

        <div class="border-t mt-6 pt-4">
            <form method="POST" action="{{ route('superadmin.tenants.destroy', $tenant->id) }}" onsubmit="return confirm('Delete tenant record? This will remove the tenant record and domains; it will not automatically drop tenant DB unless configured.');">
                @csrf
                @method('DELETE')
                <button class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm rounded">Delete Tenant</button>
            </form>
        </div>

        @php($provisionStatus = data_get($tenant->data, 'provision_status', 'unknown'))
        @if($provisionStatus === 'failed')
            <div class="border-t mt-6 pt-4">
                <h3 class="text-sm font-semibold text-slate-800 mb-2">Provisioning failed</h3>
                <p class="text-sm text-red-600">Error: {{ data_get($tenant->data, 'provision_error', 'Unknown error') }}</p>

                <form method="POST" action="{{ route('superadmin.tenants.reprovision', $tenant->id) }}" class="mt-3 space-y-3">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Admin Email</label>
                            <input name="admin_email" type="email" required value="{{ old('admin_email', data_get($tenant->data, 'config.mail.from.address')) }}" class="mt-1 block w-full rounded-md border border-slate-200 px-3 py-2" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Admin Password</label>
                            <input name="admin_password" type="password" required class="mt-1 block w-full rounded-md border border-slate-200 px-3 py-2" />
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-medium rounded">Retry Provisioning</button>
                    </div>
                </form>
            </div>
        @endif
    </div>
</div>
@endsection
