@extends(request()->has('iframe') ? 'layouts.iframe' : 'layouts.app')

@section('page-title', 'Communication Engine: Compose')

@section('content')
<div class="page-fade-in max-w-4xl mx-auto pb-12">
    <!-- Premium Header -->
    <div class="stagger-in mb-8">
        <div class="flex items-center space-x-3 text-slate-500 text-sm mb-2">
            <a href="{{ route('emails.index') }}" class="hover:text-blue-600 transition-colors">Transmission Hub</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            <span class="text-slate-900 font-medium tracking-tight">New Broadcast Initialization</span>
        </div>
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-100">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                </svg>
            </div>
            <div>
                <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Compose Dispatch</h1>
                <p class="text-slate-500 font-medium italic">Configuring a new Outbound Communication Node</p>
            </div>
        </div>
    </div>

    @if(session('error'))
        <div class="stagger-in rounded-3xl bg-rose-50 p-4 mb-8 border border-rose-100">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-rose-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-bold text-rose-800">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    <form action="{{ route('emails.send') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @if(request()->has('iframe'))
            <input type="hidden" name="iframe" value="1">
        @endif
        @csrf
        
        <!-- Metadata Section -->
        <div class="stagger-in bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm" style="--delay: 1">
            <div class="flex items-center space-x-3 mb-8">
                <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
                <h2 class="text-xl font-bold text-slate-900 tracking-tight">Transmission Metadata</h2>
            </div>
            
            <div class="space-y-6 text-sm">
                <div class="space-y-2">
                    <label class="block font-bold text-slate-700 tracking-tight">Protocol Template (Optional)</label>
                    <select id="templateSelect" class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-medium text-slate-700 appearance-none" onchange="loadTemplate()">
                        <option value="">Select a pre-configured template...</option>
                        @foreach($templates as $key => $template)
                        <option value="{{ $key }}">{{ $template['name'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="block font-bold text-slate-700 tracking-tight">Target Nodes (Recipients) <span class="text-blue-500">*</span></label>
                    <div class="space-y-3">
                        <div class="flex items-center space-x-2">
                            <input type="email" name="recipients[]" class="flex-1 px-5 py-3.5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-medium text-slate-700" placeholder="Enter target email address" required>
                            <button type="button" onclick="addRecipient()" class="w-12 h-12 bg-blue-600 text-white rounded-2xl flex items-center justify-center shadow-lg hover:bg-blue-700 transition-all active:scale-95">+</button>
                        </div>
                        <div id="additionalRecipients" class="space-y-3"></div>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block font-bold text-slate-700 tracking-tight">Subject Line <span class="text-blue-500">*</span></label>
                    <input type="text" name="subject" id="emailSubject" class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-medium text-slate-700" placeholder="System Notice: ..." required>
                </div>
            </div>
        </div>

        <!-- Narrative Hub -->
        <div class="stagger-in bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm" style="--delay: 2">
            <div class="flex items-center space-x-3 mb-8">
                <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
                </div>
                <h2 class="text-xl font-bold text-slate-900 tracking-tight">Dispatch Narrative</h2>
            </div>
            
            <div class="space-y-4">
                <textarea name="body" id="emailBody" rows="12" class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-medium text-slate-700 resize-none placeholder-slate-400" placeholder="Initialize dispatch content here..." required></textarea>
                
                <div class="pt-4">
                    <label class="block font-bold text-slate-700 tracking-tight mb-2">Resource Attachment Hub</label>
                    <div class="border-2 border-dashed border-slate-200 rounded-2xl p-6 text-center hover:border-blue-300 transition-all bg-slate-50/50">
                        <input type="file" name="attachments[]" multiple class="hidden" id="fileInput">
                        <label for="fileInput" class="cursor-pointer group">
                            <svg class="w-12 h-12 text-slate-300 mx-auto mb-2 group-hover:text-blue-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.414a4 4 0 00-5.656-5.656l-6.415 6.415a6 6 0 108.486 8.486L20.5 13"></path></svg>
                            <p class="text-sm font-bold text-slate-500">Attach Secure Documents</p>
                            <p class="text-[10px] text-slate-400 font-medium">X-Rays, Lab Reports, Financial Ledger Extracts</p>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submission Hub -->
        <div class="stagger-in flex flex-col md:flex-row md:items-center justify-end gap-3 pt-4" style="--delay: 3">
            <a href="{{ route('emails.index') }}" class="px-8 py-4 bg-slate-50 text-slate-500 rounded-2xl font-bold hover:bg-slate-100 transition-all text-center">
                Abort Dispatch
            </a>
            <button type="submit" class="px-10 py-4 bg-blue-600 text-white rounded-2xl font-black uppercase tracking-widest text-xs hover:bg-blue-700 transition-all shadow-lg shadow-blue-100 active:scale-95 flex items-center justify-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                <span>Commit & Transmit Dispatch</span>
            </button>
        </div>
    </form>
</div>

<script>
function addRecipient() {
    const container = document.getElementById('additionalRecipients');
    const div = document.createElement('div');
    div.className = 'flex items-center space-x-2 stagger-in';
    div.innerHTML = `
        <input type="email" name="recipients[]" class="flex-1 px-5 py-3.5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-medium text-slate-700" placeholder="secondary@target.node">
        <button type="button" onclick="this.parentElement.remove()" class="w-12 h-12 bg-rose-50 text-rose-500 rounded-2xl flex items-center justify-center hover:bg-rose-100 transition-all active:scale-95">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
        </button>
    `;
    container.appendChild(div);
}

function loadTemplate() {
    const templateSelect = document.getElementById('templateSelect');
    const templateKey = templateSelect.value;
    
    if (templateKey) {
        fetch(`/emails/template/${templateKey}`)
            .then(response => response.json())
            .then(template => {
                document.getElementById('emailSubject').value = template.subject;
                document.getElementById('emailBody').value = template.body;
            })
            .catch(error => console.error('Error loading template:', error));
    }
}
</script>
@endsection

{{-- Auto-close modal script on success --}}
@if(session('success') && request()->has('iframe'))
    <script>
        setTimeout(() => {
            window.parent.location.reload();
        }, 1500);
    </script>
@endif