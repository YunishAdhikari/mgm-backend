@extends('dashboard.dop.layout')

@section('title', 'Group Maintenance')
@section('page-title', 'Group Maintenance')

@section('content')
<section class="maintenance-page">

    <div class="maintenance-hero">
        <div>
            <p>MGM One / Group Engineering</p>
            <h1>Maintenance Operations Centre</h1>
            <span>Monitor maintenance issues, urgent jobs and engineering workload across all hotels.</span>
        </div>

        <div class="hero-pill">
            <i class="fas fa-bolt"></i>
            Live Engineering View
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon orange"><i class="fas fa-screwdriver-wrench"></i></div>
            <div>
                <div class="stat-value">{{ $openJobs }}</div>
                <div class="stat-label">Open Jobs</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon red"><i class="fas fa-triangle-exclamation"></i></div>
            <div>
                <div class="stat-value">{{ $urgentJobs }}</div>
                <div class="stat-label">Urgent Jobs</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon green"><i class="fas fa-circle-check"></i></div>
            <div>
                <div class="stat-value">{{ $completedToday }}</div>
                <div class="stat-label">Completed Today</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon red-dim"><i class="fas fa-clock"></i></div>
            <div>
                <div class="stat-value">{{ $over24Hours }}</div>
                <div class="stat-label">Over 24 Hours</div>
            </div>
        </div>
    </div>

    <div class="content-grid">
        <div class="main-panel">

            <form method="GET" class="filter-card">
                <div class="filter-grid">
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
                        <select name="status">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>

                    <div>
                        <label>Priority</label>
                        <select name="priority">
                            <option value="">All Priority</option>
                            <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>High</option>
                            <option value="urgent" {{ request('priority') === 'urgent' ? 'selected' : '' }}>Urgent</option>
                        </select>
                    </div>

                    <div>
                        <label>Search</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Room, hotel, title...">
                    </div>
                </div>

                <div class="filter-actions">
                    <button class="btn btn-primary">
                        <i class="fas fa-filter"></i>
                        Filter
                    </button>

                    <a href="{{ route('dop.maintenance.index') }}" class="btn btn-secondary">
                        Reset
                    </a>
                </div>
            </form>

            <div class="jobs-card">
                <div class="panel-head">
                    <div>
                        <p>Maintenance Jobs</p>
                        <h2>Group Job List</h2>
                    </div>

                    <span>{{ $jobs->total() }} Jobs</span>
                </div>

                <div class="jobs-list">
                    @forelse($jobs as $job)
                        @php
                            $ageHours = $job->created_at ? $job->created_at->diffInHours(now()) : 0;
                        @endphp

                        <div class="job-row priority-{{ $job->priority }}">
                            <div class="job-main">
                                <div class="job-title-row">
                                    <h3>{{ $job->title }}</h3>

                                    <span class="priority-pill {{ $job->priority }}">
                                        {{ ucfirst($job->priority) }}
                                    </span>
                                </div>

                                <p>
                                    {{ $job->hotel->name ?? 'No Hotel' }}
                                    • {{ $job->department->name ?? 'No Department' }}
                                    • Room {{ $job->room_number ?? '-' }}
                                </p>

                                <div class="job-meta">
                                    <span><i class="fas fa-user"></i> {{ $job->reporter->name ?? 'Unknown' }}</span>
                                    <span><i class="fas fa-user-gear"></i> {{ $job->assignedUser->name ?? 'Unassigned' }}</span>
                                    <span><i class="fas fa-clock"></i> {{ $job->created_at?->diffForHumans() }}</span>
                                </div>
                            </div>

                            <div class="job-side">
                                <span class="status-pill {{ $job->status }}">
                                    {{ ucwords(str_replace('_', ' ', $job->status)) }}
                                </span>

                                <span class="age-pill {{ $ageHours >= 24 && in_array($job->status, ['pending','in_progress']) ? 'overdue' : '' }}">
                                    {{-- {{ $ageHours }}h old --}}
                                    {{ $job->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="empty-box">
                            <i class="fas fa-circle-check"></i>
                            <strong>No maintenance jobs found</strong>
                            <p>Try changing the filters.</p>
                        </div>
                    @endforelse
                </div>

                <div class="pagination-wrap">
                    {{ $jobs->links() }}
                </div>
            </div>
        </div>

        <aside class="side-panel">
            <div class="ranking-card">
                <div class="panel-head">
                    <div>
                        <p>Risk Ranking</p>
                        <h2>Hotels by Open Jobs</h2>
                    </div>
                </div>

                <div class="ranking-list">
                    @foreach($hotelRankings as $rankedHotel)
                        <div class="ranking-row">
                            <div class="ranking-info">
                                <strong>{{ $rankedHotel->name }}</strong>
                                <span>{{ $rankedHotel->city ?? 'No city' }}</span>
                            </div>

                            <b class="{{ $rankedHotel->open_maintenance_count > 0 ? 'hot' : 'clear' }}">
                                {{ $rankedHotel->open_maintenance_count }}
                            </b>
                        </div>
                    @endforeach
                </div>
            </div>
        </aside>
    </div>

</section>

<style>
.maintenance-page {
    width: 100%;
    max-width: 100%;
}

.maintenance-hero {
    background:
        radial-gradient(circle at 15% 15%, rgba(139,92,246,.3), transparent 35%),
        radial-gradient(circle at 85% 20%, rgba(249,115,22,.22), transparent 35%),
        linear-gradient(135deg, rgba(18,18,20,.96), rgba(8,8,10,.96));
    border: 2px solid var(--border);
    border-radius: 28px;
    padding: 34px;
    margin-bottom: 24px;
    display: flex;
    justify-content: space-between;
    gap: 18px;
    align-items: center;
    flex-wrap: wrap;
}

.maintenance-hero p,
.panel-head p {
    color: var(--primary);
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    font-size: 12px;
}

.maintenance-hero h1 {
    font-size: 38px;
    font-weight: 900;
    margin-top: 8px;
}

.maintenance-hero span {
    display: block;
    color: var(--text-muted);
    margin-top: 8px;
}

.hero-pill {
    padding: 12px 20px;
    border-radius: 999px;
    background: rgba(249,115,22,.14);
    color: #fb923c;
    border: 1px solid rgba(249,115,22,.35);
    font-weight: 900;
    display: flex;
    gap: 10px;
    align-items: center;
    font-size: 13px;
}

/* --- Added Missing Metric Cards Structure --- */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-bottom: 24px;
}

.stat-card {
    background: linear-gradient(180deg, rgba(23,23,23,.98), rgba(10,10,10,.98));
    border: 2px solid var(--border);
    border-radius: 22px;
    padding: 22px;
    display: flex;
    align-items: center;
    gap: 18px;
}

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
}
.stat-icon.orange { background: rgba(249,115,22, 0.12); color: #f97316; }
.stat-icon.red { background: rgba(239,68,68, 0.12); color: #ef4444; }
.stat-icon.green { background: rgba(34,197,94, 0.12); color: #22c55e; }
.stat-icon.red-dim { background: rgba(220,38,38, 0.12); color: #f87171; }

.stat-value {
    font-size: 28px;
    font-weight: 900;
    color: #fff;
    line-height: 1.1;
}

.stat-label {
    font-size: 12px;
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
.jobs-card,
.ranking-card {
    background: linear-gradient(180deg, rgba(23,23,23,.98), rgba(10,10,10,.98));
    border: 2px solid var(--border);
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
    padding: 8px 14px;
    border-radius: 999px;
    background: rgba(139,92,246,.14);
    color: #c4b5fd;
    font-size: 12px;
    font-weight: 900;
}

.jobs-list {
    display: flex;
    flex-direction: column;
    gap: 14px;
}

.job-row {
    display: grid;
    grid-template-columns: 1fr 150px;
    gap: 18px;
    padding: 18px;
    border-radius: 18px;
    background: rgba(255,255,255,.02);
    border: 1px solid var(--border);
    border-left: 5px solid rgba(139,92,246,.5);
}

.job-row.priority-low { border-left-color: #22c55e; }
.job-row.priority-medium { border-left-color: #3b82f6; }
.job-row.priority-high { border-left-color: #f97316; }
.job-row.priority-urgent { border-left-color: #ef4444; }

.job-title-row {
    display: flex;
    gap: 12px;
    align-items: center;
    flex-wrap: wrap;
}

.job-title-row h3 {
    font-size: 18px;
    font-weight: 900;
}

.job-main p {
    color: var(--text-muted);
    margin-top: 7px;
    font-weight: 700;
}

.job-meta {
    display: flex;
    gap: 14px;
    flex-wrap: wrap;
    margin-top: 12px;
    color: var(--text-muted);
    font-size: 12px;
    font-weight: 800;
}

.job-meta i {
    color: var(--primary);
}

.job-side {
    display: flex;
    flex-direction: column;
    gap: 10px;
    align-items: flex-end;
    justify-content: center;
}

.priority-pill,
.status-pill,
.age-pill {
    padding: 7px 11px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 900;
    text-transform: uppercase;
    width: fit-content;
}

.priority-pill.low { background: rgba(34,197,94,.14); color: #4ade80; }
.priority-pill.medium { background: rgba(59,130,246,.14); color: #60a5fa; }
.priority-pill.high { background: rgba(249,115,22,.14); color: #fb923c; }
.priority-pill.urgent { background: rgba(239,68,68,.14); color: #f87171; }

.status-pill.pending { background: rgba(249,115,22,.14); color: #fb923c; }
.status-pill.in_progress { background: rgba(59,130,246,.14); color: #60a5fa; }
.status-pill.completed { background: rgba(34,197,94,.14); color: #4ade80; }
.status-pill.cancelled { background: rgba(113,113,122,.16); color: #a1a1aa; }

.age-pill {
    background: rgba(255,255,255,.06);
    color: var(--text-muted);
}

.age-pill.overdue {
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
    width: 42px;
    height: 42px;
    border-radius: 14px;
    display: flex;
    flex-shrink: 0;
    justify-content: center;
    align-items: center;
    font-weight: 900;
}

.ranking-row b.hot {
    background: rgba(239,68,68,.14);
    color: #f87171;
}

.ranking-row b.clear {
    background: rgba(34,197,94,.14);
    color: #4ade80;
}

.empty-box {
    text-align: center;
    padding: 42px;
    color: var(--text-muted);
}

.empty-box i {
    display: block;
    font-size: 32px;
    color: #4ade80;
    margin-bottom: 12px;
}

.pagination-wrap {
    margin-top: 20px;
}

/* --- Responsive Layout Rules --- */
@media(max-width: 1400px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
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
    .filter-grid {
        grid-template-columns: 1fr 1fr;
    }

    .job-row {
        grid-template-columns: 1fr;
    }

    .job-side {
        align-items: flex-start;
        flex-direction: row;
        justify-content: space-between;
        border-top: 1px solid var(--border);
        padding-top: 12px;
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

    .maintenance-hero h1 {
        font-size: 30px;
    }
}
</style>
@endsection