@extends('dashboard.dop.layout')

@section('title', 'Hotels Overview')
@section('page-title', 'Hotels Overview')

@section('content')
<section class="hotels-overview-page">

    <div class="overview-hero">
        <div>
            <p>MGM One / Group Operations</p>
            <h1>Hotels Overview</h1>
            <span>Live performance, hotel health, housekeeping, maintenance and guest complaint monitoring.</span>
        </div>

        <div class="overview-pill">
            <i class="fas fa-circle-dot"></i>
            Live Performance
        </div>
    </div>

    <div class="overview-grid">
        @forelse($hotels as $hotel)
            <div class="hotel-card health-{{ $hotel->health_level }}">
                <div class="hotel-top">
                    <div>
                        <h2>{{ $hotel->name }}</h2>
                        <p>{{ $hotel->code }} • {{ $hotel->city ?? 'No city' }}</p>
                    </div>

                    @if($hotel->is_active)
                        <span class="status-pill active">Active</span>
                    @else
                        <span class="status-pill inactive">Inactive</span>
                    @endif
                </div>

                <div class="health-row">
                    <div class="health-ring">
                        <svg viewBox="0 0 120 120">
                            <circle class="ring-bg" cx="60" cy="60" r="52"></circle>
                            <circle class="ring-value"
                                    cx="60"
                                    cy="60"
                                    r="52"
                                    style="stroke-dashoffset: {{ 327 - (327 * ($hotel->health_score ?? 0) / 100) }}">
                            </circle>
                        </svg>

                        <div>
                            <strong>{{ $hotel->health_score ?? 0 }}%</strong>
                            <span>Health</span>
                        </div>
                    </div>

                    <div class="health-text">
                        <strong>
                            @if(($hotel->health_score ?? 0) >= 85)
                                Excellent
                            @elseif(($hotel->health_score ?? 0) >= 70)
                                Good
                            @elseif(($hotel->health_score ?? 0) >= 50)
                                Needs Attention
                            @else
                                Critical
                            @endif
                        </strong>

                        <p>
                            Housekeeping:
                            {{ $hotel->hk_completion === null ? 'No allocation' : $hotel->hk_completion.'%' }}
                        </p>

                        <p>
                            OOO / OOI Rooms: {{ $hotel->ooo_rooms_count ?? 0 }}
                        </p>
                    </div>
                </div>

                <div class="metric-grid">
                    <div>
                        <strong>{{ $hotel->users_count }}</strong>
                        <span>Staff</span>
                    </div>

                    <div>
                        <strong>{{ $hotel->rooms_count }}</strong>
                        <span>Rooms</span>
                    </div>

                    <div>
                        <strong>{{ $hotel->departments_count }}</strong>
                        <span>Departments</span>
                    </div>

                    <div>
                        <strong>{{ $hotel->restaurants_count }}</strong>
                        <span>Restaurants</span>
                    </div>
                </div>

                <div class="ops-grid">
                    <div>
                        <i class="fas fa-screwdriver-wrench"></i>
                        <span>Open Jobs</span>
                        <strong>{{ $hotel->open_maintenance_count }}</strong>
                    </div>

                    <div>
                        <i class="fas fa-comments"></i>
                        <span>Complaints</span>
                        <strong>{{ $hotel->pending_complaints_count }}</strong>
                    </div>

                    <div>
                        <i class="fas fa-utensils"></i>
                        <span>Bookings</span>
                        <strong>{{ $hotel->today_restaurant_bookings_count ?? 0 }}</strong>
                    </div>

                    <div>
                        <i class="fas fa-broom"></i>
                        <span>HK Done</span>
                        <strong>{{ $hotel->today_hk_completed_count ?? 0 }}/{{ $hotel->today_hk_allocations_count ?? 0 }}</strong>
                    </div>
                </div>

                <a href="{{ route('dop.hotels.show', $hotel) }}" class="view-btn">
                    View Hotel Detail
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        @empty
            <div class="card">
                No hotels found.
            </div>
        @endforelse
    </div>

</section>

<style>
.overview-hero {
    background:
        radial-gradient(circle at 15% 15%, rgba(139,92,246,.3), transparent 35%),
        radial-gradient(circle at 85% 20%, rgba(236,72,153,.2), transparent 35%),
        linear-gradient(135deg, rgba(18,18,20,.96), rgba(8,8,10,.96));
    border: 1px solid var(--border);
    border-radius: 28px;
    padding: 34px;
    margin-bottom: 28px;
    display: flex;
    justify-content: space-between;
    gap: 18px;
    align-items: center;
    flex-wrap: wrap;
}

.overview-hero p {
    color: var(--primary);
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    font-size: 12px;
}

.overview-hero h1 {
    font-size: 38px;
    font-weight: 900;
    margin-top: 8px;
}

.overview-hero span {
    display: block;
    color: var(--text-muted);
    margin-top: 8px;
}

.overview-pill {
    padding: 12px 18px;
    border-radius: 999px;
    background: rgba(34,197,94,.14);
    color: #4ade80;
    border: 1px solid rgba(34,197,94,.35);
    font-weight: 900;
    display: flex;
    gap: 10px;
    align-items: center;
}

.overview-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(365px, 1fr));
    gap: 24px;
}

.hotel-card {
    background:
        radial-gradient(circle at 100% 0%, rgba(139,92,246,.14), transparent 34%),
        linear-gradient(180deg, rgba(23,23,23,.98), rgba(10,10,10,.98));
    border: 1px solid var(--border);
    border-radius: 26px;
    padding: 24px;
    transition: .25s ease;
}

.hotel-card:hover {
    border-color: rgba(139,92,246,.6);
    transform: translateY(-5px);
    box-shadow: 0 20px 70px rgba(139,92,246,.16);
}

.hotel-card.health-excellent {
    border-color: rgba(34,197,94,.32);
}

.hotel-card.health-good {
    border-color: rgba(139,92,246,.32);
}

.hotel-card.health-warning {
    border-color: rgba(249,115,22,.42);
}

.hotel-card.health-danger {
    border-color: rgba(239,68,68,.52);
}

.hotel-top {
    display: flex;
    justify-content: space-between;
    gap: 16px;
    align-items: flex-start;
    margin-bottom: 22px;
}

.hotel-top h2 {
    font-size: 22px;
    font-weight: 900;
}

.hotel-top p {
    color: var(--text-muted);
    margin-top: 5px;
    font-weight: 700;
}

.status-pill {
    padding: 7px 12px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 900;
    text-transform: uppercase;
}

.status-pill.active {
    background: rgba(34,197,94,.14);
    color: #4ade80;
}

.status-pill.inactive {
    background: rgba(239,68,68,.14);
    color: #f87171;
}

.health-row {
    display: flex;
    gap: 18px;
    align-items: center;
    padding: 16px;
    border-radius: 20px;
    background: rgba(255,255,255,.04);
    border: 1px solid var(--border);
    margin-bottom: 18px;
}

.health-ring {
    position: relative;
    width: 104px;
    height: 104px;
    flex-shrink: 0;
}

.health-ring svg {
    width: 104px;
    height: 104px;
    transform: rotate(-90deg);
}

.ring-bg,
.ring-value {
    fill: none;
    stroke-width: 10;
}

.ring-bg {
    stroke: rgba(255,255,255,.08);
}

.ring-value {
    stroke: #8b5cf6;
    stroke-linecap: round;
    stroke-dasharray: 327;
}

.health-excellent .ring-value {
    stroke: #22c55e;
}

.health-warning .ring-value {
    stroke: #f97316;
}

.health-danger .ring-value {
    stroke: #ef4444;
}

.health-ring > div {
    position: absolute;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
}

.health-ring strong {
    font-size: 24px;
    font-weight: 900;
}

.health-ring span {
    color: var(--text-dim);
    font-size: 10px;
    font-weight: 900;
    text-transform: uppercase;
}

.health-text strong {
    font-size: 20px;
    font-weight: 900;
}

.health-text p {
    color: var(--text-muted);
    font-size: 13px;
    margin-top: 5px;
}

.metric-grid,
.ops-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 10px;
}

.metric-grid div,
.ops-grid div {
    background: #0a0a0a;
    border: 1px solid var(--border);
    border-radius: 14px;
    padding: 14px 8px;
    text-align: center;
}

.metric-grid strong,
.ops-grid strong {
    display: block;
    font-size: 22px;
    font-weight: 900;
}

.metric-grid span,
.ops-grid span {
    display: block;
    margin-top: 4px;
    color: var(--text-dim);
    font-size: 10px;
    font-weight: 900;
    text-transform: uppercase;
}

.ops-grid {
    margin-top: 12px;
}

.ops-grid i {
    color: var(--primary);
    margin-bottom: 8px;
}

.view-btn {
    margin-top: 18px;
    width: 100%;
    min-height: 46px;
    border-radius: 14px;
    background: rgba(139,92,246,.14);
    border: 1px solid rgba(139,92,246,.35);
    color: #c4b5fd;
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 10px;
    font-weight: 900;
    text-transform: uppercase;
    font-size: 12px;
}

.view-btn:hover {
    background: linear-gradient(135deg, #8b5cf6, #ec4899);
    color: white;
}

@media(max-width: 700px) {
    .overview-grid {
        grid-template-columns: 1fr;
    }

    .metric-grid,
    .ops-grid {
        grid-template-columns: 1fr 1fr;
    }

    .health-row {
        flex-direction: column;
        text-align: center;
    }

    .overview-hero h1 {
        font-size: 30px;
    }
}

@media(max-width: 480px) {
    .metric-grid,
    .ops-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection