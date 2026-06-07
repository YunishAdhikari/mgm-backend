@extends('dashboard.housekeeping.layout')

@section('content')

<div class="page-wrapper">

    <div class="page-header">
        <div>
            <h1>Out Of Order / Out Of Inventory</h1>
        </div>
    </div>

    <div class="stats-grid">

        <div class="stat-card">
            <div class="stat-number">{{ $stats['total'] }}</div>
            <div class="stat-label">Total Rooms</div>
        </div>

        <div class="stat-card danger">
            <div class="stat-number">{{ $stats['ooo'] }}</div>
            <div class="stat-label">OOO Rooms</div>
        </div>

        <div class="stat-card warning">
            <div class="stat-number">{{ $stats['ooi'] }}</div>
            <div class="stat-label">OOI Rooms</div>
        </div>

    </div>

    <div class="table-card">

        <table class="table">

            <thead>
            <tr>
                <th>Room Number</th>
                <th>Floor</th>
                <th>Status</th>
                <th>Remarks</th>
                <th>Updated</th>
            </tr>
            </thead>

            <tbody>

            @forelse($rooms as $room)

                @php
                    $roomNumber = $room->room->room_number ?? '-';
                    $floor = strlen($roomNumber) >= 3 ? substr($roomNumber, 0, 1) : '-';
                @endphp

                <tr>

                    <td>
                        <strong>{{ $roomNumber }}</strong>
                    </td>

                    <td>
                        Floor {{ $floor }}
                    </td>

                    <td>

                        @if($room->status == 'OOO')
                            <span class="badge badge-red">
                                OOO
                            </span>
                        @else
                            <span class="badge badge-yellow">
                                OOI
                            </span>
                        @endif

                    </td>

                    <td>
                        {{ $room->remarks ?? '-' }}
                    </td>

                    <td>
                        {{ $room->updated_at?->format('d M Y H:i') }}
                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="5" class="empty">
                        No Out Of Order or Out Of Inventory rooms today.
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
    color:#fafafa;
    font-size:30px;
    font-weight:800;
    margin:0;
}

.page-header p{
    color:#a1a1aa;
    margin-top:8px;
}

.stats-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:16px;
    margin-bottom:24px;
}

.stat-card{
    background:#18181b;
    border:1px solid #3f3f46;
    border-radius:18px;
    padding:22px;
}

.stat-number{
    font-size:34px;
    font-weight:800;
    color:#fafafa;
}

.stat-label{
    color:#a1a1aa;
    margin-top:6px;
}

.danger .stat-number{
    color:#ef4444;
}

.warning .stat-number{
    color:#f59e0b;
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

.table thead{
    background:#27272a;
}

.table th{
    color:#fafafa;
    text-align:left;
    padding:16px;
    font-weight:700;
}

.table td{
    padding:16px;
    border-top:1px solid #3f3f46;
    color:#d4d4d8;
}

.badge{
    padding:6px 12px;
    border-radius:999px;
    font-size:12px;
    font-weight:700;
}

.badge-red{
    background:rgba(239,68,68,.15);
    color:#ef4444;
}

.badge-yellow{
    background:rgba(245,158,11,.15);
    color:#f59e0b;
}

.empty{
    text-align:center;
    color:#a1a1aa;
}

</style>

@endsection