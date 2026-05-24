<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Monthly Attendance Report</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #111827;
        }

        .page-break {
            page-break-after: always;
        }

        h1 {
            text-align: center;
            margin: 0 0 4px;
            font-size: 20px;
        }

        .subtitle {
            text-align: center;
            margin-bottom: 16px;
            color: #555;
            font-size: 11px;
        }

        .staff-header {
            margin-top: 10px;
            margin-bottom: 10px;
            padding: 8px;
            background: #f3f4f6;
            border: 1px solid #d1d5db;
        }

        .staff-header strong {
            font-size: 13px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }

        th,
        td {
            border: 1px solid #777;
            padding: 6px;
            text-align: center;
            vertical-align: middle;
        }

        th {
            background: #e5e7eb;
            font-weight: bold;
        }

        .totals {
            margin-top: 10px;
            width: 100%;
        }

        .totals td {
            text-align: left;
            font-weight: bold;
        }

        .blank {
            color: #999;
        }
    </style>
</head>

<body>

@foreach($reports as $report)

    <h1>MGRH Monthly Attendance Report</h1>

    <div class="subtitle">
        Month: {{ $monthStart->format('F Y') }}
        |
        Generated: {{ now()->format('d M Y H:i') }}
    </div>

    <div class="staff-header">
        <strong>Staff:</strong> {{ $report['user']->name }}
        <br>
        <strong>Department:</strong> {{ $report['user']->department->name ?? 'N/A' }}
        <br>
        <strong>Role:</strong> {{ $report['user']->role->name ?? 'N/A' }}
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 22%;">Date</th>
                <th style="width: 20%;">Time In</th>
                <th style="width: 20%;">Time Out</th>
                <th style="width: 18%;">Initials</th>
                <th style="width: 20%;">Hours</th>
            </tr>
        </thead>

        <tbody>
            @foreach($report['days'] as $day)
                <tr>
                    <td>{{ $day['date'] }}</td>
                    <td>{{ $day['time_in'] ?: '' }}</td>
                    <td>{{ $day['time_out'] ?: '' }}</td>
                    <td>{{ $day['initials'] ?: '' }}</td>
                    <td>{{ $day['hours'] !== '' ? number_format($day['hours'], 2) : '' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals">
        <tr>
            <td>Actual Hours Worked</td>
            <td>{{ number_format($report['actual_hours'], 2) }}</td>
        </tr>
        <tr>
            <td>Forecasted Hours</td>
            <td>{{ number_format($report['forecast_hours'], 2) }}</td>
        </tr>
        <tr>
            <td>Projected Monthly Total</td>
            <td>{{ number_format($report['projected_hours'], 2) }}</td>
        </tr>
    </table>

    @if(!$loop->last)
        <div class="page-break"></div>
    @endif

@endforeach

</body>
</html>