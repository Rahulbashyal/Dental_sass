<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Appointment Reminder</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #2563eb; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background: #f9fafb; }
        .appointment-details { background: white; padding: 15px; border-radius: 8px; margin: 15px 0; }
        .footer { text-align: center; padding: 20px; color: #666; font-size: 14px; }
        .btn { display: inline-block; padding: 12px 24px; background: #2563eb; color: white; text-decoration: none; border-radius: 6px; margin: 10px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ $clinic->name }}</h1>
            <p>Appointment Reminder</p>
        </div>
        
        <div class="content">
            <h2>Dear {{ $patient->name }},</h2>
            
            <p>This is a friendly reminder about your upcoming appointment:</p>
            
            <div class="appointment-details">
                <h3>Appointment Details</h3>
                <p><strong>Date:</strong> {{ $appointment->appointment_date }} ({{ $nepaliDate['formatted'] }})</p>
                <p><strong>Time:</strong> {{ $appointment->appointment_time }}</p>
                <p><strong>Type:</strong> {{ $appointment->type }}</p>
                <p><strong>Doctor:</strong> {{ $appointment->dentist->name }}</p>
                <p><strong>Clinic:</strong> {{ $clinic->name }}</p>
                <p><strong>Address:</strong> {{ $clinic->address }}</p>
            </div>
            
            <p><strong>Important:</strong> Please arrive 15 minutes early for your appointment.</p>
            
            <p>If you need to reschedule or cancel, please contact us as soon as possible.</p>
        </div>
        
        <div class="footer">
            <p>Best regards,<br>{{ $clinic->name }}</p>
            <p>{{ $clinic->phone ?? '' }} | {{ $clinic->email ?? '' }}</p>
        </div>
    </div>
</body>
</html>