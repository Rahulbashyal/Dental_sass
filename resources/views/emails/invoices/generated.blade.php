@extends('emails.layouts.base')

@section('title', 'Invoice Generated')

@section('content')
<div class="greeting">
    Dear {{ $invoice->patient->first_name }},
</div>

<div class="message">
    <p>A new invoice has been generated for your recent dental services.</p>
</div>

<div class="info-box">
    <strong>🧾 Invoice Details:</strong>
    <p style="margin: 5px 0; color: #4a5568;">
        <strong>Invoice #:</strong> {{ $invoice->invoice_number }}<br>
        <strong>Date:</strong> {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('F j, Y') }}<br>
        <strong>Due Date:</strong> {{ \Carbon\Carbon::parse($invoice->due_date)->format('F j, Y') }}<br>
        <strong>Total Amount:</strong> रू {{ number_format($invoice->total_amount, 2) }}<br>
        <strong>Status:</strong> <span style="color: {{ $invoice->status === 'paid' ? '#48bb78' : '#f56565' }};">{{ ucfirst($invoice->status) }}</span>
    </p>
</div>

<div class="message">
    <p>A detailed PDF invoice is attached to this email for your records.</p>
</div>

@if($invoice->status !== 'paid')
<div class="message">
    <p><strong>Payment Information:</strong></p>
    <ul style="color: #4a5568; line-height: 1.8;">
        <li>You can pay at our clinic reception</li>
        <li>Payment due by: {{ \Carbon\Carbon::parse($invoice->due_date)->format('F j, Y') }}</li>
        <li>Accepted payment methods: Cash, Card, Bank Transfer</li>
    </ul>
</div>
@endif

<div class="message" style="margin-top: 30px;">
    <p>If you have any questions about this invoice, please feel free to contact us.</p>
    <p>Thank you for choosing our services!</p>
    <p>Best regards,<br>
    <strong>{{ $clinic->name }}</strong></p>
</div>
@endsection
