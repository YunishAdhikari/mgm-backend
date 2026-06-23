@extends('dashboard.reception.layout')

@section('content')

<style>
    :root {
        --primary: #8b5cf6;
        --secondary: #ec4899;
        --bg-card: #27272a;
        --bg-input: #1c1c1f;
        --text-main: #fafafa;
        --text-muted: #a1a1aa;
        --text-dim: #71717a;
        --border: #3f3f46;
        --success: #10b981;
        --warning: #f59e0b;
        --danger: #ef4444;
        --info: #3b82f6;
        --radius-lg: 1.5rem;
        --radius-md: 1rem;
        --glow-success: 0 0 20px rgba(16, 185, 129, 0.3);
        --glow-info: 0 0 20px rgba(59, 130, 246, 0.3);
    }

    .page-wrap { max-width: 1200px; margin: 0 auto; padding: 24px; }
    .card { background: var(--bg-card); border-radius: var(--radius-lg); padding: 28px; border: 1px solid var(--border); margin-bottom: 24px; }
    .header { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px; margin-bottom: 24px; }
    .title { font-size: 28px; font-weight: 800; color: var(--text-main); }
    .subtitle { color: var(--text-muted); font-weight: 500; margin-top: 4px; }
    .btn-back, .btn-cancel { background: var(--bg-input); color: var(--text-muted); padding: 12px 18px; border-radius: var(--radius-md); font-weight: 700; text-decoration: none; border: 1px solid var(--border); }
    .btn-back:hover, .btn-cancel:hover { background: var(--border); color: var(--text-main); }

    .info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 16px; margin-bottom: 24px; }
    .info-card { background: linear-gradient(135deg, rgba(139, 92, 246, 0.1), rgba(236, 72, 153, 0.05)); padding: 20px; border-radius: var(--radius-md); border: 1px solid var(--border); }
    .info-label { font-size: 11px; font-weight: 700; color: var(--text-dim); text-transform: uppercase; margin-bottom: 6px; }
    .info-value { font-size: 18px; font-weight: 800; color: var(--text-main); }
    .info-value.highlight { color: var(--info); }

    .alert { padding: 16px 20px; border-radius: var(--radius-md); margin-bottom: 24px; display: flex; align-items: center; gap: 12px; font-weight: 600; }
    .alert-warning { background: rgba(245, 158, 11, 0.15); border: 1px solid rgba(245, 158, 11, 0.3); color: #fbbf24; }

    .section-title { font-size: 18px; font-weight: 800; color: var(--text-main); margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
    .section-title::before { content: ''; width: 4px; height: 20px; background: linear-gradient(180deg, var(--info), #60a5fa); border-radius: 2px; }

    .table-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 16px; }
    .table-option { position: relative; }
    .table-option input { position: absolute; opacity: 0; width: 0; height: 0; }
    .table-option label { display: block; padding: 20px; border: 2px solid var(--border); border-radius: var(--radius-lg); cursor: pointer; transition: all 0.25s ease; text-align: center; }
    .table-option label:hover { border-color: var(--info); background: rgba(59, 130, 246, 0.1); }
    .table-option input:checked + label { border-color: var(--info); background: linear-gradient(135deg, rgba(59, 130, 246, 0.15), rgba(99, 102, 241, 0.1)); box-shadow: var(--glow-info); }
    .table-name { font-size: 18px; font-weight: 800; color: var(--text-main); margin-bottom: 8px; }
    .table-meta { font-size: 13px; color: var(--text-muted); font-weight: 600; }
    .table-status { display: inline-block; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; margin-top: 8px; }
    .table-status.available { background: rgba(16, 185, 129, 0.15); color: #6ee7b7; }
    .table-status.occupied { background: rgba(239, 68, 68, 0.15); color: #fca5a5; }

    .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; }
    .form-group { display: flex; flex-direction: column; }
    .form-label { font-size: 13px; font-weight: 700; color: var(--text-muted); margin-bottom: 8px; }
    .form-input, .form-textarea { padding: 14px 16px; background: var(--bg-input); border: 2px solid var(--border); border-radius: var(--radius-md); font-size: 15px; color: var(--text-main); }
    .form-input:focus, .form-textarea:focus { outline: none; border-color: var(--info); box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1); }
    .form-textarea { min-height: 100px; resize: vertical; }

    .voucher-card { background: var(--bg-input); border-radius: var(--radius-lg); padding: 24px; margin-top: 24px; border: 1px solid var(--border); }
    .voucher-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; }

    .checkbox-group { display: flex; align-items: center; gap: 10px; margin-top: 16px; padding: 14px; background: rgba(59, 130, 246, 0.1); border-radius: var(--radius-md); border: 1px solid rgba(59, 130, 246, 0.2); }
    .checkbox-group label { font-weight: 600; color: var(--info); margin: 0; cursor: pointer; }

    .submit-wrap { margin-top: 28px; display: flex; gap: 16px; flex-wrap: wrap; }
    .btn-submit { background: linear-gradient(135deg, var(--success), #059669); color: white; border: none; padding: 16px 32px; border-radius: var(--radius-md); font-weight: 700; font-size: 16px; cursor: pointer; box-shadow: var(--glow-success); }

    @media (max-width: 640px) {
        .header { flex-direction: column; align-items: flex-start; }
        .table-grid, .form-grid { grid-template-columns: 1fr; }
        .btn-submit, .btn-cancel { width: 100%; text-align: center; }
    }
</style>

<div class="page-wrap">
    <div class="card">

        <div class="header">
            <div>
                <h1 class="title">{{ $restaurant->name }} 📝</h1>
                <p class="subtitle">
                    New {{ $type === 'afternoon_tea' ? 'Afternoon Tea' : 'Dinner' }} Booking
                </p>
            </div>

            <a href="{{ route('reception.restaurant.bookings.slots', [
                'type' => $type,
                'restaurant_id' => $restaurant->id,
                'date' => $bookingDate,
            ]) }}" class="btn-back">
                ← Back
            </a>
        </div>

        <div class="info-grid">
            <div class="info-card">
                <div class="info-label">Restaurant</div>
                <div class="info-value">{{ $restaurant->name }}</div>
            </div>

            <div class="info-card">
                <div class="info-label">Booking Type</div>
                <div class="info-value highlight">
                    {{ $type === 'afternoon_tea' ? 'Afternoon Tea' : 'Dinner' }}
                </div>
            </div>

            <div class="info-card">
                <div class="info-label">Date</div>
                <div class="info-value">{{ $bookingDate }}</div>
            </div>

            <div class="info-card">
                <div class="info-label">Start Time</div>
                <div class="info-value">🕐 {{ $slotStart }}</div>
            </div>

            <div class="info-card">
                <div class="info-label">End Time</div>
                <div class="info-value">🕛 {{ $slotEnd }}</div>
            </div>
        </div>

        @if(session('message'))
            <div class="alert alert-warning">
                <span>⚠️</span>
                {{ session('message') }}
            </div>
        @endif

        <form method="POST" action="{{ route('reception.restaurant.bookings.store') }}">
            @csrf

            <input type="hidden" name="restaurant_id" value="{{ $restaurant->id }}">
            <input type="hidden" name="booking_type" value="{{ $type }}">
            <input type="hidden" name="booking_date" value="{{ $bookingDate }}">
            <input type="hidden" name="slot_start_time" value="{{ $slotStart }}">
            <input type="hidden" name="slot_end_time" value="{{ $slotEnd }}">

            <h3 class="section-title">Select Table</h3>

            <div class="table-grid">
                @forelse($tables as $table)
                    <div class="table-option">
                        <input type="radio"
                               name="restaurant_table_id"
                               value="{{ $table->id }}"
                               id="table_{{ $table->id }}"
                               required>

                        <label for="table_{{ $table->id }}">
                            <div class="table-name">{{ $table->table_name }}</div>
                            <div class="table-meta">👥 {{ $table->capacity }} pax</div>
                            <div class="table-status {{ $table->status === 'available' ? 'available' : 'occupied' }}">
                                {{ ucwords(str_replace('_', ' ', $table->status)) }}
                            </div>
                        </label>
                    </div>
                @empty
                    <p style="color: var(--text-muted);">No active tables found for this restaurant.</p>
                @endforelse
            </div>

            <div style="margin-top: 28px;">
                <h3 class="section-title">Guest Details</h3>

                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Guest Name *</label>
                        <input type="text" name="guest_name" class="form-input" value="{{ old('guest_name') }}" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Number of Guests *</label>
                        <input type="number" name="pax" class="form-input" min="1" value="{{ old('pax') }}" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Phone</label>
                        <input type="text" name="guest_phone" class="form-input" value="{{ old('guest_phone') }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="guest_email" class="form-input" value="{{ old('guest_email') }}">
                    </div>
                </div>

                <div class="voucher-card">
                    <h3 class="section-title" style="margin-top: 0;">Voucher Details</h3>

                    <div class="voucher-grid">
                        <div class="form-group">
                            <label class="form-label">Voucher Code</label>
                            <input type="text" name="voucher_code" class="form-input" value="{{ old('voucher_code') }}">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Discount Amount</label>
                            <input type="number" step="0.01" name="voucher_amount" class="form-input" value="{{ old('voucher_amount') }}">
                        </div>
                    </div>

                    <div class="form-group" style="margin-top: 16px;">
                        <label class="form-label">Voucher Note</label>
                        <textarea name="voucher_note" class="form-textarea">{{ old('voucher_note') }}</textarea>
                    </div>
                </div>

                <div class="form-group" style="margin-top: 20px;">
                    <label class="form-label">Special Request</label>
                    <textarea name="special_request" class="form-textarea">{{ old('special_request') }}</textarea>
                </div>

                <div class="checkbox-group">
                    <input type="checkbox" name="force_overbooking" value="1" id="force">
                    <label for="force">Proceed even if slot exceeds available capacity</label>
                </div>

                <div class="submit-wrap">
                    <button type="submit" class="btn-submit">
                        ✅ Create Booking
                    </button>

                    <a href="{{ route('reception.restaurant.bookings.slots', [
                        'type' => $type,
                        'restaurant_id' => $restaurant->id,
                        'date' => $bookingDate,
                    ]) }}" class="btn-cancel">
                        Cancel
                    </a>
                </div>
            </div>
        </form>

    </div>
</div>

@endsection