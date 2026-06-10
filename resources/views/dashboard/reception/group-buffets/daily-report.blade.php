<!DOCTYPE html>
<html>
<head>
    <title>Daily Group Buffet Report</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            color: #111;
            padding: 28px;
        }

        .print-btn {
            margin-bottom: 20px;
            padding: 10px 18px;
            background: #111;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #111;
            padding-bottom: 14px;
            margin-bottom: 22px;
        }

        .header h1 {
            margin: 0;
            font-size: 26px;
        }

        .header p {
            margin: 6px 0 0;
            font-size: 15px;
        }

        .summary {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-bottom: 22px;
        }

        .summary-box {
            border: 1px solid #999;
            padding: 12px;
            border-radius: 8px;
        }

        .label {
            font-size: 11px;
            text-transform: uppercase;
            color: #555;
            margin-bottom: 4px;
        }

        .value {
            font-size: 20px;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }

        th, td {
            border: 1px solid #555;
            padding: 10px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background: #eee;
            font-size: 13px;
            text-transform: uppercase;
        }

        td {
            font-size: 14px;
        }

        .notes {
            max-width: 260px;
        }

        @media print {
            .print-btn {
                display: none;
            }

            body {
                padding: 10px;
            }
        }
    </style>
</head>

<body>

    <button onclick="window.print()" class="print-btn">
        Print / Save as PDF
    </button>

    <div class="header">
        <h1>Daily Group Buffet Report</h1>
        <p>MGM Muthu Glasgow River Hotel</p>
        <p>{{ \Carbon\Carbon::parse($date)->format('d M Y') }}</p>
    </div>

    <div class="summary">
        <div class="summary-box">
            <div class="label">Total Groups</div>
            <div class="value">{{ $bookings->count() }}</div>
        </div>

        <div class="summary-box">
            <div class="label">Total Pax</div>
            <div class="value">{{ $bookings->sum('pax') }}</div>
        </div>

        <div class="summary-box">
            <div class="label">Report Date</div>
            <div class="value">{{ \Carbon\Carbon::parse($date)->format('d M') }}</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Time Slot</th>
                <th>Group Name</th>
                <th>Pax</th>
                <th>Meal Type</th>
                <th>Table Numbers</th>
                <th>Notes</th>
            </tr>
        </thead>

        <tbody>
            @forelse($bookings as $booking)
                <tr>
                    <td>
                        {{ \Carbon\Carbon::parse($booking->buffet_time)->format('h:i A') }}
                    </td>

                    <td>
                        <strong>{{ $booking->group_name }}</strong>
                        @if($booking->agent_name)
                            <br>
                            <small>Agent: {{ $booking->agent_name }}</small>
                        @endif
                    </td>

                    <td>
                        {{ $booking->pax }}
                    </td>

                    <td>
                        {{ ucfirst(str_replace('_', ' ', $booking->meal_type)) }}
                    </td>

                    <td>
                        @forelse($booking->tables as $table)
                            {{ $table->table_name }} ({{ $table->capacity }}){{ !$loop->last ? ', ' : '' }}
                        @empty
                            No tables allocated
                        @endforelse
                    </td>

                    <td class="notes">
                        {{ $booking->notes ?: '-' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">
                        No group buffet bookings found for this date.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>