<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Appointment Confirmed</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #059669; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background: #f9fafb; }
        .appointment-details { background: white; padding: 15px; border-radius: 8px; margin: 15px 0; }
        .footer { text-align: center; padding: 20px; color: #666; font-size: 14px; }
        .success-badge { background: #10b981; color: white; padding: 8px 16px; border-radius: 20px; display: inline-block; margin: 10px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ $clinic->name }}</h1>
            <div class="success-badge">✓ Appointment Confirmed</div>
        </div>
        
        <div class="content">
            <h2>Dear {{ $patient->name }},</h2>
            
            <p>Great news! Your appointment has been confirmed.</p>
            
            <div class="appointment-details">
                <h3>Confirmed Appointment Details</h3>
                <p><strong>Date:</strong> {{ $appointment->appointment_date }} ({{ $nepaliDate['formatted'] }})</p>
                <p><strong>Time:</strong> {{ $appointment->appointment_time }}</p>
                <p><strong>Type:</strong> {{ $appointment->type }}</p>
                <p><strong>Doctor:</strong> {{ $appointment->dentist->name }}</p>
                <p><strong>Clinic:</strong> {{ $clinic->name }}</p>
                <p><strong>Address:</strong> {{ $clinic->address }}</p>
            </div>
            
            <p>We look forward to seeing you. Please arrive 15 minutes early.</p>
            
            <p>Thank you for choosing {{ $clinic->name }} for your dental care needs.</p>
        </div>
        
        <div class="footer">
            <p>Best regards,<br>{{ $clinic->name }}</p>
            <p>{{ $clinic->phone ?? '' }} | {{ $clinic->email ?? '' }}</p>
        </div>
    </div>
</body>
</html>