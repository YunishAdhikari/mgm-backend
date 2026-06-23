@extends('dashboard.dop.layout')

@section('title', 'DoP Dashboard')
@section('page-title', 'Director of Operations')

@section('content')
<section class="animate-fade-in">

    <div class="dop-hero">
        <div>
            <p>MGM One / Group Operations</p>
            <h1>DoP Command Centre</h1>
            <span>Group occupancy, booking pick-up, hotel health, alerts and live operational performance.</span>
        </div>

        <div class="hero-badge">
            <i class="fas fa-satellite-dish"></i>
            Live Group View
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon red"><i class="fas fa-hotel"></i></div>
            <div class="stat-value">{{ $totalHotels }}</div>
            <div class="stat-label">Total Hotels</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon green"><i class="fas fa-circle-check"></i></div>
            <div class="stat-value">{{ $activeHotels }}</div>
            <div class="stat-label">Active Hotels</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon dark"><i class="fas fa-users"></i></div>
            <div class="stat-value">{{ $totalStaff }}</div>
            <div class="stat-label">Total Staff</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon orange"><i class="fas fa-screwdriver-wrench"></i></div>
            <div class="stat-value">{{ $openMaintenance }}</div>
            <div class="stat-label">Open Maintenance</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon red"><i class="fas fa-triangle-exclamation"></i></div>
            <div class="stat-value">{{ $pendingComplaints }}</div>
            <div class="stat-label">Pending Complaints</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon green"><i class="fas fa-utensils"></i></div>
            <div class="stat-value">{{ $todayRestaurantBookings }}</div>
            <div class="stat-label">Today Bookings</div>
        </div>
    </div>

    <div class="forecast-summary-grid">
        <div class="forecast-summary-card">
            <i class="fas fa-plane-arrival"></i>
            <strong>{{ $totalArrivals }}</strong>
            <span>Total Arrivals</span>
        </div>

        <div class="forecast-summary-card">
            <i class="fas fa-door-open"></i>
            <strong>{{ $totalDepartures }}</strong>
            <span>Total Departures</span>
        </div>

        <div class="forecast-summary-card">
            <i class="fas fa-bed"></i>
            <strong>{{ $totalStayovers }}</strong>
            <span>Total Stayovers</span>
        </div>

        <div class="forecast-summary-card {{ $totalArrivalPickup >= 0 ? 'positive' : 'negative' }}">
            <i class="fas fa-chart-line"></i>
            <strong>{{ $totalArrivalPickup >= 0 ? '+' : '' }}{{ $totalArrivalPickup }}</strong>
            <span>Group Pick-up</span>
        </div>

        <div class="forecast-summary-card">
            <i class="fas fa-mug-hot"></i>
            <strong>{{ $totalBreakfast }}</strong>
            <span>Breakfast Forecast</span>
        </div>

        <div class="forecast-summary-card">
            <i class="fas fa-utensils"></i>
            <strong>{{ $totalDinner }}</strong>
            <span>Dinner Forecast</span>
        </div>
    </div>

    <div class="command-grid">

        <div class="command-card occupancy-card">
            <div class="command-head">
                <div>
                    <p>Group Occupancy</p>
                    <h2>AM vs PM Movement</h2>
                </div>

                <span class="{{ $groupOccupancyVariance >= 0 ? 'positive-pill' : 'negative-pill' }}">
                    {{ $groupOccupancyVariance >= 0 ? '+' : '' }}{{ $groupOccupancyVariance }}%
                </span>
            </div>

            <div class="big-occupancy">
                <div>
                    <span>AM Forecast</span>
                    <strong>{{ $groupAmOccupancy }}%</strong>
                </div>

                <i class="fas fa-arrow-right"></i>

                <div>
                    <span>PM Forecast</span>
                    <strong>{{ $groupPmOccupancy }}%</strong>
                </div>
            </div>

            <div class="occupancy-bars">
                <div>
                    <span>AM</span>
                    <div class="bar-track">
                        <div class="bar-fill am" style="width: {{ min(100, $groupAmOccupancy) }}%"></div>
                    </div>
                </div>

                <div>
                    <span>PM</span>
                    <div class="bar-track">
                        <div class="bar-fill pm" style="width: {{ min(100, $groupPmOccupancy) }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="command-card">
            <div class="command-head">
                <div>
                    <p>Top Occupancy</p>
                    <h2>Best Performing Hotels</h2>
                </div>
            </div>

            <div class="ranking-list">
                @forelse($topOccupancyHotels as $hotel)
                    <div class="rank-row">
                        <div>
                            <strong>{{ $hotel->name }}</strong>
                            <span>{{ $hotel->city ?? 'No city' }}</span>
                        </div>

                        <b class="good">{{ $hotel->latest_operation->occupancy_percentage }}%</b>
                    </div>
                @empty
                    <div class="mini-empty">No forecast data yet.</div>
                @endforelse
            </div>
        </div>

        <div class="command-card">
            <div class="command-head">
                <div>
                    <p>Needs Attention</p>
                    <h2>Lowest Occupancy</h2>
                </div>
            </div>

            <div class="ranking-list">
                @forelse($bottomOccupancyHotels as $hotel)
                    <div class="rank-row">
                        <div>
                            <strong>{{ $hotel->name }}</strong>
                            <span>{{ $hotel->city ?? 'No city' }}</span>
                        </div>

                        <b class="{{ $hotel->latest_operation->occupancy_percentage >= 70 ? 'good' : 'danger' }}">
                            {{ $hotel->latest_operation->occupancy_percentage }}%
                        </b>
                    </div>
                @empty
                    <div class="mini-empty">No forecast data yet.</div>
                @endforelse
            </div>
        </div>

        <div class="command-card">
            <div class="command-head">
                <div>
                    <p>Booking Pick-up</p>
                    <h2>Top Same-day Gains</h2>
                </div>
            </div>

            <div class="ranking-list">
                @forelse($topPickupHotels as $hotel)
                    <div class="rank-row">
                        <div>
                            <strong>{{ $hotel->name }}</strong>
                            <span>AM to PM arrivals</span>
                        </div>

                        <b class="{{ $hotel->arrival_pickup >= 0 ? 'good' : 'danger' }}">
                            {{ $hotel->arrival_pickup >= 0 ? '+' : '' }}{{ $hotel->arrival_pickup }}
                        </b>
                    </div>
                @empty
                    <div class="mini-empty">No AM/PM comparison yet.</div>
                @endforelse
            </div>
        </div>

    </div>

    <div class="content-split">

        <div class="card group-alerts-card">
            <div class="alerts-header">
                <div>
                    <p>Group Operations</p>
                    <h2>Today’s Alerts</h2>
                </div>

                <span>{{ count($groupAlerts) }} Alert{{ count($groupAlerts) === 1 ? '' : 's' }}</span>
            </div>

            @if(count($groupAlerts))
                <div class="alerts-grid">
                    @foreach($groupAlerts as $alert)
                        <div class="alert-item {{ $alert['level'] }}">
                            <div class="alert-icon">
                                <i class="fas {{ $alert['icon'] }}"></i>
                            </div>

                            <div>
                                <strong>{{ $alert['hotel'] }}</strong>
                                <p>{{ $alert['message'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="all-clear">
                    <i class="fas fa-circle-check"></i>
                    <div>
                        <strong>All hotels operating normally</strong>
                        <p>No major group alerts at the moment.</p>
                    </div>
                </div>
            @endif
        </div>

        <div class="card attention-card">
            <div class="alerts-header">
                <div>
                    <p>Exceptions</p>
                    <h2>Hotels Requiring Attention</h2>
                </div>
            </div>

            <div class="attention-list">
                @forelse($attentionHotels as $hotel)
                    <div class="attention-row">
                        <div>
                            <strong>{{ $hotel->name }}</strong>
                            <span>
                                Health {{ $hotel->health_score }}%
                                • Jobs {{ $hotel->open_maintenance_count }}
                                • Complaints {{ $hotel->pending_complaints_count }}
                            </span>
                        </div>

                        <b class="health-dot {{ $hotel->health_level }}"></b>
                    </div>
                @empty
                    <div class="all-clear">
                        <i class="fas fa-circle-check"></i>
                        <div>
                            <strong>No exception hotels</strong>
                            <p>Everything looks controlled today.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>

    </div>

    <div class="section-head">
        <div>
            <p>Hotel Status</p>
            <h2>Live Hotel Tiles</h2>
        </div>
        <span>{{ $hotels->count() }} Hotel{{ $hotels->count() === 1 ? '' : 's' }}</span>
    </div>

    <div class="status-tile-grid">
        @foreach($hotels as $hotel)
            <div class="status-tile health-{{ $hotel->health_level }}">
                <div>
                    <strong>{{ $hotel->code }}</strong>
                    <span>{{ $hotel->name }}</span>
                </div>

                <b>{{ $hotel->health_score }}%</b>
            </div>
        @endforeach
    </div>

    <div class="section-head">
        <div>
            <p>Hotel Health</p>
            <h2>Live Hotel Performance</h2>
        </div>
        <span>{{ $hotels->count() }} Hotel{{ $hotels->count() === 1 ? '' : 's' }}</span>
    </div>

    <div class="hotel-grid">
        @forelse($hotels as $hotel)
            <div class="hotel-card health-{{ $hotel->health_level }}">
                <div class="hotel-card-top">
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

                <div class="health-panel">
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

                    <div class="health-copy">
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
                            HK:
                            {{ $hotel->hk_completion === null ? 'No allocation' : $hotel->hk_completion.'%' }}
                            • OOO/OOI: {{ $hotel->ooo_rooms_count ?? 0 }}
                        </p>
                    </div>
                </div>

                <div class="forecast-panel">
                    <div class="forecast-title">
                        <div>
                            <p>Daily Operations Forecast</p>
                            <h3>
                                @if($hotel->latest_operation)
                                    {{ $hotel->latest_operation->snapshot }} Snapshot
                                @else
                                    No Forecast Entered
                                @endif
                            </h3>
                        </div>

                        @if($hotel->arrival_pickup !== null)
                            <span class="{{ $hotel->arrival_pickup >= 0 ? 'pickup-up' : 'pickup-down' }}">
                                {{ $hotel->arrival_pickup >= 0 ? '+' : '' }}{{ $hotel->arrival_pickup }} Pickup
                            </span>
                        @endif
                    </div>

                    @if($hotel->latest_operation)
                        <div class="forecast-grid">
                            <div>
                                <strong>{{ $hotel->latest_operation->occupancy_percentage }}%</strong>
                                <span>Occupancy</span>
                            </div>

                            <div>
                                <strong>{{ $hotel->latest_operation->arrivals }}</strong>
                                <span>Arrivals</span>
                            </div>

                            <div>
                                <strong>{{ $hotel->latest_operation->departures }}</strong>
                                <span>Departures</span>
                            </div>

                            <div>
                                <strong>{{ $hotel->latest_operation->stayovers }}</strong>
                                <span>Stayovers</span>
                            </div>

                            <div>
                                <strong>{{ $hotel->latest_operation->expected_breakfast }}</strong>
                                <span>Breakfast</span>
                            </div>

                            <div>
                                <strong>{{ $hotel->latest_operation->expected_dinner }}</strong>
                                <span>Dinner</span>
                            </div>
                        </div>
                    @else
                        <div class="no-forecast">
                            <i class="fas fa-clipboard-list"></i>
                            Forecast not submitted yet.
                        </div>
                    @endif
                </div>

                <div class="hotel-metrics">
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

                <div class="ops-metrics">
                    <div>
                        <i class="fas fa-utensils"></i>
                        <span>Restaurant</span>
                        <strong>{{ $hotel->today_restaurant_bookings_count ?? 0 }}</strong>
                    </div>

                    <div>
                        <i class="fas fa-broom"></i>
                        <span>HK Complete</span>
                        <strong>{{ $hotel->today_hk_completed_count ?? 0 }}/{{ $hotel->today_hk_allocations_count ?? 0 }}</strong>
                    </div>
                </div>

                <div class="risk-row">
                    <div class="{{ $hotel->open_maintenance_count > 0 ? 'risk danger' : 'risk safe' }}">
                        <i class="fas fa-screwdriver-wrench"></i>
                        {{ $hotel->open_maintenance_count }} Open Jobs
                    </div>

                    <div class="{{ $hotel->pending_complaints_count > 0 ? 'risk danger' : 'risk safe' }}">
                        <i class="fas fa-comments"></i>
                        {{ $hotel->pending_complaints_count }} Complaints
                    </div>
                </div>

                <a href="{{ route('dop.hotels.show', $hotel) }}" class="view-hotel-btn">
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
.dop-hero {
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
    box-shadow: 0 25px 90px rgba(0,0,0,.35);
}

.dop-hero p,
.alerts-header p,
.section-head p,
.command-head p {
    color: var(--primary);
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    font-size: 12px;
}

.dop-hero h1 {
    font-size: 38px;
    font-weight: 900;
    margin-top: 8px;
}

.dop-hero span {
    display: block;
    color: var(--text-muted);
    margin-top: 8px;
}

.hero-badge {
    padding: 12px 18px;
    border-radius: 999px;
    background: rgba(139,92,246,.14);
    color: #c4b5fd;
    border: 1px solid rgba(139,92,246,.35);
    font-weight: 900;
    display: flex;
    gap: 10px;
    align-items: center;
}

.forecast-summary-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
    gap: 18px;
    margin-bottom: 28px;
}

.forecast-summary-card,
.command-card {
    background: linear-gradient(180deg, rgba(23,23,23,.98), rgba(10,10,10,.98));
    border: 1px solid var(--border);
    border-radius: 24px;
    padding: 24px;
}

.forecast-summary-card i {
    color: var(--primary);
    font-size: 22px;
    margin-bottom: 14px;
}

.forecast-summary-card strong {
    display: block;
    font-size: 30px;
    font-weight: 900;
}

.forecast-summary-card span {
    display: block;
    color: var(--text-muted);
    margin-top: 5px;
    font-size: 11px;
    font-weight: 900;
    text-transform: uppercase;
}

.forecast-summary-card.positive strong,
.positive-pill,
.pickup-up {
    color: #4ade80;
}

.forecast-summary-card.negative strong,
.negative-pill,
.pickup-down {
    color: #f87171;
}

.command-grid {
    display: grid;
    grid-template-columns: 1.35fr 1fr 1fr 1fr;
    gap: 24px;
    margin-bottom: 28px;
}

.command-head {
    display: flex;
    justify-content: space-between;
    gap: 14px;
    align-items: center;
    margin-bottom: 18px;
}

.command-head h2 {
    font-size: 23px;
    font-weight: 900;
    margin-top: 5px;
}

.positive-pill,
.negative-pill {
    padding: 8px 12px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 900;
    background: rgba(255,255,255,.06);
}

.big-occupancy {
    display: grid;
    grid-template-columns: 1fr 40px 1fr;
    align-items: center;
    gap: 12px;
    margin: 18px 0 22px;
}

.big-occupancy div {
    background: #0a0a0a;
    border: 1px solid var(--border);
    border-radius: 18px;
    padding: 18px;
    text-align: center;
}

.big-occupancy span {
    color: var(--text-dim);
    font-size: 11px;
    font-weight: 900;
    text-transform: uppercase;
}

.big-occupancy strong {
    display: block;
    margin-top: 8px;
    font-size: 34px;
    font-weight: 900;
}

.big-occupancy i {
    text-align: center;
    color: var(--primary);
}

.occupancy-bars {
    display: flex;
    flex-direction: column;
    gap: 14px;
}

.occupancy-bars span {
    display: block;
    color: var(--text-muted);
    font-size: 11px;
    font-weight: 900;
    margin-bottom: 7px;
}

.bar-track {
    height: 12px;
    border-radius: 999px;
    background: rgba(255,255,255,.08);
    overflow: hidden;
}

.bar-fill {
    height: 100%;
    border-radius: 999px;
}

.bar-fill.am {
    background: linear-gradient(90deg, #8b5cf6, #a78bfa);
}

.bar-fill.pm {
    background: linear-gradient(90deg, #ec4899, #f472b6);
}

.ranking-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.rank-row,
.attention-row {
    display: flex;
    justify-content: space-between;
    gap: 14px;
    align-items: center;
    background: rgba(255,255,255,.035);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 14px;
}

.rank-row strong,
.attention-row strong {
    display: block;
    font-weight: 900;
}

.rank-row span,
.attention-row span {
    display: block;
    color: var(--text-muted);
    font-size: 12px;
    margin-top: 4px;
}

.rank-row b {
    min-width: 54px;
    height: 42px;
    border-radius: 14px;
    padding: 0 10px;
    display: flex;
    justify-content: center;
    align-items: center;
}

.rank-row b.good {
    background: rgba(34,197,94,.14);
    color: #4ade80;
}

.rank-row b.danger {
    background: rgba(239,68,68,.14);
    color: #f87171;
}

.mini-empty {
    color: var(--text-muted);
    background: rgba(255,255,255,.04);
    border-radius: 16px;
    padding: 18px;
    font-weight: 800;
}

.content-split {
    display: grid;
    grid-template-columns: 1.2fr .8fr;
    gap: 24px;
    margin-bottom: 28px;
}

.alerts-header,
.section-head {
    display: flex;
    justify-content: space-between;
    gap: 16px;
    align-items: center;
    margin-bottom: 20px;
}

.alerts-header h2,
.section-head h2 {
    font-size: 26px;
    font-weight: 900;
    margin-top: 4px;
}

.alerts-header span,
.section-head span {
    padding: 8px 14px;
    border-radius: 999px;
    background: rgba(139,92,246,.14);
    color: #c4b5fd;
    font-weight: 900;
    font-size: 12px;
    text-transform: uppercase;
}

.alerts-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 14px;
}

.alert-item {
    display: flex;
    gap: 14px;
    align-items: center;
    padding: 16px;
    border-radius: 18px;
    border: 1px solid var(--border);
    background: rgba(255,255,255,.04);
}

.alert-icon {
    width: 46px;
    height: 46px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.alert-item strong {
    display: block;
    font-weight: 900;
}

.alert-item p {
    color: var(--text-muted);
    margin-top: 4px;
    font-size: 13px;
}

.alert-item.danger .alert-icon {
    background: rgba(239,68,68,.14);
    color: #f87171;
}

.alert-item.warning .alert-icon {
    background: rgba(249,115,22,.14);
    color: #fb923c;
}

.alert-item.muted .alert-icon {
    background: rgba(113,113,122,.16);
    color: #a1a1aa;
}

.all-clear {
    display: flex;
    gap: 14px;
    align-items: center;
    padding: 18px;
    border-radius: 18px;
    background: rgba(34,197,94,.12);
    border: 1px solid rgba(34,197,94,.3);
    color: #4ade80;
}

.all-clear i {
    font-size: 28px;
}

.all-clear p {
    color: #86efac;
    margin-top: 3px;
}

.attention-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.health-dot {
    width: 18px;
    height: 18px;
    border-radius: 999px;
    flex-shrink: 0;
}

.health-dot.excellent {
    background: #22c55e;
}

.health-dot.good {
    background: #8b5cf6;
}

.health-dot.warning {
    background: #f97316;
}

.health-dot.danger {
    background: #ef4444;
}

.status-tile-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(185px, 1fr));
    gap: 14px;
    margin-bottom: 30px;
}

.status-tile {
    background: rgba(255,255,255,.035);
    border: 1px solid var(--border);
    border-radius: 18px;
    padding: 16px;
    display: flex;
    justify-content: space-between;
    gap: 12px;
    align-items: center;
}

.status-tile strong {
    display: block;
    font-size: 18px;
    font-weight: 900;
}

.status-tile span {
    display: block;
    color: var(--text-muted);
    margin-top: 4px;
    font-size: 12px;
}

.status-tile b {
    font-size: 20px;
    font-weight: 900;
}

.status-tile.health-excellent {
    border-color: rgba(34,197,94,.35);
}

.status-tile.health-good {
    border-color: rgba(139,92,246,.35);
}

.status-tile.health-warning {
    border-color: rgba(249,115,22,.45);
}

.status-tile.health-danger {
    border-color: rgba(239,68,68,.5);
}

.hotel-grid {
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
    position: relative;
    overflow: hidden;
}

.hotel-card:hover {
    border-color: rgba(139,92,246,.6);
    transform: translateY(-5px);
    box-shadow: 0 20px 70px rgba(139,92,246,.16);
}

.hotel-card.health-excellent {
    border-color: rgba(34,197,94,.28);
}

.hotel-card.health-good {
    border-color: rgba(139,92,246,.28);
}

.hotel-card.health-warning {
    border-color: rgba(249,115,22,.35);
}

.hotel-card.health-danger {
    border-color: rgba(239,68,68,.42);
}

.hotel-card-top {
    display: flex;
    justify-content: space-between;
    gap: 16px;
    align-items: flex-start;
    margin-bottom: 22px;
}

.hotel-card h2 {
    font-size: 22px;
    font-weight: 900;
}

.hotel-card p {
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

.health-panel,
.forecast-panel {
    display: flex;
    gap: 18px;
    align-items: center;
    padding: 16px;
    border-radius: 20px;
    background: rgba(255,255,255,.04);
    border: 1px solid var(--border);
    margin-bottom: 18px;
}

.forecast-panel {
    display: block;
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
    transition: .4s ease;
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

.health-copy strong {
    font-size: 20px;
    font-weight: 900;
}

.health-copy p {
    font-size: 13px;
    line-height: 1.5;
}

.forecast-title {
    display: flex;
    justify-content: space-between;
    gap: 12px;
    align-items: center;
    margin-bottom: 14px;
}

.forecast-title p {
    color: var(--primary);
    font-size: 10px;
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.forecast-title h3 {
    font-size: 17px;
    font-weight: 900;
    margin-top: 3px;
}

.pickup-up,
.pickup-down {
    padding: 7px 10px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 900;
    white-space: nowrap;
    background: rgba(255,255,255,.06);
}

.forecast-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 10px;
}

.forecast-grid div,
.hotel-metrics div,
.ops-metrics div {
    background: #0a0a0a;
    border: 1px solid var(--border);
    border-radius: 14px;
    padding: 12px 8px;
    text-align: center;
}

.forecast-grid strong,
.hotel-metrics strong,
.ops-metrics strong {
    display: block;
    font-size: 20px;
    font-weight: 900;
}

.forecast-grid span,
.hotel-metrics span,
.ops-metrics span {
    display: block;
    margin-top: 4px;
    color: var(--text-dim);
    font-size: 10px;
    font-weight: 900;
    text-transform: uppercase;
}

.no-forecast {
    padding: 16px;
    border-radius: 14px;
    background: rgba(249,115,22,.1);
    color: #fb923c;
    border: 1px solid rgba(249,115,22,.28);
    font-weight: 900;
    display: flex;
    gap: 10px;
    align-items: center;
}

.hotel-metrics {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 10px;
}

.ops-metrics {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    margin-top: 12px;
}

.ops-metrics i {
    color: var(--primary);
    margin-bottom: 8px;
}

.risk-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    margin-top: 18px;
}

.risk {
    padding: 12px;
    border-radius: 12px;
    font-weight: 900;
    font-size: 12px;
    display: flex;
    align-items: center;
    gap: 8px;
    justify-content: center;
}

.risk.safe {
    background: rgba(34,197,94,.12);
    color: #4ade80;
    border: 1px solid rgba(34,197,94,.3);
}

.risk.danger {
    background: rgba(239,68,68,.12);
    color: #f87171;
    border: 1px solid rgba(239,68,68,.3);
}

.view-hotel-btn {
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

.view-hotel-btn:hover {
    background: linear-gradient(135deg, #8b5cf6, #ec4899);
    color: white;
}

@media(max-width: 1350px) {
    .command-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .occupancy-card {
        grid-column: 1 / -1;
    }
}

@media(max-width: 1100px) {
    .content-split {
        grid-template-columns: 1fr;
    }
}

@media(max-width: 700px) {
    .command-grid,
    .hotel-grid {
        grid-template-columns: 1fr;
    }

    .hotel-metrics,
    .risk-row,
    .ops-metrics,
    .forecast-grid {
        grid-template-columns: 1fr 1fr;
    }

    .health-panel {
        flex-direction: column;
        text-align: center;
    }

    .dop-hero h1 {
        font-size: 30px;
    }

    .big-occupancy {
        grid-template-columns: 1fr;
    }
}

@media(max-width: 480px) {
    .hotel-metrics,
    .risk-row,
    .ops-metrics,
    .forecast-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection