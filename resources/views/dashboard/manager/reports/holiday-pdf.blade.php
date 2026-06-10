```blade
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Holiday Request Form</title>

    <style>
        @page {
            size: A4 portrait;
            margin: 20px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #000;
        }

        .page {
            page-break-after: always;
        }

        .page:last-child {
            page-break-after: auto;
        }

        .header {
            width: 100%;
            margin-bottom: 20px;
            position: relative;
            min-height: 90px;
        }

        .logo {
            position: absolute;
            right: 10px;
            top: 0;
            width: 110px;
            height: auto;
        }

        .title {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            text-decoration: underline;
            padding-top: 35px;
        }

        .employee-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .employee-table td {
            border: 1px solid #000;
            padding: 6px;
            height: 22px;
        }

        .label {
            background: #c75b32;
            font-weight: bold;
            width: 150px;
        }

        .holiday-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 28px;
        }

        .holiday-table th,
        .holiday-table td {
            border: 1px solid #000;
            text-align: center;
            vertical-align: middle;
            height: 58px;
            padding: 5px;
        }

        .holiday-table th {
            background: #c75b32;
            font-weight: bold;
        }

        .blank-row td {
            height: 62px;
        }

        .small {
            font-size: 11px;
        }
    </style>
</head>

<body>

@foreach($employees as $employee)

<div class="page">

    <div class="header">

        @if(file_exists(public_path('images/mgm-logo.png')))
            <img src="{{ public_path('images/mgm-logo.png') }}" class="logo">
        @endif

        <div class="title">
            HOLIDAY REQUEST FORM
        </div>

    </div>

    <table class="employee-table">

        <tr>
            <td class="label">Employee Full Name:</td>
            <td>{{ $employee->name }}</td>
        </tr>

        <tr>
            <td class="label">Hotel Unit:</td>
            <td>Muthu Glasgow River Hotel</td>
        </tr>

        <tr>
            <td class="label">Position:</td>
            <td>{{ $employee->role->name ?? 'Staff' }}</td>
        </tr>

        <tr>
            <td class="label">Department:</td>
            <td>{{ $employee->department->name ?? 'N/A' }}</td>
        </tr>

        <tr>
            <td class="label">Holiday Year:</td>
            <td>
                {{ $year }}

                @if(!empty($month))
                    - {{ \Carbon\Carbon::create()->month((int)$month)->format('F') }}
                @endif
            </td>
        </tr>

        <tr>
            <td class="label">Entitlement in Full Year:</td>
            <td></td>
        </tr>

    </table>

    <table class="holiday-table">

        <thead>
            <tr>
                <th>First Day<br>of Leave</th>
                <th>Last Day<br>of Leave</th>
                <th>Number of<br>Working Days</th>
                <th>Date of Request /<br>Employee Signature</th>
                <th>Approved By /<br>Signature / Date</th>
                <th>Days<br>Remaining</th>
            </tr>
        </thead>

        <tbody>

            @forelse($employee->holidayRequests as $holiday)

                <tr>

                    <td>
                        {{ optional($holiday->start_date)->format ? '' : '' }}
                        {{ \Carbon\Carbon::parse($holiday->start_date)->format('d M Y') }}
                    </td>

                    <td>
                        {{ \Carbon\Carbon::parse($holiday->end_date)->format('d M Y') }}
                    </td>

                    <td>
                        {{ $holiday->total_days ?? 0 }}
                    </td>

                    <td>
                        {{ \Carbon\Carbon::parse($holiday->created_at)->format('d M Y') }}
                        <br>
                        <span class="small">
                            {{ $employee->name }}
                        </span>
                    </td>

                    <td>
                        Approved
                        <br>

                        {{ optional($holiday->approver)->name ?? 'Manager' }}

                        <br>

                        @if($holiday->updated_at)
                            {{ \Carbon\Carbon::parse($holiday->updated_at)->format('d M Y') }}
                        @endif
                    </td>

                    <td></td>

                </tr>

            @empty

                <tr>
                    <td colspan="6">
                        No holiday requests found
                    </td>
                </tr>

            @endforelse

            @for($i = $employee->holidayRequests->count(); $i < 7; $i++)
                <tr class="blank-row">
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            @endfor

        </tbody>

    </table>

</div>

@endforeach

</body>
</html>
```
