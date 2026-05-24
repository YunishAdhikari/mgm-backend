<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Holiday Request Form</title>

    <style>
        /* @page {
            margin: 20px;
        } */

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

        }

        .logo {
            position: absolute;
            right: 10px;
            top: 0;
            width: 110px;
            height: auto;
            background: #b91c1c;
            color: #facc15;
            text-align: center;
            font-size: 30px;
            font-weight: bold;
            line-height: 70px;
        }
       

        .title {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            text-decoration: underline;
            margin-top: 35px;
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
        {{-- <div class="logo">MGM</div> --}}
        <img
            src="{{ public_path('images/mgm-logo.png') }}"
            class="logo"
        >

        <div class="title">HOLIDAY REQUEST FORM</div>
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
            <td>{{ ucfirst($employee->role->name ?? 'Staff') }}</td>
        </tr>

        <tr>
            <td class="label">Department:</td>
            <td>{{ $employee->department->name ?? 'N/A' }}</td>
        </tr>

        {{-- <tr>
            <td class="label">Holiday year:</td>
            <td>{{ $year }}</td>
        </tr> --}}
        <td class="label">Holiday year:</td>
            <td>
                {{ $year }}
                @if(!empty($month))
                {{ \Carbon\Carbon::create()->month((int)$month)->format('F') }}
                    {{-- - {{ \Carbon\Carbon::create()->month($month)->format('F') }} --}}
                @endif
            </td>

        <tr>
            <td class="label">Entitlement in full year:</td>
            <td></td>
        </tr>
    </table>

    <table class="holiday-table">
        <thead>
            <tr>
                <th>First Day<br>of Leave</th>
                <th>Last Day of<br>Leave</th>
                <th>Number<br>of working<br>days</th>
                <th>Date of<br>request/employee<br>signature</th>
                <th>Authorization:<br>Approved<br>by/Signature/Date</th>
                <th>Days<br>remaining</th>
            </tr>
        </thead>

        <tbody>
            @foreach($employee->holidayRequests as $holiday)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($holiday->start_date)->format('d M Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($holiday->end_date)->format('d M Y') }}</td>
                    <td>{{ $holiday->total_days }}</td>
                    <td>
                        {{ \Carbon\Carbon::parse($holiday->created_at)->format('d M Y') }}
                        <br>
                        <span class="small">{{ $employee->name }}</span>
                    </td>
                    <td>
                        Approved
                        <br>
                        {{ $holiday->approver->name ?? 'Manager' }}
                        <br>
                        {{ \Carbon\Carbon::parse($holiday->updated_at)->format('d M Y') }}
                    </td>
                    <td></td>
                </tr>
            @endforeach

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