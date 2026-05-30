@extends('dashboard.supervisor.layout')

@section('content')

<div class="rota-board-card">
    <div class="board-header">
        <div class="header-content">
            <h1>Weekly Rota View</h1>
            <p>
                {{ $weekStart->format('d M Y') }}
                -
                {{ $weekStart->copy()->addDays(6)->format('d M Y') }}
            </p>
        </div>

        <form method="GET" class="week-form">
            <input type="date" name="week_start" value="{{ $weekStart->format('Y-m-d') }}">
            <button type="submit">
                <i class="fa-solid fa-eye"></i>
                View Week
            </button>
        </form>
    </div>

    <div class="rota-table-wrapper">
        <table class="rota-table">
            <thead>
                <tr>
                    <th colspan="{{ count($weekDates) + 1 }}" class="department-title">
                        <i class="fa-solid fa-users"></i>
                        {{ $supervisor->department->name ?? 'Department' }}
                    </th>
                </tr>

                <tr>
                    <th><i class="fa-solid fa-user"></i> Employee</th>

                    @foreach($weekDates as $date)
                        <th>
                            {{ $date->format('D') }}
                            <span>{{ $date->format('d M') }}</span>
                        </th>
                    @endforeach
                </tr>
            </thead>

            <tbody>
                @foreach($employees as $employee)
                    <tr>
                        <td class="employee-name">
                            <div class="emp-avatar">
                                {{ strtoupper(substr($employee->name, 0, 1)) }}
                            </div>
                            {{ $employee->name }}
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
                                
                                $shiftType = $dayShifts->first()?->shift_type ?? null;
                            @endphp

                            <td class="{{ $isOff ? 'off-cell' : '' }} {{ $shiftType ? 'type-' . $shiftType : '' }}">
                                @forelse($dayShifts as $shift)
                                    @if($shift->shift_type === 'day_off')
                                        <span class="shift-badge off">OFF</span>
                                    @elseif($shift->shift_type === 'holiday')
                                        <span class="shift-badge holiday">HOLIDAY</span>
                                    @elseif($shift->shift_type === 'sick')
                                        <span class="shift-badge sick">SICK</span>
                                    @elseif($shift->start_time && $shift->end_time)
                                        <span class="time-badge">
                                            {{ \Carbon\Carbon::parse($shift->start_time)->format('H:i') }}
                                            -
                                            {{ \Carbon\Carbon::parse($shift->end_time)->format('H:i') }}
                                        </span>
                                    @else
                                        {{ ucwords(str_replace('_', ' ', $shift->shift_type)) }}
                                    @endif

                                    @if(!$loop->last)
                                        <br>
                                    @endif
                                @empty
                                    <span class="empty-cell">-</span>
                                @endforelse
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<style>
    :root {
        --primary: #8b5cf6;
        --primary-hover: #a78bfa;
        --secondary: #ec4899;
        
        --bg-card: #27272a;
        --bg-input: #1c1c1f;
        
        --text-main: #fafafa;
        --text-muted: #a1a1aa;
        --text-dim: #71717a;
        
        --border: #3f3f46;
        --border-light: #52525b;
        
        --success: #10b981;
        --warning: #f59e0b;
        --danger: #ef4444;
        --info: #06b6d4;
        
        --glow: 0 0 20px rgba(139, 92, 246, 0.3);
        
        --radius-lg: 1.5rem;
        --radius-md: 1rem;
    }

    .rota-board-card {
        background: var(--bg-card);
        border-radius: var(--radius-lg);
        padding: 28px;
        border: 1px solid var(--border);
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        animation: fadeIn 0.4s ease-out;
    }

    .board-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        margin-bottom: 28px;
        flex-wrap: wrap;
    }

    .header-content h1 {
        margin: 0 0 6px;
        font-size: 26px;
        font-weight: 800;
        background: linear-gradient(135deg, #fff 0%, #a1a1aa 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .header-content p {
        margin: 0;
        color: var(--text-muted);
        font-weight: 600;
        font-size: 14px;
    }

    .week-form {
        display: flex;
        gap: 12px;
        align-items: center;
    }

    .week-form input {
        padding: 12px 16px;
        background: var(--bg-input);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        color: var(--text-main);
        font-size: 14px;
    }

    .week-form button {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 12px 20px;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
        border: none;
        border-radius: var(--radius-md);
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(139, 92, 246, 0.3);
    }

    .week-form button:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(139, 92, 246, 0.4);
    }

    .rota-table-wrapper {
        overflow-x: auto;
    }

    .rota-table {
        width: 100%;
        min-width: 900px;
        border-collapse: separate;
        border-spacing: 0;
        text-align: center;
    }

    .rota-table th,
    .rota-table td {
        border: 1px solid var(--border);
        padding: 14px 12px;
        font-size: 14px;
    }

    .department-title {
        background: linear-gradient(135deg, var(--primary), var(--secondary)) !important;
        color: white !important;
        font-size: 20px !important;
        font-weight: 800;
        padding: 16px !important;
        border-radius: var(--radius-md) var(--radius-md) 0 0;
    }

    .rota-table thead th {
        background: var(--bg-input);
        color: var(--text-muted);
        font-weight: 700;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .rota-table thead th span {
        display: block;
        font-size: 11px;
        font-weight: 500;
        margin-top: 4px;
        color: var(--text-dim);
    }

    .employee-name {
        font-weight: 700;
        text-align: left !important;
        background: var(--bg-input) !important;
        color: var(--text-main);
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .emp-avatar {
        width: 32px;
        height: 32px;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        font-weight: 700;
        color: white;
    }

    .off-cell {
        background: rgba(113, 113, 122, 0.15) !important;
    }

    .shift-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
    }

    .shift-badge.off {
        background: rgba(113, 113, 122, 0.2);
        color: #a1a1aa;
    }

    .shift-badge.holiday {
        background: rgba(16, 185, 129, 0.2);
        color: #6ee7b7;
    }

    .shift-badge.sick {
        background: rgba(239, 68, 68, 0.2);
        color: #fca5a5;
    }

    .time-badge {
        font-weight: 600;
        font-size: 13px;
        color: var(--primary-hover);
    }

    .empty-cell {
        color: var(--text-dim);
    }

    /* Shift Type Colors */
    .type-morning td:not(.off-cell) {
        background: rgba(59, 130, 246, 0.08);
    }
    
    .type-evening td:not(.off-cell) {
        background: rgba(245, 158, 11, 0.08);
    }
    
    .type-night td:not(.off-cell) {
        background: rgba(139, 92, 246, 0.08);
    }
    
    .type-split td:not(.off-cell) {
        background: rgba(236, 72, 153, 0.08);
    }

    .rota-table tbody tr:hover td {
        background: rgba(255,255,255,0.03);
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @media (max-width: 768px) {
        .board-header {
            flex-direction: column;
            align-items: stretch;
        }

        .week-form {
            flex-direction: column;
        }

        .week-form input,
        .week-form button {
            width: 100%;
        }

        .rota-table {
            font-size: 12px;
        }

        .rota-table th,
        .rota-table td {
            padding: 10px 6px;
        }
    }
</style>

@endsection