@extends('dashboard.dop.layout')

@section('title', 'Group Complaints')
@section('page-title', 'Group Complaints')

@section('content')
<section class="complaints-page">

    <div class="complaints-hero">
        <div>
            <p>MGM One / Guest Experience</p>
            <h1>Complaints Command Centre</h1>
            <span>Monitor guest complaints, urgent service issues and hotel complaint trends across the group.</span>
        </div>

        <div class="hero-pill">
            <i class="fas fa-headset"></i>
            Guest Experience View
        </div>
    </div>

    <!-- Stats Grid Layout Matrix -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon orange"><i class="fas fa-hourglass-half"></i></div>
            <div>
                <div class="stat-value">{{ $pendingComplaints }}</div>
                <div class="stat-label">Pending</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon dark"><i class="fas fa-spinner"></i></div>
            <div>
                <div class="stat-value">{{ $inProgressComplaints }}</div>
                <div class="stat-label">In Progress</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon green"><i class="fas fa-circle-check"></i></div>
            <div>
                <div class="stat-value">{{ $resolvedToday }}</div>
                <div class="stat-label">Resolved Today</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon red"><i class="fas fa-triangle-exclamation"></i></div>
            <div>
                <div class="stat-value">{{ $urgentComplaints }}</div>
                <div class="stat-label">Urgent Open</div>
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
                            <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Resolved</option>
                            <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Closed</option>
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
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Guest, room, title, category...">
                    </div>
                </div>

                <div class="filter-actions">
                    <button class="btn btn-primary">
                        <i class="fas fa-filter"></i>
                        Filter
                    </button>

                    <a href="{{ route('dop.complaints.index') }}" class="btn btn-secondary">
                        Reset
                    </a>
                </div>
            </form>

            <div class="complaints-card">
                <div class="panel-head">
                    <div>
                        <p>Guest Issues</p>
                        <h2>Group Complaint List</h2>
                    </div>

                    <span>{{ $complaints->total() }} Complaint{{ $complaints->total() === 1 ? '' : 's' }}</span>
                </div>

                <div class="complaints-list">
                    @forelse($complaints as $complaint)
                        @php
                            $ageHours = $complaint->created_at ? (int) $complaint->created_at->diffInHours(now()) : 0;

                            if ($ageHours < 2) {
                                $sla = 'green';
                            } elseif ($ageHours < 8) {
                                $sla = 'yellow';
                            } elseif ($ageHours < 24) {
                                $sla = 'orange';
                            } else {
                                $sla = 'red';
                            }
                        @endphp

                        <div class="complaint-row priority-{{ $complaint->priority }}">
                            <div class="complaint-main">
                                <div class="complaint-title-row">
                                    <h3>{{ $complaint->title }}</h3>

                                    <span class="priority-pill {{ $complaint->priority }}">
                                        {{ ucfirst($complaint->priority) }}
                                    </span>
                                </div>

                                <p>
                                    {{ $complaint->hotel->name ?? 'No Hotel' }}
                                    • Room {{ $complaint->room_number ?? '-' }}
                                    • {{ $complaint->category ?? 'No Category' }}
                                </p>

                                <div class="complaint-meta">
                                    <span><i class="fas fa-user"></i> {{ $complaint->guest_name ?? 'Guest' }}</span>
                                    <span><i class="fas fa-user-check"></i> {{ $complaint->handler->name ?? 'Unassigned' }}</span>
                                    <span><i class="fas fa-clock"></i> {{ $complaint->created_at?->diffForHumans() }}</span>
                                </div>
                            </div>

                            <div class="complaint-side">
                                <span class="status-pill {{ $complaint->status }}">
                                    {{ ucwords(str_replace('_', ' ', $complaint->status)) }}
                                </span>

                                <span class="age-pill {{ $sla }}">
                                    {{ $ageHours }}h
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="empty-box">
                            <i class="fas fa-circle-check"></i>
                            <strong>No complaints found</strong>
                            <p>Try changing the filters.</p>
                        </div>
                    @endforelse
                </div>

                <div class="pagination-wrap">
                    {{ $complaints->links() }}
                </div>
            </div>
        </div>

        <aside class="side-panel">
            <div class="ranking-card">
                <div class="panel-head">
                    <div>
                        <p>Risk Ranking</p>
                        <h2>Hotels by Complaints</h2>
                    </div>
                </div>

                <div class="ranking-list">
                    @foreach($hotelRankings as $rankedHotel)
                        <div class="ranking-row">
                            <div class="ranking-info">
                                <strong>{{ $rankedHotel->name }}</strong>
                                <span>{{ $rankedHotel->city ?? 'No city' }}</span>
                            </div>

                            <b class="{{ $rankedHotel->pending_complaints_count > 0 ? 'hot' : 'clear' }}">
                                {{ $rankedHotel->pending_complaints_count }}
                            </b>
                        </div>
                    @endforeach
                </div>
            </div>
        </aside>
    </div>

</section>

<style>
.complaints-page {
    width: 100%;
    max-width: 100%;
}

.complaints-hero {
    background:
        radial-gradient(circle at 15% 15%, rgba(139,92,246,.3), transparent 35%),
        radial-gradient(circle at 85% 20%, rgba(239,68,68,.22), transparent 35%),
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

.complaints-hero p,
.panel-head p {
    color: var(--primary);
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    font-size: 12px;
}

.complaints-hero h1 {
    font-size: 38px;
    font-weight: 900;
    margin-top: 8px;
}

.complaints-hero span {
    display: block;
    color: var(--text-muted);
    margin-top: 8px;
}

.hero-pill {
    padding: 12px 18px;
    border-radius: 999px;
    background: rgba(239,68,68,.14);
    color: #fca5a5;
    border: 1px solid rgba(239,68,68,.35);
    font-weight: 900;
    display: flex;
    gap: 10px;
    align-items: center;
}

/* --- Added Missing Metric Cards Structure --- */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-bottom: 28px;
}

.stat-card {
    background: linear-gradient(180deg, rgba(23,23,23,.98), rgba(10,10,10,.98));
    border: 1px solid var(--border);
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
.stat-icon.dark { background: rgba(255,255,255, 0.06); color: #e4e4e7; }
.stat-icon.green { background: rgba(34,197,94, 0.12); color: #22c55e; }
.stat-icon.red { background: rgba(239,68,68, 0.12); color: #ef4444; }

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

/* --- Form Fields Base Fix --- */
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
.complaints-card,
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

.complaints-list {
    display: flex;
    flex-direction: column;
    gap: 14px;
}

.complaint-row {
    display: grid;
    grid-template-columns: 1fr 150px;
    gap: 18px;
    padding: 18px;
    border-radius: 18px;
    background: rgba(255,255,255,.035);
    border: 1px solid var(--border);
    border-left: 5px solid rgba(139,92,246,.5);
}

.complaint-row.priority-low { border-left-color: #22c55e; }
.complaint-row.priority-medium { border-left-color: #3b82f6; }
.complaint-row.priority-high { border-left-color: #f97316; }
.complaint-row.priority-urgent { border-left-color: #ef4444; }

.complaint-title-row {
    display: flex;
    gap: 12px;
    align-items: center;
    flex-wrap: wrap;
}

.complaint-title-row h3 {
    font-size: 18px;
    font-weight: 900;
}

.complaint-main p {
    color: var(--text-muted);
    margin-top: 7px;
}

.complaint-meta {
    display: flex;
    gap: 14px;
    flex-wrap: wrap;
    margin-top: 12px;
    color: var(--text-dim);
    font-size: 12px;
    font-weight: 800;
}

.complaint-meta i {
    color: var(--primary);
}

.complaint-side {
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
.status-pill.resolved { background: rgba(34,197,94,.14); color: #4ade80; }
.status-pill.closed { background: rgba(113,113,122,.16); color: #a1a1aa; }

.age-pill.green { background: rgba(34,197,94,.14); color: #4ade80; }
.age-pill.yellow { background: rgba(234,179,8,.14); color: #facc15; }
.age-pill.orange { background: rgba(249,115,22,.14); color: #fb923c; }
.age-pill.red { background: rgba(239,68,68,.14); color: #f87171; }

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

/* --- Responsive & Wrap Rules --- */
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

    .complaint-row {
        grid-template-columns: 1fr;
    }

    .complaint-side {
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

    .complaints-hero h1 {
        font-size: 30px;
    }
}
</style>
@endsection