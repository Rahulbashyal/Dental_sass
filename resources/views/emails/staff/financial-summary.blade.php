@extends('emails.layouts.base')

@section('title', 'Daily Financial Summary')

@section('content')
<div class="greeting">
    Hello {{ $accountant->name }},
</div>

<div class="message">
    <p>Here's your financial summary for <strong>{{ now()->format('l, F j, Y') }}</strong></p>
</div>

<div class="info-box">
    <strong>💰 Today's Financial Overview:</strong>
    <p style="margin: 5px 0; color: #4a5568;">
        <strong>Total Revenue:</strong> रू {{ number_format($summary['total_revenue'], 2) }}<br>
        <strong>Invoices Generated:</strong> {{ $summary['invoices_generated'] }}<br>
        <strong>Payments Received:</strong> {{ $summary['payments_received'] }}<br>
        <strong>Outstanding Balance:</strong> रू {{ number_format($summary['outstanding_balance'], 2) }}
    </p>
</div>

@if($summary['invoices_generated'] > 0)
<div style="margin: 20px 0;">
    <strong style="color: #2d3748;">📊 Payment Method Breakdown:</strong>
    <table style="width: 100%; margin-top: 10px; border-collapse: collapse;">
        <thead>
            <tr style="background-color: #f7fafc;">
                <th style="padding: 10px; text-align: left; border-bottom: 2px solid #e2e8f0;">Payment Method</th>
                <th style="padding: 10px; text-align: right; border-bottom: 2px solid #e2e8f0;">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($summary['payment_methods'] as $method => $amount)
            <tr>
                <td style="padding: 10px; border-bottom: 1px solid #e2e8f0;">{{ ucfirst($method) }}</td>
                <td style="padding: 10px; text-align: right; border-bottom: 1px solid #e2e8f0;">रू {{ number_format($amount, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

<div class="info-box" style="background-color: #f0f9ff; border-left-color: #3b82f6;">
    <strong>📈 Month-to-Date Summary:</strong>
    <p style="margin: 5px 0; color: #4a5568;">
        <strong>Total Revenue (This Month):</strong> रू {{ number_format($summary['monthly_total'], 2) }}<br>
        <strong>Pending Invoices:</strong> {{ $summary['pending_invoices'] }}<br>
        <strong>Collection Rate:</strong> {{ number_format($summary['collection_rate'], 1) }}%
    </p>
</div>

@if($summary['overdue_invoices'] > 0)
<div class="info-box" style="background-color: #fff5f5; border-left-color: #f56565;">
    <strong>⚠️ Attention Required:</strong>
    <p style="margin: 5px 0; color: #4a5568;">
        You have <strong>{{ $summary['overdue_invoices'] }} overdue invoices</strong> requiring follow-up.
    </p>
</div>
@endif

<div class="message" style="margin-top: 30px;">
    <p>Best regards,<br>
    <strong>{{ $clinic->name }} Accounting System</strong></p>
</div>
@endsection
