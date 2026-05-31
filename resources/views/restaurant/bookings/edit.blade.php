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

    .page-wrap { 
        padding: 24px; 
        max-width: 900px;
        background: var(--bg-page);
    }

    .title { 
        font-size: 28px; 
        font-weight: 900; 
        color: var(--text-main); 
        margin-bottom: 6px; 
    }

    .subtitle { 
        color: var(--text-muted); 
        margin-bottom: 24px; 
    }

    .card-box { 
        background: var(--bg-card); 
        padding: 24px; 
        border-radius: 20px; 
        box-shadow: 0 8px 30px rgba(0,0,0,0.3); 
        margin-bottom: 20px;
        border: 1px solid var(--border);
    }

    .card-title { 
        font-size: 18px; 
        font-weight: 800; 
        color: var(--text-main); 
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 1px solid var(--border);
    }

    .grid { 
        display: grid; 
        grid-template-columns: repeat(2, 1fr);
        gap: 16px; 
    }

    label.field-label { 
        font-weight: 700; 
        color: var(--text-muted); 
        margin-bottom: 8px; 
        display: block;
        font-size: 13px;
    }

    .full-label {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 12px;
    }

    .full-label input {
        width: auto;
        margin: 0;
    }

    input, select, textarea { 
        width: 100%; 
        padding: 12px 14px; 
        border: 2px solid var(--border); 
        border-radius: 12px; 
        background: var(--bg-input); 
        color: var(--text-main);
        font-size: 14px;
        transition: all 0.3s ease;
    }

    input:focus, select:focus, textarea:focus { 
        outline: none; 
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(139, 92, 246, 0.15);
    }

    textarea { 
        min-height: 100px; 
        resize: vertical;
    }

    .btn { 
        padding: 14px 24px; 
        border-radius: 14px; 
        text-decoration: none; 
        font-weight: 800; 
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border: none; 
        cursor: pointer; 
        transition: all 0.3s ease;
    }

    .btn-dark { 
        background: var(--bg-input); 
        color: var(--text-main);
        border: 1px solid var(--border);
    }

    .btn-dark:hover { 
        border-color: var(--primary); 
    }

    .btn-main { 
        background: linear-gradient(135deg, var(--primary), #ec4899);
        color: white;
        box-shadow: 0 4px 15px rgba(139, 92, 246, 0.3);
    }

    .btn-main:hover { 
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(139, 92, 246, 0.4);
    }

    .actions {
        display: flex;
        gap: 12px;
        margin-top: 24px;
    }

    .warning { 
        background: rgba(245, 158, 11, 0.15); 
        border: 1px solid rgba(245, 158, 11, 0.3);
        color: #fbbf24; 
        padding: 14px 18px; 
        border-radius: 12px; 
        margin-bottom: 18px; 
        font-weight: 700; 
    }

    .error-box { 
        background: rgba(239, 68, 68, 0.15); 
        border: 1px solid rgba(239, 68, 68, 0.3);
        color: #fca5a5; 
        padding: 14px 18px; 
        border-radius: 12px; 
        margin-bottom: 18px; 
        font-weight: 700; 
    }

    /* Dark Floor Plan */
    .floor-plan-container {
        margin-top: 16px;
    }
    
    .floor-plan-label {
        font-size: 14px;
        font-weight: 700;
        color: var(--text-muted);
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .floor-plan {
        position: relative;
        min-width: 800px;
        height: 450px;
        background: 
            linear-gradient(90deg, rgba(63, 63, 70, 0.5) 1px, transparent 1px),
            linear-gradient(rgba(63, 63, 70, 0.5) 1px, transparent 1px),
            var(--bg-input);
        background-size: 40px 40px;
        border: 3px solid var(--primary);
        border-radius: 20px;
        overflow: auto;
    }

    .floor-table {
        position: absolute;
        width: 85px;
        height: 85px;
        background: linear-gradient(145deg, #27272a, #3f3f46);
        border: 3px solid var(--primary);
        color: var(--text-main);
        border-radius: 16px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        font-weight: 900;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 6px 15px rgba(0,0,0,0.3);
    }

    .floor-table input {
        display: none;
    }

    .floor-table strong {
        font-size: 15px;
    }

    .floor-table span {
        font-size: 11px;
        color: var(--text-muted);
        margin-top: 2px;
    }

    .floor-table:hover {
        transform: scale(1.05);
        border-color: var(--primary-hover);
    }

    .floor-table.booked {
        background: linear-gradient(145deg, #3f1111, #7f1d1d);
        border-color: #ef4444;
        color: #fca5a5;
        cursor: not-allowed;
        opacity: 0.7;
    }

    .floor-table.booked:hover {
        transform: none;
    }

    .floor-table.selected {
        background: linear-gradient(145deg, #1e3a8a, #3b82f6);
        border-color: #60a5fa;
        color: white;
        transform: scale(1.08);
        box-shadow: 0 0 20px rgba(59, 130, 246, 0.4);
    }

    .floor-table.selected span {
        color: rgba(255,255,255,0.8);
    }

    /* Legend */
    .floor-legend {
        display: flex;
        gap: 16px;
        margin-top: 12px;
        flex-wrap: wrap;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        color: var(--text-muted);
    }

    .legend-dot {
        width: 14px;
        height: 14px;
        border-radius: 4px;
    }

    .legend-available {
        background: linear-gradient(145deg, #27272a, #3f3f46);
        border: 2px solid var(--primary);
    }

    .legend-booked {
        background: linear-gradient(145deg, #3f1111, #7f1d1d);
        border: 2px solid #ef4444;
    }

    .legend-selected {
        background: linear-gradient(145deg, #1e3a8a, #3b82f6);
        border: 2px solid #60a5fa;
    }

    @media (max-width: 700px) {
        .grid { grid-template-columns: 1fr; }
        
        .actions {
            flex-direction: column;
        }
        
        .btn {
            width: 100%;
            justify-content: center;
        }

        .floor-plan {
            min-width: 100%;
            height: 350px;
        }

        .floor-table {
            width: 70px;
            height: 70px;
        }
    }
</style>

<div class="page-wrap">
    <h1 class="title">Edit Booking</h1>
    <p class="subtitle">Update guest details, date, time, table, pax, voucher and status.</p>

    @if ($errors->any())
        <div class="error-box">
            @foreach ($errors->all() as $error)
                <div>• {{ $error }}</div>
            @endforeach
        </div>
    @endif

    @if(session('message'))
        <div class="warning">
            {{ session('message') }}

            @if(session('available_pax') !== null)
                <br><br>
                Available pax: {{ session('available_pax') }}
            @endif
        </div>
    @endif

    <form method="POST" action="{{ route('reception.restaurant.bookings.update', $booking) }}">
        @csrf
        @method('PUT')

        <div class="card-box">
            <h3 class="card-title">Booking Date & Time</h3>

            <div class="grid">
                <div>
                    <label class="field-label">Booking Date</label>
                    <input type="date"
                           name="booking_date"
                           value="{{ old('booking_date', \Carbon\Carbon::parse($booking->booking_date)->format('Y-m-d')) }}"
                           required>
                </div>

                <div>
                    <label class="field-label">Slot Start Time</label>
                    <input type="time"
                           name="slot_start_time"
                           value="{{ old('slot_start_time', \Carbon\Carbon::parse($booking->slot_start_time)->format('H:i')) }}"
                           required>
                </div>

                <div>
                    <label class="field-label">Slot End Time</label>
                    <input type="time"
                           name="slot_end_time"
                           value="{{ old('slot_end_time', \Carbon\Carbon::parse($booking->slot_end_time)->format('H:i')) }}"
                           required>
                </div>

                <div>
                    <label class="field-label">Status</label>
                    <select name="status" required>
                        @foreach(['confirmed', 'seated', 'completed', 'cancelled', 'no_show'] as $status)
                            <option value="{{ $status }}" {{ old('status', $booking->status) == $status ? 'selected' : '' }}>
                                {{ ucwords(str_replace('_', ' ', $status)) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="card-box">
            <h3 class="card-title">Guest & Table Details</h3>

            <div class="grid">
                <div>
                    <label class="field-label">Guest Name</label>
                    <input type="text" name="guest_name" value="{{ old('guest_name', $booking->guest_name) }}" required>
                </div>

                <div>
                    <label class="field-label">Number of Pax</label>
                    <input type="number" name="pax" min="1" value="{{ old('pax', $booking->pax) }}" required>
                </div>

                <div>
                    <label class="field-label">Phone</label>
                    <input type="text" name="guest_phone" value="{{ old('guest_phone', $booking->guest_phone) }}">
                </div>

                <div>
                    <label class="field-label">Email</label>
                    <input type="email" name="guest_email" value="{{ old('guest_email', $booking->guest_email) }}">
                </div>

                <div class="full-label">
                    <input type="checkbox" name="force_overbooking" value="1">
                    <label style="margin:0; color:var(--text-muted);">Allow overbooking</label>
                </div>
            </div>

            <div class="floor-plan-container">
                <div class="floor-plan-label">
                    <i class="fas fa-layer-group"></i>
                    Select Table from Floor Plan
                </div>

                <div class="floor-plan">
                    @foreach($tables as $table)
                        @php
                            $isBooked = in_array($table->id, $bookedTableIds ?? []);
                            $isSelected = old('restaurant_table_id', $booking->restaurant_table_id) == $table->id;
                        @endphp

                        <label class="floor-table {{ $isBooked ? 'booked' : '' }} {{ $isSelected ? 'selected' : '' }}"
                               style="
                                    left: {{ 30 + ($table->position_x * 110) }}px;
                                    top: {{ 30 + ($table->position_y * 110) }}px;
                               ">

                            <input type="radio"
                                   name="restaurant_table_id"
                                   value="{{ $table->id }}"
                                   {{ $isSelected ? 'checked' : '' }}
                                   {{ $isBooked ? 'disabled' : '' }}
                                   required>

                            <strong>{{ $table->table_name }}</strong>
                            <span>{{ $table->capacity }} Pax</span>
                        </label>
                    @endforeach
                </div>

                <div class="floor-legend">
                    <div class="legend-item">
                        <span class="legend-dot legend-available"></span> Available
                    </div>
                    <div class="legend-item">
                        <span class="legend-dot legend-booked"></span> Booked
                    </div>
                    <div class="legend-item">
                        <span class="legend-dot legend-selected"></span> Selected
                    </div>
                </div>
            </div>
        </div>

        <div class="card-box">
            <h3 class="card-title">Voucher Details</h3>

            <div class="grid">
                <div>
                    <label class="field-label">Voucher Code</label>
                    <input type="text" name="voucher_code" value="{{ old('voucher_code', $booking->voucher_code) }}">
                </div>

                <div>
                    <label class="field-label">Voucher Amount</label>
                    <input type="number" step="0.01" name="voucher_amount" value="{{ old('voucher_amount', $booking->voucher_amount) }}">
                </div>
            </div>

            <div style="margin-top:16px;">
                <label class="field-label">Voucher Note</label>
                <textarea name="voucher_note">{{ old('voucher_note', $booking->voucher_note) }}</textarea>
            </div>
        </div>

        <div class="card-box">
            <h3 class="card-title">Special Request</h3>
            <textarea name="special_request" placeholder="Any special requests from the guest...">{{ old('special_request', $booking->special_request) }}</textarea>
        </div>

        <div class="actions">
            <a href="{{ route('reception.restaurant.bookings.list') }}" class="btn btn-dark">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            <button type="submit" class="btn btn-main">
                <i class="fas fa-check"></i> Update Booking
            </button>
        </div>
    </form>
</div>
@endsection