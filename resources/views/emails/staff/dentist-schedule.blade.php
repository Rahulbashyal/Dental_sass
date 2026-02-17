@extends('emails.layouts.base')

@section('title', 'Your Schedule for Today')

@section('content')
<div class="greeting">
    Good Morning, Dr. {{ $dentist->name }}!
</div>

<div class="message">
    <p>Here's your schedule for <strong>{{ now()->format('l, F j, Y') }}</strong></p>
</div>

@if($appointments->count() > 0)
<div class="info-box">
    <strong>📅 Today's Appointments ({{ $appointments->count() }} total)</strong>
</div>

@foreach($appointments as $appointment)
<div style="background-color: #f7fafc; padding: 15px; margin: 10px 0; border-radius: 5px; border-left: 4px solid #667eea;">
    <div style="display: flex; justify-content: space-between; align-items: start;">
        <div>
            <strong style="color: #2d3748; font-size: 16px;">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A') }}</strong>
            <p style="margin: 5px 0; color: #4a5568;">
                <strong>Patient:</strong> {{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}<br>
                <strong>Treatment:</strong> {{ $appointment->treatment_type }}<br>
                <strong>Phone:</strong> {{ $appointment->patient->phone }}
                @if($appointment->notes)
                    <br><strong>Notes:</strong> {{ $appointment->notes }}
                @endif
            </p>
        </div>
        <span style="background-color: {{ $appointment->status === 'confirmed' ? '#48bb78' : '#ecc94b' }}; color: white; padding: 5px 10px; border-radius: 3px; font-size: 12px;">
            {{ ucfirst($appointment->status) }}
        </span>
    </div>
</div>
@endforeach

@else
<div class="info-box" style="background-color: #f0fff4; border-left-color: #48bb78;">
    <strong>✨ No Appointments Scheduled</strong>
    <p style="margin: 5px 0; color: #4a5568;">You have no appointments scheduled for today. Enjoy your day!</p>
</div>
@endif

<div class="message" style="margin-top: 30px;">
    <p><strong>Quick Tips:</strong></p>
    <ul style="color: #4a5568; line-height: 1.8;">
        <li>Review patient files before each appointment</li>
        <li>Ensure all necessary equipment is prepared</li>
        <li>Update treatment plans after each visit</li>
    </ul>
</div>

<div class="message" style="margin-top: 30px;">
    <p>Have a great day!</p>
    <p>Best regards,<br>
    <strong>{{ $clinic->name }} Team</strong></p>
</div>
@endsection
