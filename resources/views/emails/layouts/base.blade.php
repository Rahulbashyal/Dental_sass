<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Medical Notification')</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f8fafc;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }
        
        .email-wrapper {
            width: 100%;
            background-color: #f8fafc;
            padding: 40px 0;
        }
        
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 10px 15px -3px rgba(0, 0, 0, 0.02);
            border: 1px solid #edf2f7;
        }
        
        .header {
            background-color: #0f172a;
            padding: 48px 40px;
            text-align: center;
            position: relative;
        }
        
        .header-logo {
            width: 56px;
            height: 56px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 24px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .header h1 {
            color: #ffffff;
            font-size: 24px;
            font-weight: 800;
            margin: 0;
            letter-spacing: -0.025em;
        }
        
        .header p {
            color: #94a3b8;
            font-size: 14px;
            margin: 8px 0 0;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .content {
            padding: 48px 40px;
        }
        
        .greeting {
            font-size: 20px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 16px;
            letter-spacing: -0.025em;
        }
        
        .message {
            font-size: 16px;
            line-height: 1.6;
            color: #475569;
            margin-bottom: 32px;
        }
        
        .info-card {
            background-color: #f8fafc;
            border-radius: 20px;
            padding: 24px;
            margin-bottom: 32px;
            border: 1px solid #f1f5f9;
        }
        
        .info-item {
            margin-bottom: 16px;
        }
        
        .info-item:last-child {
            margin-bottom: 0;
        }
        
        .info-label {
            font-size: 11px;
            font-weight: 800;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 4px;
        }
        
        .info-value {
            font-size: 15px;
            font-weight: 600;
            color: #1e293b;
        }
        
        .cta-button {
            display: inline-block;
            background-color: #0f172a;
            color: #ffffff !important;
            padding: 16px 32px;
            border-radius: 14px;
            font-weight: 700;
            font-size: 14px;
            text-decoration: none;
            text-align: center;
            transition: all 0.2s ease;
            box-shadow: 0 10px 15px -3px rgba(15, 23, 42, 0.1);
        }
        
        .footer {
            padding: 40px;
            background-color: #f8fafc;
            text-align: center;
            border-top: 1px solid #f1f5f9;
        }
        
        .footer-text {
            font-size: 13px;
            color: #64748b;
            margin: 0 0 12px;
            line-height: 1.5;
        }
        
        .footer-meta {
            font-size: 11px;
            color: #94a3b8;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .badge {
            display: inline-block;
            padding: 6px 12px;
            background-color: #ecfdf5;
            color: #059669;
            border-radius: 8px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 16px;
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-container">
            <div class="header">
                <div class="header-logo">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M7 3C4.23858 3 2 5.23858 2 8V16C2 18.7614 4.23858 21 7 21H17C19.7614 21 22 18.7614 22 16V8C22 5.23858 19.7614 3 17 3H7Z" fill="white" fill-opacity="0.2"/>
                        <path d="M16 8L12 11L8 8" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <h1>{{ $clinic->name ?? config('app.name') }}</h1>
                <p>Medical Communication System</p>
            </div>
            
            <div class="content">
                @yield('content')
            </div>
            
            <div class="footer">
                <p class="footer-text">
                    {{ $clinic->name ?? config('app.name') }} &bull; {{ $clinic->address ?? 'Professional Medical Care' }}
                </p>
                <p class="footer-text">
                    📞 {{ $clinic->phone ?? 'Support Line' }} | ✉️ {{ $clinic->email ?? 'Support Email' }}
                </p>
                <div style="height: 1px; background-color: #e2e8f0; margin: 24px 0;"></div>
                <p class="footer-meta">
                    This is a secure automated notification. Please do not reply.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
