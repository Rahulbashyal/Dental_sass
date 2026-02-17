<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invoice</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #7c3aed; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background: #f9fafb; }
        .invoice-details { background: white; padding: 15px; border-radius: 8px; margin: 15px 0; }
        .footer { text-align: center; padding: 20px; color: #666; font-size: 14px; }
        .amount { font-size: 24px; font-weight: bold; color: #7c3aed; }
        .btn { display: inline-block; padding: 12px 24px; background: #7c3aed; color: white; text-decoration: none; border-radius: 6px; margin: 10px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ $clinic->name }}</h1>
            <p>Invoice #{{ $invoice->invoice_number }}</p>
        </div>
        
        <div class="content">
            <h2>Dear {{ $patient->name }},</h2>
            
            <p>Please find your invoice details below:</p>
            
            <div class="invoice-details">
                <h3>Invoice Information</h3>
                <p><strong>Invoice Number:</strong> {{ $invoice->invoice_number }}</p>
                <p><strong>Invoice Date:</strong> {{ $invoice->invoice_date }}</p>
                <p><strong>Due Date:</strong> {{ $invoice->due_date }}</p>
                <p><strong>Amount:</strong> <span class="amount">NPR {{ number_format($invoice->total_amount, 2) }}</span></p>
                
                @if($invoice->description)
                <p><strong>Description:</strong> {{ $invoice->description }}</p>
                @endif
            </div>
            
            <p>You can pay online through our patient portal or visit our clinic during business hours.</p>
            
            <p>If you have any questions about this invoice, please don't hesitate to contact us.</p>
        </div>
        
        <div class="footer">
            <p>Thank you for your business,<br>{{ $clinic->name }}</p>
            <p>{{ $clinic->phone ?? '' }} | {{ $clinic->email ?? '' }}</p>
        </div>
    </div>
</body>
</html>