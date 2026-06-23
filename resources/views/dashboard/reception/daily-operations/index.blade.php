@extends('dashboard.reception.layout')

@section('title', 'Daily Operations')
@section('content')

<section class="daily-ops-page">

    <div class="ops-hero">
        <div class="hero-text">
            <p class="breadcrumb">MGM One &middot; Reception</p>
            <h1>Daily Operations Forecast</h1>
            <span class="subtitle">Enter morning and evening snapshots to maintain crisp visibility over hotel occupancy, room allocations, and absolute physical inventory.</span>
        </div>

        <form method="GET" class="date-filter">
            <div class="input-with-icon">
                <i class="fas fa-calendar-alt"></i>
                <input type="date" name="date" value="{{ $date }}">
            </div>
            <button type="submit" class="btn-filter">
                <i class="fas fa-sync-alt"></i>
                <span>Load Snapshot</span>
            </button>
        </form>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            <i class="fas fa-exclamation-triangle"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-error batch-errors">
            <div class="errors-header">
                <i class="fas fa-ban"></i>
                <strong>Validation conflicts detected:</strong>
            </div>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @php
        $amOcc = $am?->occupancy_percentage ?? 0;
        $pmOcc = $pm?->occupancy_percentage ?? 0;
        $pickup = ($am && $pm) ? ($pm->arrivals - $am->arrivals) : null;
    @endphp

    <div class="summary-grid">
        <div class="summary-card text-card">
            <div class="card-icon"><i class="fas fa-bed"></i></div>
            <div class="card-data">
                <strong class="stat-counter">{{ $totalRooms }}</strong>
                <span>Active Physical Inventory</span>
            </div>
        </div>

        <div class="summary-card am-theme-card">
            <div class="card-icon"><i class="fas fa-sun"></i></div>
            <div class="card-data">
                <strong class="stat-counter" id="summary-am-occ">{{ $am ? $amOcc.'%' : '—' }}</strong>
                <span>AM Occupancy Ratio</span>
            </div>
        </div>

        <div class="summary-card pm-theme-card">
            <div class="card-icon"><i class="fas fa-moon"></i></div>
            <div class="card-data">
                <strong class="stat-counter" id="summary-pm-occ">{{ $pm ? $pmOcc.'%' : '—' }}</strong>
                <span>PM Occupancy Ratio</span>
            </div>
        </div>

        <div class="summary-card {{ $pickup !== null && $pickup >= 0 ? 'state-positive' : 'state-negative' }}" id="summary-pickup-card">
            <div class="card-icon"><i class="fas fa-chart-line"></i></div>
            <div class="card-data">
                <strong class="stat-counter" id="summary-pickup">
                    @if($pickup === null)
                        —
                    @elseif($pickup > 0)
                        +{{ $pickup }}
                    @else
                        {{ $pickup }}
                    @endif
                </strong>
                <span>Net Intake Pick-up</span>
            </div>
        </div>
    </div>

    <div class="snapshot-grid">

        @foreach(['AM' => $am, 'PM' => $pm] as $snapshot => $record)
            @php
                $availableRooms = $record?->available_rooms ?? max(0, $totalRooms);
                $occupiedRooms = $record?->occupied_rooms ?? 0;
                $occupancy = $record?->occupancy_percentage ?? 0;
                $isAm = $snapshot === 'AM';
            @endphp

            <div class="snapshot-card {{ $isAm ? 'card-theme-am' : 'card-theme-pm' }}" data-snapshot="{{ $snapshot }}">
                
                <div class="snapshot-header">
                    <div class="header-title-block">
                        <span class="context-tag">{{ $isAm ? 'Morning Standard Check' : 'Evening Settlement Check' }}</span>
                        <h2>{{ $snapshot }} Operations</h2>
                    </div>

                    @if($record?->is_finalised)
                        <span class="status-badge state-finalised">
                            <i class="fas fa-lock"></i>
                            <span>Finalised &amp; Locked</span>
                        </span>
                    @else
                        <span class="status-badge state-editable">
                            <i class="fas fa-pen-nib"></i>
                            <span>Open for Entry</span>
                        </span>
                    @endif
                </div>

                <div class="calc-panel">
                    <div class="calc-box">
                        <span class="calc-label">Calculated Live Available</span>
                        <strong class="calc-val calc-available">{{ $availableRooms }}</strong>
                    </div>

                    <div class="calc-box">
                        <span class="calc-label">Calculated Live Occupied</span>
                        <strong class="calc-val calc-occupied">{{ $occupiedRooms }}</strong>
                    </div>

                    <div class="calc-box highlight-box">
                        <span class="calc-label">Target Occupancy</span>
                        <strong class="calc-val calc-occupancy">{{ $record ? $occupancy.'%' : '—' }}</strong>
                    </div>
                </div>

                <form method="POST" action="{{ route('reception.daily-operations.store') }}" class="ops-form">
                    @csrf

                    <input type="hidden" name="operation_date" value="{{ $date }}">
                    <input type="hidden" name="snapshot" value="{{ $snapshot }}">

                    <div class="form-grid">
                        <div class="form-group">
                            <label>Arrivals <span class="req-star">*</span></label>
                            <div class="input-wrapper">
                                <input type="number" name="arrivals" min="0" required class="input-arrivals"
                                       value="{{ old('arrivals', $record->arrivals ?? 0) }}"
                                       {{ $record?->is_finalised ? 'readonly' : '' }}>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Departures <span class="req-star">*</span></label>
                            <div class="input-wrapper">
                                <input type="number" name="departures" min="0" required class="input-departures"
                                       value="{{ old('departures', $record->departures ?? 0) }}"
                                       {{ $record?->is_finalised ? 'readonly' : '' }}>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Stayovers <span class="req-star">*</span></label>
                            <div class="input-wrapper">
                                <input type="number" name="stayovers" min="0" required class="input-stayovers"
                                       value="{{ old('stayovers', $record->stayovers ?? 0) }}"
                                       {{ $record?->is_finalised ? 'readonly' : '' }}>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Out of Order (OOO) <span class="req-star">*</span></label>
                            <div class="input-wrapper">
                                <input type="number" name="ooo_rooms" min="0" required class="input-ooo"
                                       value="{{ old('ooo_rooms', $record->ooo_rooms ?? 0) }}"
                                       {{ $record?->is_finalised ? 'readonly' : '' }}>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Out of Service (OOI) <span class="req-star">*</span></label>
                            <div class="input-wrapper">
                                <input type="number" name="ooi_rooms" min="0" required class="input-ooi"
                                       value="{{ old('ooi_rooms', $record->ooi_rooms ?? 0) }}"
                                       {{ $record?->is_finalised ? 'readonly' : '' }}>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>VIP Arrivals</label>
                            <div class="input-wrapper">
                                <input type="number" name="vip_arrivals" min="0"
                                       value="{{ old('vip_arrivals', $record->vip_arrivals ?? 0) }}"
                                       {{ $record?->is_finalised ? 'readonly' : '' }}>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Group Alloc. Arrivals</label>
                            <div class="input-wrapper">
                                <input type="number" name="group_arrivals" min="0"
                                       value="{{ old('group_arrivals', $record->group_arrivals ?? 0) }}"
                                       {{ $record?->is_finalised ? 'readonly' : '' }}>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Group Alloc. Departures</label>
                            <div class="input-wrapper">
                                <input type="number" name="group_departures" min="0"
                                       value="{{ old('group_departures', $record->group_departures ?? 0) }}"
                                       {{ $record?->is_finalised ? 'readonly' : '' }}>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Expected Breakfast covers</label>
                            <div class="input-wrapper">
                                <input type="number" name="expected_breakfast" min="0"
                                       value="{{ old('expected_breakfast', $record->expected_breakfast ?? 0) }}"
                                       {{ $record?->is_finalised ? 'readonly' : '' }}>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Expected Dinner covers</label>
                            <div class="input-wrapper">
                                <input type="number" name="expected_dinner" min="0"
                                       value="{{ old('expected_dinner', $record->expected_dinner ?? 0) }}"
                                       {{ $record?->is_finalised ? 'readonly' : '' }}>
                            </div>
                        </div>

                        <div class="form-group full">
                            <label>Shift Handover &amp; Strategic Notes</label>
                            <div class="input-wrapper">
                                <textarea name="notes" rows="3" placeholder="Log anomalies, specific group descriptions, or special guest handling instructions..."
                                          {{ $record?->is_finalised ? 'readonly' : '' }}>{{ old('notes', $record->notes ?? '') }}</textarea>
                            </div>
                        </div>

                        @if(!$record?->is_finalised)
                            <div class="form-group full finalise-wrapper">
                                <label class="checkbox-container">
                                    <input type="checkbox" name="is_finalised" value="1">
                                    <span class="custom-checkbox"></span>
                                    <span class="checkbox-label-text">Finalise and lock this {{ $snapshot }} metrics set from downstream modification</span>
                                </label>
                            </div>
                        @endif
                    </div>

                    <div class="form-actions">
                        @if($record?->is_finalised)
                            <button type="button" class="btn-ops-action btn-state-locked" disabled>
                                <i class="fas fa-lock"></i>
                                <span>Record Locked &amp; Audited</span>
                            </button>
                        @else
                            <button type="submit" class="btn-ops-action btn-state-submit">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <span>Save &amp; Process {{ $snapshot }} Sheet</span>
                            </button>
                        @endif
                    </div>
                </form>
            </div>
        @endforeach

    </div>

</section>

<style>
/* Base Variables & Structural Resets */
:root {
    --bg-surface: #121214;
    --bg-surface-elevated: #1a1a1e;
    --bg-surface-box: #0e0e10;
    --border-color: #2a2a32;
    --border-color-hover: #3e3e4a;
    
    --text-main: #f4f4f7;
    --text-muted: #a0a0b0;
    --text-dim: #6c6c7c;
    
    --color-am: #ff9d42;
    --color-am-glow: rgba(255, 157, 66, 0.15);
    --color-pm: #4da6ff;
    --color-pm-glow: rgba(77, 166, 255, 0.15);
    
    --color-success: #10b981;
    --color-success-glow: rgba(16, 185, 129, 0.15);
    --color-error: #ef4444;
    --color-error-glow: rgba(239, 68, 68, 0.15);
    
    --transition-smooth: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
}

.daily-ops-page {
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    color: var(--text-main);
    max-width: 1600px;
    margin: 0 auto;
    padding: 16px;
    animation: layoutFadeIn 0.4s ease-out;
}

@keyframes layoutFadeIn {
    from { opacity: 0; transform: translateY(8px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Hero Header Dashboard Section */
.ops-hero {
    background: linear-gradient(145deg, #1d1010, #131316 60%);
    border: 1px solid var(--border-color);
    box-shadow: 0 4px 30px rgba(0, 0, 0, 0.4);
    border-radius: 16px;
    padding: 28px 32px;
    margin-bottom: 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 24px;
    flex-wrap: wrap;
}

.breadcrumb {
    color: var(--primary);
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 2px;
    text-transform: uppercase;
    margin: 0 0 6px 0;
}

.ops-hero h1 {
    font-size: 28px;
    font-weight: 800;
    letter-spacing: -0.5px;
    margin: 0 0 8px 0;
    background: linear-gradient(to right, #ffffff, #dcdcdf);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.subtitle {
    color: var(--text-muted);
    font-size: 14px;
    line-height: 1.5;
    max-width: 680px;
    display: block;
}

/* Control Elements & Filters */
.date-filter {
    display: flex;
    align-items: center;
    gap: 12px;
    background: var(--bg-surface-box);
    padding: 8px 12px;
    border-radius: 12px;
    border: 1px solid var(--border-color);
}

.input-with-icon {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 0 4px;
}

.input-with-icon i {
    color: var(--text-dim);
    font-size: 15px;
}

.date-filter input[type="date"] {
    background: transparent;
    border: none;
    color: var(--text-main);
    font-size: 14px;
    font-weight: 600;
    outline: none;
    cursor: pointer;
}

.date-filter input[type="date"]::-webkit-calendar-picker-indicator {
    filter: invert(0.9);
}

.btn-filter {
    background: linear-gradient(135deg, var(--border-color-hover), var(--border-color));
    color: var(--text-main);
    border: 1px solid #4a4a5a;
    min-height: 38px;
    padding: 0 16px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: var(--transition-smooth);
}

.btn-filter:hover {
    background: var(--text-main);
    color: #000000;
    border-color: var(--text-main);
    box-shadow: 0 0 12px rgba(255, 255, 255, 0.15);
}

/* Global Notification Systems */
.alert {
    padding: 14px 20px;
    border-radius: 10px;
    margin-bottom: 20px;
    font-size: 14px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 12px;
    border-left: 4px solid transparent;
}

.alert-success {
    background: rgba(16, 185, 129, 0.06);
    color: #34d399;
    border: 1px solid rgba(16, 185, 129, 0.2);
    border-left-color: var(--color-success);
}

.alert-error {
    background: rgba(239, 68, 68, 0.06);
    color: #f87171;
    border: 1px solid rgba(239, 68, 68, 0.2);
    border-left-color: var(--color-error);
}

.batch-errors {
    flex-direction: column;
    align-items: flex-start;
    gap: 6px;
}

.errors-header {
    display: flex;
    align-items: center;
    gap: 10px;
}

.batch-errors ul {
    margin: 4px 0 0 0;
    padding-left: 24px;
    font-size: 13px;
    color: #fca5a5;
}

/* Master Realtime Aggregations Bar */
.summary-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 16px;
    margin-bottom: 24px;
}

.summary-card {
    background: var(--bg-surface-elevated);
    border: 1px solid var(--border-color);
    border-radius: 14px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 18px;
    position: relative;
    overflow: hidden;
    transition: var(--transition-smooth);
}

.summary-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; width: 100%; height: 3px;
    background: transparent;
}

.summary-card.text-card::before { background: var(--border-color-hover); }
.summary-card.am-theme-card::before { background: var(--color-am); }
.summary-card.pm-theme-card::before { background: var(--color-pm); }
.summary-card.state-positive::before { background: var(--color-success); }
.summary-card.state-negative::before { background: var(--color-error); }

.card-icon {
    font-size: 22px;
    width: 44px;
    height: 44px;
    border-radius: 10px;
    background: var(--bg-surface-box);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-muted);
    border: 1px solid var(--border-color);
}

.am-theme-card .card-icon { color: var(--color-am); background: var(--color-am-glow); border-color: rgba(255,157,66,0.3); }
.pm-theme-card .card-icon { color: var(--color-pm); background: var(--color-pm-glow); border-color: rgba(77,166,255,0.3); }
.state-positive .card-icon { color: var(--color-success); background: var(--color-success-glow); border-color: rgba(16,185,129,0.3); }
.state-negative .card-icon { color: var(--color-error); background: var(--color-error-glow); border-color: rgba(239,68,68,0.3); }

.card-data {
    display: flex;
    flex-direction: column;
}

.stat-counter {
    font-size: 26px;
    font-weight: 800;
    line-height: 1.1;
    letter-spacing: -0.5px;
}

.state-positive .stat-counter { color: #34d399; }
.state-negative .stat-counter { color: #f87171; }

.card-data span {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    color: var(--text-muted);
    letter-spacing: 0.5px;
    margin-top: 4px;
}

/* Snapshots Column Split View */
.snapshot-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 24px;
}

.snapshot-card {
    background: var(--bg-surface-elevated);
    border: 1px solid var(--border-color);
    border-radius: 18px;
    padding: 28px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.25);
    position: relative;
    transition: var(--transition-smooth);
}

.snapshot-card:focus-within {
    border-color: var(--border-color-hover);
    box-shadow: 0 12px 48px rgba(0,0,0,0.35);
}

.snapshot-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 16px;
    margin-bottom: 24px;
    border-bottom: 1px solid var(--border-color);
    padding-bottom: 16px;
}

.context-tag {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: var(--text-dim);
    display: block;
}

.snapshot-header h2 {
    font-size: 22px;
    font-weight: 800;
    margin: 4px 0 0 0;
    letter-spacing: -0.3px;
}

.card-theme-am h2 { color: var(--color-am); }
.card-theme-pm h2 { color: var(--color-pm); }

/* Lock status styles */
.status-badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    letter-spacing: 0.3px;
}

.state-finalised {
    background: rgba(16, 185, 129, 0.1);
    color: #34d399;
    border: 1px solid rgba(16, 185, 129, 0.25);
}

.state-editable {
    background: rgba(245, 158, 11, 0.1);
    color: #fbbf24;
    border: 1px solid rgba(245, 158, 11, 0.25);
}

/* Derived Metrics Panels */
.calc-panel {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
    margin-bottom: 24px;
}

.calc-box {
    background: var(--bg-surface-box);
    border: 1px solid var(--border-color);
    border-radius: 10px;
    padding: 12px;
    text-align: center;
}

.calc-label {
    display: block;
    color: var(--text-dim);
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.calc-val {
    display: block;
    margin-top: 6px;
    font-size: 20px;
    font-weight: 800;
    color: var(--text-main);
}

.highlight-box {
    background: rgba(255, 255, 255, 0.02);
    border-color: var(--border-color-hover);
}

.card-theme-am .highlight-box .calc-val { color: var(--color-am); }
.card-theme-pm .highlight-box .calc-val { color: var(--color-pm); }

/* Matrix Form Layout definitions */
.form-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 16px;
}

.form-group.full {
    grid-column: 1 / -1;
}

.form-group label {
    display: block;
    color: var(--text-muted);
    font-size: 12px;
    font-weight: 700;
    margin-bottom: 6px;
}

.req-star {
    color: var(--color-error);
}

.input-wrapper {
    position: relative;
    width: 100%;
}

.form-grid input[type="number"],
.form-grid textarea {
    width: 100%;
    background: var(--bg-surface-box);
    border: 1px solid var(--border-color);
    color: var(--text-main);
    border-radius: 8px;
    padding: 10px 14px;
    font-size: 14px;
    font-weight: 600;
    outline: none;
    box-sizing: border-box;
    transition: var(--transition-smooth);
}

.form-grid input[type="number"]:focus,
.form-grid textarea:focus {
    background: #000000;
    border-color: #6366f1;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
}

.card-theme-am input[type="number"]:focus { border-color: var(--color-am); box-shadow: 0 0 0 3px var(--color-am-glow); }
.card-theme-pm input[type="number"]:focus { border-color: var(--color-pm); box-shadow: 0 0 0 3px var(--color-pm-glow); }

.form-grid textarea {
    resize: vertical;
    font-family: inherit;
    line-height: 1.5;
}

/* Custom Checkbox Design */
.finalise-wrapper {
    margin-top: 8px;
    background: rgba(239, 68, 68, 0.02);
    border: 1px dashed rgba(239, 68, 68, 0.15);
    border-radius: 8px;
    padding: 12px;
}

.checkbox-container {
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
    user-select: none;
}

.checkbox-container input {
    position: absolute;
    opacity: 0;
    cursor: pointer;
    height: 0; width: 0;
}

.custom-checkbox {
    height: 18px;
    width: 18px;
    background-color: var(--bg-surface-box);
    border: 1px solid var(--border-color);
    border-radius: 4px;
    display: inline-block;
    position: relative;
    transition: var(--transition-smooth);
}

.checkbox-container:hover input ~ .custom-checkbox {
    border-color: var(--color-error);
}

.checkbox-container input:checked ~ .custom-checkbox {
    background-color: var(--color-error);
    border-color: var(--color-error);
}

.custom-checkbox::after {
    content: "";
    position: absolute;
    display: none;
    left: 5px; top: 2px;
    width: 4px; height: 8px;
    border: solid white;
    border-width: 0 2px 2px 0;
    transform: rotate(45deg);
}

.checkbox-container input:checked ~ .custom-checkbox::after {
    display: block;
}

.checkbox-label-text {
    font-size: 13px !important;
    font-weight: 600 !important;
    text-transform: none !important;
    color: var(--text-muted) !important;
}

/* Functional Submission Interfaces */
.form-actions {
    margin-top: 24px;
}

.btn-ops-action {
    width: 100%;
    min-height: 44px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    cursor: pointer;
    border: none;
    transition: var(--transition-smooth);
}

.btn-state-submit {
    background: var(--text-main);
    color: #000000;
}

.btn-state-submit:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 16px rgba(255,255,255,0.1);
}

.card-theme-am .btn-state-submit { background: var(--color-am); color: #000000; }
.card-theme-pm .btn-state-submit { background: var(--color-pm); color: #000000; }

.card-theme-am .btn-state-submit:hover { box-shadow: 0 6px 20px var(--color-am-glow); }
.card-theme-pm .btn-state-submit:hover { box-shadow: 0 6px 20px var(--color-pm-glow); }

.btn-state-locked {
    background: rgba(42, 42, 50, 0.4);
    color: var(--text-dim);
    border: 1px solid var(--border-color);
    cursor: not-allowed;
}

/* Immutable Readonly System Assertions */
input[readonly], 
textarea[readonly] {
    background: rgba(14, 14, 16, 0.4) !important;
    border-color: rgba(42, 42, 50, 0.5) !important;
    color: var(--text-dim) !important;
    cursor: not-allowed;
    box-shadow: none !important;
}

/* Adaptive Breakpoint Mediations */
@media(max-width: 1200px) {
    .snapshot-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }
}

@media(max-width: 768px) {
    .ops-hero {
        padding: 20px;
        flex-direction: column;
        align-items: flex-start;
    }
    
    .date-filter {
        width: 100%;
        justify-content: space-between;
    }
}

@media(max-width: 480px) {
    .form-grid, .calc-panel {
        grid-template-columns: 1fr;
    }
    .form-group.full {
        grid-column: auto;
    }
    .date-filter {
        flex-direction: column;
        align-items: stretch;
        gap: 12px;
    }
    .btn-filter {
        justify-content: center;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const totalRooms = parseInt("{{ $totalRooms }}") || 0;

    function calculateCardMetrics(card) {
        const arrivals = parseInt(card.querySelector('.input-arrivals').value) || 0;
        const stayovers = parseInt(card.querySelector('.input-stayovers').value) || 0;
        const ooo = parseInt(card.querySelector('.input-ooo').value) || 0;
        const ooi = parseInt(card.querySelector('.input-ooi').value) || 0;

        const occupied = arrivals + stayovers;
        const available = Math.max(0, totalRooms - ooo - ooi);
        
        let occupancyPercent = 0;
        if (available > 0) {
            occupancyPercent = Math.round((occupied / available) * 100);
        }

        card.querySelector('.calc-available').textContent = available;
        card.querySelector('.calc-occupied').textContent = occupied;
        card.querySelector('.calc-occupancy').textContent = occupancyPercent + '%';

        return {
            arrivals: arrivals,
            occupancy: occupancyPercent + '%'
        };
    }

    function updateGlobalSummaries() {
        let amArrivals = 0, pmArrivals = 0;

        document.querySelectorAll('.snapshot-card').forEach(card => {
            const snapshotType = card.getAttribute('data-snapshot');
            const metrics = calculateCardMetrics(card);

            if (snapshotType === 'AM') {
                amArrivals = metrics.arrivals;
                document.getElementById('summary-am-occ').textContent = metrics.occupancy;
            } else if (snapshotType === 'PM') {
                pmArrivals = metrics.arrivals;
                document.getElementById('summary-pm-occ').textContent = metrics.occupancy;
            }
        });

        const pickup = pmArrivals - amArrivals;
        const pickupElement = document.getElementById('summary-pickup');
        const pickupCard = document.getElementById('summary-pickup-card');

        if (pickup > 0) {
            pickupElement.textContent = '+' + pickup;
            pickupCard.classList.remove('state-negative');
            pickupCard.classList.add('state-positive');
        } else if (pickup < 0) {
            pickupElement.textContent = pickup;
            pickupCard.classList.remove('state-positive');
            pickupCard.classList.add('state-negative');
        } else {
            pickupElement.textContent = '0';
            pickupCard.classList.remove('state-negative');
            pickupCard.classList.add('state-positive');
        }
    }

    document.querySelectorAll('.snapshot-card input[type="number"]').forEach(input => {
        input.addEventListener('input', updateGlobalSummaries);
    });

    updateGlobalSummaries();
});
</script>
@endsection