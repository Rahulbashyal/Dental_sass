@extends('emails.layouts.base')

@section('title', 'Welcome to Our Dental Clinic')

@section('content')
<div class="greeting">
    Welcome, {{ $patient->first_name }}!
</div>

<div class="message">
    <p>Thank you for choosing <strong>{{ $clinic->name }}</strong> for your dental care needs. We're delighted to have you as part of our patient family!</p>
</div>

<div class="info-box">
    <strong>👤 Your Patient Profile:</strong>
    <p style="margin: 5px 0; color: #4a5568;">
        <strong>Name:</strong> {{ $patient->first_name }} {{ $patient->last_name }}<br>
        <strong>Email:</strong> {{ $patient->email }}<br>
        <strong>Phone:</strong> {{ $patient->phone }}<br>
        <strong>Patient ID:</strong> #{{ str_pad($patient->id, 6, '0', STR_PAD_LEFT) }}
    </p>
</div>

<div class="message">
    <p><strong>What's Next?</strong></p>
    <ul style="color: #4a5568; line-height: 1.8;">
        <li>📅 Schedule your first appointment with our reception team</li>
        <li>📋 Complete your medical history form (if not already done)</li>
        <li>💳 Familiarize yourself with our payment and insurance policies</li>
        <li>📞 Save our contact number for easy access</li>
    </ul>
</div>

<div class="info-box" style="background-color: #f0fff4; border-left-color: #48bb78;">
    <strong>✨ Why Choose Us?</strong>
    <p style="margin: 5px 0; color: #4a5568;">
        • Experienced dental professionals<br>
        • State-of-the-art equipment<br>
        • Comfortable and hygienic environment<br>
        • Personalized treatment plans<br>
        • Transparent pricing
    </p>
</div>

<div class="message">
    <p><strong>Need Help?</strong></p>
    <p>Our team is here to answer any questions you may have. Feel free to reach out to us anytime!</p>
</div>

<div class="message" style="margin-top: 30px;">
    <p>We look forward to providing you with excellent dental care.</p>
    <p>Warm regards,<br>
    <strong>The {{ $clinic->name }} Team</strong></p>
</div>
@endsection
