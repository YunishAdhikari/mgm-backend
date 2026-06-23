@extends('dashboard.dop.layout')

@section('title', 'Group Housekeeping')
@section('page-title', 'Group Housekeeping')

@section('content')
<section class="housekeeping-page">

    <div class="hk-hero">
        <div>
            <p>MGM One / Housekeeping Operations</p>
            <h1>Housekeeping Live Board</h1>
            <span>Track room cleaning progress, DND, refused service and OOO/OOI rooms across all hotels.</span>
        </div>

        <div class="hero-pill">
            <i class="fas fa-broom"></i>
            {{ $completionRate }}% Complete
        </div>
    </div>

    <!-- Stats Grid Layout Matrix -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon dark"><i class="fas fa-bed"></i></div>
            <div>
                <div class="stat-value">{{ $totalAllocated }}</div>
                <div class="stat-label">Allocated Rooms</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon green"><i class="fas fa-circle-check"></i></div>
            <div>
                <div class="stat-value">{{ $completed }}</div>
                <div class="stat-label">Cleaned / Inspected</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon orange"><i class="fas fa-spinner"></i></div>
            <div>
                <div class="stat-value">{{ $inProgress }}</div>
                <div class="stat-label">In Progress</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon red"><i class="fas fa-hourglass-half"></i></div>
            <div>
                <div class="stat-value">{{ $pending }}</div>
                <div class="stat-label">Pending</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon orange"><i class="fas fa-door-closed"></i></div>
            <div>
                <div class="stat-value">{{ $dndRefused }}</div>
                <div class="stat-label">DND / Refused</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon red"><i class="fas fa-ban"></i></div>
            <div>
                <div class="stat-value">{{ $oooRooms }}</div>
                <div class="stat-label">OOO / OOI Rooms</div>
            </div>
        </div>
    </div>

    <div class="content-grid">
        <div class="main-panel">

            <form method="GET" class="filter-card">
                <div class="filter-grid">
                    <div>
                        <label>Date</label>
                        <input type="date" name="date" value="{{ request('date', $date) }}">
                    </div>

                    <div>
                        <label>Hotel</label>
                        <select name="hotel_id">
                            <option value="">All Hotels</option>
                            @foreach($hotels as $hotel)
                                <option value="{{ $hotel->id }}" {{ request('hotel_id') == $hotel->id ? 'selected' : '' }}>
                                    {{ $hotel->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label>Status</label>
                        <select name="cleaning_status">
                            <option value="">All Status</option>
                            <option value="assigned" {{ request('cleaning_status') === 'assigned' ? 'selected' : '' }}>Assigned</option>
                            <option value="pending" {{ request('cleaning_status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="in_progress" {{ request('cleaning_status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="cleaned" {{ request('cleaning_status') === 'cleaned' ? 'selected' : '' }}>Cleaned</option>
                            <option value="inspected" {{ request('cleaning_status') === 'inspected' ? 'selected' : '' }}>Inspected</option>
                            <option value="dnd" {{ request('cleaning_status') === 'dnd' ? 'selected' : '' }}>DND</option>
                            <option value="refused_service" {{ request('cleaning_status') === 'refused_service' ? 'selected' : '' }}>Refused Service</option>
                            <option value="maintenance_required" {{ request('cleaning_status') === 'maintenance_required' ? 'selected' : '' }}>Maintenance Required</option>
                        </select>
                    </div>

                    <div>
                        <label>Search</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Room, staff, hotel...">
                    </div>
                </div>

                <div class="filter-actions">
                    <button class="btn btn-primary">
                        <i class="fas fa-filter"></i>
                        Filter
                    </button>

                    <a href="{{ route('dop.housekeeping.index') }}" class="btn btn-secondary">
                        Reset
                    </a>
                </div>
            </form>

            <div class="hk-card">
                <div class="panel-head">
                    <div>
                        <p>Room Operations</p>
                        <h2>Live Room Allocation List</h2>
                    </div>

                    <span>{{ $allocations->total() }} Room{{ $allocations->total() === 1 ? '' : 's' }}</span>
                </div>

                <div class="allocation-list">
                    @forelse($allocations as $allocation)
                        <div class="allocation-row status-{{ $allocation->cleaning_status }}">
                            <div class="room-badge">
                                {{ $allocation->room->room_number ?? '-' }}
                            </div>

                            <div class="allocation-main">
                                <div class="allocation-title-row">
                                    <h3>{{ $allocation->hotel->name ?? 'No Hotel' }}</h3>

                                    <span class="status-pill {{ $allocation->cleaning_status }}">
                                        {{ ucwords(str_replace('_', ' ', $allocation->cleaning_status)) }}
                                    </span>
                                </div>

                                <p>
                                    {{ $allocation->roomStatusUpdate->status ?? 'No room status' }}
                                    • Assigned to {{ $allocation->assignedTo->name ?? 'Unassigned' }}
                                    • Estimated {{ $allocation->estimated_minutes ?? 0 }} mins
                                </p>

                                <div class="allocation-meta">
                                    <span><i class="fas fa-calendar"></i> {{ \Carbon\Carbon::parse($allocation->allocation_date)->format('d M Y') }}</span>

                                    @if($allocation->started_at)
                                        <span><i class="fas fa-play"></i> Started {{ $allocation->started_at->diffForHumans() }}</span>
                                    @endif

                                    @if($allocation->cleaned_at)
                                        <span><i class="fas fa-check"></i> Cleaned {{ $allocation->cleaned_at->diffForHumans() }}</span>
                                    @endif

                                    @if($allocation->inspected_at)
                                        <span><i class="fas fa-clipboard-check"></i> Inspected {{ $allocation->inspected_at->diffForHumans() }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="empty-box">
                            <i class="fas fa-broom"></i>
                            <strong>No housekeeping allocations found</strong>
                            <p>Try changing the filters or select another date.</p>
                        </div>
                    @endforelse
                </div>

                <div class="pagination-wrap">
                    {{ $allocations->links() }}
                </div>
            </div>
        </div>

        <aside class="side-panel">
            <div class="ranking-card">
                <div class="panel-head">
                    <div>
                        <p>Hotel Progress</p>
                        <h2>HK Completion Ranking</h2>
                    </div>
                </div>

                <div class="ranking-list">
                    @foreach($hotelRankings as $rankedHotel)
                        <div class="ranking-row">
                            <div class="ranking-info">
                                <strong>{{ $rankedHotel->name }}</strong>
                                <span>
                                    {{ $rankedHotel->today_hk_completed_count }}/{{ $rankedHotel->today_hk_allocations_count }} rooms
                                </span>
                            </div>

                            <b class="
                                @if($rankedHotel->hk_completion >= 80)
                                    good
                                @elseif($rankedHotel->hk_completion >= 50)
                                    warning
                                @else
                                    danger
                                @endif
                            ">
                                {{ $rankedHotel->hk_completion }}%
                            </b>
                        </div>
                    @endforeach
                </div>
            </div>
        </aside>
    </div>

</section>

<style>
.housekeeping-page {
    width: 100%;
    max-width: 100%;
}

.hk-hero {
    background:
        radial-gradient(circle at 15% 15%, rgba(139,92,246,.3), transparent 35%),
        radial-gradient(circle at 85% 20%, rgba(34,197,94,.18), transparent 35%),
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

.hk-hero p,
.panel-head p {
    color: var(--primary);
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    font-size: 12px;
}

.hk-hero h1 {
    font-size: 38px;
    font-weight: 900;
    margin-top: 8px;
}

.hk-hero span {
    display: block;
    color: var(--text-muted);
    margin-top: 8px;
}

.hero-pill {
    padding: 12px 18px;
    border-radius: 999px;
    background: rgba(34,197,94,.14);
    color: #86efac;
    border: 1px solid rgba(34,197,94,.35);
    font-weight: 900;
    display: flex;
    gap: 10px;
    align-items: center;
}

/* --- Added Stats Grid System --- */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 16px;
    margin-bottom: 28px;
}

.stat-card {
    background: linear-gradient(180deg, rgba(23,23,23,.98), rgba(10,10,10,.98));
    border: 1px solid var(--border);
    border-radius: 22px;
    padding: 20px 16px;
    display: flex;
    align-items: center;
    gap: 14px;
}

.stat-icon {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    flex-shrink: 0;
}
.stat-icon.dark { background: rgba(255,255,255, 0.06); color: #e4e4e7; }
.stat-icon.green { background: rgba(34,197,94, 0.12); color: #22c55e; }
.stat-icon.orange { background: rgba(249,115,22, 0.12); color: #f97316; }
.stat-icon.red { background: rgba(239,68,68, 0.12); color: #ef4444; }

.stat-value {
    font-size: 24px;
    font-weight: 900;
    color: #fff;
    line-height: 1.1;
}

.stat-label {
    font-size: 11px;
    color: var(--text-muted);
    font-weight: 800;
    margin-top: 4px;
}

/* --- Form Fields Fix --- */
.filter-grid select, 
.filter-grid input {
    width: 100%;
    background: #141414;
    border: 1px solid var(--border);
    color: white;
    padding: 10px 14px;
    border-radius: 10px;
    font-weight: 800;
    font-size: 13px;
    outline: none;
}
.filter-grid select:focus, 
.filter-grid input:focus {
    border-color: var(--primary);
}

.content-grid {
    display: grid;
    grid-template-columns: 1fr 340px;
    gap: 24px;
    align-items: start;
}

.filter-card,
.hk-card,
.ranking-card {
    background: linear-gradient(180deg, rgba(23,23,23,.98), rgba(10,10,10,.98));
    border: 1px solid var(--border);
    border-radius: 24px;
    padding: 24px;
}

.filter-card {
    margin-bottom: 24px;
}

.filter-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
}

.filter-grid label {
    display: block;
    color: var(--text-muted);
    font-size: 11px;
    font-weight: 900;
    text-transform: uppercase;
    margin-bottom: 8px;
}

.filter-actions {
    display: flex;
    gap: 12px;
    margin-top: 18px;
    flex-wrap: wrap;
}

.panel-head {
    display: flex;
    justify-content: space-between;
    gap: 16px;
    align-items: center;
    margin-bottom: 20px;
}

.panel-head h2 {
    font-size: 24px;
    font-weight: 900;
    margin-top: 4px;
}

.panel-head span {
    padding: 8px 12px;
    border-radius: 999px;
    background: rgba(139,92,246,.14);
    color: #c4b5fd;
    font-size: 12px;
    font-weight: 900;
}

.allocation-list {
    display: flex;
    flex-direction: column;
    gap: 14px;
}

.allocation-row {
    display: grid;
    grid-template-columns: 72px 1fr;
    gap: 16px;
    align-items: center;
    padding: 18px;
    border-radius: 18px;
    background: rgba(255,255,255,.035);
    border: 1px solid var(--border);
    border-left: 5px solid rgba(139,92,246,.5);
}

.allocation-row.status-assigned,
.allocation-row.status-pending {
    border-left-color: #f97316;
}

.allocation-row.status-in_progress {
    border-left-color: #3b82f6;
}

.allocation-row.status-cleaned,
.allocation-row.status-inspected {
    border-left-color: #22c55e;
}

.allocation-row.status-dnd,
.allocation-row.status-refused_service,
.allocation-row.status-maintenance_required {
    border-left-color: #ef4444;
}

.room-badge {
    width: 72px;
    height: 72px;
    border-radius: 20px;
    background: linear-gradient(135deg, #8b5cf6, #ec4899);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    font-weight: 900;
    flex-shrink: 0;
}

.allocation-title-row {
    display: flex;
    gap: 12px;
    align-items: center;
    flex-wrap: wrap;
}

.allocation-title-row h3 {
    font-size: 18px;
    font-weight: 900;
}

.allocation-main p {
    color: var(--text-muted);
    margin-top: 7px;
}

.allocation-meta {
    display: flex;
    gap: 14px;
    flex-wrap: wrap;
    margin-top: 12px;
    color: var(--text-dim);
    font-size: 12px;
    font-weight: 800;
}

.allocation-meta i {
    color: var(--primary);
}

.status-pill {
    padding: 7px 11px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 900;
    text-transform: uppercase;
    width: fit-content;
}

.status-pill.assigned,
.status-pill.pending {
    background: rgba(249,115,22,.14);
    color: #fb923c;
}

.status-pill.in_progress {
    background: rgba(59,130,246,.14);
    color: #60a5fa;
}

.status-pill.cleaned,
.status-pill.inspected {
    background: rgba(34,197,94,.14);
    color: #4ade80;
}

.status-pill.dnd,
.status-pill.refused_service,
.status-pill.maintenance_required {
    background: rgba(239,68,68,.14);
    color: #f87171;
}

.ranking-list {
    display: flex;
    flex-direction: column;
}

.ranking-row {
    display: flex;
    justify-content: space-between;
    gap: 14px;
    align-items: center;
    padding: 14px 0;
    border-bottom: 1px solid var(--border);
}

.ranking-row:last-child {
    border-bottom: none;
}

.ranking-info {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.ranking-row strong {
    display: block;
    font-weight: 900;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.ranking-row span {
    display: block;
    color: var(--text-muted);
    margin-top: 4px;
    font-size: 12px;
}

.ranking-row b {
    min-width: 52px;
    height: 42px;
    border-radius: 14px;
    padding: 0 10px;
    display: flex;
    flex-shrink: 0;
    justify-content: center;
    align-items: center;
    font-weight: 900;
}

.ranking-row b.good {
    background: rgba(34,197,94,.14);
    color: #4ade80;
}

.ranking-row b.warning {
    background: rgba(249,115,22,.14);
    color: #fb923c;
}

.ranking-row b.danger {
    background: rgba(239,68,68,.14);
    color: #f87171;
}

.empty-box {
    text-align: center;
    padding: 42px;
    color: var(--text-muted);
}

.empty-box i {
    display: block;
    font-size: 32px;
    color: #c4b5fd;
    margin-bottom: 12px;
}

.pagination-wrap {
    margin-top: 20px;
}

/* --- Breakpoints System --- */
@media(max-width: 1600px) {
    .stats-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media(max-width: 1200px) {
    .content-grid {
        grid-template-columns: 1fr;
    }

    .side-panel {
        order: -1;
    }
}

@media(max-width: 900px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .filter-grid {
        grid-template-columns: 1fr 1fr;
    }

    .allocation-row {
        grid-template-columns: 1fr;
    }

    .room-badge {
        width: 64px;
        height: 64px;
    }
}

@media(max-width: 520px) {
    .stats-grid {
        grid-template-columns: 1fr;
        gap: 12px;
    }

    .filter-grid {
        grid-template-columns: 1fr;
    }

    .hk-hero h1 {
        font-size: 30px;
    }
}
</style>
@endsection