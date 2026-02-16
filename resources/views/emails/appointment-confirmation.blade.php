@extends('emails.layouts.base')

@section('title', 'Appointment Confirmed')

@section('content')
    <div class="badge" style="background-color: #ecfdf5; color: #059669;">✓ Session Confirmed</div>
    <div class="greeting">Excellent News, {{ $patient->first_name ?? $patient->name }}!</div>
    
    <div class="message">
        Your clinical session at <strong>{{ $clinic->name }}</strong> has been successfully verified and locked into our schedule.
    </div>
    
    <div class="info-card">
        <div class="info-item">
            <div class="info-label">Scheduled Date</div>
            <div class="info-value">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('l, F j, Y') }}</div>
            <div class="info-value" style="font-size: 13px; color: #64748b; margin-top: 2px;">{{ $nepaliDate['formatted'] ?? '' }}</div>
        </div>
        
        <div class="info-item">
            <div class="info-label">Access Time</div>
            <div class="info-value">{{ $appointment->appointment_time }}</div>
        </div>
        
        <div class="info-item">
            <div class="info-label">Clinical Provider</div>
            <div class="info-value">Dr. {{ $appointment->dentist->name }}</div>
        </div>
        
        <div class="info-item">
            <div class="info-label">Diagnostic Type</div>
            <div class="info-value">{{ ucfirst($appointment->type) }}</div>
        </div>
    </div>
    
    <div style="text-align: center;">
        <a href="{{ route('patient.login') }}" class="cta-button">
            View Journey Dashboard
        </a>
    </div>
    
    <div class="message" style="margin-top: 32px; font-size: 14px; border-top: 1px solid #f1f5f9; pt: 24px;">
        <p>Your oral health journey is our priority. We have prepared the necessary clinical nodes for your arrival.</p>
        <p style="color: #94a3b8;">Need assistance? Contact us at {{ $clinic->phone }}.</p>
    </div>
@endsection