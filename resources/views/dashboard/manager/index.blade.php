@extends('dashboard.manager.layout')


@section('content')

<!-- Breadcrumb -->
<div class="breadcrumb">
    <span><i class="fas fa-home"></i> Home</span> 
    <span class="separator">/</span> 
    <span class="current">Manager Dashboard</span>
</div>

<!-- Stats Cards -->
<div class="dashboard-cards">
    <div class="card">
        <div class="card-header">
            <div class="card-icon red">
                <i class="fas fa-users"></i>
            </div>
        </div>
        <h3>Total Employees</h3>
        <p>{{ $totalEmployees }}</p>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="card-icon green">
                <i class="fas fa-user-check"></i>
            </div>
        </div>
        <h3>Active Employees</h3>
        <p>{{ $activeEmployees }}</p>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="card-icon yellow">
                <i class="fas fa-tools"></i>
            </div>
        </div>
        <h3>Pending Maintenance</h3>
        <p>{{ $pendingMaintenance }}</p>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="card-icon blue">
                <i class="fas fa-newspaper"></i>
            </div>
        </div>
        <h3>Active News</h3>
        <p>{{ $activeNews }}</p>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="card-icon red">
                <i class="fas fa-exclamation-circle"></i>
            </div>
        </div>
        <h3>Pending Complaints</h3>
        <p>{{ $pendingComplaints }}</p>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="card-icon green">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
        <h3>Resolved Complaints</h3>
        <p>{{ $resolvedComplaints }}</p>
    </div>
</div>

<!-- Content Grid -->
<div class="content-grid">
    
    <!-- Recent Maintenance Jobs -->
    <div class="manager-card">
        <div class="card-title">
            <h3><i class="fas fa-tools"></i> Recent Maintenance Jobs</h3>
            <a href="{{ route('manager.maintenance.index') }}" class="view-all">
                View All <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        @forelse($latestMaintenance as $job)
            <div class="list-item">
                <div class="list-content">
                    <strong>{{ $job->title }}</strong>
                    <p>{{ $job->location ?? 'N/A' }} | Room: {{ $job->room_number ?? 'N/A' }}</p>
                </div>
                <span class="status-badge {{ $job->status }}">
                    {{ ucwords(str_replace('_', ' ', $job->status)) }}
                </span>
            </div>
        @empty
            <div class="empty-text">
                <i class="fas fa-tools"></i>
                <p>No maintenance jobs found.</p>
            </div>
        @endforelse
    </div>

    <!-- Recent Complaints -->
    <div class="manager-card">
        <div class="card-title">
            <h3><i class="fas fa-exclamation-circle"></i> Recent Complaints</h3>
            <a href="{{ route('manager.complaints.index') }}" class="view-all">
                View All <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        @forelse($latestComplaints as $complaint)
            <div class="list-item">
                <div class="list-content">
                    <strong>{{ $complaint->title }}</strong>
                    <p>Room: {{ $complaint->room_number ?? 'N/A' }} | {{ ucfirst($complaint->type) }}</p>
                </div>
                <span class="status-badge {{ $complaint->status }}">
                    {{ ucwords(str_replace('_', ' ', $complaint->status)) }}
                </span>
            </div>
        @empty
            <div class="empty-text">
                <i class="fas fa-exclamation-circle"></i>
                <p>No complaints found.</p>
            </div>
        @endforelse
    </div>

</div>

<style>
/* Additional Dashboard Styles */
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

/* Stats Cards */
.dashboard-cards {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 16px;
    margin-bottom: 32px;
}

.dashboard-cards .card {
    padding: 20px;
}

.dashboard-cards .card h3 {
    font-size: 12px;
    margin-bottom: 8px;
}

.dashboard-cards .card p {
    font-size: 24px;
}

/* Content Grid */
.content-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
}

/* Manager Card */
.manager-card {
    background: var(--dark-secondary);
    border: 1px solid var(--gray);
    border-radius: 12px;
    padding: 20px;
}

.manager-card .card-title {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 16px;
    border-bottom: 1px solid var(--gray);
    padding-bottom: 12px;
}

.manager-card .card-title h3 {
    font-size: 16px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 10px;
}

.manager-card .card-title h3 i {
    color: var(--primary);
}

.manager-card .view-all {
    font-size: 13px;
    color: var(--primary);
    display: flex;
    align-items: center;
    gap: 6px;
    transition: all 0.3s;
}

.manager-card .view-all:hover {
    gap: 10px;
}

/* List Item */
.list-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    padding: 14px 0;
    border-bottom: 1px solid var(--gray);
}

.list-item:last-child {
    border-bottom: none;
}

.list-item .list-content strong {
    color: var(--text);
    font-size: 14px;
    font-weight: 600;
    display: block;
}

.list-item .list-content p {
    color: var(--text-muted);
    font-size: 12px;
    margin-top: 4px;
}

/* Status Badge */
.status-badge {
    padding: 6px 12px;
    font-size: 11px;
    font-weight: 600;
    border-radius: 100px;
    white-space: nowrap;
}

.status-badge.pending {
    background: rgba(234, 179, 8, 0.15);
    color: #eab308;
}

.status-badge.in_progress {
    background: rgba(59, 130, 246, 0.15);
    color: #3b82f6;
}

.status-badge.completed,
.status-badge.resolved {
    background: rgba(34, 197, 94, 0.15);
    color: #22c55e;
}

.status-badge.closed,
.status-badge.cancelled {
    background: rgba(107, 114, 128, 0.15);
    color: #6b7280;
}

/* Empty State */
.empty-text {
    text-align: center;
    padding: 32px;
    color: var(--text-muted);
}

.empty-text i {
    font-size: 32px;
    margin-bottom: 12px;
    display: block;
    opacity: 0.5;
}

.empty-text p {
    font-size: 14px;
}

/* Responsive */
@media (max-width: 1200px) {
    .dashboard-cards {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (max-width: 768px) {
    .dashboard-cards {
        grid-template-columns: repeat(2, 1fr);
    }

    .content-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 480px) {
    .dashboard-cards {
        grid-template-columns: 1fr;
    }
}
</style>

@endsection