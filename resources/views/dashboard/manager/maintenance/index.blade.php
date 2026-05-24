@extends('dashboard.manager.layout')

@section('content')

<!-- Breadcrumb -->
<div class="breadcrumb">
    <span><i class="fas fa-home"></i> Home</span>
    <span class="separator">/</span>
    <span class="current">Maintenance Jobs</span>
</div>

<!-- Page Header -->
<div class="page-header">
    <div class="header-content">
        <div>
            <h1><i class="fas fa-tools"></i> Maintenance Jobs</h1>
            <p>View and track all maintenance tasks across the hotel.</p>
        </div>
    </div>
</div>

<!-- Table Card -->
<div class="table-card">
    <div class="table-header">
        <h3><i class="fas fa-clipboard-list"></i> All Maintenance Jobs</h3>
        {{-- <span class="record-count">{{ $jobs->total() }} records</span> --}}
    </div>
    
    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th><i class="fas fa-tasks"></i> Task</th>
                    <th><i class="fas fa-building"></i> Department</th>
                    <th><i class="fas fa-door-open"></i> Room</th>
                    <th><i class="fas fa-flag"></i> Priority</th>
                    <th><i class="fas fa-info-circle"></i> Status</th>
                    <th><i class="fas fa-user"></i> Reported By</th>
                    <th><i class="fas fa-calendar"></i> Date</th>
                </tr>
            </thead>

            <tbody>
                @forelse($jobs as $job)
                    <tr>
                        <td>
                            <div class="task-info">
                                <strong>{{ $job->title }}</strong>
                                <small>{{ Str::limit($job->description, 55) }}</small>
                            </div>
                        </td>
                        <td>
                            <span class="dept-badge">
                                <i class="fas fa-building"></i> {{ $job->department->name ?? 'N/A' }}
                            </span>
                        </td>
                        <td>
                            <span class="room-badge">
                                <i class="fas fa-door-open"></i> {{ $job->room_number ?? 'N/A' }}
                            </span>
                        </td>
                        <td>
                            <span class="badge priority-{{ $job->priority }}">
                                <i class="fas fa-flag"></i> {{ ucfirst($job->priority) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge status-{{ $job->status }}">
                                {{ ucwords(str_replace('_', ' ', $job->status)) }}
                            </span>
                        </td>
                        <td>
                            <div class="reporter-info">
                                <i class="fas fa-user"></i> {{ $job->reporter->name ?? 'N/A' }}
                            </div>
                        </td>
                        <td>
                            <span class="date-badge">
                                <i class="fas fa-calendar"></i> {{ $job->created_at->format('d M Y') }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="empty-text">
                            <i class="fas fa-tools"></i>
                            <p>No maintenance jobs found.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-wrap">
        {{-- {{ $jobs->links() }} --}}
    </div>
</div>

<style>
/* Page Header */
.page-header {
    margin-bottom: 24px;
}

.page-header h1 {
    font-size: 24px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 8px;
}

.page-header h1 i {
    color: var(--primary);
}

.page-header p {
    color: var(--text-muted);
    font-size: 14px;
}

/* Table Card */
.table-card {
    background: var(--dark-secondary);
    border: 1px solid var(--gray);
    border-radius: 12px;
    overflow: hidden;
}

.table-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    border-bottom: 1px solid var(--gray);
}

.table-header h3 {
    font-size: 18px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 10px;
}

.table-header h3 i {
    color: var(--primary);
}

.record-count {
    font-size: 13px;
    color: var(--text-muted);
    background: var(--gray);
    padding: 6px 12px;
    border-radius: 100px;
}

/* Table */
.table-wrap {
    overflow-x: auto;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 900px;
}

.data-table th {
    background: var(--gray);
    padding: 14px 16px;
    text-align: left;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--text-muted);
    white-space: nowrap;
}

.data-table th i {
    color: var(--primary);
    margin-right: 8px;
}

.data-table td {
    padding: 16px;
    border-bottom: 1px solid var(--gray);
    font-size: 14px;
    color: var(--text);
    vertical-align: middle;
}

.data-table tr:hover td {
    background: rgba(255, 255, 255, 0.02);
}

.task-info strong {
    display: block;
    font-size: 14px;
    font-weight: 600;
}

.task-info small {
    display: block;
    font-size: 12px;
    color: var(--text-muted);
    margin-top: 4px;
}

/* Department & Room Badges */
.dept-badge,
.room-badge,
.date-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    font-size: 12px;
    font-weight: 600;
    background: var(--gray);
    color: var(--text-muted);
    border-radius: 8px;
}

.dept-badge i,
.room-badge i,
.date-badge i {
    font-size: 10px;
}

.reporter-info {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    color: var(--text-muted);
}

.reporter-info i {
    color: var(--primary);
}

/* Priority Badge */
.badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    font-size: 12px;
    font-weight: 600;
    border-radius: 100px;
}

.badge i {
    font-size: 10px;
}

.priority-low {
    background: rgba(34, 197, 94, 0.15);
    color: #22c55e;
}

.priority-medium {
    background: rgba(59, 130, 246, 0.15);
    color: #3b82f6;
}

.priority-high {
    background: rgba(234, 179, 8, 0.15);
    color: #eab308;
}

.priority-urgent {
    background: rgba(220, 38, 38, 0.15);
    color: #ef4444;
}

/* Status Badge */
.status-pending {
    background: rgba(234, 179, 8, 0.15);
    color: #eab308;
}

.status-in_progress {
    background: rgba(59, 130, 246, 0.15);
    color: #3b82f6;
}

.status-completed {
    background: rgba(34, 197, 94, 0.15);
    color: #22c55e;
}

.status-cancelled {
    background: rgba(107, 114, 128, 0.15);
    color: #6b7280;
}

/* Empty State */
.empty-text {
    text-align: center;
    padding: 48px;
    color: var(--text-muted);
}

.empty-text i {
    font-size: 40px;
    margin-bottom: 16px;
    display: block;
    opacity: 0.5;
}

.empty-text p {
    font-size: 14px;
}

/* Pagination */
.pagination-wrap {
    padding: 20px;
    border-top: 1px solid var(--gray);
}

/* Breadcrumb */
.breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 24px;
    font-size: 14px;
    color: var(--text-muted);
}

.breadcrumb .separator {
    color: var(--text-dim);
}

.breadcrumb .current {
    color: var(--text);
}
</style>

@endsection