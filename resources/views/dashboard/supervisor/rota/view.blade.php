@extends('dashboard.supervisor.layout')

@section('content')

<div class="rota-board-card">

    <div class="board-header">
        <div>
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
                View Week
            </button>
        </form>
    </div>

    <div class="rota-table-wrapper">
        <table class="rota-table">
            <thead>
                <tr>
                    <th colspan="{{ count($weekDates) + 1 }}" class="department-title">
                        {{ $supervisor->department->name ?? 'Department' }}
                    </th>
                </tr>

                <tr>
                    <th>Employee</th>

                    @foreach($weekDates as $date)
                        <th>
                            {{ $date->format('D') }}
                            <br>
                            <span>{{ $date->format('d M') }}</span>
                        </th>
                    @endforeach
                </tr>
            </thead>

            <tbody>
                @foreach($employees as $employee)
                    <tr>
                        <td class="employee-name">
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
                            @endphp

                            <td class="{{ $isOff ? 'off-cell' : '' }}">
                                @forelse($dayShifts as $shift)
                                    @if($shift->shift_type === 'day_off')
                                        OFF
                                    @elseif($shift->shift_type === 'holiday')
                                        HOLIDAY
                                    @elseif($shift->shift_type === 'sick')
                                        SICK
                                    @elseif($shift->start_time && $shift->end_time)
                                        {{ \Carbon\Carbon::parse($shift->start_time)->format('H:i') }}
                                        -
                                        {{ \Carbon\Carbon::parse($shift->end_time)->format('H:i') }}
                                    @else
                                        {{ ucwords(str_replace('_', ' ', $shift->shift_type)) }}
                                    @endif

                                    @if(!$loop->last)
                                        <br>
                                    @endif
                                @empty
                                    -
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
.rota-board-card {
    background: white;
    border-radius: 20px;
    padding: 22px;
    box-shadow: 0 8px 24px rgba(0,0,0,0.06);
}

.board-header {
    display: flex;
    justify-content: space-between;
    align-items: end;
    gap: 16px;
    margin-bottom: 20px;
}

.board-header h1 {
    margin: 0 0 6px;
    font-size: 26px;
}

.board-header p {
    margin: 0;
    color: #6b7280;
    font-weight: 700;
}

.week-form {
    display: flex;
    gap: 10px;
}

.week-form input {
    padding: 10px 12px;
    border: 1px solid #d1d5db;
    border-radius: 10px;
}

.week-form button {
    border: none;
    background: #1583ff;
    color: white;
    padding: 10px 14px;
    border-radius: 10px;
    font-weight: 800;
    cursor: pointer;
}

.rota-table-wrapper {
    overflow-x: auto;
}

.rota-table {
    width: 100%;
    min-width: 900px;
    border-collapse: collapse;
    text-align: center;
}

.rota-table th,
.rota-table td {
    border: 1px solid #111827;
    padding: 8px;
    font-size: 14px;
}

.department-title {
    background: #00aeea;
    color: black;
    font-size: 20px !important;
    font-weight: 900;
    padding: 10px !important;
}

.rota-table th {
    background: #f3f4f6;
    font-weight: 900;
}

.rota-table th span {
    font-size: 12px;
    color: #6b7280;
}

.employee-name {
    font-weight: 900;
    text-align: left;
    background: #f9fafb;
}

.off-cell {
    background: yellow;
    font-weight: 900;
}

@media(max-width: 700px) {
    .board-header {
        flex-direction: column;
        align-items: stretch;
    }

    .week-form {
        flex-direction: column;
    }

    .week-form button {
        width: 100%;
    }
}
</style>

@endsection