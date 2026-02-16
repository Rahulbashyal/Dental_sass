@extends('emails.layouts.base')

@section('title', 'Appointment Reminder')

@section('content')
    <div class="badge">Session Reminder</div>
    <div class="greeting">Hello, {{ $patient->first_name ?? $patient->name }}</div>
    
    <div class="message">
        This is a friendly reminder from <strong>{{ $clinic->name }}</strong> regarding your upcoming clinical scheduled session. We look forward to seeing you.
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
            <div class="info-label">Service Node</div>
            <div class="info-value">{{ ucfirst($appointment->type) }}</div>
        </div>
    </div>
    
    <div style="text-align: center;">
        <a href="{{ route('patient.login') }}" class="cta-button">
            Manage Appointment
        </a>
    </div>
    
    <div class="message" style="margin-top: 32px; font-size: 14px; border-top: 1px solid #f1f5f9; pt: 24px;">
        <p><strong>Note:</strong> Please arrive at least 10 minutes prior to your scheduled time for processing.</p>
        <p style="color: #94a3b8;">If you need to adjust this session, please contact the clinic node at {{ $clinic->phone }}.</p>
    </div>
@endsection