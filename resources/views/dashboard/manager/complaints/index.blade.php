@extends('dashboard.manager.layout')


@section('content')

<!-- Breadcrumb -->
<div class="breadcrumb">
    <span><i class="fas fa-home"></i> Home</span>
    <span class="separator">/</span>
    <span class="current">Guest Complaints</span>
</div>

<!-- Page Header -->
<div class="page-header">
    <div class="header-content">
        <div>
            <h1><i class="fas fa-exclamation-circle"></i> Guest Complaints & Feedback</h1>
            <p>Track complaints submitted through the guest QR form.</p>
        </div>
    </div>
</div>

<!-- Success Message -->
@if(session('success'))
    <div class="alert-success">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

<!-- Table Card -->
<div class="table-card">
    <div class="table-header">
        <h3><i class="fas fa-list"></i> All Complaints</h3>
        {{-- <span class="record-count">{{ $complaints->total() }} records</span> --}}
    </div>
    
    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th><i class="fas fa-user"></i> Guest</th>
                    <th><i class="fas fa-door-open"></i> Room</th>
                    <th><i class="fas fa-tag"></i> Type</th>
                    <th><i class="fas fa-exclamation-triangle"></i> Issue</th>
                    <th><i class="fas fa-flag"></i> Priority</th>
                    <th><i class="fas fa-info-circle"></i> Status</th>
                    <th><i class="fas fa-calendar"></i> Submitted</th>
                </tr>
            </thead>

            <tbody>
                @forelse($complaints as $complaint)
                    <tr>
                        <td>
                            <div class="guest-info">
                                <strong>{{ $complaint->guest_name ?? 'Guest' }}</strong>
                                <small>{{ $complaint->email ?? $complaint->phone ?? 'No contact' }}</small>
                            </div>
                        </td>

                        <td>{{ $complaint->room_number ?? 'N/A' }}</td>

                        <td>
                            <span class="type-badge">
                                <i class="fas fa-tag"></i> {{ ucfirst($complaint->type) }}
                            </span>
                        </td>

                        <td>
                            <div class="issue-info">
                                <strong>{{ $complaint->title }}</strong>
                                <small>{{ Str::limit($complaint->description, 60) }}</small>
                            </div>
                        </td>

                        <td>
                            <span class="badge priority-{{ $complaint->priority }}">
                                <i class="fas fa-flag"></i> {{ ucfirst($complaint->priority) }}
                            </span>
                        </td>

                        <td>
                            <form action="{{ route('manager.complaints.status', $complaint->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <select name="status" onchange="this.form.submit()" class="status-select">
                                    <option value="pending" {{ $complaint->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="in_progress" {{ $complaint->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="resolved" {{ $complaint->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                                    <option value="closed" {{ $complaint->status === 'closed' ? 'selected' : '' }}>Closed</option>
                                </select>
                            </form>
                        </td>

                        <td>{{ $complaint->created_at->format('d M Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="empty-text">
                            <i class="fas fa-exclamation-circle"></i>
                            <p>No complaints found.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-wrap">
        {{-- {{ $complaints->links() }} --}}
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

/* Success Alert */
.alert-success {
    background: rgba(34, 197, 94, 0.15);
    border: 1px solid rgba(34, 197, 94, 0.3);
    border-radius: 12px;
    padding: 16px;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    gap: 12px;
    color: #22c55e;
    font-size: 14px;
    font-weight: 600;
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

.guest-info strong {
    display: block;
    font-size: 14px;
    font-weight: 600;
}

.guest-info small {
    display: block;
    font-size: 12px;
    color: var(--text-muted);
    margin-top: 4px;
}

.issue-info strong {
    display: block;
    font-size: 14px;
    font-weight: 600;
}

.issue-info small {
    display: block;
    font-size: 12px;
    color: var(--text-muted);
    margin-top: 4px;
}

/* Type Badge */
.type-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    font-size: 12px;
    font-weight: 600;
    background: rgba(59, 130, 246, 0.15);
    color: #3b82f6;
    border-radius: 100px;
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

/* Status Select */
.status-select {
    padding: 8px 12px;
    background: var(--dark);
    border: 1px solid var(--gray-light);
    border-radius: 8px;
    color: var(--text);
    font-size: 13px;
    cursor: pointer;
}

.status-select:focus {
    outline: none;
    border-color: var(--primary);
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