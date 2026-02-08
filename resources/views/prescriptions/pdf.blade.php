<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Prescription - {{ $prescription->prescription_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
        }

        .container {
            padding: 20px;
            max-width: 210mm;
            margin: 0 auto;
        }

        /* Clinic Header with Branding */
        .header {
            border-bottom: 3px solid #2563eb;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .clinic-name {
            font-size: 24px;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 5px;
        }

        .clinic-info {
            font-size: 11px;
            color: #666;
            line-height: 1.4;
        }

        .prescription-title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            background: #eff6ff;
            padding: 10px;
            margin: 20px 0;
            border-left: 4px solid #2563eb;
        }

        .prescription-number {
            text-align: right;
            font-size: 11px;
            color: #666;
            margin-bottom: 15px;
        }

        .info-section {
            margin-bottom: 15px;
        }

        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }

        .info-row {
            display: table-row;
        }

        .info-label {
            display: table-cell;
            font-weight: bold;
            padding: 5px 10px 5px 0;
            width: 30%;
        }

        .info-value {
            display: table-cell;
            padding: 5px 0;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #1e40af;
            margin: 15px 0 10px 0;
            padding-bottom: 5px;
            border-bottom: 2px solid #e5e7eb;
        }

        .warning-box {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 10px;
            margin: 10px 0;
            font-size: 11px;
        }

        .medications-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }

        .medications-table th {
            background: #eff6ff;
            color: #1e40af;
            padding: 8px;
            text-align: left;
            font-size: 11px;
            border: 1px solid #ddd;
        }

        .medications-table td {
            padding: 8px;
            border: 1px solid #ddd;
            font-size: 11px;
        }

        .medications-table tr:nth-child(even) {
            background: #f9fafb;
        }

        .instructions-box {
            background: #e0f2fe;
            border-left: 4px solid #0284c7;
            padding: 12px;
            margin: 15px 0;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            font-size: 10px;
            color: #666;
        }

        .signature-section {
            margin-top: 50px;
            text-align: right;
        }

        .signature-line {
            display: inline-block;
            border-top: 1px solid #333;
            width: 200px;
            margin-top: 50px;
        }

        .dentist-name {
            font-weight: bold;
            margin-top: 5px;
        }

        @media print {
            .container {
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Clinic Header -->
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

        <!-- Prescription Title -->
        <div class="prescription-title">PRESCRIPTION</div>

        <!-- Prescription Number & Date -->
        <div class="prescription-number">
            <strong>Prescription #:</strong> {{ $prescription->prescription_number }}<br>
            <strong>Date:</strong> {{ $prescription->prescribed_date->format('d M Y') }}
        </div>

        <!-- Patient Information -->
        <div class="info-section">
            <div class="section-title">Patient Information</div>
            <div class="info-grid">
                <div class="info-row">
                    <div class="info-label">Name:</div>
                    <div class="info-value">{{ $prescription->patient->first_name }} {{ $prescription->patient->last_name }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Age/Gender:</div>
                    <div class="info-value">
                        @if($prescription->patient->date_of_birth)
                            {{ $prescription->patient->date_of_birth->age }} years
                        @else
                            N/A
                        @endif
                        / {{ ucfirst($prescription->patient->gender ?? 'N/A') }}
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">Phone:</div>
                    <div class="info-value">{{ $prescription->patient->phone }}</div>
                </div>
            </div>
        </div>

        <!-- Health Warnings -->
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

        <!-- Diagnosis -->
        <div class="info-section">
            <div class="section-title">Diagnosis & Treatment</div>
            @if($prescription->chief_complaint)
                <div class="info-grid">
                    <div class="info-row">
                        <div class="info-label">Chief Complaint:</div>
                        <div class="info-value">{{ $prescription->chief_complaint }}</div>
                    </div>
                </div>
            @endif
            <div class="info-grid">
                <div class="info-row">
                    <div class="info-label">Diagnosis:</div>
                    <div class="info-value">{{ $prescription->diagnosis }}</div>
                </div>
                @if($prescription->treatment_provided)
                    <div class="info-row">
                        <div class="info-label">Treatment:</div>
                        <div class="info-value">{{ $prescription->treatment_provided }}</div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Medications Table -->
        <div class="section-title">Prescribed Medications</div>
        <table class="medications-table">
            <thead>
                <tr>
                    <th style="width: 5%">#</th>
                    <th style="width: 25%">Medication</th>
                    <th style="width: 10%">Dosage</th>
                    <th style="width: 15%">Frequency</th>
                    <th style="width: 10%">Duration</th>
                    <th style="width: 10%">Quantity</th>
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
                                <br><span style="font-size: 10px; color: #666;">({{ $item->generic_name }})</span>
                            @endif
                        </td>
                        <td>{{ $item->dosage }}</td>
                        <td>{{ $item->frequency }}</td>
                        <td>{{ $item->duration_days }} days</td>
                        <td>{{ $item->quantity }}</td>
                        <td>
                            {{ $item->route }}
                            @if($item->instructions)
                                <br><em style="font-size: 10px;">{{ $item->instructions }}</em>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- General Instructions -->
        @if($prescription->general_instructions)
            <div class="instructions-box">
                <strong>GENERAL INSTRUCTIONS:</strong><br>
                {{ $prescription->general_instructions }}
            </div>
        @endif

        <!-- Signature -->
        <div class="signature-section">
            <div class="signature-line"></div>
            <div class="dentist-name">Dr. {{ $prescription->dentist->name }}</div>
            <div style="font-size: 10px; color: #666;">Dentist</div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>Note:</strong> This is a computer-generated prescription. Follow all medication instructions carefully. 
            If you experience any adverse reactions, contact your dentist immediately.</p>
            <p style="margin-top: 5px;">Generated on {{ now()->format('d M Y, h:i A') }}</p>
        </div>
    </div>
</body>
</html>
