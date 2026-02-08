@extends('emails.layouts.base')

@section('title', 'Appointment Confirmation')

@section('content')
<div class="greeting">
    Dear {{ $appointment->patient->first_name }},
</div>

<div class="message">
    <p>Your dental appointment has been confirmed! We're looking forward to seeing you.</p>
</div>

<div class="info-box">
    <strong>📅 Appointment Details:</strong>
    <p style="margin: 5px 0; color: #4a5568;">
        <strong>Date:</strong> {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('l, F j, Y') }}<br>
        <strong>Time:</strong> {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A') }}<br>
        <strong>Dentist:</strong> Dr. {{ $appointment->dentist->name }}<br>
        <strong>Treatment:</strong> {{ $appointment->treatment_type }}<br>
        @if($appointment->notes)
            <strong>Notes:</strong> {{ $appointment->notes }}
        @endif
    </p>
</div>

<div class="message">
    <p><strong>Important Reminders:</strong></p>
    <ul style="color: #4a5568; line-height: 1.8;">
        <li>Please arrive 10 minutes before your scheduled time</li>
        <li>Bring any relevant medical records or insurance information</li>
        <li>If you need to reschedule, please contact us at least 24 hours in advance</li>
    </ul>
</div>

<div class="message">
    <p>If you have any questions or need to make changes to your appointment, please don't hesitate to contact us.</p>
</div>

<div class="message" style="margin-top: 30px;">
    <p>Best regards,<br>
    <strong>{{ $clinic->name }}</strong></p>
</div>
@endsection
