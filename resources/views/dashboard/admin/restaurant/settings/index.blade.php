@extends('dashboard.admin.layout')

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
    @import url('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css');

    :root {
        --rs-bg: #09090b;
        --rs-card: #18181b;
        --rs-card-2: #1c1c1f;
        --rs-card-3: #27272a;
        --rs-border: #3f3f46;
        --rs-primary: #8b5cf6;
        --rs-primary-light: #a78bfa;
        --rs-secondary: #ec4899;
        --rs-text: #fafafa;
        --rs-muted: #a1a1aa;
        --rs-dim: #71717a;
        --rs-green: #22c55e;
        --rs-red: #fb7185;
        --rs-shadow: 0 0 20px rgba(139, 92, 246, 0.30);
    }

    .restaurant-settings-page,
    .restaurant-settings-page * {
        box-sizing: border-box;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .restaurant-settings-page {
        color: var(--rs-text);
        padding-bottom: 30px;
        animation: rsFadeIn .45s ease both;
    }

    .rs-container {
        width: 100%;
        max-width: 1180px;
        margin: 0 auto;
    }

    .rs-hero {
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(139, 92, 246, .25);
        border-radius: 24px;
        padding: 26px;
        background:
            radial-gradient(circle at top left, rgba(139, 92, 246, .24), transparent 34%),
            radial-gradient(circle at bottom right, rgba(236, 72, 153, .18), transparent 36%),
            linear-gradient(135deg, rgba(24, 24, 27, .96), rgba(9, 9, 11, .96));
        box-shadow: 0 20px 60px rgba(0, 0, 0, .28);
        margin-bottom: 22px;
    }

    .rs-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,.06), transparent);
        transform: translateX(-100%);
        animation: rsSweep 6s ease-in-out infinite;
        pointer-events: none;
    }

    .rs-hero-content {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
    }

    .rs-title-wrap {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .rs-title-icon {
        width: 58px;
        height: 58px;
        flex: 0 0 58px;
        border-radius: 18px;
        display: grid;
        place-items: center;
        background: linear-gradient(135deg, var(--rs-primary), var(--rs-secondary));
        box-shadow: var(--rs-shadow);
        color: white;
        font-size: 25px;
    }

    .rs-title {
        margin: 0;
        font-size: clamp(1.75rem, 3vw, 2.45rem);
        line-height: 1.05;
        font-weight: 800;
        letter-spacing: -0.04em;
        background: linear-gradient(135deg, #fafafa 0%, #c4b5fd 52%, #f0abfc 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .rs-subtitle {
        margin: 8px 0 0;
        color: var(--rs-muted);
        font-size: .98rem;
        line-height: 1.55;
    }

    .rs-badge {
        display: inline-flex;
        align-items: center;
        gap: 9px;
        padding: 11px 16px;
        border-radius: 999px;
        border: 1px solid rgba(139, 92, 246, .34);
        background: rgba(139, 92, 246, .12);
        color: #ddd6fe;
        font-weight: 800;
        font-size: .83rem;
        white-space: nowrap;
    }

    .rs-stats-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 22px;
    }

    .rs-stat-card {
        background: linear-gradient(180deg, rgba(39,39,42,.88), rgba(24,24,27,.95));
        border: 1px solid var(--rs-border);
        border-radius: 20px;
        padding: 18px;
        display: flex;
        align-items: center;
        gap: 14px;
        box-shadow: 0 16px 45px rgba(0,0,0,.22);
        transition: .25s ease;
    }

    .rs-stat-card:hover {
        transform: translateY(-3px);
        border-color: rgba(139, 92, 246, .52);
        box-shadow: var(--rs-shadow);
    }

    .rs-stat-icon {
        width: 46px;
        height: 46px;
        border-radius: 15px;
        display: grid;
        place-items: center;
        background: rgba(139, 92, 246, .13);
        color: var(--rs-primary-light);
        font-size: 21px;
    }

    .rs-stat-label {
        color: var(--rs-muted);
        font-size: .78rem;
        text-transform: uppercase;
        letter-spacing: .08em;
        font-weight: 800;
        margin-bottom: 4px;
    }

    .rs-stat-value {
        font-size: 1.4rem;
        font-weight: 800;
        color: var(--rs-text);
        line-height: 1;
    }

    .rs-success {
        border: 1px solid rgba(34, 197, 94, .32);
        background: rgba(34, 197, 94, .10);
        color: #86efac;
        padding: 14px 16px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 22px;
        font-weight: 700;
    }

    .rs-service-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 22px;
        align-items: start;
    }

    .rs-service-card {
        overflow: hidden;
        border-radius: 24px;
        background: linear-gradient(180deg, rgba(24,24,27,.96), rgba(9,9,11,.96));
        border: 1px solid var(--rs-border);
        box-shadow: 0 18px 55px rgba(0,0,0,.32);
        animation: rsCardIn .55s ease both;
    }

    .rs-service-card:nth-child(2) { animation-delay: .08s; }

    .rs-card-top {
        position: relative;
        padding: 22px;
        min-height: 128px;
        overflow: hidden;
    }

    .rs-card-top.tea {
        background: linear-gradient(135deg, rgba(245,158,11,.95), rgba(236,72,153,.86));
    }

    .rs-card-top.dinner {
        background: linear-gradient(135deg, rgba(139,92,246,.96), rgba(236,72,153,.85));
    }

    .rs-card-top::after {
        content: '';
        position: absolute;
        width: 190px;
        height: 190px;
        right: -62px;
        top: -80px;
        border-radius: 999px;
        background: rgba(255,255,255,.16);
    }

    .rs-service-head {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 14px;
    }

    .rs-service-meta {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .rs-service-icon {
        width: 52px;
        height: 52px;
        flex: 0 0 52px;
        border-radius: 17px;
        display: grid;
        place-items: center;
        background: rgba(255,255,255,.18);
        border: 1px solid rgba(255,255,255,.18);
        color: white;
        font-size: 23px;
        backdrop-filter: blur(10px);
    }

    .rs-service-title {
        margin: 0;
        color: white;
        font-size: 1.35rem;
        font-weight: 800;
        letter-spacing: -.02em;
    }

    .rs-service-desc {
        margin: 5px 0 0;
        color: rgba(255,255,255,.78);
        font-size: .86rem;
        font-weight: 600;
    }

    .rs-status-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 12px;
        border-radius: 999px;
        background: rgba(0,0,0,.26);
        color: white;
        font-weight: 800;
        font-size: .78rem;
        white-space: nowrap;
    }

    .rs-dot {
        width: 8px;
        height: 8px;
        border-radius: 999px;
        background: var(--rs-green);
        box-shadow: 0 0 0 5px rgba(34,197,94,.14);
        animation: rsPulse 1.7s infinite;
    }

    .rs-dot.off {
        background: var(--rs-red);
        box-shadow: 0 0 0 5px rgba(251,113,133,.14);
    }

    .rs-card-body {
        padding: 22px;
    }

    .rs-form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
    }

    .rs-field label {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 9px;
        color: var(--rs-muted);
        font-size: .84rem;
        font-weight: 800;
    }

    .rs-field label i { color: var(--rs-primary-light); }

    .rs-input {
        width: 100%;
        min-height: 50px;
        background: var(--rs-card-2);
        border: 1px solid var(--rs-border);
        border-radius: 15px;
        color: var(--rs-text);
        padding: 0 14px;
        font-size: .98rem;
        font-weight: 700;
        outline: none;
        transition: .22s ease;
        color-scheme: dark;
    }

    .rs-input:focus {
        border-color: var(--rs-primary);
        box-shadow: 0 0 0 4px rgba(139,92,246,.18), var(--rs-shadow);
        background: #202024;
    }

    .rs-options {
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid var(--rs-border);
    }

    .rs-options-title {
        margin: 0 0 14px;
        display: flex;
        align-items: center;
        gap: 9px;
        color: var(--rs-text);
        font-size: 1rem;
        font-weight: 800;
    }

    .rs-options-title i { color: var(--rs-primary-light); }

    .rs-options-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 12px;
    }

    .rs-option {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        min-height: 70px;
        padding: 14px 16px;
        background: rgba(39,39,42,.66);
        border: 1px solid var(--rs-border);
        border-radius: 18px;
        transition: .22s ease;
    }

    .rs-option:hover {
        border-color: rgba(139,92,246,.5);
        background: rgba(39,39,42,.95);
    }

    .rs-option strong {
        display: block;
        color: var(--rs-text);
        font-size: .94rem;
        margin-bottom: 4px;
    }

    .rs-option span {
        display: block;
        color: var(--rs-muted);
        font-size: .8rem;
        line-height: 1.35;
    }

    .rs-checkbox {
        appearance: none;
        width: 24px;
        height: 24px;
        flex: 0 0 24px;
        border-radius: 8px;
        border: 2px solid var(--rs-border);
        background: var(--rs-card-2);
        cursor: pointer;
        position: relative;
        transition: .2s ease;
    }

    .rs-checkbox:checked {
        border-color: transparent;
        background: linear-gradient(135deg, var(--rs-primary), var(--rs-secondary));
        box-shadow: var(--rs-shadow);
    }

    .rs-checkbox:checked::after {
        content: '✓';
        position: absolute;
        inset: 0;
        display: grid;
        place-items: center;
        color: white;
        font-weight: 900;
        font-size: 14px;
    }

    .rs-toggle {
        position: relative;
        display: inline-block;
        width: 56px;
        height: 30px;
        flex: 0 0 56px;
    }

    .rs-toggle input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .rs-slider {
        position: absolute;
        cursor: pointer;
        inset: 0;
        border-radius: 999px;
        background: var(--rs-border);
        transition: .25s ease;
    }

    .rs-slider::before {
        content: '';
        position: absolute;
        width: 22px;
        height: 22px;
        left: 4px;
        top: 4px;
        border-radius: 999px;
        background: white;
        transition: .25s ease;
        box-shadow: 0 6px 16px rgba(0,0,0,.35);
    }

    .rs-toggle input:checked + .rs-slider {
        background: linear-gradient(135deg, var(--rs-primary), var(--rs-secondary));
        box-shadow: var(--rs-shadow);
    }

    .rs-toggle input:checked + .rs-slider::before {
        transform: translateX(26px);
    }

    .rs-action-bar {
        margin-top: 20px;
        display: flex;
        justify-content: flex-end;
    }

    .rs-save-btn {
        border: none;
        min-height: 50px;
        padding: 0 22px;
        border-radius: 16px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        color: white;
        background: linear-gradient(135deg, var(--rs-primary), var(--rs-secondary));
        font-size: .95rem;
        font-weight: 900;
        cursor: pointer;
        box-shadow: var(--rs-shadow);
        transition: .22s ease;
    }

    .rs-save-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 0 28px rgba(139,92,246,.45);
    }

    .rs-save-btn:active { transform: translateY(0); }

    @keyframes rsFadeIn {
        from { opacity: 0; transform: translateY(8px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes rsCardIn {
        from { opacity: 0; transform: translateY(14px) scale(.98); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }

    @keyframes rsPulse {
        0% { transform: scale(1); opacity: 1; }
        50% { transform: scale(.78); opacity: .65; }
        100% { transform: scale(1); opacity: 1; }
    }

    @keyframes rsSweep {
        0%, 70% { transform: translateX(-120%); }
        100% { transform: translateX(120%); }
    }

    @media (max-width: 1180px) {
        .rs-service-grid { grid-template-columns: 1fr; }
    }

    @media (max-width: 768px) {
        .rs-hero { padding: 20px; border-radius: 20px; }
        .rs-hero-content { align-items: flex-start; flex-direction: column; }
        .rs-stats-grid { grid-template-columns: 1fr; }
        .rs-form-grid { grid-template-columns: 1fr; }
        .rs-service-head { flex-direction: column; }
        .rs-status-pill { align-self: flex-start; }
        .rs-action-bar { justify-content: stretch; }
        .rs-save-btn { width: 100%; }
    }
</style>
@endsection

@section('content')
@php
    $teaSetting = $settings['afternoon_tea'] ?? null;
    $dinnerSetting = $settings['dinner'] ?? null;
    $teaActive = old('is_active', $teaSetting?->is_active ?? false);
    $dinnerActive = old('is_active', $dinnerSetting?->is_active ?? false);
    $totalCapacity = ($teaSetting?->max_pax_per_slot ?? 0) + ($dinnerSetting?->max_pax_per_slot ?? 0);
@endphp

<div class="restaurant-settings-page">
    <div class="rs-container">
        <div class="rs-hero">
            <div class="rs-hero-content">
                <div class="rs-title-wrap">
                    <div class="rs-title-icon">
                        <i class="bi bi-sliders2-vertical"></i>
                    </div>
                    <div>
                        <h1 class="rs-title">Restaurant Settings</h1>
                        <p class="rs-subtitle">Manage service timings, booking capacity and restaurant availability from one clean admin panel.</p>
                    </div>
                </div>

                <div class="rs-badge">
                    <i class="bi bi-shield-check"></i>
                    Admin Control Panel
                </div>
            </div>
        </div>

        <div class="rs-stats-grid">
            <div class="rs-stat-card">
                <div class="rs-stat-icon"><i class="bi bi-cup-hot-fill"></i></div>
                <div>
                    <div class="rs-stat-label">Afternoon Tea</div>
                    <div class="rs-stat-value">{{ $teaActive ? 'Active' : 'Off' }}</div>
                </div>
            </div>

            <div class="rs-stat-card">
                <div class="rs-stat-icon"><i class="bi bi-moon-stars-fill"></i></div>
                <div>
                    <div class="rs-stat-label">Dinner Service</div>
                    <div class="rs-stat-value">{{ $dinnerActive ? 'Active' : 'Off' }}</div>
                </div>
            </div>

            <div class="rs-stat-card">
                <div class="rs-stat-icon"><i class="bi bi-people-fill"></i></div>
                <div>
                    <div class="rs-stat-label">Total Slot Capacity</div>
                    <div class="rs-stat-value">{{ $totalCapacity }} Guests</div>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="rs-success">
                <i class="bi bi-check-circle-fill"></i>
                {{ session('success') }}
            </div>
        @endif

        <div class="rs-service-grid">
            @foreach([
                'afternoon_tea' => [
                    'label' => 'Afternoon Tea',
                    'icon' => 'bi-cup-hot-fill',
                    'class' => 'tea',
                    'description' => 'Configure afternoon tea booking slots'
                ],
                'dinner' => [
                    'label' => 'Dinner Service',
                    'icon' => 'bi-moon-stars-fill',
                    'class' => 'dinner',
                    'description' => 'Configure evening dinner reservations'
                ]
            ] as $type => $data)
                @php
                    $setting = $settings[$type] ?? null;
                    $isActive = old('is_active', $setting?->is_active ?? false);
                @endphp

                <div class="rs-service-card">
                    <div class="rs-card-top {{ $data['class'] }}">
                        <div class="rs-service-head">
                            <div class="rs-service-meta">
                                <div class="rs-service-icon">
                                    <i class="bi {{ $data['icon'] }}"></i>
                                </div>
                                <div>
                                    <h2 class="rs-service-title">{{ $data['label'] }}</h2>
                                    <p class="rs-service-desc">{{ $data['description'] }}</p>
                                </div>
                            </div>

                            <div class="rs-status-pill">
                                <span class="rs-dot {{ $isActive ? '' : 'off' }}"></span>
                                {{ $isActive ? 'Active' : 'Inactive' }}
                            </div>
                        </div>
                    </div>

                    <div class="rs-card-body">
                        <form method="POST" action="{{ route('restaurant.settings.store') }}">
                            @csrf
                            <input type="hidden" name="booking_type" value="{{ $type }}">

                            <div class="rs-form-grid">
                                <div class="rs-field">
                                    <label for="{{ $type }}_opening_time">
                                        <i class="bi bi-clock"></i>
                                        Opening Time
                                    </label>
                                    <input id="{{ $type }}_opening_time" type="time" name="opening_time"
                                        value="{{ old('opening_time', $setting?->opening_time) }}"
                                        class="rs-input" required>
                                </div>

                                <div class="rs-field">
                                    <label for="{{ $type }}_closing_time">
                                        <i class="bi bi-clock-history"></i>
                                        Closing Time
                                    </label>
                                    <input id="{{ $type }}_closing_time" type="time" name="closing_time"
                                        value="{{ old('closing_time', $setting?->closing_time) }}"
                                        class="rs-input" required>
                                </div>

                                <div class="rs-field">
                                    <label for="{{ $type }}_slot_duration">
                                        <i class="bi bi-hourglass-split"></i>
                                        Slot Duration
                                    </label>
                                    <input id="{{ $type }}_slot_duration" type="number" name="slot_duration_minutes"
                                        value="{{ old('slot_duration_minutes', $setting?->slot_duration_minutes ?? 30) }}"
                                        class="rs-input" min="5" required>
                                </div>

                                <div class="rs-field">
                                    <label for="{{ $type }}_max_pax">
                                        <i class="bi bi-people"></i>
                                        Max Guests Per Slot
                                    </label>
                                    <input id="{{ $type }}_max_pax" type="number" name="max_pax_per_slot"
                                        value="{{ old('max_pax_per_slot', $setting?->max_pax_per_slot ?? 1) }}"
                                        class="rs-input" min="1" required>
                                </div>
                            </div>

                            <div class="rs-options">
                                <h3 class="rs-options-title">
                                    <i class="bi bi-calendar-check"></i>
                                    Booking Options
                                </h3>

                                <div class="rs-options-grid">
                                    <label class="rs-option" for="{{ $type }}_allow_overbooking">
                                        <span>
                                            <strong>Allow Overbooking</strong>
                                            <span>Let staff accept bookings even when the slot is full.</span>
                                        </span>
                                        <input id="{{ $type }}_allow_overbooking" type="checkbox" name="allow_overbooking"
                                            value="1" {{ old('allow_overbooking', $setting?->allow_overbooking ?? true) ? 'checked' : '' }}
                                            class="rs-checkbox">
                                    </label>

                                    <label class="rs-option" for="{{ $type }}_is_active">
                                        <span>
                                            <strong>Enable Booking</strong>
                                            <span>Show this service as available for restaurant reservations.</span>
                                        </span>
                                        <span class="rs-toggle">
                                            <input id="{{ $type }}_is_active" type="checkbox" name="is_active"
                                                value="1" {{ old('is_active', $setting?->is_active ?? false) ? 'checked' : '' }}>
                                            <span class="rs-slider"></span>
                                        </span>
                                    </label>
                                </div>
                            </div>

                            <div class="rs-action-bar">
                                <button type="submit" class="rs-save-btn">
                                    <i class="bi bi-check2-circle"></i>
                                    Save {{ $data['label'] }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
