@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-6xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Email Templates</h1>
                <a href="{{ route('emails.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    Compose Email
                </a>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                @foreach($templates as $key => $template)
                <div class="border border-gray-200 rounded-lg p-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $template['name'] }}</h3>
                    <p class="text-sm text-gray-600 mb-3">Subject: {{ $template['subject'] }}</p>
                    <div class="bg-gray-50 p-3 rounded text-sm text-gray-700 mb-4">
                        {{ Str::limit($template['body'], 150) }}
                    </div>
                    <button onclick="useTemplate('{{ $key }}')" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 text-sm">
                        Use Template
                    </button>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script>
function useTemplate(templateKey) {
    const templates = @json($templates);
    const template = templates[templateKey];
    
    // Store in localStorage and redirect to create page
    localStorage.setItem('emailTemplate', JSON.stringify(template));
    window.location.href = '{{ route("emails.create") }}';
}
</script>
@endsection