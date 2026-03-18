@extends('layouts.app')

@section('page-title', 'View Email')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-slate-600">Email details and content</p>
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
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-slate-900">{{ $email->subject }}</h3>
                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                    @if($email->status === 'sent') bg-green-100 text-green-800
                    @elseif($email->status === 'failed') bg-red-100 text-red-800
                    @else bg-yellow-100 text-yellow-800 @endif">
                    {{ ucfirst($email->status) }}
                </span>
            </div>
        </div>
        
        <div class="p-6 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="text-sm font-medium text-slate-700 mb-2">From</h4>
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                            <span class="text-sm font-medium text-blue-600">{{ substr($email->sender->name, 0, 1) }}</span>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-slate-900">{{ $email->sender->name }}</div>
                            <div class="text-sm text-slate-500">{{ $email->sender->email }}</div>
                        </div>
                    </div>
                </div>
                
                <div>
                    <h4 class="text-sm font-medium text-slate-700 mb-2">Sent</h4>
                    <div class="text-sm text-slate-900">
                        {{ $email->sent_at ? $email->sent_at->format('M d, Y \a\t H:i') : 'Not sent' }}
                    </div>
                </div>
            </div>
            
            <div>
                <h4 class="text-sm font-medium text-slate-700 mb-2">Recipients ({{ count($email->recipients) }})</h4>
                <div class="flex flex-wrap gap-2">
                    @foreach($email->recipients as $recipientEmail)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-blue-100 text-blue-800">
                            {{ $recipientEmail }}
                        </span>
                    @endforeach
                </div>
            </div>
            
            <div>
                <h4 class="text-sm font-medium text-slate-700 mb-2">Message</h4>
                <div class="bg-slate-50 rounded-lg p-4">
                    <div class="prose prose-sm max-w-none">
                        {!! nl2br(e($email->body)) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection