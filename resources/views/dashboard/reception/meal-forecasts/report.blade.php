@extends('dashboard.reception.layout')

@section('content')

<style>
.report-wrapper{
    padding:30px;
    background:#f4f4f5;
    min-height:100vh;
    color:#111827;
}

.report-actions{
    display:flex;
    gap:10px;
    margin-bottom:18px;
}

.report-btn{
    border:none;
    padding:10px 16px;
    border-radius:10px;
    font-weight:800;
    cursor:pointer;
    text-decoration:none;
}

.btn-print{
    background:#111827;
    color:white;
}

.btn-back{
    background:#dc2626;
    color:white;
}

.report-paper{
    background:white;
    padding:30px;
    border-radius:12px;
    box-shadow:0 10px 30px rgba(0,0,0,.08);
}

.report-header{
    display:flex;
    justify-content:space-between;
    align-items:flex-start;
    border-bottom:3px solid #111827;
    padding-bottom:18px;
    margin-bottom:22px;
}

.report-header h2{
    margin:0;
    font-weight:900;
}

.report-table{
    width:100%;
    border-collapse:collapse;
    font-size:12px;
}

.report-table th{
    background:#111827;
    color:white;
    padding:9px;
    border:1px solid #111827;
    text-align:left;
}

.report-table td{
    padding:9px;
    border:1px solid #d1d5db;
    vertical-align:top;
}

.report-table tbody tr:nth-child(even){
    background:#f9fafb;
}

.group-text{
    font-size:11px;
    line-height:1.5;
}

.summary-box{
    margin-top:20px;
    display:grid;
    grid-template-columns:repeat(6, 1fr);
    gap:12px;
}

.summary-card{
    border:1px solid #d1d5db;
    border-radius:10px;
    padding:14px;
    background:#f9fafb;
}

.summary-card span{
    display:block;
    color:#6b7280;
    font-size:11px;
    font-weight:700;
}

.summary-card strong{
    font-size:22px;
    color:#111827;
}

.footer-note{
    margin-top:22px;
    font-size:11px;
    color:#6b7280;
    border-top:1px solid #e5e7eb;
    padding-top:12px;
}

.report-logo{
    height:70px;
}

@media print{
    .report-logo{
        height:60px;
    }
}

@media print{
    body *{
        visibility:hidden;
    }

    .report-wrapper,
    .report-wrapper *{
        visibility:visible;
    }

    .report-wrapper{
        position:absolute;
        left:0;
        top:0;
        width:100%;
        background:white;
        padding:0;
    }

    .report-actions{
        display:none;
    }

    .report-paper{
        box-shadow:none;
        border-radius:0;
        padding:15px;
    }

    .report-table{
        font-size:10px;
    }

    .report-table th,
    .report-table td{
        padding:6px;
    }

    .summary-box{
        grid-template-columns:repeat(3, 1fr);
    }
}
</style>

<div class="report-wrapper">

    <div class="report-actions">
        <a href="{{ route('reception.meal-forecasts.index') }}" class="report-btn btn-back">
            Back
        </a>

        <button onclick="window.print()" class="report-btn btn-print">
            Print / Save PDF
        </button>
    </div>

    <div class="report-paper">

        <div class="report-header">

    <div style="display:flex;align-items:center;gap:20px;">

        <img src="{{ asset('images/mgm-logo.png') }}"
             alt="MGM Logo"
             style="height:70px;">

        <div>
            <h2 style="margin:0;">
                Dinner & Breakfast Forecast
            </h2>

            <p style="margin:5px 0 0;">
                MGM Muthu Glasgow River Hotel
            </p>
        </div>

    </div>

            <div>
                <strong>From:</strong> {{ \Carbon\Carbon::parse($fromDate)->format('d/m/Y') }}<br>
                <strong>To:</strong> {{ \Carbon\Carbon::parse($toDate)->format('d/m/Y') }}<br>
                <strong>Generated:</strong> {{ now()->format('d/m/Y H:i') }}
            </div>
        </div>

        @php
            $totalBreakfast = 0;
            $totalDinner = 0;
            $totalGroupBreakfast = 0;
            $totalGroupDinner = 0;
            $totalFitBreakfast = 0;
            $totalFitDinner = 0;
        @endphp

        <table class="report-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Day</th>
                    <th>B/F Total</th>
                    <th>B/F Groups</th>
                    <th>B/F FIT</th>
                    <th>Dinner Total</th>
                    <th>Dinner Groups</th>
                    <th>Dinner FIT</th>
                    <th>Notes</th>
                </tr>
            </thead>

            <tbody>
                @forelse($forecasts as $forecast)
                    @php
                        $forecastDate = $forecast->forecast_date;

                        $bfGroupTotal = 0;
                        $dnGroupTotal = 0;

                        $bfGroupList = [];
                        $dnGroupList = [];

                        foreach ($groupStays as $groupStay) {
                            $checkIn = $groupStay->check_in_date;
                            $checkOut = $groupStay->check_out_date;
                            $package = $groupStay->package_type;
                            $pax = $groupStay->pax;
                            $name = optional($groupStay->forecastGroup)->name ?? 'Unknown Group';

                            $hasBreakfast = false;
                            $hasDinner = false;

                            if (($package === 'bb' || $package === 'dbb')
                                && $forecastDate->gt($checkIn)
                                && $forecastDate->lte($checkOut)) {
                                $hasBreakfast = true;
                            }

                            if (($package === 'dinner_only' || $package === 'dbb')
                                && $forecastDate->gte($checkIn)
                                && $forecastDate->lt($checkOut)) {
                                $hasDinner = true;
                            }

                            if ($hasBreakfast) {
                                $bfGroupTotal += $pax;
                                $bfGroupList[] = $name . ' (' . $pax . ')';
                            }

                            if ($hasDinner) {
                                $dnGroupTotal += $pax;
                                $dnGroupList[] = $name . ' (' . $pax . ')';
                            }
                        }

                        $bfFit = $forecast->total_breakfast - $bfGroupTotal;
                        $dnFit = $forecast->total_dinner - $dnGroupTotal;

                        $totalBreakfast += $forecast->total_breakfast;
                        $totalDinner += $forecast->total_dinner;
                        $totalGroupBreakfast += $bfGroupTotal;
                        $totalGroupDinner += $dnGroupTotal;
                        $totalFitBreakfast += $bfFit;
                        $totalFitDinner += $dnFit;

                        $bfGroups = implode(', ', $bfGroupList);
                        $dnGroups = implode(', ', $dnGroupList);
                    @endphp

                    <tr>
                        <td>{{ $forecast->forecast_date->format('d/m/Y') }}</td>
                        <td>{{ $forecast->forecast_date->format('l') }}</td>
                        <td><strong>{{ $forecast->total_breakfast }}</strong></td>
                        <td class="group-text">{{ $bfGroups ?: 'No Group' }}</td>
                        <td>{{ $bfFit }}</td>
                        <td><strong>{{ $forecast->total_dinner }}</strong></td>
                        <td class="group-text">{{ $dnGroups ?: 'No Group' }}</td>
                        <td>{{ $dnFit }}</td>
                        <td>{{ $forecast->notes ?: '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" style="text-align:center;">
                            No forecast found for this date range.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="summary-box">
            <div class="summary-card">
                <span>Total Breakfast</span>
                <strong>{{ $totalBreakfast }}</strong>
            </div>

            <div class="summary-card">
                <span>Group Breakfast</span>
                <strong>{{ $totalGroupBreakfast }}</strong>
            </div>

            <div class="summary-card">
                <span>FIT Breakfast</span>
                <strong>{{ $totalFitBreakfast }}</strong>
            </div>

            <div class="summary-card">
                <span>Total Dinner</span>
                <strong>{{ $totalDinner }}</strong>
            </div>

            <div class="summary-card">
                <span>Group Dinner</span>
                <strong>{{ $totalGroupDinner }}</strong>
            </div>

            <div class="summary-card">
                <span>FIT Dinner</span>
                <strong>{{ $totalFitDinner }}</strong>
            </div>
        </div>

    </div>
</div>

@endsection