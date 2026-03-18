@extends(request()->has('iframe') ? 'layouts.iframe' : 'layouts.app')

@section('content')
<!-- Header -->
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-12">
    <div>
        <h1 class="text-4xl font-black text-slate-900 tracking-tight flex items-center gap-4">
            <div class="w-12 h-12 bg-white border border-slate-100 rounded-2xl flex items-center justify-center shadow-sm">
                <i class="fas fa-plus text-blue-500"></i>
            </div>
            Initialize New Tenant
        </h1>
        <p class="text-slate-500 font-medium mt-2 flex items-center gap-2 italic">
            <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
            Deploying a new isolated infrastructure node.
        </p>
    </div>
    <div class="flex items-center gap-4">
        <a href="{{ route('superadmin.tenants.index') }}" class="btn-premium-outline !py-3 !px-6 !rounded-2xl !text-xs !bg-white group">
            <i class="fas fa-arrow-left mr-2 text-slate-400 group-hover:text-slate-900 transition-colors"></i>
            Back to Registry
        </a>
    </div>
</div>

<form method="POST" action="{{ route('superadmin.tenants.store') }}" class="space-y-10">
    @if(request()->has('iframe'))
        <input type="hidden" name="iframe" value="1">
    @endif

    @csrf

    <!-- Node Configuration Card -->
    <div class="relative bg-white rounded-[3rem] border border-slate-100 p-10 hover:shadow-2xl transition-all duration-700 overflow-hidden">
        <div class="absolute -top-24 -right-24 w-64 h-64 bg-blue-50/30 rounded-full blur-[100px] pointer-events-none"></div>
        
        <div class="relative z-10 flex items-center gap-4 mb-10 pb-6 border-b border-slate-50">
            <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center shadow-sm">
                <i class="fas fa-server text-xs"></i>
            </div>
            <div>
                <h3 class="text-lg font-black text-slate-900 tracking-tight">Node Configuration</h3>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5 italic">Primary Infrastructure Mapping</p>
            </div>
        </div>

        <div class="relative z-10 grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-3">
                <label for="id" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">
                    Infrastructure ID <span class="text-rose-500">*</span>
                </label>
                <input name="id" id="id" required value="{{ old('id') }}"
                       class="w-full px-5 py-4 bg-slate-50/50 border border-slate-100 rounded-2xl text-[11px] font-bold text-slate-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500/30 transition-all placeholder-slate-400"
                       placeholder="e.g. clinic-alpha">
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest ml-1 italic">Must be unique and lowercase</p>
            </div>

            <div class="space-y-3">
                <label for="domain" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">
                    Global Domain <span class="text-rose-500">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                        <i class="fas fa-globe text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
                    </div>
                    <input name="domain" id="domain" required value="{{ old('domain') }}"
                           class="w-full pl-12 pr-4 py-4 bg-slate-50/50 border border-slate-100 rounded-2xl text-[11px] font-bold text-slate-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500/30 transition-all placeholder-slate-400"
                           placeholder="e.g. alpha.dentalsass.com">
                </div>
            </div>

            <div class="space-y-3">
                <label for="admin_email" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">
                    Node Master Email <span class="text-rose-500">*</span>
                </label>
                <input name="admin_email" id="admin_email" required type="email" value="{{ old('admin_email') }}"
                       class="w-full px-5 py-4 bg-slate-50/50 border border-slate-100 rounded-2xl text-[11px] font-bold text-slate-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500/30 transition-all placeholder-slate-400"
                       placeholder="admin@clinic.com">
            </div>

            <div class="space-y-3">
                <label for="admin_password" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">
                    Provision Passcode <span class="text-rose-500">*</span>
                </label>
                <input name="admin_password" id="admin_password" required type="password"
                       class="w-full px-5 py-4 bg-slate-50/50 border border-slate-100 rounded-2xl text-[11px] font-bold text-slate-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500/30 transition-all placeholder-slate-400"
                       placeholder="••••••••">
            </div>
        </div>
    </div>

    <!-- SMTP Protocol Card -->
    <div class="relative bg-white rounded-[3rem] border border-slate-100 p-10 hover:shadow-2xl transition-all duration-700 overflow-hidden">
        <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-slate-50/50 rounded-full blur-[100px] pointer-events-none"></div>

        <div class="relative z-10 flex items-center gap-4 mb-10 pb-6 border-b border-slate-50">
            <div class="w-10 h-10 bg-slate-50 text-slate-600 rounded-xl flex items-center justify-center shadow-sm">
                <i class="fas fa-paper-plane text-xs"></i>
            </div>
            <div>
                <h3 class="text-lg font-black text-slate-900 tracking-tight">SMTP Protocol</h3>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5 italic">Communication Gateway (Optional)</p>
            </div>
        </div>

        <div class="relative z-10 grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="space-y-3">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Gateway Host</label>
                <input name="smtp_host" value="{{ old('smtp_host') }}"
                       class="w-full px-5 py-4 bg-slate-50/50 border border-slate-100 rounded-2xl text-[11px] font-bold text-slate-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500/30 transition-all placeholder-slate-400"
                       placeholder="smtp.mailtrap.io">
            </div>
            <div class="space-y-3">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Port</label>
                <input name="smtp_port" value="{{ old('smtp_port') }}"
                       class="w-full px-5 py-4 bg-slate-50/50 border border-slate-100 rounded-2xl text-[11px] font-bold text-slate-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500/30 transition-all placeholder-slate-400"
                       placeholder="587">
            </div>
            <div class="space-y-3">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Encryption</label>
                <input name="smtp_encryption" value="{{ old('smtp_encryption') }}"
                       class="w-full px-5 py-4 bg-slate-50/50 border border-slate-100 rounded-2xl text-[11px] font-bold text-slate-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500/30 transition-all placeholder-slate-400"
                       placeholder="tls">
            </div>
            <div class="space-y-3">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Username</label>
                <input name="smtp_username" value="{{ old('smtp_username') }}"
                       class="w-full px-5 py-4 bg-slate-50/50 border border-slate-100 rounded-2xl text-[11px] font-bold text-slate-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500/30 transition-all placeholder-slate-400">
            </div>
            <div class="space-y-3">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Security Key</label>
                <input name="smtp_password" type="password" value=""
                       class="w-full px-5 py-4 bg-slate-50/50 border border-slate-100 rounded-2xl text-[11px] font-bold text-slate-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500/30 transition-all placeholder-slate-400">
            </div>
            <div class="space-y-3">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Sender Alias</label>
                <input name="from_name" value="{{ old('from_name') }}"
                       class="w-full px-5 py-4 bg-slate-50/50 border border-slate-100 rounded-2xl text-[11px] font-bold text-slate-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500/30 transition-all placeholder-slate-400"
                       placeholder="Clinic Name">
            </div>
            <div class="md:col-span-2 space-y-3">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Broadcast Address</label>
                <input name="from_address" value="{{ old('from_address') }}"
                       class="w-full px-5 py-4 bg-slate-50/50 border border-slate-100 rounded-2xl text-[11px] font-bold text-slate-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500/30 transition-all placeholder-slate-400"
                       placeholder="noreply@clinic.com">
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="flex items-center justify-end gap-6 pt-6 mb-12">
        <a href="{{ route('superadmin.tenants.index') }}" class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] hover:text-slate-900 transition-colors px-6">
            Cancel Operation
        </a>
        <button type="submit" class="btn-premium-primary !py-5 !px-12 !rounded-[2rem] !text-xs !bg-slate-900 !text-white flex items-center gap-4 hover:!bg-blue-600 transition-all shadow-2xl shadow-slate-900/10 hover:shadow-blue-500/30 group">
            <i class="fas fa-rocket text-blue-400 group-hover:text-white transition-all"></i>
            Deploy Infrastructure
        </button>
    </div>
</form>
@endsection


{{-- Auto-close modal script on success --}}
@if(session('success') && request()->has('iframe'))
    <script>
        setTimeout(() => {
            window.parent.location.reload();
        }, 1500);
    </script>
@endif
