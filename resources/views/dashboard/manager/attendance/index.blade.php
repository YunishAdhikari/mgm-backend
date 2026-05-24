@extends('dashboard.manager.layout')

@section('content')

<!-- Breadcrumb -->
<div class="breadcrumb">
    <span><i class="fas fa-home"></i> Home</span>
    <span class="separator">/</span>
    <span class="current">Staff Attendance</span>
</div>

<!-- Success Message -->
@if(session('success'))
    <div class="alert-success">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

<!-- Filter Card -->
<div class="filter-card">
    <form method="GET" action="{{ route('manager.attendance.index') }}">
        <div class="filter-group">
            <input type="date" name="date" value="{{ request('date') }}" placeholder="Select date">
        </div>
        
        <div class="filter-group">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search staff name or email">
        </div>
        
        <div class="filter-actions">
            <button type="submit" class="btn-filter">
                <i class="fas fa-search"></i> Filter
            </button>
            
            <a href="{{ route('manager.attendance.index') }}" class="btn-clear">
                <i class="fas fa-times"></i> Clear
            </a>
        </div>
    </form>
</div>

<!-- Table Card -->
<div class="table-card">
    <div class="table-header">
        <h3><i class="fas fa-clock"></i> Staff Attendance</h3>
        <span class="record-count">{{ $logs->total() }} records</span>
    </div>
    
    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th><i class="fas fa-calendar"></i> Date</th>
                    <th><i class="fas fa-user"></i> Staff</th>
                    <th><i class="fas fa-sign-in-alt"></i> Clock In</th>
                    <th><i class="fas fa-sign-out-alt"></i> Clock Out</th>
                    <th><i class="fas fa-info-circle"></i> Status</th>
                    <th><i class="fas fa-cog"></i> Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td>{{ $log->attendance_date }}</td>
                        <td>
                            <div class="staff-info">
                                <strong>{{ $log->user->name ?? 'N/A' }}</strong>
                                <small>{{ $log->user->email ?? '' }}</small>
                            </div>
                        </td>
                        <td>{{ $log->clock_in_at ? \Carbon\Carbon::parse($log->clock_in_at)->format('H:i') : '-' }}</td>
                        <td>{{ $log->clock_out_at ? \Carbon\Carbon::parse($log->clock_out_at)->format('H:i') : '-' }}</td>
                        <td>
                            <span class="badge {{ $log->status }}">
                                {{ ucwords(str_replace('_', ' ', $log->status)) }}
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-edit" onclick="openModal({{ $log->id }})">
                                    <i class="fas fa-edit"></i> Edit
                                </button>

                                <form method="POST" action="{{ route('manager.attendance.destroy', $log->id) }}" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete" onclick="return confirm('Delete this attendance record?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    <!-- Modal -->
                    <div class="modal" id="modal{{ $log->id }}">
                        <div class="modal-box">
                            <div class="modal-header">
                                <h2><i class="fas fa-edit"></i> Edit Attendance</h2>
                                <button onclick="closeModal({{ $log->id }})">&times;</button>
                            </div>

                            <form method="POST" action="{{ route('manager.attendance.update', $log->id) }}">
                                @csrf
                                @method('PUT')

                                <div class="form-group">
                                    <label>Date</label>
                                    <input type="date" name="attendance_date" value="{{ $log->attendance_date }}" required>
                                </div>

                                <div class="form-group">
                                    <label>Clock In</label>
                                    <input type="time" name="clock_in_at" value="{{ $log->clock_in_at ? \Carbon\Carbon::parse($log->clock_in_at)->format('H:i') : '' }}">
                                </div>

                                <div class="form-group">
                                    <label>Clock Out</label>
                                    <input type="time" name="clock_out_at" value="{{ $log->clock_out_at ? \Carbon\Carbon::parse($log->clock_out_at)->format('H:i') : '' }}">
                                </div>

                                <div class="form-group">
                                    <label>Status</label>
                                    <select name="status" required>
                                        <option value="clocked_in" {{ $log->status === 'clocked_in' ? 'selected' : '' }}>Clocked In</option>
                                        <option value="clocked_out" {{ $log->status === 'clocked_out' ? 'selected' : '' }}>Clocked Out</option>
                                    </select>
                                </div>

                                <button type="submit" class="btn-save">
                                    <i class="fas fa-save"></i> Save Changes
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="6" class="empty-text">
                            <i class="fas fa-clock"></i>
                            <p>No attendance records found.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-wrap">
        {{ $logs->links() }}
    </div>
</div>

<style>
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

/* Filter Card */
.filter-card {
    background: var(--dark-secondary);
    border: 1px solid var(--gray);
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 24px;
}

.filter-card form {
    display: flex;
    gap: 12px;
    align-items: center;
}

.filter-group {
    flex: 1;
}

.filter-group input {
    width: 100%;
    padding: 12px 16px;
    background: var(--dark);
    border: 1px solid var(--gray-light);
    border-radius: 8px;
    color: var(--text);
    font-size: 14px;
}

.filter-group input::placeholder {
    color: var(--text-dim);
}

.filter-group input:focus {
    outline: none;
    border-color: var(--primary);
}

.filter-actions {
    display: flex;
    gap: 8px;
}

.btn-filter {
    padding: 12px 20px;
    background: var(--primary);
    border: none;
    border-radius: 8px;
    color: white;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-filter:hover {
    background: var(--primary-dark);
}

.btn-clear {
    padding: 12px 20px;
    background: var(--gray);
    border: none;
    border-radius: 8px;
    color: var(--text-muted);
    font-size: 14px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s;
}

.btn-clear:hover {
    background: var(--gray-light);
    color: var(--text);
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
    min-width: 700px;
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

.staff-info strong {
    display: block;
    font-size: 14px;
    font-weight: 600;
    color: var(--text);
}

.staff-info small {
    display: block;
    font-size: 12px;
    color: var(--text-muted);
    margin-top: 4px;
}

/* Badge */
.badge {
    display: inline-flex;
    align-items: center;
    padding: 6px 12px;
    font-size: 12px;
    font-weight: 600;
    border-radius: 100px;
}

.badge.clocked_in {
    background: rgba(34, 197, 94, 0.15);
    color: #22c55e;
}

.badge.clocked_out {
    background: rgba(107, 114, 128, 0.15);
    color: #6b7280;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 8px;
}

.btn-edit {
    padding: 8px 14px;
    background: var(--primary);
    border: none;
    border-radius: 8px;
    color: white;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-edit:hover {
    background: var(--primary-dark);
}

.btn-delete {
    padding: 8px 14px;
    background: rgba(220, 38, 38, 0.15);
    border: none;
    border-radius: 8px;
    color: var(--primary-light);
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-delete:hover {
    background: var(--primary);
    color: white;
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

.pagination-wrap .pagination {
    display: flex;
    gap: 8px;
    justify-content: center;
}

.pagination-wrap .page-item .page-link {
    padding: 8px 14px;
    background: var(--gray);
    border: none;
    border-radius: 8px;
    color: var(--text-muted);
    font-size: 14px;
}

.pagination-wrap .page-item.active .page-link {
    background: var(--primary);
    color: white;
}

/* Modal */
.modal {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.75);
    z-index: 9999;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

.modal.active {
    display: flex;
}

.modal-box {
    background: var(--dark-secondary);
    width: 100%;
    max-width: 450px;
    border-radius: 16px;
    padding: 24px;
    border: 1px solid var(--gray);
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
    padding-bottom: 16px;
    border-bottom: 1px solid var(--gray);
}

.modal-header h2 {
    font-size: 18px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 10px;
}

.modal-header h2 i {
    color: var(--primary);
}

.modal-header button {
    border: none;
    background: var(--gray);
    font-size: 24px;
    width: 36px;
    height: 36px;
    border-radius: 8px;
    color: var(--text-muted);
    cursor: pointer;
}

.modal-header button:hover {
    background: var(--gray-light);
    color: var(--text);
}

/* Form */
.form-group {
    margin-bottom: 16px;
}

.form-group label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    color: var(--text-muted);
    margin-bottom: 8px;
}

.form-group input,
.form-group select {
    width: 100%;
    padding: 12px 16px;
    background: var(--dark);
    border: 1px solid var(--gray-light);
    border-radius: 8px;
    color: var(--text);
    font-size: 14px;
}

.form-group input:focus,
.form-group select:focus {
    outline: none;
    border-color: var(--primary);
}

.btn-save {
    width: 100%;
    padding: 14px;
    background: var(--primary);
    border: none;
    border-radius: 8px;
    color: white;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    margin-top: 20px;
    transition: all 0.3s;
}

.btn-save:hover {
    background: var(--primary-dark);
}

/* Responsive */
@media (max-width: 768px) {
    .filter-card form {
        flex-direction: column;
    }
    
    .filter-group {
        width: 100%;
    }
    
    .filter-actions {
        width: 100%;
        justify-content: stretch;
    }
    
    .btn-filter,
    .btn-clear {
        flex: 1;
        text-align: center;
    }
}
</style>

<script>
function openModal(id) {
    document.getElementById('modal' + id).classList.add('active');
}

function closeModal(id) {
    document.getElementById('modal' + id).classList.remove('active');
}

// Close modal when clicking outside
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('modal')) {
        e.target.classList.remove('active');
    }
});
</script>

@endsection