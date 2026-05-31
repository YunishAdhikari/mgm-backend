@php
    $role = strtolower(auth()->user()->role->slug ?? '');

    $layout = str_contains($role, 'supervisor')
        ? 'dashboard.supervisor.layout'
        : 'dashboard.reception.layout';
@endphp

@extends($layout)

@section('content')

<style>
    :root {
        --bg-page: #09090b;
        --bg-card: #27272a;
        --bg-input: #1c1c1f;
        --text-main: #fafafa;
        --text-muted: #a1a1aa;
        --text-dim: #71717a;
        --border: #3f3f46;
        --primary: #8b5cf6;
        --primary-hover: #a78bfa;
    }

    .page-wrap { padding: 20px; }

    .page-title {
        font-size: 26px;
        font-weight: 900;
        color: var(--text-main);
        margin-bottom: 6px;
    }

    .page-subtitle { color: var(--text-muted); margin-bottom: 20px; }

    .filter-card, .summary-card, .table-card {
        background: var(--bg-card);
        padding: 20px;
        border-radius: 16px;
        box-shadow: 0 8px 30px rgba(0,0,0,0.3);
        margin-bottom: 20px;
        border: 1px solid var(--border);
    }

    .filter-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 14px;
    }

    .filter-item label {
        font-weight: 700;
        font-size: 12px;
        margin-bottom: 6px;
        display: block;
        color: var(--text-muted);
    }

    .filter-item input, .filter-item select {
        width: 100%;
        padding: 10px 12px;
        border: 2px solid var(--border);
        border-radius: 10px;
        background: var(--bg-input);
        color: var(--text-main);
        font-size: 13px;
    }

    .filter-item input:focus, .filter-item select:focus {
        outline: none;
        border-color: var(--primary);
    }

    .filter-actions {
        display: flex;
        align-items: flex-end;
        gap: 10px;
    }

    .btn-main {
        background: var(--primary);
        color: white;
        border: none;
        padding: 10px 16px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-main:hover { background: var(--primary-hover); }

    .btn-dark {
        background: var(--bg-input);
        color: var(--text-main);
        padding: 10px 16px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 13px;
        text-decoration: none;
        display: inline-block;
        border: 1px solid var(--border);
    }

    .summary-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 14px;
    }

    .summary-box {
        background: var(--bg-input);
        padding: 16px;
        border-radius: 14px;
        text-align: center;
    }

    .summary-label {
        color: var(--text-dim);
        font-weight: 700;
        font-size: 12px;
        text-transform: uppercase;
        margin-bottom: 4px;
    }

    .summary-value {
        font-size: 26px;
        font-weight: 900;
        color: var(--text-main);
    }

    .booking-card {
        background: var(--bg-input);
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 16px;
        margin-bottom: 12px;
    }

    .booking-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
    }

    .booking-date {
        font-weight: 800;
        color: var(--text-main);
        font-size: 15px;
    }

    .booking-time {
        font-size: 13px;
        color: var(--text-muted);
    }

    .booking-type {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
    }

    .badge-tea {
        background: rgba(59, 130, 246, 0.15);
        color: #60a5fa;
    }

    .badge-dinner {
        background: rgba(16, 185, 129, 0.15);
        color: #6ee7b7;
    }

    .booking-details {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
    }

    .detail-item {
        font-size: 13px;
    }

    .detail-label {
        color: var(--text-dim);
        font-weight: 600;
        font-size: 11px;
        text-transform: uppercase;
    }

    .detail-value {
        color: var(--text-main);
        font-weight: 600;
    }

    .booking-status {
        grid-column: span 2;
        text-align: center;
        padding: 8px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 12px;
    }

    .empty-cell {
        text-align: center;
        padding: 40px 20px;
        color: var(--text-dim);
    }

    /* Responsive */
    @media (max-width: 900px) {
        .filter-grid { grid-template-columns: repeat(2, 1fr); }
    }

    @media (max-width: 600px) {
        .page-wrap { padding: 14px; }
        .page-title { font-size: 22px; }
        
        .filter-grid { grid-template-columns: 1fr; }
        .filter-actions { flex-direction: column; }
        .filter-actions .btn-main, .filter-actions .btn-dark { width: 100%; text-align: center; }
        
        .summary-grid { grid-template-columns: 1fr; }
        
        .booking-card { margin-bottom: 10px; }
        .booking-header { flex-direction: column; align-items: flex-start; gap: 8px; }
        .booking-details { grid-template-columns: 1fr; }
        .booking-status { grid-column: span 1; }
    }
</style>

<div class="page-wrap">

    <h1 class="page-title">Restaurant Booking Report</h1>
    <p class="page-subtitle">Generate report for Afternoon Tea and Dinner bookings.</p>

    <div class="filter-card">
        <form method="GET" action="{{ route('reception.restaurant.bookings.report') }}">
            <div class="filter-grid">
                <div class="filter-item">
                    <label>Booking Type</label>
                    <select name="booking_type">
                        <option value="">All</option>
                        <option value="afternoon_tea" {{ request('booking_type') == 'afternoon_tea' ? 'selected' : '' }}>Afternoon Tea</option>
                        <option value="dinner" {{ request('booking_type') == 'dinner' ? 'selected' : '' }}>Dinner</option>
                    </select>
                </div>

                <div class="filter-item">
                    <label>Date From</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}">
                </div>

                <div class="filter-item">
                    <label>Date To</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}">
                </div>

                <div class="filter-item">
                    <label>Status</label>
                    <select name="status">
                        <option value="">All</option>
                        @foreach(['confirmed', 'seated', 'completed', 'cancelled', 'no_show'] as $status)
                            <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ ucwords(str_replace('_', ' ', $status)) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-actions">
                    <button type="submit" class="btn-main">Generate</button>
                    <a href="{{ route('reception.restaurant.bookings.report') }}" class="btn-dark">Reset</a>
                </div>
            </div>
        </form>
    </div>

    <div class="summary-card">
        <div class="summary-grid">
            <div class="summary-box">
                <div class="summary-label">Total Bookings</div>
                <div class="summary-value">{{ $totalBookings }}</div>
            </div>

            <div class="summary-box">
                <div class="summary-label">Total Pax</div>
                <div class="summary-value">{{ $totalPax }}</div>
            </div>

            <div class="summary-box">
                <div class="summary-label">Overbookings</div>
                <div class="summary-value">{{ $overBookings }}</div>
            </div>
        </div>
        <a href="{{ route('reception.restaurant.bookings.report.pdf', request()->query()) }}"
   class="btn-dark">
    Download PDF
</a>
    </div>

    <div class="table-card">
        @forelse($bookings as $booking)
            <div class="booking-card">
                <div class="booking-header">
                    <div>
                        <div class="booking-date">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}</div>
                        <div class="booking-time">
                            {{ \Carbon\Carbon::parse($booking->slot_start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($booking->slot_end_time)->format('g:i A') }}
                        </div>
                    </div>
                    <span class="booking-type {{ $booking->booking_type == 'afternoon_tea' ? 'badge-tea' : 'badge-dinner' }}">
                        {{ $booking->booking_type == 'afternoon_tea' ? 'Afternoon Tea' : 'Dinner' }}
                    </span>
                </div>

                <div class="booking-details">
                    <div class="detail-item">
                        <div class="detail-label">Guest</div>
                        <div class="detail-value">{{ $booking->guest_name }}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Phone</div>
                        <div class="detail-value">{{ $booking->guest_phone ?? '-' }}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Pax</div>
                        <div class="detail-value">{{ $booking->pax }}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Table</div>
                        <div class="detail-value">{{ $booking->table->table_name ?? '-' }}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Voucher</div>
                        <div class="detail-value">{{ $booking->voucher_code ?? '-' }}</div>
                    </div>
                    <div class="booking-status" style="background: var(--bg-card);">
                        {{ ucwords(str_replace('_', ' ', $booking->status)) }}
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-cell">
                No bookings found for selected filters.
            </div>
        @endforelse
    </div>

</div>

@endsection