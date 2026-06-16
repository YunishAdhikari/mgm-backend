@extends('dashboard.reception.layout')

@section('content')

<style>
:root {
    --bg-dark: #09090b;
    --card-dark: #18181b;
    --border-dark: #3f3f46;
    --text-light: #fafafa;
    --text-muted: #a1a1aa;
    --red-brand: #dc2626;
    --red-gradient: linear-gradient(135deg, #991b1b, #dc2626);
}

/* Base UI Component Containers */
.report-view-container {
    width: 100%;
    box-sizing: border-box;
}

.report-view-container .report-wrapper {
    padding: 30px;
    background: var(--bg-dark);
    min-height: 100vh;
    color: var(--text-light);
}

.report-view-container .report-actions {
    display: flex;
    gap: 12px;
    margin-bottom: 25px;
}

.report-view-container .report-btn {
    border: none;
    padding: 12px 20px;
    border-radius: 10px;
    font-weight: 800;
    cursor: pointer;
    text-decoration: none;
    font-size: 14px;
    transition: 0.2s;
    display: inline-flex;
    align-items: center;
}

.report-view-container .btn-print {
    background: var(--red-gradient);
    color: white;
    box-shadow: 0 4px 15px rgba(220, 38, 38, 0.2);
}

.report-view-container .btn-print:hover { opacity: 0.95; }

.report-view-container .btn-back {
    background: rgba(255, 255, 255, 0.05);
    color: white;
    border: 1px solid var(--border-dark);
}

.report-view-container .btn-back:hover { background: rgba(255, 255, 255, 0.1); }

/* Premium Screen Paper Element Layout */
.report-view-container .report-paper {
    background: var(--card-dark);
    padding: 40px;
    border-radius: 16px;
    border: 1px solid var(--border-dark);
}

.report-view-container .report-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    border-bottom: 3px solid var(--text-light);
    padding-bottom: 20px;
    margin-bottom: 30px;
}

.report-view-container .report-header h2 {
    margin: 0;
    font-size: 32px;
    font-weight: 800;
    letter-spacing: -0.5px;
    text-transform: uppercase;
}

.report-view-container .report-header p {
    margin: 5px 0 0;
    color: var(--text-muted);
    font-size: 16px;
    font-weight: 500;
}

.report-view-container .report-meta-box {
    text-align: right;
    font-size: 13px;
    color: var(--text-muted);
    line-height: 1.6;
}

.report-view-container .report-meta-box strong { color: var(--text-light); }

/* Screen Table Settings */
.report-view-container .report-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
    margin-top: 20px;
}

.report-view-container .report-table th {
    background: #27272a;
    color: var(--text-light) !important;
    padding: 14px 12px;
    font-weight: 700;
    text-transform: uppercase;
    font-size: 12px;
    letter-spacing: 0.5px;
    border: 1px solid var(--border-dark);
    text-align: left;
}

.report-view-container .report-table td {
    padding: 14px 12px;
    border: 1px solid var(--border-dark);
    color: #e4e4e7;
    vertical-align: middle;
}

.report-view-container .report-table tbody tr:nth-child(even) { background: rgba(255, 255, 255, 0.02); }
.report-view-container .report-table tbody tr.summary-row { background: #27272a !important; font-weight: 700; }
.report-view-container .report-table tbody tr.summary-row td {
    border-top: 2px solid var(--text-light);
    border-bottom: 2px solid var(--text-light);
    color: var(--text-light);
}

.report-view-container .group-text { font-size: 13px; line-height: 1.4; color: #d4d4d8; }
.report-view-container .no-group-msg { color: #71717a; font-style: italic; }
.report-view-container .footer-note {
    margin-top: 35px;
    font-size: 12px;
    color: var(--text-muted);
    border-top: 1px dashed var(--border-dark);
    padding-top: 15px;
    line-height: 1.6;
}

@media (max-width: 768px) {
    .report-view-container .report-wrapper { padding: 15px; }
    .report-view-container .report-header { flex-direction: column; align-items: flex-start; gap: 15px; }
    .report-view-container .report-meta-box { text-align: left; }
}

/* ==========================================================================
   SOLID HIGH-CONTRAST MONOCHROME PRINT CONFIGURATION (Fixed for image_eac101.png Errors)
   ========================================================================== */
@media print {
    body * {
        visibility: hidden !important;
    }

    .report-view-container,
    .report-view-container .report-wrapper,
    .report-view-container .report-wrapper * {
        visibility: visible !important;
    }

    .report-view-container {
        position: absolute;
        left: 0;
        top: 0;
        width: 100% !important;
    }

    .report-view-container .report-wrapper {
        background: #ffffff !important;
        color: #000000 !important;
        padding: 0 !important;
        min-height: auto !important;
    }

    .report-view-container .report-actions {
        display: none !important;
    }

    .report-view-container .report-paper {
        background: #ffffff !important;
        color: #000000 !important;
        box-shadow: none !important;
        border: none !important;
        padding: 0 !important;
    }

    .report-view-container .report-header {
        border-bottom: 3px solid #000000 !important;
        padding-bottom: 12px !important;
        margin-bottom: 25px !important;
    }

    .report-view-container .report-header h2 {
        color: #000000 !important;
        font-size: 26px !important;
        font-weight: 800 !important;
    }

    .report-view-container .report-header p,
    .report-view-container .report-meta-box {
        color: #000000 !important;
        font-size: 12px !important;
    }

    .report-view-container .report-meta-box strong {
        color: #000000 !important;
    }

    /* Fixed Unified Print Layout Table Elements */
    .report-view-container .report-table {
        margin-top: 15px !important;
        font-size: 12px !important;
        border: 1px solid #1e293b !important;
    }

    /* Unified high-contrast typography matrix across headers */
    .report-view-container .report-table th {
        background: #f1f5f9 !important;
        color: #000000 !important;
        border: 1px solid #1e293b !important;
        padding: 10px 8px !important;
        font-size: 11px !important;
        font-weight: 800 !important;
    }

    /* Forcing all table text to uniform bold contrast values */
    .report-view-container .report-table td {
        color: #000000 !important;
        border: 1px solid #1e293b !important;
        padding: 10px 8px !important;
        font-weight: 600 !important;
    }

    .report-view-container .report-table td strong {
        font-weight: 800 !important;
    }

    .report-view-container .report-table tbody tr:nth-child(even) {
        background: #f8fafc !important;
    }

    /* Double-Line Accounting Summary Block styling */
    .report-view-container .report-table tbody tr.summary-row {
        background: #f1f5f9 !important;
    }

    .report-view-container .report-table tbody tr.summary-row td {
        border-top: 2px solid #000000 !important;
        border-bottom: 4px double #000000 !important;
        color: #000000 !important;
        font-weight: 800 !important;
    }

    .report-view-container .group-text {
        color: #000000 !important;
        font-weight: 600 !important;
    }

    .report-view-container .no-group-msg {
        color: #475569 !important;
        font-style: normal !important;
        font-weight: 500 !important;
    }

    .report-view-container .footer-note {
        color: #000000 !important;
        border-top: 1px solid #000000 !important;
        margin-top: 30px !important;
        font-size: 11px !important;
    }
}
</style>

<div class="report-view-container">
    <div class="report-wrapper">

        <div class="report-actions">
            <a href="{{ route('reception.meal-forecasts.index') }}" class="report-btn btn-back">
                ← Return to Workspace
            </a>

            <button onclick="window.print()" class="report-btn btn-print">
                🖨️ Print Statement / Export PDF
            </button>
        </div>

        <div class="report-paper">

            <!-- Executive Header Structure -->
            <div class="report-header">
                <div>
                    <h2>Dinner & Breakfast Forecast</h2>
                    <p>MGM Muthu Glasgow River Hotel</p>
                </div>

                <div class="report-meta-box">
                    <strong>Report Period:</strong> {{ \Carbon\Carbon::parse($fromDate)->format('d/m/Y') }} – {{ \Carbon\Carbon::parse($toDate)->format('d/m/Y') }}<br>
                    <strong>Execution Timestamp:</strong> {{ now()->format('d/m/Y H:i') }}<br>
                    <strong>System Identity:</strong> Reception Ledger
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
                        <th style="width: 11%;">Date</th>
                        <th style="width: 10%;">Day</th>
                        <th style="width: 10%;">B/F Total</th>
                        <th style="width: 21%;">B/F Groups Allocation</th>
                        <th style="width: 9%;">B/F FIT</th>
                        <th style="width: 11%;">Dinner Total</th>
                        <th style="width: 21%;">Dinner Groups Allocation</th>
                        <th style="width: 8%;">Dinner FIT</th>
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
                            <td><strong>{{ $forecast->forecast_date->format('d/m/Y') }}</strong></td>
                            <td>{{ $forecast->forecast_date->format('l') }}</td>
                            <td>{{ $forecast->total_breakfast }}</td>
                            <td class="group-text">
                                @if($bfGroups)
                                    {{ $bfGroups }}
                                @else
                                    <span class="no-group-msg">No Groups Allocated</span>
                                @endif
                            </td>
                            <td>{{ $bfFit }}</td>
                            <td>{{ $forecast->total_dinner }}</td>
                            <td class="group-text">
                                @if($dnGroups)
                                    {{ $dnGroups }}
                                @else
                                    <span class="no-group-msg">No Groups Allocated</span>
                                @endif
                            </td>
                            <td>{{ $dnFit }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align:center; padding: 40px 0; color: var(--text-muted);">
                                No processing forecasts discovered within the specified parameters.
                            </td>
                        </tr>
                    @endforelse

                    <!-- Consolidated Summary Balance Footer Row -->
                    <tr class="summary-row">
                        <td colspan="2" style="text-align: right; text-transform: uppercase; letter-spacing: 0.5px;">Summary Totals:</td>
                        <td>{{ $totalBreakfast }}</td>
                        <td>{{ $totalGroupBreakfast }} <span style="font-size:11px; font-weight:normal; opacity:0.85;">(Group)</span></td>
                        <td>{{ $totalFitBreakfast }} <span style="font-size:11px; font-weight:normal; opacity:0.85;">(FIT)</span></td>
                        <td>{{ $totalDinner }}</td>
                        <td>{{ $totalGroupDinner }} <span style="font-size:11px; font-weight:normal; opacity:0.85;">(Group)</span></td>
                        <td>{{ $totalFitDinner }} <span style="font-size:11px; font-weight:normal; opacity:0.85;">(FIT)</span></td>
                    </tr>
                </tbody>
            </table>

            <div class="footer-note">
                <strong>Statement Note:</strong> This ledger index is automatically calculated by the MGM Operations Processing Engine. Group allocations are extracted dynamically via group stay package profiles, contract windows, and arrival matrices. Confidential Document — Internal Hotel Operations Verification Only.
            </div>

        </div>
    </div>
</div>

@endsection