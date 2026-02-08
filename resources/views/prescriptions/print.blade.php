<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Print Prescription - {{ $prescription->prescription_number }}</title>
    <style>
        @media print {
            .no-print {
                display: none;
            }
            @page {
                margin: 15mm;
            }
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            line-height: 1.6;
            color: #333;
            padding: 20px;
        }

        .print-button {
            margin-bottom: 20px;
            padding: 10px 20px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .print-button:hover {
            background: #1d4ed8;
        }

        /* Same styles as PDF */
        .container {
            max-width: 210mm;
            margin: 0 auto;
            background: white;
            padding: 20px;
        }

        .header {
            border-bottom: 3px solid #2563eb;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .clinic-name {
            font-size: 26px;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 5px;
        }

        .clinic-info {
            font-size: 12px;
            color: #666;
        }

        .prescription-title {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            background: #eff6ff;
            padding: 12px;
            margin: 20px 0;
            border-left: 4px solid #2563eb;
        }

        .prescription-number {
            text-align: right;
            font-size: 12px;
            color: #666;
            margin-bottom: 15px;
        }

        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #1e40af;
            margin: 15px 0 10px 0;
            padding-bottom: 5px;
            border-bottom: 2px solid #e5e7eb;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 150px 1fr;
            gap: 8px;
            margin-bottom: 15px;
        }

        .info-label {
            font-weight: bold;
        }

        .warning-box {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 12px;
            margin: 10px 0;
        }

        .medications-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }

        .medications-table th {
            background: #eff6ff;
            color: #1e40af;
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        .medications-table td {
            padding: 10px;
            border: 1px solid #ddd;
        }

        .medications-table tr:nth-child(even) {
            background: #f9fafb;
        }

        .instructions-box {
            background: #e0f2fe;
            border-left: 4px solid #0284c7;
            padding: 15px;
            margin: 15px 0;
        }

        .signature-section {
            margin-top: 60px;
            text-align: right;
        }

        .signature-line {
            display: inline-block;
            border-top: 2px solid #333;
            width: 250px;
            margin-top: 60px;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            font-size: 11px;
            color: #666;
        }
    </style>
</head>
<body>
    <button onclick="window.print()" class="print-button no-print">🖨️ Print Prescription</button>

    <div class="container">
        <!-- Same content as PDF template -->
        <div class="header">
            <div class="clinic-name">{{ $prescription->clinic->name }}</div>
            <div class="clinic-info">
                @if($prescription->clinic->address)
                    {{ $prescription->clinic->address }}<br>
                @endif
                @if($prescription->clinic->phone)
                    Phone: {{ $prescription->clinic->phone }}
                @endif
                @if($prescription->clinic->email)
                    | Email: {{ $prescription->clinic->email }}
                @endif
            </div>
        </div>

        <div class="prescription-title">PRESCRIPTION</div>

        <div class="prescription-number">
            <strong>Prescription #:</strong> {{ $prescription->prescription_number }}<br>
            <strong>Date:</strong> {{ $prescription->prescribed_date->format('d M Y') }}
        </div>

        <div class="section-title">Patient Information</div>
        <div class="info-grid">
            <div class="info-label">Name:</div>
            <div>{{ $prescription->patient->first_name }} {{ $prescription->patient->last_name }}</div>
            <div class="info-label">Age/Gender:</div>
            <div>
                @if($prescription->patient->date_of_birth)
                    {{ $prescription->patient->date_of_birth->age }} years
                @else
                    N/A
                @endif
                / {{ ucfirst($prescription->patient->gender ?? 'N/A') }}
            </div>
            <div class="info-label">Phone:</div>
            <div>{{ $prescription->patient->phone }}</div>
        </div>

        @if($prescription->known_allergies || $prescription->medical_conditions)
            <div class="warning-box">
                <strong>⚠ IMPORTANT HEALTH INFORMATION:</strong><br>
                @if($prescription->known_allergies)
                    <strong>Allergies:</strong> {{ $prescription->known_allergies }}<br>
                @endif
                @if($prescription->medical_conditions)
                    <strong>Medical Conditions:</strong> {{ $prescription->medical_conditions }}
                @endif
            </div>
        @endif

        <div class="section-title">Diagnosis & Treatment</div>
        <div class="info-grid">
            @if($prescription->chief_complaint)
                <div class="info-label">Chief Complaint:</div>
                <div>{{ $prescription->chief_complaint }}</div>
            @endif
            <div class="info-label">Diagnosis:</div>
            <div>{{ $prescription->diagnosis }}</div>
            @if($prescription->treatment_provided)
                <div class="info-label">Treatment:</div>
                <div>{{ $prescription->treatment_provided }}</div>
            @endif
        </div>

        <div class="section-title">Prescribed Medications</div>
        <table class="medications-table">
            <thead>
                <tr>
                    <th style="width: 5%">#</th>
                    <th style="width: 25%">Medication</th>
                    <th style="width: 10%">Dosage</th>
                    <th style="width: 15%">Frequency</th>
                    <th style="width: 10%">Duration</th>
                    <th style="width: 10%">Qty</th>
                    <th style="width: 25%">Instructions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($prescription->items as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <strong>{{ $item->medication_name }}</strong>
                            @if($item->generic_name)
                                <br><small style="color: #666;">({{ $item->generic_name }})</small>
                            @endif
                        </td>
                        <td>{{ $item->dosage }}</td>
                        <td>{{ $item->frequency }}</td>
                        <td>{{ $item->duration_days }} days</td>
                        <td>{{ $item->quantity }}</td>
                        <td>
                            {{ $item->route }}
                            @if($item->instructions)
                                <br><em><small>{{ $item->instructions }}</small></em>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if($prescription->general_instructions)
            <div class="instructions-box">
                <strong>GENERAL INSTRUCTIONS:</strong><br>
                {{ $prescription->general_instructions }}
            </div>
        @endif>

        <div class="signature-section">
            <div class="signature-line"></div>
            <div style="font-weight: bold; margin-top: 5px;">Dr. {{ $prescription->dentist->name }}</div>
            <div style="font-size: 12px; color: #666;">Dentist</div>
        </div>

        <div class="footer">
            <p><strong>Note:</strong> This is a computer-generated prescription. Follow all medication instructions carefully. 
            If you experience any adverse reactions, contact your dentist immediately.</p>
            <p style="margin-top: 5px;">Generated on {{ now()->format('d M Y, h:i A') }}</p>
        </div>
    </div>

    <script>
        // Auto-print dialog when page loads (optional)
        // window.addEventListener('load', function() {
        //     window.print();
        // });
    </script>
</body>
</html>
