@extends('patient-portal.layout')

@section('title', 'Redirecting to ' . $gateway)

@section('content')
<div class="max-w-2xl mx-auto text-center py-32 page-fade-in">
    @php
        $patient = Auth::guard('patient')->user();
        $clinic = $patient ? $patient->clinic : null;
        $clinicColor = $clinic && $clinic->primary_color ? $clinic->primary_color : '#0ea5e9';
    @endphp

    <div class="bg-white rounded-[3rem] border border-slate-100 shadow-2xl shadow-slate-200/50 p-16 space-y-8">
        <!-- Animated Gateway Icon -->
        <div class="w-24 h-24 rounded-[2rem] flex items-center justify-center mx-auto shadow-lg" style="background-color: {{ $clinicColor }}">
            <i class="fas fa-shield-alt text-white text-4xl animate-pulse"></i>
        </div>

        <div class="space-y-3">
            <h2 class="text-2xl font-black text-slate-900 Outfit">Establishing Secure Connection</h2>
            <p class="text-slate-500 font-medium">Redirecting you to <strong class="text-slate-900">{{ $gateway }}</strong> payment gateway...</p>
        </div>

        <!-- Loading Animation -->
        <div class="flex justify-center gap-2 py-4">
            <div class="w-3 h-3 rounded-full animate-bounce" style="background-color: {{ $clinicColor }}; animation-delay: 0ms"></div>
            <div class="w-3 h-3 rounded-full animate-bounce" style="background-color: {{ $clinicColor }}; animation-delay: 150ms"></div>
            <div class="w-3 h-3 rounded-full animate-bounce" style="background-color: {{ $clinicColor }}; animation-delay: 300ms"></div>
        </div>

        <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Do not close this window</p>
    </div>

    <!-- Hidden Auto-Submit Form -->
    <form id="payment-redirect-form" action="{{ $url }}" method="POST" class="hidden">
        @foreach($fields as $name => $value)
            <input type="hidden" name="{{ $name }}" value="{{ $value }}">
        @endforeach
    </form>
</div>

<script>
    // Auto-submit after a short visual delay
    setTimeout(function() {
        document.getElementById('payment-redirect-form').submit();
    }, 1500);
</script>
@endsection
