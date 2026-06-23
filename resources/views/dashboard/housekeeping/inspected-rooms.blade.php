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
        <form method="GET" class="filter-card">
    <div>
        <label>Date</label>
        <input type="date" name="date" value="{{ request('date', today()->toDateString()) }}">
    </div>

    <div>
        <label>Cleaned By</label>
        <select name="staff_id">
            <option value="">All Staff</option>
            @foreach($staff as $member)
                <option value="{{ $member->id }}" {{ request('staff_id') == $member->id ? 'selected' : '' }}>
                    {{ $member->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label>Room Type</label>
        <select name="room_status">
            <option value="">All Types</option>
            <option value="departure" {{ request('room_status') == 'departure' ? 'selected' : '' }}>Departure</option>
            <option value="stay" {{ request('room_status') == 'stay' ? 'selected' : '' }}>Stay</option>
            <option value="stayover" {{ request('room_status') == 'stayover' ? 'selected' : '' }}>Stayover</option>
            <option value="room_move" {{ request('room_status') == 'room_move' ? 'selected' : '' }}>Room Move</option>
            <option value="carry_forward" {{ request('room_status') == 'carry_forward' ? 'selected' : '' }}>Carry Forward</option>
        </select>
    </div>

    <div class="filter-actions">
        <button type="submit">
            <i class="fas fa-filter"></i> Filter
        </button>

        <a href="{{ route('housekeeping-supervisor.inspectedRooms') }}">
            Reset
        </a>
    </div>
</form>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Room</th>
                    <th>Floor</th>
                    <th>Type</th>
                    <th>Cleaned By</th>
                    <th>Inspected By</th>
                    <th>Inspection Time</th>
                    {{-- <th>Status</th> --}}
                </tr>
            </thead>

            <tbody>
                @forelse($rooms as $room)
                    <tr>

                       <td>
                            <strong>{{ $room->room->room_number ?? '-' }}</strong>
                        </td>

                        <td>
                            {{ $room->room->floor ?? (is_numeric($room->room->room_number ?? null) ? substr($room->room->room_number, 0, 1) : '-') }}
                        </td>

                        <td>
                            {{ $room->roomStatusUpdate->status ?? '-' }}
                        </td>

                        <td>
                            {{ $room->assignedTo->name ?? 'N/A' }}
                        </td>

                        <td>
                            {{ $room->inspectedBy->name ?? 'N/A' }}
                        </td>
                        <td>
                            @if(!empty($room->inspected_at))
                                {{ \Carbon\Carbon::parse($room->inspected_at)->format('d M Y H:i') }}
                            @else
                                -
                            @endif
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

.filter-card{
    background:#18181b;
    border:1px solid #27272a;
    border-radius:16px;
    padding:18px;
    margin-bottom:20px;
    display:grid;
    grid-template-columns: repeat(4, 1fr);
    gap:16px;
    align-items:end;
}

.filter-card label{
    display:block;
    color:#9ca3af;
    font-size:12px;
    font-weight:700;
    margin-bottom:7px;
}

.filter-card input,
.filter-card select{
    width:100%;
    background:#09090b;
    border:1px solid #27272a;
    color:#fff;
    border-radius:10px;
    padding:11px 12px;
}

.filter-actions{
    display:flex;
    gap:10px;
}

.filter-actions button,
.filter-actions a{
    height:43px;
    padding:0 16px;
    border-radius:10px;
    font-weight:800;
    display:flex;
    align-items:center;
    justify-content:center;
    gap:8px;
}

.filter-actions button{
    background:#dc2626;
    color:white;
    border:none;
}

.filter-actions a{
    background:#27272a;
    color:#fff;
    text-decoration:none;
}

@media(max-width:900px){
    .filter-card{
        grid-template-columns:1fr;
    }
}

</style>