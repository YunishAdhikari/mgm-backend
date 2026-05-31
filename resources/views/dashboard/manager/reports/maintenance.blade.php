@extends('dashboard.manager.layout')

@section('content')

<style>
    :root {
        --bg-page: #09090b;
        --bg-card: #18181b;
        --bg-input: #27272a;
        --text-main: #fafafa;
        --text-muted: #a1a1aa;
        --text-dim: #71717a;
        --border: #3f3f46;
        --primary: #8b5cf6;
    }

    .page-wrap {
        padding: 20px;
        max-width: 1200px;
    }

    .page-title {
        font-size: 26px;
        font-weight: 800;
        margin-bottom: 6px;
        color: var(--text-main);
    }

    .page-subtitle {
        color: var(--text-muted);
        margin-bottom: 20px;
    }

    .card-box {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 18px;
        padding: 20px;
        margin-bottom: 20px;
    }

    .card-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--text-main);
        margin-bottom: 16px;
    }

    /* Filter Form */
    .filter-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 14px;
    }

    .filter-field label {
        display: block;
        color: var(--text-muted);
        font-size: 12px;
        font-weight: 700;
        margin-bottom: 6px;
    }

    .filter-field input,
    .filter-field select {
        width: 100%;
        padding: 10px 12px;
        border: 2px solid var(--border);
        border-radius: 10px;
        background: var(--bg-input);
        color: var(--text-main);
        font-size: 14px;
    }

    .filter-field input:focus,
    .filter-field select:focus {
        outline: none;
        border-color: var(--primary);
    }

    .filter-actions {
        display: flex;
        gap: 10px;
        align-items: flex-end;
    }

    .btn-filter {
        background: var(--primary);
        color: white;
        border: none;
        padding: 11px 18px;
        border-radius: 10px;
        font-weight: 800;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-filter:hover { background: #a78bfa; }

    .btn-reset {
        background: var(--bg-input);
        color: var(--text-main);
        padding: 11px 18px;
        border-radius: 10px;
        font-weight: 800;
        text-decoration: none;
        border: 1px solid var(--border);
    }

    .btn-download {
        background: #ef4444;
        color: white;
        padding: 11px 18px;
        border-radius: 10px;
        font-weight: 800;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    /* Summary Cards */
    .summary-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 14px;
    }

    .summary-card {
        background: var(--bg-input);
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 18px;
    }

    .summary-label {
        color: var(--text-muted);
        font-size: 13px;
        font-weight: 600;
    }

    .summary-value {
        font-size: 28px;
        font-weight: 900;
        margin-top: 6px;
        color: var(--text-main);
    }

    .summary-value.pending { color: #f59e0b; }
    .summary-value.progress { color: #3b82f6; }
    .summary-value.completed { color: #22c55e; }
    .summary-value.urgent { color: #ef4444; }

    /* Jobs Grid - No Horizontal Scroll */
    .jobs-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 14px;
    }

    .job-card {
        background: var(--bg-input);
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 16px;
        transition: all 0.3s ease;
    }

    .job-card:hover {
        border-color: var(--primary);
        box-shadow: 0 0 20px rgba(139, 92, 246, 0.15);
    }

    .job-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 12px;
    }

    .job-title {
        font-size: 15px;
        font-weight: 700;
        color: var(--text-main);
    }

    .job-location {
        font-size: 12px;
        color: var(--text-muted);
    }

    .badge {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
    }

    .badge-low { background: rgba(16, 185, 129, 0.15); color: #6ee7b7; }
    .badge-medium { background: rgba(59, 130, 246, 0.15); color: #60a5fa; }
    .badge-high { background: rgba(234, 179, 8, 0.15); color: #facc15; }
    .badge-urgent { background: rgba(239, 68, 68, 0.15); color: #f87171; }
    .badge-pending { background: rgba(245, 158, 11, 0.15); color: #fbbf24; }
    .badge-in_progress { background: rgba(59, 130, 246, 0.15); color: #60a5fa; }
    .badge-completed { background: rgba(16, 185, 129, 0.15); color: #6ee7b7; }
    .badge-cancelled { background: rgba(113, 113, 122, 0.15); color: #a1a1aa; }

    .job-description {
        font-size: 13px;
        color: var(--text-muted);
        margin-bottom: 12px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .job-meta {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
        padding: 10px 0;
        border-top: 1px solid var(--border);
        border-bottom: 1px solid var(--border);
        margin-bottom: 12px;
    }

    .meta-item {
        font-size: 12px;
    }

    .meta-label {
        color: var(--text-dim);
        font-weight: 600;
        text-transform: uppercase;
        font-size: 10px;
    }

    .meta-value {
        color: var(--text-main);
        font-weight: 600;
    }

    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: var(--text-dim);
    }

    @media (max-width: 900px) {
        .summary-grid { grid-template-columns: repeat(3, 1fr); }
    }

    @media (max-width: 700px) {
        .page-wrap { padding: 14px; }
        .page-title { font-size: 22px; }
        
        .filter-grid { grid-template-columns: repeat(2, 1fr); }
        .filter-actions { grid-column: span 2; flex-direction: column; }
        
        .summary-grid { grid-template-columns: repeat(2, 1fr); }
        
        .jobs-grid { grid-template-columns: 1fr; }
    }

    @media (max-width: 500px) {
        .filter-grid { grid-template-columns: 1fr; }
        .filter-actions { grid-column: span 1; }
        .summary-grid { grid-template-columns: 1fr; }
    }
</style>

<div class="page-wrap">
    <h1 class="page-title">Maintenance Report</h1>
    <p class="page-subtitle">Filter and review maintenance jobs by date, status and priority.</p>

    <div class="card-box">
        <form method="GET" action="{{ route('manager.reports.maintenance') }}" class="filter-grid">
            <div class="filter-field">
                <label>Date From</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}">
            </div>

            <div class="filter-field">
                <label>Date To</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}">
            </div>

            <div class="filter-field">
                <label>Status</label>
                <select name="status">
                    <option value="">All</option>
                    @foreach(['pending', 'in_progress', 'completed', 'cancelled'] as $status)
                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                            {{ ucwords(str_replace('_', ' ', $status)) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-field">
                <label>Priority</label>
                <select name="priority">
                    <option value="">All</option>
                    @foreach(['low', 'medium', 'high', 'urgent'] as $priority)
                        <option value="{{ $priority }}" {{ request('priority') == $priority ? 'selected' : '' }}>
                            {{ ucfirst($priority) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-actions">
                <button type="submit" class="btn-filter">Generate</button>
                <a href="{{ route('manager.reports.maintenance') }}" class="btn-reset">Reset</a>
            </div>
        </form>
    </div>

    <div class="summary-grid">
        <div class="summary-card">
            <div class="summary-label">Total Jobs</div>
            <div class="summary-value">{{ $totalJobs }}</div>
        </div>

        <div class="summary-card">
            <div class="summary-label">Pending</div>
            <div class="summary-value pending">{{ $pendingJobs }}</div>
        </div>

        <div class="summary-card">
            <div class="summary-label">In Progress</div>
            <div class="summary-value progress">{{ $inProgressJobs }}</div>
        </div>

        <div class="summary-card">
            <div class="summary-label">Completed</div>
            <div class="summary-value completed">{{ $completedJobs }}</div>
        </div>

        <div class="summary-card">
            <div class="summary-label">Urgent</div>
            <div class="summary-value urgent">{{ $urgentJobs }}</div>
        </div>
    </div>

    <div class="card-box">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;flex-wrap:wrap;gap:12px;">
            <h3 class="card-title" style="margin:0;">Maintenance Jobs</h3>
            <a href="{{ route('manager.reports.maintenance.pdf', request()->query()) }}" class="btn-download">
                <i class="fas fa-download"></i> Download PDF
            </a>
        </div>

        <div class="jobs-grid">
            @forelse($jobs as $job)
                <div class="job-card">
                    <div class="job-header">
                        <div>
                            <div class="job-title">{{ $job->title }}</div>
                            <div class="job-location">
                                {{ $job->location ?? '-' }}@if($job->room_number) • Room {{ $job->room_number }}@endif
                            </div>
                        </div>
                        <span class="badge badge-{{ $job->priority }}">{{ ucfirst($job->priority) }}</span>
                    </div>

                    <div class="job-description">{{ Str::limit($job->description, 80) }}</div>

                    <div class="job-meta">
                        <div class="meta-item">
                            <div class="meta-label">Date</div>
                            <div class="meta-value">{{ $job->created_at->format('d M Y') }}</div>
                        </div>
                        <div class="meta-item">
                            <div class="meta-label">Status</div>
                            <span class="badge badge-{{ $job->status }}">{{ ucwords(str_replace('_', ' ', $job->status)) }}</span>
                        </div>
                        <div class="meta-item">
                            <div class="meta-label">Department</div>
                            <div class="meta-value">{{ $job->department->name ?? '-' }}</div>
                        </div>
                        <div class="meta-item">
                            <div class="meta-label">Assigned To</div>
                            <div class="meta-value">{{ $job->assignedTo->name ?? '-' }}</div>
                        </div>
                    </div>

                    <div class="meta-item" style="margin-top:4px;">
                        <div class="meta-label">Reported By</div>
                        <div class="meta-value">{{ $job->reportedBy->name ?? '-' }}</div>
                    </div>
                </div>
            @empty
                <div class="empty-state" style="grid-column: span-full;">
                    No maintenance jobs found for selected filters.
                </div>
            @endforelse
        </div>
    </div>
</div>

@endsection