@extends('layouts.app')

@section('page-title', 'Communication Pulse')

@section('content')
<div class="space-y-8 pb-10">
    <!-- Header Strategy -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <p class="text-[10px] font-black text-blue-600 uppercase tracking-[0.2em] mb-1">Clinic Communication Hub</p>
            <h1 class="text-4xl font-black text-slate-900 tracking-tight">Email Hub</h1>
        </div>
        <div class="flex items-center gap-3">
            <button onclick="openBulkEmailModal()" class="px-6 py-3 bg-white border border-slate-200 text-slate-700 rounded-2xl font-bold hover:bg-slate-50 transition-all flex items-center gap-2 shadow-sm text-sm uppercase tracking-widest text-[10px]">
                <i class="fa-solid fa-layer-group"></i> Bulk Broadcast
            </button>
            <a href="{{ route('emails.compose') }}" class="px-6 py-3 bg-slate-900 text-white rounded-2xl font-bold hover:bg-slate-800 transition-all flex items-center gap-2 shadow-xl shadow-slate-200 text-sm uppercase tracking-widest text-[10px]">
                <i class="fa-solid fa-plus"></i> Compose New
            </a>
        </div>
    </div>

    <!-- Communication Metrics (Optional, add later) -->

    <!-- Email Log Card -->
    <div class="bg-white rounded-[2rem] shadow-2xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
        <div class="px-8 py-6 border-b border-slate-50 flex items-center justify-between bg-slate-50/30">
            <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest">Outbound Transmission Log</h3>
            <div class="flex items-center gap-2">
                <span class="h-2 w-2 bg-green-400 rounded-full animate-pulse"></span>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Live Monitoring</span>
            </div>
        </div>

        <div class="overflow-x-auto no-scrollbar">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-8 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Subject & Narrative</th>
                        <th class="px-8 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Recipients</th>
                        <th class="px-8 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Originator</th>
                        <th class="px-8 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Delivery Status</th>
                        <th class="px-8 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Timestamp</th>
                        <th class="px-8 py-4 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest">Nodes</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($emails as $email)
                    <tr class="hover:bg-slate-50/80 transition-all group">
                        <td class="px-8 py-6">
                            <div class="flex flex-col">
                                <span class="text-sm font-black text-slate-900 leading-tight group-hover:text-blue-600 transition-colors">{{ $email->subject }}</span>
                                <span class="text-[10px] text-slate-400 font-bold uppercase mt-1">Ref #{{ str_pad($email->id, 6, '0', STR_PAD_LEFT) }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-sm text-slate-600 font-medium">
                            {{ count($email->recipients) }} Target Nodes
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-2">
                                <div class="h-6 w-6 rounded-full bg-slate-100 flex items-center justify-center text-[10px] font-bold text-slate-500">
                                    {{ substr($email->sender->name ?? 'S', 0, 1) }}
                                </div>
                                <span class="text-xs font-bold text-slate-700">{{ $email->sender->name ?? 'System Engine' }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest
                                {{ $email->status === 'sent' ? 'bg-emerald-50 text-emerald-600' : 
                                   ($email->status === 'failed' ? 'bg-rose-50 text-rose-600' : 'bg-amber-50 text-amber-600') }}">
                                <span class="h-1 w-1 rounded-full mr-2 {{ $email->status === 'sent' ? 'bg-emerald-400' : ($email->status === 'failed' ? 'bg-rose-400' : 'bg-amber-400') }}"></span>
                                {{ $email->status }}
                            </span>
                        </td>
                        <td class="px-8 py-6 text-xs text-slate-500 font-bold">
                            {{ $email->sent_at ? $email->sent_at->format('M d, H:i') : 'Queued' }}
                        </td>
                        <td class="px-8 py-6 text-right">
                            <a href="{{ route('emails.show', $email) }}" class="p-2 text-slate-400 hover:text-blue-600 transition-all">
                                <i class="fa-solid fa-arrow-right-long text-lg"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-8 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <i class="fa-solid fa-inbox text-slate-100 text-6xl mb-4"></i>
                                <h3 class="text-sm font-black text-slate-400 uppercase tracking-widest">No Transmissions Recorded</h3>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($emails->hasPages())
        <div class="px-8 py-6 bg-slate-50/50">
            {{ $emails->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Bulk Broadcast Panel -->
<div id="bulkEmailModal" class="fixed inset-0 z-[60] hidden" x-data="{ type: 'all_patients' }">
    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="closeBulkEmailModal()"></div>
    <div class="absolute right-0 top-0 bottom-0 w-full max-w-xl bg-white shadow-2xl p-10 overflow-y-auto no-scrollbar transform transition-transform duration-500">
        <div class="flex items-center justify-between mb-10">
            <div>
                <p class="text-[10px] font-black text-blue-600 uppercase tracking-widest mb-1">Broadcasting System</p>
                <h3 class="text-3xl font-black text-slate-900">Bulk Broadcast</h3>
            </div>
            <button onclick="closeBulkEmailModal()" class="h-10 w-10 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 hover:bg-slate-100 transition-all">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form action="{{ route('emails.bulk') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Target Audience</label>
                <select name="recipient_type" x-model="type" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-4 text-sm font-bold text-slate-900 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all outline-none appearance-none">
                    <option value="all_patients">Broad Spectrum (All Patients)</option>
                    <option value="selected_patients">Defined Cluster (Selected Patients)</option>
                </select>
            </div>
            
            <div x-show="type === 'selected_patients'" x-transition class="bg-slate-50 rounded-2xl p-6 border border-slate-100">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Patient Cluster Selection</label>
                <div class="max-h-60 overflow-y-auto no-scrollbar space-y-3">
                    @foreach(\App\Models\Patient::where('clinic_id', Auth::user()->clinic_id)->get() as $patient)
                    <label class="flex items-center justify-between p-3 rounded-xl bg-white border border-slate-50 hover:border-blue-100 cursor-pointer transition-all">
                        <div class="flex items-center gap-3">
                            <div class="h-8 w-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-[10px] font-bold">
                                {{ substr($patient->first_name, 0, 1) }}
                            </div>
                            <div class="flex flex-col">
                                <span class="text-xs font-black text-slate-900">{{ $patient->full_name }}</span>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">{{ $patient->email }}</span>
                            </div>
                        </div>
                        <input type="checkbox" name="selected_patients[]" value="{{ $patient->id }}" class="h-5 w-5 rounded-lg border-slate-200 text-blue-600 focus:ring-blue-500 transition-all">
                    </label>
                    @endforeach
                </div>
            </div>

            <div class="space-y-4">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Transmission Content</label>
                <input type="text" name="subject" placeholder="Broadcast Subject Line" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-4 text-sm font-bold text-slate-900 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all outline-none" required>
                <textarea name="body" rows="8" placeholder="Enter your clinical update or newsletter content here..." class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-4 text-sm font-bold text-slate-900 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all outline-none resize-none" required></textarea>
            </div>

            <button type="submit" class="w-full py-5 bg-slate-900 text-white rounded-2xl font-black uppercase tracking-[0.2em] text-xs hover:bg-slate-800 transition-all shadow-xl shadow-slate-200 active:scale-[0.98]">
                Execute Broadcast
            </button>
        </form>
    </div>
</div>

<script>
function openBulkEmailModal() {
    const modal = document.getElementById('bulkEmailModal');
    modal.classList.remove('hidden');
    setTimeout(() => {
        modal.querySelector('.transform').classList.remove('translate-x-full');
    }, 10);
}

function closeBulkEmailModal() {
    const modal = document.getElementById('bulkEmailModal');
    modal.querySelector('.transform').classList.add('translate-x-full');
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 500);
}
</script>

<style>
.translate-x-full { transform: translateX(100%); }
</style>
@endsection