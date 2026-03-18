@extends(request()->has('iframe') ? 'layouts.iframe' : 'layouts.app')

@section('page-title', 'Compose Email')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-slate-600">Send emails to users in your organization</p>
        </div>
        <a href="{{ route('emails.index') }}" class="inline-flex items-center px-4 py-2 border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50 transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Emails
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200">
        <div class="px-6 py-4 border-b border-slate-200">
            <h3 class="text-lg font-semibold text-slate-900">Compose New Email</h3>
        </div>
        
        <form action="{{ route('emails.store') }}" method="POST" class="p-6 space-y-6">
    @if(request()->has('iframe'))
        <input type="hidden" name="iframe" value="1">
    @endif

            @csrf
            
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Recipients</label>
                <select name="recipients[]" multiple class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">
                            {{ $user->name }} ({{ $user->email }})
                            @if($user->clinic) - {{ $user->clinic->name }} @endif
                        </option>
                    @endforeach
                </select>
                <p class="text-xs text-slate-500 mt-1">Hold Ctrl/Cmd to select multiple recipients</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Subject</label>
                <input type="text" name="subject" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Email subject" required>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Message</label>
                <textarea name="body" rows="10" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Type your message here..." required></textarea>
            </div>
            
            <div class="flex justify-end space-x-4 pt-6 border-t border-slate-200">
                <a href="{{ route('emails.index') }}" class="px-6 py-3 border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50 transition-colors">
                    Cancel
                </a>
                <button type="submit" class="px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                    Send Email
                </button>
            </div>
        </form>
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
