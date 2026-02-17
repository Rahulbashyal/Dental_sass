<!DOCTYPE html>
<html>
<head>
    <title>Daily Schedule - {{ date('M d, Y') }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .header { text-align: center; margin-bottom: 30px; }
        .schedule-table { width: 100%; border-collapse: collapse; }
        .schedule-table th, .schedule-table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .schedule-table th { background-color: #f2f2f2; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ auth()->user()->clinic->name ?? 'Dental Clinic' }}</h1>
        <h2>Daily Schedule - {{ date('M d, Y') }}</h2>
    </div>

    <table class="schedule-table">
        <thead>
            <tr>
                <th>Time</th>
                <th>Patient</th>
                <th>Dentist</th>
                <th>Type</th>
                <th>Status</th>
                <th>Notes</th>
            </tr>
        </thead>
        <tbody>
            @forelse($appointments as $appointment)
            <tr>
                <td>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</td>
                <td>{{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}</td>
                <td>{{ $appointment->dentist->name }}</td>
                <td>{{ ucfirst($appointment->type) }}</td>
                <td>{{ ucfirst($appointment->status) }}</td>
                <td>{{ $appointment->notes ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center;">No appointments scheduled for today</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="no-print" style="margin-top: 20px;">
        <button onclick="window.print()">Print Schedule</button>
        <button onclick="window.close()">Close</button>
    </div>
</body>
</html>