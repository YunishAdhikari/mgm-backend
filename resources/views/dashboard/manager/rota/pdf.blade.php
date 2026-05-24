<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Weekly Staff Rota - {{ $weekStart->format('d M Y') }}</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', 'Helvetica', sans-serif;
            font-size: 10px;
            color: #111;
            line-height: 1.4;
            padding: 10px;
        }

        /* Header */
        .header {
            text-align: center;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 2px solid #dc2626;
        }

        .company-name {
            font-size: 18px;
            font-weight: bold;
            color: #dc2626;
            margin-bottom: 5px;
        }

        .title {
            font-size: 14px;
            font-weight: bold;
            color: #111;
            margin-bottom: 5px;
        }

        .week-info {
            font-size: 11px;
            color: #555;
        }

        .week-dates {
            font-weight: bold;
            color: #111;
        }

        /* Legend */
        .legend {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 15px;
            padding: 8px;
            background: #f9fafb;
            border-radius: 5px;
            font-size: 9px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .legend-dot {
            width: 10px;
            height: 10px;
            border-radius: 2px;
        }

        /* Department */
        .department {
            margin-top: 20px;
            margin-bottom: 10px;
        }

        .dept-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(135deg, #00aeea, #0096c7);
            color: #000;
            font-size: 13px;
            font-weight: bold;
            padding: 8px 12px;
            border-radius: 5px 5px 0 0;
        }

        .emp-count {
            font-size: 10px;
            background: rgba(0,0,0,0.15);
            padding: 2px 8px;
            border-radius: 10px;
        }

        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #333;
        }

        th, td {
            border: 1px solid #333;
            padding: 8px 6px;
            vertical-align: top;
            text-align: center;
        }

        th {
            background: #e5e7eb;
            font-size: 10px;
            font-weight: bold;
        }

        .staff-name {
            font-weight: bold;
            width: 120px;
            text-align: left !important;
            background: #f3f4f6 !important;
        }

        .day-header {
            font-size: 10px;
        }

        .day-num {
            display: block;
            font-size: 9px;
            color: #666;
        }

        /* Shift Types */
        .shift {
            font-size: 9px;
            line-height: 1.4;
            padding: 2px 4px;
            border-radius: 3px;
            margin-bottom: 2px;
        }

        .morning {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .evening {
            background: #fef3c7;
            color: #92400e;
        }

        .night {
            background: #ede9fe;
            color: #6d28d9;
        }

        .split {
            background: #fce7f3;
            color: #be185d;
        }

        .off {
            background: #f3f4f6;
            color: #666;
            font-style: italic;
        }

        .holiday {
            background: #dcfce7;
            color: #166534;
            font-weight: bold;
        }

        .sick {
            background: #fee2e2;
            color: #991b1b;
            font-weight: bold;
        }

        .day-off {
            background: #6b7280;
            color: white;
        }

        .notes {
            font-size: 8px;
            color: #666;
            font-style: italic;
        }

        /* Footer */
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 9px;
            color: #666;
        }

        .print-date {
            font-size: 8px;
            color: #999;
        }

        /* Page break */
        .page-break {
            page-break-before: always;
        }
    </style>
</head>

<body>

<!-- Header -->
<div class="header">
    <div class="company-name">MGM HOTEL & CASINO</div>
    <div class="title">Weekly Staff Rota</div>
    <div class="week-info">
        Week: <span class="week-dates">{{ $weekStart->format('d M Y') }} - {{ $weekStart->copy()->addDays(6)->format('d M Y') }}</span>
    </div>
</div>

<!-- Legend -->
<div class="legend">
    <div class="legend-item">
        <span class="legend-dot" style="background: #dbeafe;"></span> Morning
    </div>
    <div class="legend-item">
        <span class="legend-dot" style="background: #fef3c7;"></span> Evening
    </div>
    <div class="legend-item">
        <span class="legend-dot" style="background: #ede9fe;"></span> Night
    </div>
    <div class="legend-item">
        <span class="legend-dot" style="background: #fce7f3;"></span> Split
    </div>
    <div class="legend-item">
        <span class="legend-dot" style="background: #f3f4f6;"></span> OFF
    </div>
    <div class="legend-item">
        <span class="legend-dot" style="background: #dcfce7;"></span> Holiday
    </div>
    <div class="legend-item">
        <span class="legend-dot" style="background: #fee2e2;"></span> Sick
    </div>
</div>

@foreach($departments as $department)

    <div class="department">
        
        <div class="dept-header">
            <span>{{ $department->name }}</span>
            <span class="emp-count">{{ $department->users->count() }} Staff</span>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Staff Name</th>
                    @foreach($weekDates as $date)
                        <th>
                            <div class="day-header">
                                {{ $date->format('D') }}
                                <span class="day-num">{{ $date->format('d M') }}</span>
                            </div>
                        </th>
                    @endforeach
                </tr>
            </thead>

            <tbody>
                @forelse($department->users as $user)
                    <tr>
                        <td class="staff-name">
                            {{ $user->name }}
                        </td>

                        @foreach($weekDates as $date)
                            @php
                                $dayShifts = $shifts->where('user_id', $user->id)
                                    ->where('shift_date', $date->format('Y-m-d'));
                            @endphp

                            <td>
                                @forelse($dayShifts as $shift)
                                    <div class="shift {{ $shift->shift_type }}">
                                        @if($shift->shift_type === 'split')
                                            {{ Carbon\Carbon::parse($shift->split_start_time_1)->format('H:i') }}-{{ Carbon\Carbon::parse($shift->split_end_time_1)->format('H:i') }}
                                            <br>
                                            {{ Carbon\Carbon::parse($shift->split_start_time_2)->format('H:i') }}-{{ Carbon\Carbon::parse($shift->split_end_time_2)->format('H:i') }}
                                        @elseif(in_array($shift->shift_type, ['day_off', 'holiday', 'sick']))
                                            {{ strtoupper(str_replace('_', ' ', $shift->shift_type)) }}
                                        @else
                                            {{ Carbon\Carbon::parse($shift->start_time)->format('H:i') }} - {{ Carbon\Carbon::parse($shift->end_time)->format('H:i') }}
                                        @endif

                                        @if($shift->note)
                                            <div class="notes">{{ $shift->note }}</div>
                                        @endif
                                    </div>
                                @empty
                                    <div class="shift off">OFF</div>
                                @endforelse
                            </td>
                        @endforeach
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">No staff in this department.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>

@endforeach

<!-- Footer -->
<div class="footer">
    <div>Generated on {{ now()->format('d M Y H:i:s') }}</div>
    <div class="print-date">For internal use only</div>
</div>

</body>
</html>