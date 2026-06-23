@extends('dashboard.admin.layout')

@section('title', 'Restaurant Booking Settings')
@section('page-title', 'Restaurant Booking Settings')

@section('content')
<section class="settings-page">

    <div class="settings-hero">
        <div>
            <p>MGM One / {{ $hotel->name }} / Restaurant Settings</p>
            <h1>{{ $restaurant->name }}</h1>
            <span>Manage service-specific booking rules for Afternoon Tea and Dinner.</span>
        </div>

        <a href="{{ route('admin.hotels.restaurants.index', $hotel) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Back to Restaurants
        </a>
    </div>

    @if(session('success'))
        <div class="success-message">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="error-message">
            @foreach($errors->all() as $error)
                <p>• {{ $error }}</p>
            @endforeach
        </div>
    @endif

    <div class="settings-grid">

        @foreach([
            'afternoon_tea' => ['title' => 'Afternoon Tea', 'icon' => '☕', 'open' => '12:00', 'close' => '16:00', 'duration' => 30, 'interval' => 15, 'pax' => 40],
            'dinner' => ['title' => 'Dinner', 'icon' => '🍽', 'open' => '17:00', 'close' => '22:00', 'duration' => 45, 'interval' => 15, 'pax' => 60],
        ] as $type => $default)

            @php
                $setting = $settings[$type] ?? null;
            @endphp

            <div class="setting-card">
                <div class="setting-card-top">
                    <div class="setting-icon">{{ $default['icon'] }}</div>
                    <div>
                        <h2>{{ $default['title'] }}</h2>
                        <p>{{ $setting ? 'Configured' : 'Default values ready to save' }}</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.restaurants.settings.store', [$hotel, $restaurant]) }}">
                    @csrf

                    <input type="hidden" name="booking_type" value="{{ $type }}">

                    <div class="setting-form-grid">
                        <div class="form-group">
                            <label class="form-label">Opening Time</label>
                            <input type="time"
                                   name="opening_time"
                                   value="{{ old('opening_time', $setting->opening_time ?? $default['open']) }}"
                                   required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Closing Time</label>
                            <input type="time"
                                   name="closing_time"
                                   value="{{ old('closing_time', $setting->closing_time ?? $default['close']) }}"
                                   required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Slot Duration</label>
                            <input type="number"
                                   name="slot_duration_minutes"
                                   value="{{ old('slot_duration_minutes', $setting->slot_duration_minutes ?? $default['duration']) }}"
                                   min="5"
                                   required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Slot Interval</label>
                            <input type="number"
                                   name="slot_interval_minutes"
                                   value="{{ old('slot_interval_minutes', $setting->slot_interval_minutes ?? $default['interval']) }}"
                                   min="5"
                                   required>
                        </div>

                        <div class="form-group full">
                            <label class="form-label">Max Pax Per Slot</label>
                            <input type="number"
                                   name="max_pax_per_slot"
                                   value="{{ old('max_pax_per_slot', $setting->max_pax_per_slot ?? $default['pax']) }}"
                                   min="1"
                                   required>
                        </div>

                        <div class="toggle-box">
                            <label>
                                <input type="checkbox"
                                       name="allow_overbooking"
                                       value="1"
                                       {{ old('allow_overbooking', $setting->allow_overbooking ?? true) ? 'checked' : '' }}>
                                Allow Overbooking
                            </label>
                        </div>

                        <div class="toggle-box">
                            <label>
                                <input type="checkbox"
                                       name="is_active"
                                       value="1"
                                       {{ old('is_active', $setting->is_active ?? true) ? 'checked' : '' }}>
                                Active
                            </label>
                        </div>
                    </div>

                    <button class="btn btn-primary save-btn">
                        Save {{ $default['title'] }}
                    </button>
                </form>
            </div>
        @endforeach

    </div>
</section>

<style>
.settings-hero {
    background:
        radial-gradient(circle at 20% 20%, rgba(232,45,45,.28), transparent 35%),
        linear-gradient(135deg, #2a0606, #101010 70%);
    border: 2px solid var(--border);
    border-radius: 24px;
    padding: 28px;
    margin-bottom: 22px;
    display: flex;
    justify-content: space-between;
    gap: 18px;
    align-items: center;
    flex-wrap: wrap;
}

.settings-hero p {
    color: var(--primary);
    font-size: 12px;
    font-weight: 900;
    letter-spacing: 1.5px;
    text-transform: uppercase;
}

.settings-hero h1 {
    font-size: 34px;
    font-weight: 900;
    margin-top: 8px;
}

.settings-hero span {
    color: var(--text-muted);
    display: block;
    margin-top: 8px;
}

.success-message,
.error-message {
    padding: 14px 18px;
    border-radius: 12px;
    margin-bottom: 20px;
    font-weight: 800;
}

.success-message {
    background: rgba(34,197,94,.12);
    border: 1px solid rgba(34,197,94,.35);
    color: #4ade80;
}

.error-message {
    background: rgba(239,68,68,.12);
    border: 1px solid rgba(239,68,68,.35);
    color: #f87171;
}

.settings-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 24px;
}

.setting-card {
    background: linear-gradient(180deg, #171717, #101010);
    border: 2px solid var(--border);
    border-radius: 24px;
    padding: 24px;
    box-shadow: 0 25px 60px rgba(0,0,0,.35);
}

.setting-card-top {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 22px;
}

.setting-icon {
    width: 70px;
    height: 70px;
    border-radius: 22px;
    background: rgba(232,45,45,.12);
    border: 1px solid rgba(232,45,45,.35);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 34px;
}

.setting-card h2 {
    font-size: 25px;
    font-weight: 900;
}

.setting-card p {
    color: var(--text-muted);
    margin-top: 4px;
    font-weight: 700;
}

.setting-form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 18px;
}

.form-group.full {
    grid-column: 1 / -1;
}

.toggle-box {
    background: rgba(0,0,0,.25);
    border: 1px solid var(--border);
    border-radius: 14px;
    padding: 14px;
}

.toggle-box label {
    display: flex;
    align-items: center;
    gap: 10px;
    color: var(--text-muted);
    font-weight: 900;
}

.save-btn {
    width: 100%;
    margin-top: 22px;
}

@media(max-width: 1000px) {
    .settings-grid {
        grid-template-columns: 1fr;
    }
}

@media(max-width: 640px) {
    .setting-form-grid {
        grid-template-columns: 1fr;
    }

    .settings-hero h1 {
        font-size: 26px;
    }
}
</style>
@endsection