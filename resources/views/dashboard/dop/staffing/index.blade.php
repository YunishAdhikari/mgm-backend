@extends('dashboard.dop.layout')

@section('title', 'Group Staffing')
@section('page-title', 'Group Staffing')

@section('content')
<section class="staffing-page">

    <div class="staffing-hero">
        <div>
            <p>MGM One / People Operations</p>
            <h1>Staffing Command Centre</h1>
            <span>Monitor active staff, rota coverage, attendance and department staffing across the hotel group.</span>
        </div>

        <div class="hero-pill">
            <i class="fas fa-user-shield"></i>
            People Operations View
        </div>
    </div>

    <!-- Stats Grid Layout Matrix -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon dark"><i class="fas fa-users"></i></div>
            <div>
                <div class="stat-value">{{ $totalActiveStaff }}</div>
                <div class="stat-label">Active Staff</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon green"><i class="fas fa-calendar-check"></i></div>
            <div>
                <div class="stat-value">{{ $workingToday }}</div>
                <div class="stat-label">Working Today</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon orange"><i class="fas fa-calendar-xmark"></i></div>
            <div>
                <div class="stat-value">{{ $dayOffToday }}</div>
                <div class="stat-label">Off / Sick / Holiday</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon red"><i class="fas fa-fingerprint"></i></div>
            <div>
                <div class="stat-value">{{ $clockedInToday }}</div>
                <div class="stat-label">Clocked In</div>
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
                        <label>Department</label>
                        <select name="department_id">
                            <option value="">All Departments</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" {{ request('department_id') == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                    @if($department->hotel)
                                        — {{ $department->hotel->name }}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label>Search</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, email, job title...">
                    </div>
                </div>

                <div class="filter-actions">
                    <button class="btn btn-primary">
                        <i class="fas fa-filter"></i>
                        Filter
                    </button>

                    <a href="{{ route('dop.staffing.index') }}" class="btn btn-secondary">
                        Reset
                    </a>
                </div>
            </form>

            <div class="staff-card">
                <div class="panel-head">
                    <div>
                        <p>People Operations</p>
                        <h2>Group Staff List</h2>
                    </div>

                    <span>{{ $staff->total() }} Staff</span>
                </div>

                <div class="staff-list">
                    @forelse($staff as $employee)
                        <div class="staff-row">
                            <div class="staff-avatar">
                                @if($employee->image)
                                    <img src="{{ asset('uploads/users/' . $employee->image) }}" alt="{{ $employee->name }}">
                                @else
                                    {{ strtoupper(substr($employee->name ?? 'S', 0, 1)) }}
                                @endif
                            </div>

                            <div class="staff-main">
                                <h3>{{ $employee->name }}</h3>
                                <p>
                                    {{ $employee->hotel->name ?? 'No Hotel' }}
                                    • {{ $employee->department->name ?? 'No Department' }}
                                    • {{ $employee->role->name ?? 'No Role' }}
                                </p>

                                <div class="staff-meta">
                                    <span><i class="fas fa-envelope"></i> {{ $employee->email ?? '-' }}</span>
                                    <span><i class="fas fa-briefcase"></i> {{ $employee->job_title ?? 'No title' }}</span>
                                    <span><i class="fas fa-id-card"></i> {{ $employee->employee_code ?? '-' }}</span>
                                </div>
                            </div>

                            <div class="staff-side">
                                <span class="status-pill active">
                                    {{ ucfirst($employee->status) }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="empty-box">
                            <i class="fas fa-users-slash"></i>
                            <strong>No staff found</strong>
                            <p>Try changing the filters.</p>
                        </div>
                    @endforelse
                </div>

                <div class="pagination-wrap">
                    {{ $staff->links() }}
                </div>
            </div>
        </div>

        <aside class="side-panel">
            <div class="ranking-card">
                <div class="panel-head">
                    <div>
                        <p>Staffing Distribution</p>
                        <h2>Hotels by Active Staff</h2>
                    </div>
                </div>

                <div class="ranking-list">
                    @foreach($hotelRankings as $rankedHotel)
                        <div class="ranking-row">
                            <div class="ranking-info">
                                <strong>{{ $rankedHotel->name }}</strong>
                                <span>{{ $rankedHotel->city ?? 'No city' }}</span>
                            </div>

                            <b>
                                {{ $rankedHotel->active_staff_count }}
                            </b>
                        </div>
                    @endforeach
                </div>
            </div>
        </aside>
    </div>

</section>

<style>
.staffing-page {
    width: 100%;
    max-width: 100%;
}

.staffing-hero {
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

.staffing-hero p,
.panel-head p {
    color: var(--primary);
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    font-size: 12px;
}

.staffing-hero h1 {
    font-size: 38px;
    font-weight: 900;
    margin-top: 8px;
}

.staffing-hero span {
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

/* --- Added Stats Grid Layout System --- */
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
.stat-icon.dark { background: rgba(255,255,255, 0.06); color: #e4e4e7; }
.stat-icon.green { background: rgba(34,197,94, 0.12); color: #22c55e; }
.stat-icon.orange { background: rgba(249,115,22, 0.12); color: #f97316; }
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
.staff-card,
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

.staff-list {
    display: flex;
    flex-direction: column;
    gap: 14px;
}

.staff-row {
    display: grid;
    grid-template-columns: 58px 1fr 110px;
    gap: 16px;
    align-items: center;
    padding: 18px;
    border-radius: 18px;
    background: rgba(255,255,255,.035);
    border: 1px solid var(--border);
    transition: .2s ease;
}

.staff-row:hover {
    border-color: rgba(139,92,246,.5);
    background: rgba(139,92,246,.07);
}

.staff-avatar {
    width: 58px;
    height: 58px;
    border-radius: 18px;
    background: linear-gradient(135deg, #8b5cf6, #ec4899);
    display: flex;
    justify-content: center;
    align-items: center;
    font-weight: 900;
    font-size: 22px;
    overflow: hidden;
}

.staff-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.staff-main h3 {
    font-size: 18px;
    font-weight: 900;
}

.staff-main p {
    color: var(--text-muted);
    margin-top: 6px;
}

.staff-meta {
    display: flex;
    gap: 14px;
    flex-wrap: wrap;
    margin-top: 12px;
    color: var(--text-dim);
    font-size: 12px;
    font-weight: 800;
}

.staff-meta i {
    color: var(--primary);
}

.staff-side {
    display: flex;
    justify-content: flex-end;
}

.status-pill {
    padding: 7px 11px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 900;
    text-transform: uppercase;
    width: fit-content;
}

.status-pill.active {
    background: rgba(34,197,94,.14);
    color: #4ade80;
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
    background: rgba(139,92,246,.14);
    color: #c4b5fd;
    display: flex;
    flex-shrink: 0;
    justify-content: center;
    align-items: center;
    font-weight: 900;
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

/* --- Breakpoints Matrix --- */
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

    .staff-row {
        grid-template-columns: 58px 1fr;
    }

    .staff-side {
        grid-column: 2;
        justify-content: flex-start;
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

    .staff-row {
        grid-template-columns: 1fr;
        text-align: center;
    }

    .staff-avatar {
        margin: auto;
    }

    .staff-side {
        grid-column: 1;
        justify-content: center;
    }

    .staffing-hero h1 {
        font-size: 30px;
    }
}
</style>
@endsection