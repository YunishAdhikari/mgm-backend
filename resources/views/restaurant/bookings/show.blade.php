@php
    $role = strtolower(auth()->user()->role->slug ?? '');
    $layout = str_contains($role, 'supervisor')
        ? 'dashboard.supervisor.layout'
        : 'dashboard.reception.layout';
@endphp

@extends($layout)

@section('content')
<style>
    .page-wrap { padding:28px; max-width:900px; }
    .card { background:white; padding:26px; border-radius:22px; box-shadow:0 8px 30px rgba(15,23,42,.08); margin-bottom:20px; }
    .title { font-size:30px; font-weight:900; color:#0f172a; margin-bottom:6px; }
    .subtitle { color:#64748b; margin-bottom:24px; }
    .grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); gap:16px; }
    .box { background:#f8fafc; padding:16px; border-radius:14px; }
    .label { color:#64748b; font-size:12px; font-weight:900; text-transform:uppercase; }
    .value { color:#0f172a; font-size:17px; font-weight:800; margin-top:4px; }
    .btn { padding:12px 18px; border-radius:12px; text-decoration:none; font-weight:900; display:inline-block; border:none; cursor:pointer; }
    .btn-dark { background:#0f172a; color:white; }
    .btn-main { background:#1583ff; color:white; }
    .btn-danger { background:#fee2e2; color:#991b1b; }
    .success { background:#dcfce7; color:#166534; padding:12px 16px; border-radius:12px; margin-bottom:18px; font-weight:800; }
</style>

<div class="page-wrap">
    <h1 class="title">Booking Details</h1>
    <p class="subtitle">View restaurant booking information.</p>

    @if(session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="grid">
            <div class="box">
                <div class="label">Type</div>
                <div class="value">{{ $booking->booking_type === 'afternoon_tea' ? 'Afternoon Tea' : 'Dinner' }}</div>
            </div>

            <div class="box">
                <div class="label">Date</div>
                <div class="value">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y') }}</div>
            </div>

            <div class="box">
                <div class="label">Time</div>
                <div class="value">
                    {{ \Carbon\Carbon::parse($booking->slot_start_time)->format('g:i A') }}
                    -
                    {{ \Carbon\Carbon::parse($booking->slot_end_time)->format('g:i A') }}
                </div>
            </div>

            <div class="box">
                <div class="label">Status</div>
                <div class="value">{{ ucwords(str_replace('_', ' ', $booking->status)) }}</div>
            </div>

            <div class="box">
                <div class="label">Guest Name</div>
                <div class="value">{{ $booking->guest_name }}</div>
            </div>

            <div class="box">
                <div class="label">Phone</div>
                <div class="value">{{ $booking->guest_phone ?? '-' }}</div>
            </div>

            <div class="box">
                <div class="label">Email</div>
                <div class="value">{{ $booking->guest_email ?? '-' }}</div>
            </div>

            <div class="box">
                <div class="label">Pax</div>
                <div class="value">{{ $booking->pax }}</div>
            </div>

            <div class="box">
                <div class="label">Table</div>
                <div class="value">{{ $booking->table->table_name ?? '-' }}</div>
            </div>

            <div class="box">
                <div class="label">Overbooking</div>
                <div class="value">{{ $booking->is_overbooking ? 'Yes' : 'No' }}</div>
            </div>

            <div class="box">
                <div class="label">Voucher Code</div>
                <div class="value">{{ $booking->voucher_code ?? '-' }}</div>
            </div>

            <div class="box">
                <div class="label">Voucher Amount</div>
                <div class="value">{{ $booking->voucher_amount ? '£'.$booking->voucher_amount : '-' }}</div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="label">Special Request</div>
        <div class="value">{{ $booking->special_request ?? '-' }}</div>

        <br>

        <div class="label">Voucher Note</div>
        <div class="value">{{ $booking->voucher_note ?? '-' }}</div>
    </div>

    <a href="{{ route('reception.restaurant.bookings.list') }}" class="btn btn-dark">Back</a>
    <a href="{{ route('reception.restaurant.bookings.edit', $booking) }}" class="btn btn-main">Edit</a>

    @if(!in_array($booking->status, ['cancelled', 'completed']))
        <form method="POST"
              action="{{ route('reception.restaurant.bookings.cancel', $booking) }}"
              style="display:inline-block;"
              onsubmit="return confirm('Cancel this booking?')">
            @csrf
            @method('PATCH')
            <button class="btn btn-danger">Cancel Booking</button>
        </form>
    @endif
</div>
@endsection