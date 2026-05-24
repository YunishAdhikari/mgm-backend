@extends('dashboard.manager.layout')

@section('content')

<!-- Breadcrumb -->
<div class="breadcrumb">
    <span><i class="fas fa-home"></i> Home</span>
    <span class="separator">/</span>
    <span class="current">Holiday Requests</span>
</div>

<!-- Page Header -->
<div class="page-header">
    <div class="header-content">
        <div>
            <h1><i class="fas fa-calendar-check"></i> Holiday Requests</h1>
            <p>Review, approve or reject staff holiday applications.</p>
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
        <h3><i class="fas fa-plane"></i> All Holiday Requests</h3>
        {{-- <span class="record-count">{{ $holidayRequests->total() }} records</span> --}}
    </div>
    
    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th><i class="fas fa-user"></i> Employee</th>
                    <th><i class="fas fa-building"></i> Department</th>
                    <th><i class="fas fa-calendar"></i> Dates</th>
                    <th><i class="fas fa-clock"></i> Total Days</th>
                    <th><i class="fas fa-comment"></i> Reason</th>
                    <th><i class="fas fa-info-circle"></i> Status</th>
                    <th><i class="fas fa-cog"></i> Manager Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($holidayRequests as $request)
                    <tr>
                        <td>
                            <div class="employee-info">
                                <strong>{{ $request->user->name ?? 'N/A' }}</strong>
                                <small>{{ $request->user->email ?? '' }}</small>
                            </div>
                        </td>

                        <td>
                            <span class="dept-badge">
                                <i class="fas fa-building"></i> {{ $request->department->name ?? 'N/A' }}
                            </span>
                        </td>

                        <td>
                            <div class="date-info">
                                <strong><i class="fas fa-sign-in-alt"></i> {{ $request->start_date }}</strong>
                                <small><i class="fas fa-sign-out-alt"></i> to {{ $request->end_date }}</small>
                            </div>
                        </td>

                        <td>
                            <span class="days-badge">
                                <i class="fas fa-calendar-day"></i> {{ $request->total_days }} days
                            </span>
                        </td>

                        <td>
                            <span class="reason-text">
                                {{ $request->reason ?? 'No reason provided' }}
                            </span>
                        </td>

                        <td>
                            <span class="badge status-{{ $request->status }}">
                                <i class="fas fa-circle"></i> {{ ucfirst($request->status) }}
                            </span>
                        </td>

                        <td>
                            @if($request->status === 'pending')
                                <form action="{{ route('manager.holidays.update', $request->id) }}" method="POST" class="action-form">
                                    @csrf
                                    @method('PATCH')

                                    <textarea name="manager_note" placeholder="Manager note (optional)"></textarea>

                                    <div class="btn-row">
                                        <button type="submit" name="status" value="approved" class="approve-btn">
                                            <i class="fas fa-check"></i> Approve
                                        </button>

                                        <button type="submit" name="status" value="rejected" class="reject-btn">
                                            <i class="fas fa-times"></i> Reject
                                        </button>
                                    </div>
                                </form>
                            @else
                                <div class="processed-info">
                                    <small><i class="fas fa-user"></i> {{ $request->approver->name ?? 'N/A' }}</small>
                                    @if($request->manager_note)
                                        <div class="manager-note">
                                            <i class="fas fa-comment"></i> {{ $request->manager_note }}
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="empty-text">
                            <i class="fas fa-calendar-check"></i>
                            <p>No holiday requests found.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-wrap">
        {{-- {{ $holidayRequests->links() }} --}}
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
    min-width: 1000px;
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
    vertical-align: top;
}

.data-table tr:hover td {
    background: rgba(255, 255, 255, 0.02);
}

.employee-info strong {
    display: block;
    font-size: 14px;
    font-weight: 600;
}

.employee-info small {
    display: block;
    font-size: 12px;
    color: var(--text-muted);
    margin-top: 4px;
}

/* Badges */
.dept-badge,
.days-badge {
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
.days-badge i {
    font-size: 10px;
}

.date-info strong {
    display: block;
    font-size: 13px;
}

.date-info strong i {
    color: var(--primary);
    margin-right: 6px;
}

.date-info small {
    display: block;
    font-size: 12px;
    color: var(--text-muted);
    margin-top: 4px;
}

.date-info small i {
    color: var(--primary);
    margin-right: 6px;
}

.reason-text {
    font-size: 13px;
    color: var(--text-muted);
    max-width: 150px;
}

/* Status Badge */
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
    font-size: 8px;
}

.status-pending {
    background: rgba(234, 179, 8, 0.15);
    color: #eab308;
}

.status-approved {
    background: rgba(34, 197, 94, 0.15);
    color: #22c55e;
}

.status-rejected {
    background: rgba(220, 38, 38, 0.15);
    color: #ef4444;
}

/* Action Form */
.action-form textarea {
    width: 100%;
    min-height: 60px;
    border: 1px solid var(--gray-light);
    border-radius: 8px;
    padding: 10px;
    resize: vertical;
    margin-bottom: 10px;
    background: var(--dark);
    color: var(--text);
    font-size: 13px;
}

.action-form textarea::placeholder {
    color: var(--text-dim);
}

.action-form textarea:focus {
    outline: none;
    border-color: var(--primary);
}

.btn-row {
    display: flex;
    gap: 8px;
}

.approve-btn,
.reject-btn {
    border: none;
    padding: 8px 14px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    gap: 6px;
}

.approve-btn {
    background: rgba(34, 197, 94, 0.2);
    color: #22c55e;
}

.approve-btn:hover {
    background: #22c55e;
    color: white;
}

.reject-btn {
    background: rgba(220, 38, 38, 0.2);
    color: #ef4444;
}

.reject-btn:hover {
    background: #ef4444;
    color: white;
}

/* Processed Info */
.processed-info {
    font-size: 12px;
    color: var(--text-muted);
}

.processed-info small {
    display: block;
    margin-bottom: 4px;
}

.manager-note {
    font-size: 12px;
    color: var(--text-dim);
    font-style: italic;
    margin-top: 6px;
    padding: 8px;
    background: var(--gray);
    border-radius: 6px;
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