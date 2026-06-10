@extends('dashboard.housekeeping.layout')

@section('content')

<div class="page-header">
    <div>
        <h1><i class="fas fa-clipboard-check"></i> Inspected Rooms</h1>
        <p>Rooms approved by housekeeping supervisors</p>
    </div>
</div>

<div class="table-card">

    <div class="table-header">
        <h3>Inspection Records</h3>

        <span class="record-count">
            {{ $rooms->count() }} Rooms
        </span>
    </div>

    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Room</th>
                    <th>Floor</th>
                    <th>Type</th>
                    <th>Cleaned By</th>
                    <th>Inspected By</th>
                    <th>Inspection Time</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>
                @forelse($rooms as $room)
                    <tr>

                        <td>
                            <strong>{{ $room->room_number }}</strong>
                        </td>

                        <td>
                            {{ $room->floor ?? '-' }}
                        </td>

                        <td>
                            {{ $room->room_type ?? '-' }}
                        </td>

                        <td>
                            {{ $room->assignedUser->name ?? 'N/A' }}
                        </td>

                        <td>
                            {{ $room->inspectedBy->name ?? 'N/A' }}
                        </td>

                        <td>
                            @if($room->inspected_at)
                                {{ \Carbon\Carbon::parse($room->inspected_at)->format('d M Y H:i') }}
                            @else
                                -
                            @endif
                        </td>

                        <td>
                            <span class="badge badge-success">
                                Inspected
                            </span>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <i class="fas fa-clipboard-check"></i>
                                <p>No inspected rooms found.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection

<style>

.page-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:24px;
}

.page-header h1{
    font-size:28px;
    color:#fff;
    margin-bottom:6px;
}

.page-header p{
    color:#9ca3af;
}

.table-card{
    background:#18181b;
    border:1px solid #27272a;
    border-radius:16px;
    overflow:hidden;
}

.table-header{
    padding:20px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    border-bottom:1px solid #27272a;
}

.table-header h3{
    color:#fff;
}

.record-count{
    background:#dc2626;
    color:#fff;
    padding:8px 14px;
    border-radius:999px;
    font-size:13px;
    font-weight:600;
}

.table-wrap{
    overflow-x:auto;
}

.data-table{
    width:100%;
    border-collapse:collapse;
}

.data-table th{
    background:#09090b;
    color:#9ca3af;
    padding:16px;
    text-align:left;
    font-size:13px;
    font-weight:600;
}

.data-table td{
    padding:16px;
    border-top:1px solid #27272a;
    color:#fff;
}

.data-table tr:hover{
    background:#202024;
}

.badge-success{
    background:rgba(34,197,94,.15);
    color:#22c55e;
    padding:6px 12px;
    border-radius:999px;
    font-size:12px;
    font-weight:700;
}

.empty-state{
    text-align:center;
    padding:60px 20px;
}

.empty-state i{
    font-size:50px;
    color:#52525b;
    margin-bottom:12px;
}

.empty-state p{
    color:#9ca3af;
}

</style>