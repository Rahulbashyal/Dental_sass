@extends('emails.layouts.base')

@section('title', 'New Prescription Issued')

@section('content')
<div class="greeting">
    Dear {{ $prescription->patient->first_name }},
</div>

<div class="message">
    <p>A new prescription has been issued for you by Dr. {{ $prescription->dentist->name }}.</p>
</div>

<div class="info-box">
    <strong>💊 Prescription Details:</strong>
    <p style="margin: 5px 0; color: #4a5568;">
        <strong>Date:</strong> {{ \Carbon\Carbon::parse($prescription->prescribed_date)->format('F j, Y') }}<br>
        <strong>Valid Until:</strong> {{ \Carbon\Carbon::parse($prescription->valid_until)->format('F j, Y') }}<br>
        <strong>Diagnosis:</strong> {{ $prescription->diagnosis }}
    </p>
</div>

<div class="message">
    <p><strong>Prescribed Medications:</strong></p>
    <ul style="color: #4a5568; line-height: 1.8;">
        @foreach($prescription->items as $item)
            <li>
                <strong>{{ $item->medication_name }}</strong>
                @if($item->generic_name)
                    ({{ $item->generic_name }})
                @endif
                <br>
                <span style="font-size: 14px;">
                    Dosage: {{ $item->dosage }} | 
                    Frequency: {{ $item->frequency }} | 
                    Duration: {{ $item->duration }}
                </span>
            </li>
        @endforeach
    </ul>
</div>

@if($prescription->general_instructions)
<div class="info-box" style="background-color: #fff5f5; border-left-color: #f56565;">
    <strong>⚠️ Important Instructions:</strong>
    <p style="margin: 5px 0; color: #4a5568;">{{ $prescription->general_instructions }}</p>
</div>
@endif

<div class="message">
    <p>A detailed PDF copy of your prescription is attached to this email for your records.</p>
</div>

<div class="message">
    <p><strong>Please Note:</strong></p>
    <ul style="color: #4a5568; line-height: 1.8;">
        <li>Take medications exactly as prescribed</li>
        <li>Complete the full course even if you feel better</li>
        <li>Contact us immediately if you experience any adverse reactions</li>
        <li>Store medications in a cool, dry place</li>
    </ul>
</div>

<div class="message" style="margin-top: 30px;">
    <p>For any questions about your prescription, please contact our clinic.</p>
    <p>Best regards,<br>
    <strong>{{ $clinic->name }}</strong></p>
</div>
@endsection
