@extends('emails.layouts.base')

@section('title', 'Weekly Clinic Report')

@section('content')
<div class="greeting">
    Hello {{ $admin->name }},
</div>

<div class="message">
    <p>Here's your weekly clinic summary for the week ending <strong>{{ now()->format('F j, Y') }}</strong></p>
</div>

<div class="info-box">
    <strong>📊 Weekly Overview:</strong>
    <p style="margin: 5px 0; color: #4a5568;">
        <strong>Total Appointments:</strong> {{ $summary['total_appointments'] }}<br>
        <strong>Completed:</strong> {{ $summary['completed_appointments'] }}<br>
        <strong>Cancelled:</strong> {{ $summary['cancelled_appointments'] }}<br>
        <strong>No-Shows:</strong> {{ $summary['no_show_appointments'] }}
    </p>
</div>

<div class="info-box" style="background-color: #f0fdf4; border-left-color: #22c55e;">
    <strong>💵 Revenue Summary:</strong>
    <p style="margin: 5px 0; color: #4a5568;">
        <strong>Total Revenue:</strong> रू {{ number_format($summary['total_revenue'], 2) }}<br>
        <strong>Average per Appointment:</strong> रू {{ number_format($summary['avg_revenue_per_appointment'], 2) }}<br>
        <strong>Outstanding Invoices:</strong> रू {{ number_format($summary['outstanding_amount'], 2) }}
    </p>
</div>

<div class="info-box" style="background-color: #eff6ff; border-left-color: #3b82f6;">
    <strong>👥 Patient Statistics:</strong>
    <p style="margin: 5px 0; color: #4a5568;">
        <strong>New Patients:</strong> {{ $summary['new_patients'] }}<br>
        <strong>Total Active Patients:</strong> {{ $summary['total_patients'] }}<br>
        <strong>Returning Patients:</strong> {{ $summary['returning_patients'] }}
    </p>
</div>

@if(isset($summary['top_treatments']))
<div style="margin: 20px 0;">
    <strong style="color: #2d3748;">🔝 Top 5 Treatments:</strong>
    <table style="width: 100%; margin-top: 10px; border-collapse: collapse;">
        <thead>
            <tr style="background-color: #f7fafc;">
                <th style="padding: 10px; text-align: left; border-bottom: 2px solid #e2e8f0;">Treatment</th>
                <th style="padding: 10px; text-align: center; border-bottom: 2px solid #e2e8f0;">Count</th>
            </tr>
        </thead>
        <tbody>
            @foreach($summary['top_treatments'] as $treatment)
            <tr>
                <td style="padding: 10px; border-bottom: 1px solid #e2e8f0;">{{ $treatment['name'] }}</td>
                <td style="padding: 10px; text-align: center; border-bottom: 1px solid #e2e8f0;">{{ $treatment['count'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

<div class="info-box" style="background-color: #fef3c7; border-left-color: #f59e0b;">
    <strong>⚡ Performance Insights:</strong>
    <p style="margin: 5px 0; color: #4a5568;">
        <strong>Appointment Completion Rate:</strong> {{ number_format($summary['completion_rate'], 1) }}%<br>
        <strong>Patient Satisfaction:</strong> {{ $summary['satisfaction_score'] ?? 'N/A' }}<br>
        <strong>Average Wait Time:</strong> {{ $summary['avg_wait_time'] ?? 'N/A' }}
    </p>
</div>

<div class="message" style="margin-top: 30px;">
    <p>Keep up the great work!</p>
    <p>Best regards,<br>
    <strong>{{ $clinic->name }} Management System</strong></p>
</div>
@endsection
