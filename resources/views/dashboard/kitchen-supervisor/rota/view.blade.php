{{-- @extends('dashboard.supervisor.layout') --}}
@extends('dashboard.kitchen-supervisor.layout')

{{-- @extends('layouts.supervisor') --}}

@section('content')

<!-- Breadcrumb -->
<div class="breadcrumb">
    <span><i class="fas fa-home"></i> Home</span>
    <span class="separator">/</span>
    <span class="current">My Department Rota</span>
</div>

<!-- Rota Board Card -->
<div class="rota-board-card">

    <!-- Board Header -->
    <div class="board-header">
        <div class="header-info">
            <h1><i class="fas fa-calendar-week"></i> Weekly Rota View</h1>
            <p>
                {{ $weekStart->format('d M Y') }}
                -
                {{ $weekStart->copy()->addDays(6)->format('d M Y') }}
            </p>
        </div>

        <div class="actions">
            <!-- Week Navigation -->
            <div class="week-nav">
                <a href="{{ route('supervisor.rota.view', ['week_start' => $weekStart->copy()->subWeek()->format('Y-m-d')]) }}" 
                   class="nav-btn" title="Previous Week">
                    <i class="fas fa-chevron-left"></i>
                </a>
                
                <a href="{{ route('supervisor.rota.view', ['week_start' => now()->startOfWeek()->format('Y-m-d')]) }}" 
                   class="nav-btn today">
                    Today
                </a>
                
                <a href="{{ route('supervisor.rota.view', ['week_start' => $weekStart->copy()->addWeek()->format('Y-m-d')]) }}" 
                   class="nav-btn" title="Next Week">
                    <i class="fas fa-chevron-right"></i>
                </a>
            </div>

            <form method="GET" class="week-form">
                <input type="date" name="week_start" value="{{ $weekStart->format('Y-m-d') }}">
                <button type="submit">
                    <i class="fas fa-search"></i> View
                </button>
            </form>
        </div>
    </div>

    <!-- Department & Stats -->
    <div class="dept-highlight">
        <div class="dept-info">
            <i class="fas fa-building"></i>
            <span>{{ $supervisor->department->name ?? 'Department' }}</span>
            <span class="emp-count">{{ $employees->count() }} Staff</span>
        </div>
    </div>

    <!-- Legend -->
    <div class="legend-bar">
        <span class="legend-item morning"><span class="dot"></span> Morning</span>
        <span class="legend-item evening"><span class="dot"></span> Evening</span>
        <span class="legend-item night"><span class="dot"></span> Night</span>
        <span class="legend-item split"><span class="dot"></span> Split</span>
        <span class="legend-item off"><span class="dot"></span> Day Off</span>
        <span class="legend-item holiday"><span class="dot"></span> Holiday</span>
        <span class="legend-item sick"><span class="dot"></span> Sick</span>
    </div>

    <!-- Rota Table -->
    <div class="rota-table-wrapper">
        <table class="rota-table">
            <thead>
                <tr>
                    <th><i class="fas fa-user"></i> Employee</th>

                    @foreach($weekDates as $date)
                        <th class="{{ $date->isToday() ? 'today-col' : '' }}">
                            {{ $date->format('D') }}
                            <br>
                            <span>{{ $date->format('d M') }}</span>
                            @if($date->isToday())
                                <span class="today-badge">Today</span>
                            @endif
                        </th>
                    @endforeach
                </tr>
            </thead>

            <tbody>
                @forelse($employees as $employee)
                    <tr>
                        <td class="employee-name">
                            <strong>{{ $employee->name }}</strong>
                            @if($employee->role)
                                <small>{{ $employee->role->name }}</small>
                            @endif
                        </td>

                        @foreach($weekDates as $date)
                            @php
                                $dayShifts = $shifts->filter(function ($shift) use ($employee, $date) {
                                    return $shift->user_id == $employee->id
                                        && \Carbon\Carbon::parse($shift->shift_date)->format('Y-m-d') == $date->format('Y-m-d');
                                });

                                $isOff = $dayShifts->contains(function ($shift) {
                                    return in_array($shift->shift_type, ['day_off', 'holiday', 'sick']);
                                });
                                
                                $isToday = $date->isToday();
                            @endphp

                            <td class="{{ $isOff ? 'off-cell' : '' }} {{ $isToday ? 'today-cell' : '' }}">
                                @forelse($dayShifts as $shift)
                                    @if($shift->shift_type === 'day_off')
                                        <span class="shift-badge off">OFF</span>
                                    @elseif($shift->shift_type === 'holiday')
                                        <span class="shift-badge holiday">HOLIDAY</span>
                                    @elseif($shift->shift_type === 'sick')
                                        <span class="shift-badge sick">SICK</span>
                                    @elseif($shift->shift_type === 'split')
                                        <span class="shift-badge split">
                                            {{ \Carbon\Carbon::parse($shift->split_start_time_1)->format('H:i') }}-
                                            {{ \Carbon\Carbon::parse($shift->split_end_time_1)->format('H:i') }}
                                            <br>
                                            {{ \Carbon\Carbon::parse($shift->split_start_time_2)->format('H:i') }}-
                                            {{ \Carbon\Carbon::parse($shift->split_end_time_2)->format('H:i') }}
                                        </span>
                                    @elseif($shift->start_time && $shift->end_time)
                                        <span class="shift-badge {{ $shift->shift_type }}">
                                            {{ \Carbon\Carbon::parse($shift->start_time)->format('H:i') }}
                                            -
                                            {{ \Carbon\Carbon::parse($shift->end_time)->format('H:i') }}
                                        </span>
                                    @else
                                        <span class="shift-text">
                                            {{ ucwords(str_replace('_', ' ', $shift->shift_type)) }}
                                        </span>
                                    @endif

                                    @if(!$loop->last)
                                        <br>
                                    @endif
                                @empty
                                    <span class="no-shift">-</span>
                                @endforelse
                            </td>
                        @endforeach
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="empty-cell">
                            <i class="fas fa-users"></i>
                            <p>No staff found in your department.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

<style>
/* Rota Board Card */
.rota-board-card {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
}

/* Board Header */
.board-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
    margin-bottom: 20px;
    padding-bottom: 20px;
    border-bottom: 2px solid #f3f4f6;
    flex-wrap: wrap;
}

.header-info h1 {
    margin: 0 0 6px;
    font-size: 24px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 10px;
    color: #111827;
}

.header-info h1 i {
    color: #dc2626;
}

.header-info p {
    margin: 0;
    color: #6b7280;
    font-size: 14px;
    font-weight: 600;
}

/* Actions */
.actions {
    display: flex;
    gap: 12px;
    align-items: center;
    flex-wrap: wrap;
}

.week-nav {
    display: flex;
    gap: 6px;
}

.nav-btn {
    padding: 10px 14px;
    background: #f3f4f6;
    border-radius: 8px;
    color: #374151;
    text-decoration: none;
    font-size: 13px;
    font-weight: 600;
    transition: all 0.2s;
}

.nav-btn:hover {
    background: #e5e7eb;
}

.nav-btn.today {
    background: #dc2626;
    color: white;
}

.week-form {
    display: flex;
    gap: 8px;
}

.week-form input {
    padding: 10px 14px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-size: 13px;
    outline: none;
}

.week-form input:focus {
    border-color: #dc2626;
}

.week-form button {
    padding: 10px 16px;
    background: #dc2626;
    border: none;
    border-radius: 8px;
    color: white;
    cursor: pointer;
    font-size: 13px;
    font-weight: 600;
    transition: all 0.2s;
}

.week-form button:hover {
    background: #b91c1c;
}

/* Department Highlight */
.dept-highlight {
    background: linear-gradient(135deg, #00aeea, #0096c7);
    border-radius: 12px;
    padding: 16px;
    margin-bottom: 16px;
}

.dept-info {
    display: flex;
    align-items: center;
    gap: 12px;
    color: black;
    font-size: 18px;
    font-weight: 800;
}

.dept-info i {
    font-size: 20px;
}

.emp-count {
    font-size: 13px;
    background: rgba(0, 0, 0, 0.2);
    padding: 4px 12px;
    border-radius: 20px;
}

/* Legend */
.legend-bar {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 12px 16px;
    background: #f9fafb;
    border-radius: 10px;
    margin-bottom: 20px;
    flex-wrap: wrap;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    color: #6b7280;
}

.legend-item .dot {
    width: 10px;
    height: 10px;
    border-radius: 3px;
}

.legend-item.morning .dot { background: #3b82f6; }
.legend-item.evening .dot { background: #f59e0b; }
.legend-item.night .dot { background: #8b5cf6; }
.legend-item.split .dot { background: #ec4899; }
.legend-item.off .dot { background: #6b7280; }
.legend-item.holiday .dot { background: #22c55e; }
.legend-item.sick .dot { background: #dc2626; }

/* Table */
.rota-table-wrapper {
    overflow-x: auto;
}

.rota-table {
    width: 100%;
    min-width: 800px;
    border-collapse: collapse;
    text-align: center;
    border: 2px solid #111827;
}

.rota-table th,
.rota-table td {
    border: 1px solid #111827;
    padding: 10px 8px;
    font-size: 13px;
}

.rota-table th {
    background: #2c3e50;
    color: white;
    font-weight: 700;
}

.rota-table th.today-col {
    background: #dc2626;
}

.today-badge {
    display: block;
    background: white;
    color: #dc2626;
    font-size: 8px;
    padding: 2px 6px;
    border-radius: 10px;
    margin-top: 4px;
}

.employee-name {
    font-weight: 700;
    text-align: left !important;
    background: #f8fafc;
    min-width: 140px;
    color: #000000; /* CHANGE: Employee name in black */
}

.employee-name strong {
    display: block;
    font-size: 13px;
    color: #000000; /* CHANGE: Employee name in black */
}

.employee-name small {
    display: block;
    font-size: 11px;
    color: #6b7280;
    margin-top: 2px;
}

/* Shift Display */
.off-cell {
    background: #fef9c3;
}

.today-cell {
    background: rgba(252, 211, 77, 0.2);
}

.shift-badge {
    display: inline-block;
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 10px;
    font-weight: 800;
    line-height: 1.4;
}

.shift-badge.morning {
    background: #dbeafe;
    color: #1d4ed8;
}

.shift-badge.evening {
    background: #fef3c7;
    color: #92400e;
}

.shift-badge.night {
    background: #ede9fe;
    color: #6d28d9;
}

.shift-badge.split {
    background: #fce7f3;
    color: #be185d;
    white-space: nowrap;
}

.shift-badge.off {
    background: #6b7280;
    color: white;
    font-style: italic;
}

.shift-badge.holiday {
    background: #22c55e;
    color: white;
}

.shift-badge.sick {
    background: #dc2626;
    color: white;
}

.shift-text {
    font-size: 11px;
    font-weight: 600;
    color: #374151;
}

.no-shift {
    color: #d1d5db;
    font-size: 14px;
}

/* Empty State */
.empty-cell {
    text-align: center;
    color: #6b7280;
    font-style: italic;
    padding: 30px !important;
}

.empty-cell i {
    font-size: 30px;
    display: block;
    margin-bottom: 10px;
    opacity: 0.5;
}

/* Responsive */
@media (max-width: 768px) {
    .board-header {
        flex-direction: column;
        align-items: stretch;
    }

    .header-info {
        text-align: center;
    }

    .header-info h1 {
        justify-content: center;
    }

    .actions {
        width: 100%;
        justify-content: center;
    }

    .week-form {
        width: 100%;
    }
    
    .week-form input {
        flex: 1;
    }
}

/* Breadcrumb */
.breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 24px;
    font-size: 14px;
    color: var(--text-muted);
}

.breadcrumb .separator {
    color: var(--text-dim);
}

.breadcrumb .current {
    color: var(--text);
}
</style>

@endsection