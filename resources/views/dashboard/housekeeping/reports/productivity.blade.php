@extends('dashboard.housekeeping.layout')

@section('content')

<div class="page-wrapper">

    <div class="page-header">
        <div>
            <h1>HK Productivity Report</h1>
        </div>
    </div>

    <div class="filter-card">
        <form method="GET" action="{{ route('housekeeping-supervisor.reports.productivity') }}">
            <label>Select Date</label>
            <div class="date-row">
                <input type="date" name="date" value="{{ $date }}">
                <button type="submit">
                    <i class="fas fa-search"></i> View Report
                </button>
            </div>
        </form>
    </div>

    <!-- Desktop Table View -->
    <div class="table-card desktop-view">
        <table class="report-table">
            <thead>
                <tr>
                    <th>Staff</th>
                    <th>Clock In</th>
                    <th>Clock Out</th>
                    <th>Worked</th>
                    <th>Departures</th>
                    <th>Stays</th>
                    <th>Expected</th>
                    <th>Productivity</th>
                </tr>
            </thead>

            <tbody>
                @forelse($reports as $report)
                    <tr>
                        <td>
                            <div class="staff-cell">
                                <div class="staff-avatar">
                                    {{ strtoupper(substr($report['staff_name'], 0, 2)) }}
                                </div>
                                <strong>{{ $report['staff_name'] }}</strong>
                            </div>
                        </td>

                        <td>
                            {{ $report['clock_in'] ? \Carbon\Carbon::parse($report['clock_in'])->format('H:i') : '-' }}
                        </td>

                        <td>
                            {{ $report['clock_out'] ? \Carbon\Carbon::parse($report['clock_out'])->format('H:i') : '-' }}
                        </td>

                        <td>
                            @php
                                $hours = floor($report['worked_minutes'] / 60);
                                $mins = $report['worked_minutes'] % 60;
                            @endphp
                            {{ $report['worked_minutes'] > 0 ? $hours . 'h ' . $mins . 'm' : '-' }}
                        </td>

                        <td>
                            <span class="count-badge departure">{{ $report['departures'] }}</span>
                        </td>

                        <td>
                            <span class="count-badge stay">{{ $report['stays'] }}</span>
                        </td>

                        <td>{{ $report['expected_minutes'] }} min</td>

                        <td>
                            @if($report['productivity'] >= 80)
                                <span class="badge badge-green">
                                    {{ $report['productivity'] }}%
                                </span>
                            @elseif($report['productivity'] >= 50)
                                <span class="badge badge-yellow">
                                    {{ $report['productivity'] }}%
                                </span>
                            @else
                                <span class="badge badge-red">
                                    {{ $report['productivity'] }}%
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="empty">
                            <i class="fas fa-user-slash"></i>
                            No housekeeping staff found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Mobile Card View -->
    <div class="mobile-view">
        @forelse($reports as $report)
            <div class="report-card">
                <div class="report-card-header">
                    <div class="staff-avatar">
                        {{ strtoupper(substr($report['staff_name'], 0, 2)) }}
                    </div>
                    <div>
                        <h3>{{ $report['staff_name'] }}</h3>
                        @if($report['productivity'] >= 80)
                            <span class="badge badge-green">{{ $report['productivity'] }}%</span>
                        @elseif($report['productivity'] >= 50)
                            <span class="badge badge-yellow">{{ $report['productivity'] }}%</span>
                        @else
                            <span class="badge badge-red">{{ $report['productivity'] }}%</span>
                        @endif
                    </div>
                </div>

                <div class="report-card-body">
                    <div class="stat-row">
                        <div class="stat-item">
                            <span class="stat-label">Clock In</span>
                            <span class="stat-value">
                                {{ $report['clock_in'] ? \Carbon\Carbon::parse($report['clock_in'])->format('H:i') : '-' }}
                            </span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Clock Out</span>
                            <span class="stat-value">
                                {{ $report['clock_out'] ? \Carbon\Carbon::parse($report['clock_out'])->format('H:i') : '-' }}
                            </span>
                        </div>
                    </div>

                    <div class="stat-row">
                        <div class="stat-item">
                            <span class="stat-label">Worked</span>
                            <span class="stat-value">
                                @php
                                    $hours = floor($report['worked_minutes'] / 60);
                                    $mins = $report['worked_minutes'] % 60;
                                @endphp
                                {{ $report['worked_minutes'] > 0 ? $hours . 'h ' . $mins . 'm' : '-' }}
                            </span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Expected</span>
                            <span class="stat-value">{{ $report['expected_minutes'] }} min</span>
                        </div>
                    </div>

                    <div class="stat-row">
                        <div class="stat-item">
                            <span class="stat-label">Departures</span>
                            <span class="stat-value count-badge departure">{{ $report['departures'] }}</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Stays</span>
                            <span class="stat-value count-badge stay">{{ $report['stays'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-card">
                <i class="fas fa-user-slash"></i>
                <h3>No housekeeping staff found</h3>
                <p>No records for the selected date.</p>
            </div>
        @endforelse
    </div>

</div>

<style>
/* ============ RED THEME VARIABLES ============ */
:root {
    --primary: #ef4444;
    --primary-dark: #dc2626;
    --primary-light: #f87171;
    
    --bg-dark: #18181b;
    --bg-card: #27272a;
    
    --text-main: #fafafa;
    --text-muted: #a1a1aa;
    --text-dim: #71717a;
    
    --border: #3f3f46;
    
    --radius-lg: 20px;
    --radius-md: 12px;
}

.page-wrapper {
    padding: 24px;
}

.page-header {
    margin-bottom: 22px;
}

.page-header h1 {
    color: var(--text-main);
    font-size: 30px;
    font-weight: 900;
    margin: 0;
}

.page-header p {
    color: var(--text-muted);
    margin-top: 8px;
}

/* ============ FILTER CARD ============ */
.filter-card {
    background: var(--bg-dark);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 22px;
    margin-bottom: 20px;
}

.filter-card label {
    color: var(--text-muted);
    display: block;
    margin-bottom: 8px;
    font-weight: 700;
}

.date-row {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

.date-row input[type="date"] {
    flex: 1;
    min-width: 200px;
    background: var(--bg-card);
    border: 1px solid var(--border);
    color: var(--text-main);
    padding: 12px 14px;
    border-radius: var(--radius-md);
    font-size: 14px;
}

.date-row input[type="date"]:focus {
    outline: none;
    border-color: var(--primary);
}

.date-row button {
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: white;
    border: none;
    border-radius: var(--radius-md);
    padding: 12px 20px;
    font-weight: 900;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s;
}

.date-row button:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(239, 68, 68, 0.4);
}

/* ============ TABLE CARD ============ */
.table-card {
    background: var(--bg-dark);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    overflow: hidden;
}

.report-table {
    width: 100%;
    border-collapse: collapse;
}

.report-table th {
    background: var(--bg-card);
    color: var(--text-main);
    text-align: left;
    padding: 16px;
    font-size: 13px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--text-muted);
}

.report-table td {
    padding: 16px;
    color: var(--text-main);
    border-top: 1px solid var(--border);
}

.report-table tr:hover {
    background: rgba(239, 68, 68, 0.05);
}

/* ============ STAFF CELL ============ */
.staff-cell {
    display: flex;
    align-items: center;
    gap: 12px;
}

.staff-avatar {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 14px;
    color: white;
}

/* ============ COUNT BADGES ============ */
.count-badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 700;
}

.count-badge.departure {
    background: rgba(239, 68, 68, 0.15);
    color: var(--primary-light);
}

.count-badge.stay {
    background: rgba(251, 191, 36, 0.15);
    color: #fbbf24;
}

/* ============ PRODUCTIVITY BADGES ============ */
.badge {
    padding: 7px 13px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 900;
}

.badge-green {
    background: rgba(34, 197, 94, 0.15);
    color: #22c55e;
}

.badge-yellow {
    background: rgba(245, 158, 11, 0.15);
    color: #f59e0b;
}

.badge-red {
    background: rgba(239, 68, 68, 0.15);
    color: var(--primary-light);
}

/* ============ EMPTY STATES ============ */
.empty {
    text-align: center;
    color: var(--text-muted);
    padding: 40px !important;
}

/* ============ MOBILE CARD VIEW ============ */
.mobile-view {
    display: none;
}

.report-card {
    background: var(--bg-dark);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    margin-bottom: 16px;
    overflow: hidden;
}

.report-card-header {
    background: var(--bg-card);
    padding: 16px;
    display: flex;
    align-items: center;
    gap: 14px;
    border-bottom: 1px solid var(--border);
}

.report-card-header h3 {
    margin: 0;
    font-size: 16px;
    font-weight: 700;
}

.report-card-body {
    padding: 16px;
}

.stat-row {
    display: flex;
    gap: 12px;
    margin-bottom: 12px;
}

.stat-row:last-child {
    margin-bottom: 0;
}

.stat-item {
    flex: 1;
    background: var(--bg-card);
    border-radius: 10px;
    padding: 12px;
    text-align: center;
}

.stat-label {
    display: block;
    font-size: 11px;
    color: var(--text-dim);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 4px;
}

.stat-value {
    display: block;
    font-size: 15px;
    font-weight: 700;
    color: var(--text-main);
}

.empty-card {
    background: var(--bg-dark);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 48px;
    text-align: center;
}

.empty-card i {
    font-size: 40px;
    color: var(--text-dim);
    margin-bottom: 16px;
}

.empty-card h3 {
    font-size: 18px;
    margin-bottom: 8px;
}

.empty-card p {
    color: var(--text-muted);
}

/* ============ RESPONSIVE ============ */
@media (max-width: 900px) {
    .desktop-view {
        display: none;
    }
    
    .mobile-view {
        display: block;
    }
    
    .date-row {
        flex-direction: column;
    }
    
    .date-row input[type="date"] {
        width: 100%;
    }
    
    .date-row button {
        width: 100%;
        justify-content: center;
    }
}
</style>

@endsection