@extends('dashboard.housekeeping.layout')

@section('content')

<div class="page-wrapper">

    <div class="page-header">
        <div>
            <h1>HK Weekly Rota View</h1>
        </div>

        <a href="{{ route('housekeeping-supervisor.hk-rota.index') }}" class="create-btn">
            <i class="fas fa-plus"></i> Create Draft Rota
        </a>
    </div>

    <div class="filter-card">
        <form method="GET" action="{{ route('housekeeping-supervisor.hk-rota.view') }}">
            <label>Week Starting</label>
            <div class="date-row">
                <input type="date" name="week_start" value="{{ $weekStart->toDateString() }}">
                <button type="submit">View Week</button>
            </div>
        </form>
    </div>

    <div class="summary-row">
        <div class="summary-card">
            <span>{{ $stats['staff_count'] }}</span>
            <p>HK Staff</p>
        </div>

        <div class="summary-card warning">
            <span>{{ $stats['draft'] }}</span>
            <p>Draft Shifts</p>
        </div>

        <div class="summary-card success">
            <span>{{ $stats['published'] }}</span>
            <p>Published Shifts</p>
        </div>
    </div>

    <div class="rota-card">

        <div class="rota-header">
            <div class="dept-title">
                <i class="fas fa-broom"></i>
                <strong>Housekeeping</strong>
            </div>
            <span class="emp-pill">{{ $hkStaff->count() }} employees</span>
        </div>

        <!-- Desktop: Table View -->
        <div class="rota-desktop">
            <table class="rota-grid">
                <thead>
                    <tr>
                        <th class="employee-col">Employee</th>
                        @foreach($weekDays as $day)
                            @php $isToday = $day->isToday(); @endphp
                            <th class="{{ $isToday ? 'today-col' : '' }}">
                                <div class="day-name">{{ $day->format('D') }}</div>
                                <div class="day-date">{{ $day->format('d M') }}</div>
                                @if($isToday)<div class="today-badge">Today</div>@endif
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($hkStaff as $staff)
                        <tr>
                            <td class="employee-cell">
                                <strong>{{ $staff->name }}</strong>
                            </td>
                            @foreach($weekDays as $day)
                                @php
                                    $key = $staff->id . '_' . $day->toDateString();
                                    $shift = $shifts[$key][0] ?? null;
                                    $isToday = $day->isToday();
                                @endphp
                                <td class="shift-cell {{ $isToday ? 'today-cell' : '' }}">
                                    @if(!$shift)
                                        <span class="empty-shift">-</span>
                                    @else
                                        @if(in_array($shift->shift_type, ['day_off', 'holiday', 'sick']))
                                            <span class="shift-pill off-pill">
                                                {{ strtoupper(substr($shift->shift_type, 0, 2)) }}
                                            </span>
                                        @else
                                            <span class="shift-pill time-pill">
                                                {{ \Carbon\Carbon::parse($shift->start_time)->format('H:i') }}
                                            </span>
                                        @endif
                                        <span class="status-pill {{ $shift->status === 'published' ? 'published' : 'draft' }}">
                                            {{ $shift->status[0] }}
                                        </span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Mobile: Card View -->
        <div class="rota-mobile">
            @foreach($hkStaff as $staff)
                <div class="staff-row">
                    <div class="staff-name">
                        <i class="fas fa-user"></i> {{ $staff->name }}
                    </div>
                    <div class="shifts-grid">
                        @foreach($weekDays as $day)
                            @php
                                $key = $staff->id . '_' . $day->toDateString();
                                $shift = $shifts[$key][0] ?? null;
                                $isToday = $day->isToday();
                            @endphp
                            <div class="day-cell {{ $isToday ? 'today-cell' : '' }}">
                                <div class="day-label">
                                    {{ $day->format('D') }}
                                    @if($isToday)<span class=" today-dot"></span>@endif
                                </div>
                                @if(!$shift)
                                    <span class="empty-shift">-</span>
                                @else
                                    @if(in_array($shift->shift_type, ['day_off', 'holiday', 'sick']))
                                        <span class="shift-pill off-pill">OFF</span>
                                    @else
                                        <span class="shift-pill time-pill">
                                            {{ \Carbon\Carbon::parse($shift->start_time)->format('H:i') }}
                                        </span>
                                    @endif
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

    </div>

</div>

<style>
/* ============ RED THEME VARIABLES ============ */
:root {
    --red-primary: #ef4444;
    --red-dark: #dc2626;
    --red-light: #f87171;
    --red-subtle: rgba(239, 68, 68, 0.15);
    --red-glow: rgba(239, 68, 68, 0.3);
    
    --bg-dark: #18181b;
    --bg-card: #27272a;
    --bg-input: #27272a;
    
    --border-default: #3f3f46;
    --border-hover: #52525b;
    
    --text-primary: #fafafa;
    --text-secondary: #a1a1aa;
    --text-muted: #71717a;
}

/* ============ PAGE ============ */
.page-wrapper {
    padding: 24px;
    color: var(--text-primary);
}

/* ============ PAGE HEADER ============ */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 22px;
    flex-wrap: wrap;
    gap: 16px;
}

.page-header h1 {
    margin: 0;
    color: var(--text-primary);
    font-size: 30px;
    font-weight: 900;
}

.page-header p {
    color: var(--text-secondary);
    margin-top: 8px;
}

.create-btn {
    background: linear-gradient(135deg, var(--red-primary), var(--red-dark));
    color: white;
    text-decoration: none;
    padding: 13px 18px;
    border-radius: 14px;
    font-weight: 900;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s;
}

.create-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(239, 68, 68, 0.4);
}

/* ============ FILTER CARD ============ */
.filter-card {
    background: var(--bg-dark);
    border: 1px solid var(--border-default);
    border-radius: 20px;
    padding: 22px;
    margin-bottom: 20px;
}

.filter-card label {
    display: block;
    color: var(--text-secondary);
    font-weight: 700;
    margin-bottom: 8px;
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
    border: 1px solid var(--border-default);
    color: var(--text-primary);
    padding: 12px 14px;
    border-radius: 12px;
    font-size: 14px;
}

.date-row input[type="date"]:focus {
    outline: none;
    border-color: var(--red-primary);
}

.date-row button {
    background: linear-gradient(135deg, var(--red-primary), var(--red-dark));
    color: white;
    border: none;
    padding: 12px 22px;
    border-radius: 12px;
    font-weight: 900;
    cursor: pointer;
    transition: all 0.2s;
}

.date-row button:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
}

/* ============ SUMMARY ROW ============ */
.summary-row {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 16px;
    margin-bottom: 22px;
}

.summary-card {
    background: var(--bg-dark);
    border: 1px solid var(--border-default);
    border-radius: 18px;
    padding: 20px;
    text-align: center;
}

.summary-card span {
    display: block;
    color: var(--text-primary);
    font-size: 32px;
    font-weight: 900;
}

.summary-card p {
    color: var(--text-secondary);
    margin: 4px 0 0;
    font-size: 14px;
}

.summary-card.warning span { color: var(--red-light); }
.summary-card.success span { color: #22c55e; }

/* ============ ROTA CARD ============ */
.rota-card {
    background: var(--bg-dark);
    border: 1px solid var(--border-default);
    border-radius: 22px;
    overflow: hidden;
}

.rota-header {
    background: linear-gradient(135deg, var(--red-primary), var(--red-dark));
    color: white;
    padding: 22px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
}

.dept-title {
    font-size: 24px;
    font-weight: 900;
    display: flex;
    align-items: center;
    gap: 10px;
}

.emp-pill {
    background: rgba(0, 0, 0, 0.2);
    padding: 8px 16px;
    border-radius: 999px;
    font-size: 14px;
    font-weight: 700;
}

/* ============ DESKTOP TABLE ============ */
.rota-desktop {
    overflow-x: auto;
}

.rota-grid {
    width: 100%;
    min-width: 800px;
    border-collapse: collapse;
    background: #f8fafc;
}

.rota-grid th,
.rota-grid td {
    border: 1px solid #e2e8f0;
}

.rota-grid th {
    background: #f1f5f9;
    color: #1e293b;
    padding: 16px 12px;
    text-align: center;
    font-size: 14px;
}

.employee-col {
    width: 180px;
    text-align: left !important;
}

.day-name {
    font-weight: 900;
}

.day-date {
    margin-top: 4px;
    font-size: 12px;
    color: #64748b;
}

.today-badge {
    margin: 8px auto 0;
    background: var(--red-primary);
    color: white;
    border-radius: 999px;
    padding: 4px 12px;
    font-size: 10px;
    font-weight: 900;
}

.today-col,
.today-cell {
    background: #fef3c7 !important;
}

.employee-cell {
    background: #e2e8f0;
    color: #1e293b;
    padding: 16px;
    font-weight: 700;
    text-align: left;
}

.shift-cell {
    text-align: center;
    padding: 12px 8px;
    min-height: 60px;
    vertical-align: middle;
}

.empty-shift {
    color: #cbd5e1;
    font-size: 18px;
    font-weight: 900;
}

.shift-pill {
    display: inline-block;
    border-radius: 8px;
    padding: 4px 8px;
    font-weight: 900;
    font-size: 12px;
}

.time-pill {
    background: #dbeafe;
    color: #1d4ed8;
}

.off-pill {
    background: #6b7280;
    color: white;
}

.status-pill {
    display: block;
    width: fit-content;
    margin: 4px auto 0;
    border-radius: 999px;
    padding: 2px 6px;
    font-size: 9px;
    font-weight: 900;
}

.status-pill.draft {
    background: rgba(245, 158, 11, 0.2);
    color: #b45309;
}

.status-pill.published {
    background: rgba(34, 197, 94, 0.2);
    color: #15803d;
}

/* ============ MOBILE CARD VIEW ============ */
.rota-mobile {
    display: none;
}

.staff-row {
    border-bottom: 1px solid var(--border-default);
    padding: 16px;
}

.staff-row:last-child {
    border-bottom: none;
}

.staff-name {
    font-size: 16px;
    font-weight: 800;
    color: var(--text-primary);
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.staff-name i {
    color: var(--red-primary);
}

.shifts-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 6px;
}

.day-cell {
    background: var(--bg-card);
    border-radius: 10px;
    padding: 8px 4px;
    text-align: center;
    min-height: 50px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.day-cell.today-cell {
    border: 2px solid var(--red-primary);
    background: rgba(239, 68, 68, 0.1);
}

.day-label {
    font-size: 11px;
    font-weight: 900;
    color: var(--text-muted);
    margin-bottom: 4px;
    display: flex;
    align-items: center;
    gap: 4px;
}

.today-dot {
    width: 6px;
    height: 6px;
    background: var(--red-primary);
    border-radius: 50%;
}

/* ============ RESPONSIVE ============ */
@media (max-width: 900px) {
    .page-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .summary-row {
        grid-template-columns: 1fr;
    }
    
    .date-row {
        flex-direction: column;
    }
    
    .date-row input[type="date"] {
        width: 100%;
    }
    
    .date-row button {
        width: 100%;
    }
    
    /* Hide desktop, show mobile */
    .rota-desktop {
        display: none;
    }
    
    .rota-mobile {
        display: block;
    }
    
    .rota-header {
        flex-direction: column;
        text-align: center;
    }
}
</style>

@endsection