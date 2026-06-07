@extends('dashboard.housekeeping.layout')

@section('content')

<div class="page-wrapper">

    <div class="page-header">
        <div>
            <h1>DND / Pending Rooms</h1>
        </div>
    </div>

    <div class="stats-grid">

        <div class="stat-card">
            <div class="stat-number">{{ $stats['total'] }}</div>
            <div class="stat-label">Total Issues</div>
        </div>

        <div class="stat-card warning">
            <div class="stat-number">{{ $stats['assigned'] }}</div>
            <div class="stat-label">Pending</div>
        </div>

        <div class="stat-card info">
            <div class="stat-number">{{ $stats['in_progress'] }}</div>
            <div class="stat-label">In Progress</div>
        </div>

        <div class="stat-card purple">
            <div class="stat-number">{{ $stats['dnd'] }}</div>
            <div class="stat-label">DND</div>
        </div>

        <div class="stat-card danger">
            <div class="stat-number">{{ $stats['refused'] }}</div>
            <div class="stat-label">Refused</div>
        </div>

    </div>

    <div class="table-card">

        <table class="table">
            <thead>
            <tr>
                <th>Room</th>
                <th>Type</th>
                <th>Assigned Staff</th>
                <th>Status</th>
                <th>Notes</th>
            </tr>
            </thead>

            <tbody>

            @forelse($rooms as $allocation)

                <tr>

                    <td>
                        <strong>
                            {{ $allocation->room->room_number ?? '-' }}
                        </strong>
                    </td>

                    <td>
                        {{ ucfirst(str_replace('_',' ', $allocation->roomStatusUpdate->status ?? '-')) }}
                    </td>

                    <td>
                        {{ $allocation->assignedTo->name ?? 'Unassigned' }}
                    </td>

                    <td>

                        @php
                            $status = $allocation->cleaning_status;
                        @endphp

                        @if($status == 'dnd')
                            <span class="badge badge-purple">DND</span>

                        @elseif($status == 'refused_service')
                            <span class="badge badge-red">Refused</span>

                        @elseif($status == 'in_progress')
                            <span class="badge badge-blue">In Progress</span>

                        @else
                            <span class="badge badge-yellow">Pending</span>
                        @endif

                    </td>

                    <td>
                        {{ $allocation->notes ?? '-' }}
                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="5" class="empty">
                        No DND or pending rooms today.
                    </td>
                </tr>

            @endforelse

            </tbody>
        </table>

    </div>

</div>

<style>

.page-wrapper{
    padding:24px;
}

.page-header{
    margin-bottom:24px;
}

.page-header h1{
    margin:0;
    color:#fafafa;
    font-size:28px;
    font-weight:800;
}

.page-header p{
    color:#a1a1aa;
    margin-top:6px;
}

.stats-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(180px,1fr));
    gap:16px;
    margin-bottom:24px;
}

.stat-card{
    background:#18181b;
    border:1px solid #3f3f46;
    border-radius:18px;
    padding:20px;
}

.stat-number{
    font-size:32px;
    font-weight:800;
    color:white;
}

.stat-label{
    color:#a1a1aa;
    margin-top:6px;
}

.warning .stat-number{
    color:#f59e0b;
}

.info .stat-number{
    color:#3b82f6;
}

.purple .stat-number{
    color:#8b5cf6;
}

.danger .stat-number{
    color:#ef4444;
}

.table-card{
    background:#18181b;
    border:1px solid #3f3f46;
    border-radius:18px;
    overflow:hidden;
}

.table{
    width:100%;
    border-collapse:collapse;
}

.table th{
    background:#27272a;
    color:#fafafa;
    text-align:left;
    padding:14px;
}

.table td{
    padding:14px;
    border-top:1px solid #3f3f46;
    color:#d4d4d8;
}

.badge{
    padding:6px 12px;
    border-radius:999px;
    font-size:12px;
    font-weight:700;
}

.badge-yellow{
    background:rgba(245,158,11,.15);
    color:#f59e0b;
}

.badge-blue{
    background:rgba(59,130,246,.15);
    color:#3b82f6;
}

.badge-purple{
    background:rgba(139,92,246,.15);
    color:#8b5cf6;
}

.badge-red{
    background:rgba(239,68,68,.15);
    color:#ef4444;
}

.empty{
    text-align:center;
    color:#a1a1aa;
}

</style>

@endsection