@extends(request()->has('iframe') ? 'layouts.iframe' : 'layouts.app')

@section('content')
<!-- Header -->
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-12">
    <div>
        <h1 class="text-4xl font-black text-slate-900 tracking-tight flex items-center gap-4">
            <div class="w-12 h-12 bg-white border border-slate-100 rounded-2xl flex items-center justify-center shadow-sm">
                <i class="fas fa-edit text-blue-500"></i>
            </div>
            Node Configuration: {{ $tenant->id }}
        </h1>
        <p class="text-slate-500 font-medium mt-2 flex items-center gap-2 italic">
            <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
            Modifying infrastructure mapping and communication protocols.
        </p>
    </div>
    <div class="flex items-center gap-4">
        <a href="{{ route('superadmin.tenants.index') }}" class="btn-premium-outline !py-3 !px-6 !rounded-2xl !text-xs !bg-white group">
            <i class="fas fa-arrow-left mr-2 text-slate-400 group-hover:text-slate-900 transition-colors"></i>
            Back to Registry
        </a>
    </div>
</div>

@php
    $domain = optional(\Stancl\Tenancy\Database\Models\Domain::where('tenant_id', $tenant->id)->first())->domain;
    $config = $tenant->data['config'] ?? [];
    $mail = $config['mail'] ?? [];
    $mailer = $mail['mailers']['smtp'] ?? [];
    $provisionStatus = data_get($tenant->data, 'provision_status', 'unknown');
@endphp

<div class="space-y-10">
    <!-- Primary Configuration -->
    <form method="POST" action="{{ route('superadmin.tenants.update', $tenant->
    @if(request()->has('iframe'))
        <input type="hidden" name="iframe" value="1">
    @endif
id) }}" class="space-y-10">
        @csrf
        @method('PUT')

        <div class="relative bg-white rounded-[3rem] border border-slate-100 p-10 hover:shadow-2xl transition-all duration-700 overflow-hidden">
            <div class="absolute -top-24 -right-24 w-64 h-64 bg-blue-50/30 rounded-full blur-[100px] pointer-events-none"></div>
            
            <div class="relative z-10 flex items-center gap-4 mb-10 pb-6 border-b border-slate-50">
                <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center shadow-sm">
                    <i class="fas fa-network-wired text-xs"></i>
                </div>
                <div>
                    <h3 class="text-lg font-black text-slate-900 tracking-tight">Deployment Node</h3>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5 italic">Domain and Traffic Routing</p>
                </div>
            </div>

            <div class="relative z-10 grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-3">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Traffic Domain <span class="text-rose-500">*</span></label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                            <i class="fas fa-link text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
                        </div>
                        <input name="domain" required value="{{ old('domain', $domain) }}"
                               class="w-full pl-12 pr-4 py-4 bg-slate-50/50 border border-slate-100 rounded-2xl text-[11px] font-bold text-slate-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500/30 transition-all placeholder-slate-400">
                    </div>
                </div>
            </div>
        </div>

        <!-- SMTP Protocol -->
        <div class="relative bg-white rounded-[3rem] border border-slate-100 p-10 hover:shadow-2xl transition-all duration-700 overflow-hidden">
            <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-slate-50/50 rounded-full blur-[100px] pointer-events-none"></div>

            <div class="relative z-10 flex items-center gap-4 mb-10 pb-6 border-b border-slate-50">
                <div class="w-10 h-10 bg-slate-50 text-slate-600 rounded-xl flex items-center justify-center shadow-sm">
                    <i class="fas fa-paper-plane text-xs"></i>
                </div>
                <div>
                    <h3 class="text-lg font-black text-slate-900 tracking-tight">Communication Protocol</h3>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5 italic">Isolated SMTP Configuration</p>
                </div>
            </div>

            <div class="relative z-10 grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="space-y-3">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Gateway Host</label>
                    <input name="smtp_host" value="{{ old('smtp_host', $mailer['host'] ?? '') }}"
                           class="w-full px-5 py-4 bg-slate-50/50 border border-slate-100 rounded-2xl text-[11px] font-bold text-slate-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500/30 transition-all">
                </div>
                <div class="space-y-3">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Port</label>
                    <input name="smtp_port" value="{{ old('smtp_port', $mailer['port'] ?? '') }}"
                           class="w-full px-5 py-4 bg-slate-50/50 border border-slate-100 rounded-2xl text-[11px] font-bold text-slate-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500/30 transition-all">
                </div>
                <div class="space-y-3">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Encryption Protocol</label>
                    <input name="smtp_encryption" value="{{ old('smtp_encryption', $mailer['encryption'] ?? '') }}"
                           class="w-full px-5 py-4 bg-slate-50/50 border border-slate-100 rounded-2xl text-[11px] font-bold text-slate-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500/30 transition-all">
                </div>
                <div class="space-y-3">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Username</label>
                    <input name="smtp_username" value="{{ old('smtp_username', $mailer['username'] ?? '') }}"
                           class="w-full px-5 py-4 bg-slate-50/50 border border-slate-100 rounded-2xl text-[11px] font-bold text-slate-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500/30 transition-all">
                </div>
                <div class="space-y-3">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Security Key</label>
                    <input name="smtp_password" type="password" value=""
                           class="w-full px-5 py-4 bg-slate-50/50 border border-slate-100 rounded-2xl text-[11px] font-bold text-slate-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500/30 transition-all"
                           placeholder="Leave empty to remain unchanged">
                </div>
                <div class="space-y-3">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Sender Alias</label>
                    <input name="from_name" value="{{ old('from_name', $mail['from']['name'] ?? '') }}"
                           class="w-full px-5 py-4 bg-slate-50/50 border border-slate-100 rounded-2xl text-[11px] font-bold text-slate-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500/30 transition-all">
                </div>
                <div class="md:col-span-2 space-y-3">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Global Broadcast Address</label>
                    <input name="from_address" value="{{ old('from_address', $mail['from']['address'] ?? '') }}"
                           class="w-full px-5 py-4 bg-slate-50/50 border border-slate-100 rounded-2xl text-[11px] font-bold text-slate-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500/30 transition-all">
                </div>
            </div>

            <div class="flex justify-end mt-12">
                <button type="submit" class="btn-premium-primary !py-5 !px-12 !rounded-[2rem] !text-xs !bg-slate-900 !text-white flex items-center gap-4 hover:!bg-blue-600 transition-all shadow-2xl shadow-slate-900/10 hover:shadow-blue-500/30 group">
                    <i class="fas fa-save text-blue-400 group-hover:text-white transition-all"></i>
                    Sync Node Overrides
                </button>
            </div>
        </div>
    </form>

    <!-- Node Recovery / Reprovisioning -->
    @if($provisionStatus === 'failed')
    <div class="relative bg-rose-50 rounded-[3rem] border border-rose-100 p-10 hover:shadow-2xl transition-all duration-700 overflow-hidden stagger-in">
        <div class="relative z-10 flex items-center gap-4 mb-8">
            <div class="w-10 h-10 bg-rose-100 text-rose-600 rounded-xl flex items-center justify-center shadow-sm">
                <i class="fas fa-exclamation-triangle text-xs"></i>
            </div>
            <div>
                <h3 class="text-lg font-black text-rose-900 tracking-tight">Provisioning Interrupted</h3>
                <p class="text-[10px] font-bold text-rose-500 uppercase tracking-widest mt-0.5 italic">Protocol Failure detected</p>
            </div>
        </div>

        <div class="p-6 bg-white/50 border border-rose-100 rounded-3xl mb-10">
            <p class="text-xs font-bold text-rose-800 uppercase tracking-wider mb-2">Internal Error Report:</p>
            <code class="text-[10px] font-mono text-rose-600 leading-relaxed">{{ data_get($tenant->data, 'provision_error', 'Unknown system exception') }}</code>
        </div>

        <form method="POST" action="{{ route('superadmin.tenants.reprovision', $tenant->id) }}" class="space-y-8">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-3">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Admin Email</label>
                    <input name="admin_email" type="email" required value="{{ old('admin_email', data_get($tenant->data, 'config.mail.from.address')) }}"
                           class="w-full px-5 py-4 bg-white border border-rose-100 rounded-2xl text-[11px] font-bold text-slate-900 focus:ring-4 focus:ring-blue-500/10 transition-all">
                </div>
                <div class="space-y-3">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Security Credentials</label>
                    <input name="admin_password" type="password" required
                           class="w-full px-5 py-4 bg-white border border-rose-100 rounded-2xl text-[11px] font-bold text-slate-900 focus:ring-4 focus:ring-blue-500/10 transition-all"
                           placeholder="••••••••">
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="btn-premium-primary !py-4 !px-8 !rounded-2xl !text-[10px] !bg-rose-600 !text-white flex items-center gap-3 hover:!bg-rose-700 transition-all shadow-xl shadow-rose-600/10">
                    <i class="fas fa-redo-alt"></i>
                    Initialize Recovery Sequence
                </button>
            </div>
        </form>
    </div>
    @endif

    <!-- Destructive Zone -->
    <div class="relative bg-slate-50/50 rounded-[3rem] border border-slate-100 p-10 overflow-hidden">
        <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-4 text-center md:text-left">
                <div class="w-10 h-10 bg-slate-100 text-slate-400 rounded-xl flex items-center justify-center shadow-inner">
                    <i class="fas fa-trash-alt text-xs"></i>
                </div>
                <div>
                    <h3 class="text-sm font-black text-slate-900 uppercase tracking-widest">Danger Zone</h3>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-0.5">Permanent Deletion of Infrastructure</p>
                </div>
            </div>

            <form method="POST" action="{{ route('superadmin.tenants.destroy', $tenant->id) }}" onsubmit="return confirm('CRITICAL: Permanent deletion?');" class="relative z-10">
                @csrf
                @method('DELETE')
                <button class="btn-premium-outline !py-3 !px-8 !rounded-2xl !text-[10px] !text-rose-500 !border-rose-100 hover:!bg-rose-600 hover:!text-white hover:!border-rose-600 transition-all flex items-center gap-2 group">
                    <i class="fas fa-trash-alt group-hover:scale-110 transition-transform"></i>
                    Purge Node
                </button>
            </form>
        </div>
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
