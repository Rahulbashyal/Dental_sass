@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex items-start justify-between mb-4">
            <div>
                <h2 class="text-xl font-semibold text-slate-800">Create Tenant</h2>
                <p class="text-sm text-slate-500">Create a new clinic tenant with optional SMTP settings.</p>
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

        <form method="POST" action="{{ route('superadmin.tenants.store') }}" class="space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-medium text-slate-700">Tenant ID</label>
                <input name="id" required value="{{ old('id') }}" class="mt-1 block w-full rounded-md border border-slate-200 bg-white text-slate-800 placeholder-slate-400 px-3 py-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition duration-150" />
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">Domain</label>
                <input name="domain" required value="{{ old('domain') }}" class="mt-1 block w-full rounded-md border border-slate-200 bg-white text-slate-800 placeholder-slate-400 px-3 py-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition duration-150" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700">Admin Email</label>
                    <input name="admin_email" required type="email" value="{{ old('admin_email') }}" class="mt-1 block w-full rounded-md border border-slate-200 bg-white text-slate-800 placeholder-slate-400 px-3 py-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition duration-150" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Admin Password</label>
                    <input name="admin_password" required type="password" class="mt-1 block w-full rounded-md border border-slate-200 bg-white text-slate-800 placeholder-slate-400 px-3 py-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition duration-150" />
                </div>
            </div>

            <div class="border-t pt-4">
                <h3 class="text-sm font-semibold text-slate-800 mb-2">SMTP (optional)</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700">SMTP Host</label>
                        <input name="smtp_host" value="{{ old('smtp_host') }}" class="mt-1 block w-full rounded-md border border-slate-200 bg-white text-slate-800 placeholder-slate-400 px-3 py-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition duration-150" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Port</label>
                        <input name="smtp_port" value="{{ old('smtp_port') }}" class="mt-1 block w-full rounded-md border border-slate-200 bg-white text-slate-800 placeholder-slate-400 px-3 py-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition duration-150" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Encryption</label>
                        <input name="smtp_encryption" value="{{ old('smtp_encryption') }}" class="mt-1 block w-full rounded-md border border-slate-200 bg-white text-slate-800 placeholder-slate-400 px-3 py-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition duration-150" />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700">SMTP Username</label>
                        <input name="smtp_username" value="{{ old('smtp_username') }}" class="mt-1 block w-full rounded-md border border-slate-200 bg-white text-slate-800 placeholder-slate-400 px-3 py-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition duration-150" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">SMTP Password</label>
                        <input name="smtp_password" value="" class="mt-1 block w-full rounded-md border border-slate-200 bg-white text-slate-800 placeholder-slate-400 px-3 py-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition duration-150" />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700">From Address</label>
                        <input name="from_address" value="{{ old('from_address') }}" class="mt-1 block w-full rounded-md border border-slate-200 bg-white text-slate-800 placeholder-slate-400 px-3 py-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition duration-150" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">From Name</label>
                        <input name="from_name" value="{{ old('from_name') }}" class="mt-1 block w-full rounded-md border border-slate-200 bg-white text-slate-800 placeholder-slate-400 px-3 py-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition duration-150" />
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('superadmin.tenants.index') }}" class="inline-flex items-center px-4 py-2 border rounded text-sm text-slate-700 hover:bg-slate-50">Cancel</a>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-sky-600 hover:bg-sky-700 text-black text-sm font-medium rounded">Create Tenant</button>
            </div>
        </form>
    </div>
</div>
@endsection
