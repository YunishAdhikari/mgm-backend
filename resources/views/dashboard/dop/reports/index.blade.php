@extends('dashboard.dop.layout')

@section('title', 'Group Reports')
@section('page-title', 'Group Reports')

@section('content')
<div class="content-responsive-shield">
    <section class="reports-page">

        <div class="reports-hero">
            <div class="hero-text-block">
                <p class="section-tag">MGM One &middot; Executive Reports</p>
                <h1>Group Reports Centre</h1>
                <span class="subtitle-text">Cross-property consolidation data charting active occupancies, net inventory fluid shifts, housekeeping velocity, and maintenance outstanding loops.</span>
            </div>

            <form method="GET" class="date-filter">
                <div class="input-container-icon">
                    <i class="far fa-calendar-alt"></i>
                    <input type="date" name="date" value="{{ $date }}">
                </div>
                <button type="submit" class="btn-load-report">
                    <i class="fas fa-layer-group"></i>
                    <span>Compile Deck</span>
                </button>
            </form>
        </div>

        <div class="report-table-card">
            <div class="panel-head">
                <div class="panel-title-block">
                    <p class="panel-sub">Consolidated Property Breakdown</p>
                    <h2>{{ \Carbon\Carbon::parse($date)->format('d M Y') }}</h2>
                </div>

                <div class="property-badge">
                    <i class="fas fa-hotel"></i>
                    <span>{{ $hotels->count() }} {{ Str::plural('Hotel', $hotels->count()) }} Indexed</span>
                </div>
            </div>

            <div class="table-container-outer">
                <div class="table-wrap">
                    <table class="analytics-table">
                        <thead>
                            <tr>
                                <th>Property Details</th>
                                <th class="text-center">AM Occ</th>
                                <th class="text-center">PM Occ</th>
                                <th class="text-center">Net Pick-Up</th>
                                <th class="text-center">Arrivals</th>
                                <th class="text-center">Departures</th>
                                <th class="text-center">Stayovers</th>
                                <th class="text-center">Breakfast</th>
                                <th class="text-center">Dinner</th>
                                <th class="text-center">HK Clean %</th>
                                <th class="text-center text-dim-header"><i class="fas fa-tools"></i> Maint</th>
                                <th class="text-center text-dim-header"><i class="fas fa-exclamation-circle"></i> Compl.</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($hotels as $hotel)
                                <tr class="table-row-interactive">
                                    <td>
                                        <div class="property-identity">
                                            <strong class="hotel-main-name">{{ $hotel->name }}</strong>
                                            <span class="hotel-sub-meta">{{ $hotel->code }} &middot; {{ $hotel->city ?? 'No city logged' }}</span>
                                        </div>
                                    </td>

                                    <td class="text-center font-numeric sub-weight-value">{{ $hotel->am_operation?->occupancy_percentage ?? '—' }}%</td>
                                    <td class="text-center font-numeric main-weight-value">{{ $hotel->pm_operation?->occupancy_percentage ?? '—' }}%</td>

                                    <td class="text-center font-numeric">
                                        @if($hotel->pickup === null)
                                            <span class="null-dash">—</span>
                                        @else
                                            <span class="badge-pill-state {{ $hotel->pickup >= 0 ? 'state-positive-glow' : 'state-negative-glow' }}">
                                                {{ $hotel->pickup >= 0 ? '+' : '' }}{{ $hotel->pickup }}
                                            </span>
                                        @endif
                                    </td>

                                    <td class="text-center font-numeric data-neutral">{{ $hotel->latest_operation?->arrivals ?? '—' }}</td>
                                    <td class="text-center font-numeric data-neutral">{{ $hotel->latest_operation?->departures ?? '—' }}</td>
                                    <td class="text-center font-numeric data-neutral">{{ $hotel->latest_operation?->stayovers ?? '—' }}</td>
                                    <td class="text-center font-numeric data-neutral text-faded-numbers">{{ $hotel->latest_operation?->expected_breakfast ?? '—' }}</td>
                                    <td class="text-center font-numeric text-faded-numbers">{{ $hotel->latest_operation?->expected_dinner ?? '—' }}</td>

                                    <td class="text-center font-numeric">
                                        <span class="hk-metric-indicator {{ $hotel->hk_completion >= 80 ? 'positive-text' : 'warning-text' }}">
                                            <span class="completion-dot"></span>
                                            {{ $hotel->hk_completion }}%
                                        </span>
                                    </td>

                                    <td class="text-center font-numeric">
                                        <span class="counter-alert-pill {{ $hotel->open_maintenance_count > 0 ? 'maint-active' : 'counter-zero' }}">
                                            {{ $hotel->open_maintenance_count }}
                                        </span>
                                    </td>
                                    
                                    <td class="text-center font-numeric">
                                        <span class="counter-alert-pill {{ $hotel->pending_complaints_count > 0 ? 'complaints-active' : 'counter-zero' }}">
                                            {{ $hotel->pending_complaints_count }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="12" class="table-empty-fallback">
                                        <div class="fallback-wrapper">
                                            <i class="fas fa-folder-open"></i>
                                            <p>No operational analytics data available for the selected evaluation period.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </section>
</div>

<style>
/* Layout Shield System */
.content-responsive-shield {
    display: grid;
    grid-template-columns: minmax(0, 1fr); /* Crucial: Tells the layout pane it's allowed to shrink to 0 if needed */
    width: 100%;
    max-width: 100%;
    box-sizing: border-box;
}

/* Dashboard Variable Registry */
:root {
    --bg-main: #0c0c0e;
    --bg-surface: #121215;
    --bg-elevated: #18181c;
    --bg-nested-box: #0a0a0c;
    
    --border-main: #24242b;
    --border-hover: #373742;
    
    --text-primary: #f3f4f6;
    --text-secondary: #9ca3af;
    --text-muted: #6b7280;
    
    --executive-gradient: linear-gradient(135deg, #7c3aed, #db2777);
    --executive-glow: rgba(124, 58, 237, 0.15);
    
    --color-emerald: #10b981;
    --color-rose: #f43f5e;
    --color-amber: #f59e0b;
    
    --transition-base: all 0.22s cubic-bezier(0.4, 0, 0.2, 1);
}

.reports-page {
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
    color: var(--text-primary);
    width: 100%;
    max-width: 100%;
    margin: 0 auto;
    padding: 16px;
    box-sizing: border-box;
}

/* Hero Panels */
.reports-hero {
    background: 
        radial-gradient(circle at 10% 20%, rgba(124, 58, 237, 0.12), transparent 40%),
        radial-gradient(circle at 90% 80%, rgba(219, 39, 119, 0.08), transparent 40%),
        linear-gradient(145deg, #16161a, #0e0e11);
    border: 1px solid var(--border-main);
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.35);
    border-radius: 16px;
    padding: 24px;
    margin-bottom: 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 24px;
}

.section-tag {
    color: #a78bfa;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 2px;
    text-transform: uppercase;
    margin: 0 0 6px 0;
}

.reports-hero h1 {
    font-size: 28px;
    font-weight: 800;
    margin: 0 0 8px 0;
    letter-spacing: -0.5px;
    background: linear-gradient(to right, #ffffff, #e5e7eb);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.subtitle-text {
    display: block;
    color: var(--text-secondary);
    font-size: 13px;
    line-height: 1.5;
    max-width: 720px;
}

/* Deck Query Form controls */
.date-filter {
    display: flex;
    align-items: center;
    gap: 12px;
    background: var(--bg-nested-box);
    padding: 8px 12px;
    border-radius: 12px;
    border: 1px solid var(--border-main);
    flex-shrink: 0;
}

.input-container-icon {
    display: flex;
    align-items: center;
    gap: 10px;
    color: var(--text-muted);
}

.date-filter input[type="date"] {
    background: transparent;
    border: none;
    color: var(--text-primary);
    font-size: 14px;
    font-weight: 600;
    outline: none;
    cursor: pointer;
}

.date-filter input[type="date"]::-webkit-calendar-picker-indicator {
    filter: invert(0.85);
}

.btn-load-report {
    background: var(--executive-gradient);
    color: white;
    border: none;
    min-height: 38px;
    padding: 0 16px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 4px 14px var(--executive-glow);
    transition: var(--transition-base);
}

.btn-load-report:hover {
    opacity: 0.95;
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(124, 58, 237, 0.3);
}

/* Consolidation Deck Output Sheet UI */
.report-table-card {
    background: var(--bg-surface);
    border: 1px solid var(--border-main);
    border-radius: 16px;
    padding: 20px;
    box-shadow: 0 12px 48px rgba(0, 0, 0, 0.2);
    width: 100%;
    box-sizing: border-box;
    overflow: hidden; 
}

.panel-head {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
    margin-bottom: 20px;
}

.panel-sub {
    color: var(--text-muted);
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin: 0 0 4px 0;
}

.panel-head h2 {
    font-size: 22px;
    font-weight: 800;
    margin: 0;
    letter-spacing: -0.3px;
}

.property-badge {
    padding: 6px 14px;
    border-radius: 20px;
    background: rgba(124, 58, 237, 0.08);
    border: 1px solid rgba(124, 58, 237, 0.2);
    color: #c4b5fd;
    font-weight: 700;
    font-size: 12px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    flex-shrink: 0;
}

/* Dual-Layer Scroll Lock Engine to trap structural grid leakage */
.table-container-outer {
    width: 100%;
    max-width: 100%;
    overflow: hidden;
}

.table-wrap {
    width: 100%;
    overflow-x: auto;
    border-radius: 10px;
    border: 1px solid var(--border-main);
    background: var(--bg-nested-box);
    -webkit-overflow-scrolling: touch; 
}

/* Custom modern scrollbar styles for clean UX */
.table-wrap::-webkit-scrollbar {
    height: 8px;
}
.table-wrap::-webkit-scrollbar-track {
    background: var(--bg-nested-box);
}
.table-wrap::-webkit-scrollbar-thumb {
    background: var(--border-hover);
    border-radius: 4px;
}
.table-wrap::-webkit-scrollbar-thumb:hover {
    background: var(--text-muted);
}

.analytics-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 1350px; /* Safe density spread for all columns */
    text-align: left;
}

.analytics-table th {
    padding: 14px 16px;
    color: var(--text-secondary);
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 1px solid var(--border-main);
    background: #16161a;
}

.analytics-table td {
    padding: 14px 16px;
    border-bottom: 1px solid var(--border-main);
    color: var(--text-primary);
    vertical-align: middle;
}

.table-row-interactive {
    transition: var(--transition-base);
}

.table-row-interactive:hover {
    background: rgba(255, 255, 255, 0.015);
}

/* Specific Field Typographics */
.text-center { text-align: center; }
.font-numeric { font-variant-numeric: tabular-nums; font-family: inherit; }
.main-weight-value { font-weight: 700; color: #ffffff; }
.sub-weight-value { font-weight: 600; color: var(--text-secondary); }
.data-neutral { font-weight: 600; color: var(--text-secondary); }
.text-faded-numbers { color: #888896; font-size: 13px; font-weight: 500; }
.null-dash { color: var(--text-muted); opacity: 0.6; }

.property-identity {
    display: flex;
    flex-direction: column;
}

.hotel-main-name {
    display: block;
    color: #ffffff;
    font-size: 14px;
    font-weight: 700;
}

.hotel-sub-meta {
    display: block;
    margin-top: 3px;
    font-size: 12px;
    color: var(--text-secondary);
    font-weight: 500;
}

/* Micro Status Chips Design */
.badge-pill-state {
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 700;
    display: inline-block;
}

.state-positive-glow {
    background: rgba(16, 185, 129, 0.08);
    color: #34d399;
    border: 1px solid rgba(16, 185, 129, 0.15);
}

.state-negative-glow {
    background: rgba(244, 63, 94, 0.08);
    color: #f87171;
    border: 1px solid rgba(244, 63, 94, 0.15);
}

.hk-metric-indicator {
    font-weight: 700;
    font-size: 13px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    justify-content: center;
    width: 100%;
}

.completion-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    display: inline-block;
}

.positive-text { color: #34d399; }
.positive-text .completion-dot { background-color: var(--color-emerald); box-shadow: 0 0 6px var(--color-emerald); }

.warning-text { color: #fb923c; }
.warning-text .completion-dot { background-color: var(--color-amber); box-shadow: 0 0 6px var(--color-amber); }

/* Analytical Alert Counters */
.text-dim-header {
    color: var(--text-muted) !important;
}

.counter-alert-pill {
    min-width: 24px;
    height: 20px;
    padding: 0 6px;
    border-radius: 10px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: 700;
}

.counter-zero {
    background: rgba(255, 255, 255, 0.03);
    color: var(--text-muted);
}

.maint-active {
    background: rgba(245, 158, 11, 0.12);
    color: #fbbf24;
    border: 1px solid rgba(245, 158, 11, 0.2);
}

.complaints-active {
    background: rgba(244, 63, 94, 0.12);
    color: #fca5a5;
    border: 1px solid rgba(244, 63, 94, 0.2);
}

/* Zero Data Fallback presentation */
.table-empty-fallback {
    padding: 48px 0 !important;
    text-align: center;
}

.fallback-wrapper {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 12px;
    color: var(--text-muted);
}

.fallback-wrapper i {
    font-size: 32px;
    opacity: 0.5;
}

.fallback-wrapper p {
    margin: 0;
    font-size: 14px;
    font-weight: 500;
}

/* Screen Adapters & Unified Viewport Adjustments */
@media(max-width: 1100px) {
    .reports-hero {
        flex-direction: column;
        align-items: flex-start;
        padding: 24px;
    }

    .date-filter {
        width: 100%;
        box-sizing: border-box;
        justify-content: space-between;
    }
}

@media(max-width: 650px) {
    .reports-page {
        padding: 12px;
    }

    .reports-hero h1 {
        font-size: 24px;
    }

    .panel-head {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
    }

    .property-badge {
        align-self: flex-start;
    }

    .date-filter {
        flex-direction: column;
        align-items: stretch;
        gap: 14px;
    }

    .btn-load-report {
        justify-content: center;
        width: 100%;
    }
}
</style>
@endsection