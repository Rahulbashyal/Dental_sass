@extends('emails.layouts.base')

@section('title', 'New Appointment Alert')

@section('content')
<div class="greeting">
    Hello {{ $receptionist->name }},
</div>

<div class="message">
    <p>A new appointment has been booked and requires your attention.</p>
</div>

<div class="info-box">
    <strong>📅 Appointment Details:</strong>
    <p style="margin: 5px 0; color: #4a5568;">
        <strong>Patient:</strong> {{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}<br>
        <strong>Phone:</strong> {{ $appointment->patient->email }}<br>
        <strong>Email:</strong> {{ $appointment->patient->phone }}<br>
        <strong>Date:</strong> {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('l, F j, Y') }}<br>
        <strong>Time:</strong> {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A') }}<br>
        <strong>Dentist:</strong> Dr. {{ $appointment->dentist->name }}<br>
        <strong>Treatment:</strong> {{ $appointment->treatment_type }}<br>
        <strong>Status:</strong> {{ ucfirst($appointment->status) }}
    </p>
</div>

@if($appointment->notes)
<div class="info-box" style="background-color: #fffaf0; border-left-color: #ed8936;">
    <strong>📝 Special Notes:</strong>
    <p style="margin: 5px 0; color: #4a5568;">{{ $appointment->notes }}</p>
</div>
@endif

<div class="message">
    <p><strong>Next Steps:</strong></p>
    <ul style="color: #4a5568; line-height: 1.8;">
        <li>Confirm appointment with patient if needed</li>
        <li>Verify patient information is up to date</li>
        <li>Prepare necessary documents</li>
        <li>Send reminder 24 hours before appointment</li>
    </ul>
</div>

<div class="message" style="margin-top: 30px;">
    <p>Best regards,<br>
    <strong>{{ $clinic->name }} System</strong></p>
</div>
@endsection
