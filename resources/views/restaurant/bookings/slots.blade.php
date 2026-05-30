{{-- @extends('dashboard.reception.layout') --}}
@php
    $role = strtolower(auth()->user()->role->slug ?? auth()->user()->role->name ?? '');

    $layout = in_array($role, ['supervisor'])
        ? 'dashboard.supervisor.layout'
        : 'dashboard.reception.layout';
@endphp
@extends($layout)


@section('content')

<style>
    .page-wrap {
        max-width: 1200px;
        margin: 0 auto;
        padding: 24px;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 28px;
    }

    .header-left {
        flex: 1;
    }

    .page-title {
        font-size: 30px;
        font-weight: 800;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .page-title span {
        background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .page-subtitle {
        color: #64748b;
        margin-top: 4px;
        font-weight: 500;
    }

    .btn-back {
        background: white;
        color: #475569;
        padding: 12px 20px;
        border-radius: 12px;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border: 1px solid #e2e8f0;
        transition: all 0.2s ease;
    }

    .btn-back:hover {
        background: #f8fafc;
        color: #0f172a;
        transform: translateX(-4px);
    }

    /* ============ FILTER CARD ============ */
    .filter-card {
        background: white;
        border-radius: 24px;
        padding: 24px;
        box-shadow: 0 4px 24px rgba(15, 23, 42, 0.06);
        margin-bottom: 28px;
    }

    .form-row {
        display: flex;
        align-items: flex-end;
        gap: 16px;
        flex-wrap: wrap;
    }

    .form-group {
        flex: 1;
        min-width: 200px;
    }

    .form-label {
        font-size: 12px;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
        display: block;
    }

    .input-date {
        width: 100%;
        padding: 14px 16px;
        border: 2px solid #e2e8f0;
        border-radius: 14px;
        font-size: 15px;
        transition: all 0.2s ease;
    }

    .input-date:focus {
        outline: none;
        border-color: #22c55e;
        box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.1);
    }

    .btn-primary {
        background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
        color: white;
        border: none;
        padding: 14px 28px;
        border-radius: 14px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(34, 197, 94, 0.4);
    }

    /* ============ SLOT GRID ============ */
    .slot-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
    }

    .slot-card {
        background: white;
        border-radius: 24px;
        padding: 24px;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.06);
        border: 2px solid #f1f5f9;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }

    .slot-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #22c55e, #16a34a);
        border-radius: 24px 24px 0 0;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .slot-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 16px 40px rgba(15, 23, 42, 0.12);
        border-color: #22c55e;
    }

    .slot-card:hover::before {
        opacity: 1;
    }

    .slot-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .slot-time {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .slot-time-icon {
        width: 44px;
        height: 44px;
        background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    .slot-time-text {
        font-size: 22px;
        font-weight: 800;
        color: #0f172a;
    }

    .slot-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
    }

    .slot-badge.available {
        background: #dcfce7;
        color: #166534;
    }

    .slot-badge.full {
        background: #fee2e2;
        color: #991b1b;
    }

    .slot-stats {
        display: flex;
        gap: 16px;
        margin-bottom: 16px;
    }

    .slot-stat {
        flex: 1;
        padding: 14px;
        background: #f8fafc;
        border-radius: 12px;
        text-align: center;
    }

    .slot-stat-label {
        font-size: 11px;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        margin-bottom: 4px;
    }

    .slot-stat-value {
        font-size: 18px;
        font-weight: 800;
        color: #0f172a;
    }

    .slot-stat-value.green {
        color: #22c55e;
    }

    .slot-stat-value.red {
        color: #ef4444;
    }

    .progress-wrap {
        margin: 16px 0;
    }

    .progress-header {
        display: flex;
        justify-content: space-between;
        font-size: 12px;
        font-weight: 700;
        color: #64748b;
        margin-bottom: 8px;
    }

    .progress-track {
        height: 10px;
        background: #e2e8f0;
        border-radius: 999px;
        overflow: hidden;
    }

    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #22c55e, #16a34a);
        border-radius: 999px;
        transition: width 0.5s ease;
    }

    .progress-fill.warning {
        background: linear-gradient(90deg, #f59e0b, #d97706);
    }

    .progress-fill.danger {
        background: linear-gradient(90deg, #ef4444, #dc2626);
    }

    .slot-btn {
        width: 100%;
        padding: 14px;
        border-radius: 14px;
        font-weight: 700;
        text-decoration: none;
        text-align: center;
        margin-top: 16px;
        transition: all 0.2s ease;
    }

    .slot-btn.available {
        background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
    }

    .slot-btn.available:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(34, 197, 94, 0.4);
    }

    .slot-btn.full {
        background: #f1f5f9;
        color: #94a3b8;
        cursor: not-allowed;
    }

    /* ============ EMPTY STATE ============ */
    .empty-state {
        background: white;
        border-radius: 24px;
        padding: 60px 40px;
        text-align: center;
        box-shadow: 0 4px 24px rgba(15, 23, 42, 0.06);
    }

    .empty-icon {
        width: 80px;
        height: 80px;
        background: #f1f5f9;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 36px;
        margin: 0 auto 20px;
    }

    .empty-title {
        font-size: 20px;
        font-weight: 800;
        color: #0f172a;
    }

    .empty-text {
        color: #64748b;
        margin-top: 8px;
    }
</style>

<div class="page-wrap">

    @php
        $label = $type === 'afternoon_tea' ? 'Afternoon Tea' : 'Dinner';
    @endphp

    <div class="page-header">
        <div class="header-left">
            <h1 class="page-title">
                {{ $label }} <span>Slots</span>
            </h1>
            <p class="page-subtitle">Select a time slot to create a booking</p>
        </div>

        <a href="{{ route('reception.restaurant.bookings.index') }}" class="btn-back">
            ← Back
        </a>
    </div>

    <div class="filter-card">
        <form method="GET" action="{{ route('reception.restaurant.bookings.slots', $type) }}" class="form-row">
            <div class="form-group">
                <label class="form-label">Booking Date</label>
                <input type="date" name="date" value="{{ $bookingDate }}" class="input-date" required>
            </div>

            <button type="submit" class="btn-primary">
                Load Slots
            </button>
        </form>
    </div>

    @if(!$setting)
        <div class="empty-state">
            <div class="empty-icon">⚙️</div>
            <h3 class="empty-title">No Active Settings</h3>
            <p class="empty-text">Please ask admin to configure {{ $label }} settings.</p>
        </div>
    @elseif(count($slots) === 0)
        <div class="empty-state">
            <div class="empty-icon">📭</div>
            <h3 class="empty-title">No Slots Available</h3>
            <p class="empty-text">Please check opening hours and slot duration.</p>
        </div>
    @else
        <div class="slot-grid">
            @foreach($slots as $slot)
                @php
                    $percentage = $slot['max_pax'] > 0 
                        ? min(100, ($slot['booked_pax'] / $slot['max_pax']) * 100) 
                        : 0;
                    $isFull = $slot['is_full'];
                    $progressClass = $percentage >= 100 ? 'danger' : ($percentage >= 75 ? 'warning' : '');
                @endphp

                <div class="slot-card">
                    <div class="slot-header">
                        <div class="slot-time">
                            <div class="slot-time-icon">🕐</div>
                            <div class="slot-time-text">{{ $slot['label'] }}</div>
                        </div>
                        <div class="slot-badge {{ $isFull ? 'full' : 'available' }}">
                            {{ $isFull ? 'Full' : 'Available' }}
                        </div>
                    </div>

                    <div class="slot-stats">
                        <div class="slot-stat">
                            <div class="slot-stat-label">Booked</div>
                            <div class="slot-stat-value">{{ $slot['booked_pax'] }}</div>
                        </div>
                        <div class="slot-stat">
                            <div class="slot-stat-label">Max</div>
                            <div class="slot-stat-value">{{ $slot['max_pax'] }}</div>
                        </div>
                        <div class="slot-stat">
                            <div class="slot-stat-label">Left</div>
                            <div class="slot-stat-value {{ $slot['available_pax'] > 0 ? 'green' : 'red' }}">
                                {{ $slot['available_pax'] }}
                            </div>
                        </div>
                    </div>

                    <div class="progress-wrap">
                        <div class="progress-header">
                            <span>Capacity</span>
                            <span>{{ $percentage }}%</span>
                        </div>
                        <div class="progress-track">
                            <div class="progress-fill {{ $progressClass }}" style="width: {{ $percentage }}%;"></div>
                        </div>
                    </div>

                    @if(!$isFull)
                        <a href="{{ route('reception.restaurant.bookings.create', [
                            'type' => $type,
                            'slotStart' => $slot['start'],
                            'slotEnd' => $slot['end'],
                            'date' => $bookingDate,
                        ]) }}" class="slot-btn available">
                            Book Now →
                        </a>
                    @else
                        <span class="slot-btn full">
                            Slot Full
                        </span>
                    @endif
                </div>
            @endforeach
        </div>
    @endif

</div>

@endsection