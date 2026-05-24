@extends('dashboard.manager.layout')
{{-- @extends('layouts.manager') --}}

@section('content')

<!-- Breadcrumb -->
<div class="breadcrumb">
    <span><i class="fas fa-home"></i> Home</span>
    <span class="separator">/</span>
    <span class="current">Rota Maker</span>
</div>

<!-- Page Header -->
<div class="page-header">
    <div>
        <h1><i class="fas fa-clock"></i> Rota Maker</h1>
        <p>Create normal shifts, split shifts, day off, holiday and sick records.</p>
    </div>
</div>

<!-- Success Message -->
@if(session('success'))
    <div class="alert-success">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

<!-- Rota Form Card -->
<div class="form-card">
    <form action="{{ route('manager.rota.store') }}" method="POST" class="rota-form">
        @csrf

        <div class="form-grid">
            <div class="form-group">
                <label><i class="fas fa-building"></i> Department</label>
                <select name="department_id" id="departmentSelect">
                    <option value="">Select Department</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}">
                            {{ $department->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label><i class="fas fa-user"></i> Employee</label>
                <select name="user_id" id="employeeSelect" required>
                    <option value="">Select Employee</option>
                    @foreach($employees as $employee)
                        <option 
                            value="{{ $employee->id }}"
                            data-department="{{ $employee->department_id }}"
                        >
                            {{ $employee->name }} - {{ $employee->department->name ?? 'N/A' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label><i class="fas fa-calendar"></i> Shift Date</label>
                <input type="date" name="shift_date" required>
            </div>

            <div class="form-group">
                <label><i class="fas fa-tags"></i> Shift Type</label>
                <select name="shift_type" id="shiftType" required>
                    <option value="morning">Morning</option>
                    <option value="evening">Evening</option>
                    <option value="night">Night</option>
                    <option value="split">Split Shift</option>
                    <option value="day_off">Day Off</option>
                    <option value="holiday">Holiday</option>
                    <option value="sick">Sick</option>
                </select>
            </div>

            <div class="normal-time" id="normalTime">
                <div class="form-group">
                    <label><i class="fas fa-play"></i> Start Time</label>
                    <input type="time" name="start_time">
                </div>

                <div class="form-group">
                    <label><i class="fas fa-stop"></i> End Time</label>
                    <input type="time" name="end_time">
                </div>
            </div>

            <div class="split-time" id="splitTime">
                <div class="form-group">
                    <label><i class="fas fa-play"></i> Split Start 1</label>
                    <input type="time" name="split_start_time_1">
                </div>

                <div class="form-group">
                    <label><i class="fas fa-stop"></i> Split End 1</label>
                    <input type="time" name="split_end_time_1">
                </div>

                <div class="form-group">
                    <label><i class="fas fa-play"></i> Split Start 2</label>
                    <input type="time" name="split_start_time_2">
                </div>

                <div class="form-group">
                    <label><i class="fas fa-stop"></i> Split End 2</label>
                    <input type="time" name="split_end_time_2">
                </div>
            </div>

            <div class="form-group">
                <label><i class="fas fa-coffee"></i> Break Minutes</label>
                <input type="number" name="break_minutes" value="0" min="0">
            </div>

            <div class="form-group full">
                <label><i class="fas fa-sticky-note"></i> Notes</label>
                <textarea name="notes" placeholder="Optional rota note"></textarea>
            </div>
        </div>

        <button type="submit" class="save-btn">
            <i class="fas fa-plus"></i> Add Shift
        </button>
    </form>
</div>

<!-- Current Rota Shifts Card -->
<div class="table-card">
    <div class="section-header">
        <h2><i class="fas fa-calendar-check"></i> Current Rota Shifts</h2>
        
        <form action="{{ route('manager.rota.publish') }}" method="POST" class="publish-form">
            @csrf
            @method('PATCH')
            
            <div class="date-inputs">
                <div>
                    <label>From Date</label>
                    <input type="date" name="from_date" required>
                </div>
                
                <div>
                    <label>To Date</label>
                    <input type="date" name="to_date" required>
                </div>
            </div>
            
            <button type="submit" class="publish-btn">
                <i class="fas fa-paper-plane"></i> Publish Rota
            </button>
        </form>
    </div>
    
    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th><i class="fas fa-calendar"></i> Date</th>
                    <th><i class="fas fa-user"></i> Employee</th>
                    <th><i class="fas fa-building"></i> Department</th>
                    <th><i class="fas fa-tag"></i> Type</th>
                    <th><i class="fas fa-clock"></i> Time</th>
                    <th><i class="fas fa-coffee"></i> Break</th>
                    <th><i class="fas fa-info-circle"></i> Status</th>
                    <th><i class="fas fa-cog"></i> Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($shifts as $shift)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($shift->shift_date)->format('d M Y') }}</td>
                        <td>{{ $shift->user->name ?? 'N/A' }}</td>
                        <td>{{ $shift->department->name ?? 'N/A' }}</td>
                        <td>
                            <span class="badge type-{{ $shift->shift_type }}">
                                {{ ucwords(str_replace('_', ' ', $shift->shift_type)) }}
                            </span>
                        </td>
                        <td>
                            @if($shift->start_time && $shift->end_time)
                                <span class="time-badge">
                                    {{ \Carbon\Carbon::parse($shift->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($shift->end_time)->format('H:i') }}
                                </span>
                            @else
                                <span class="time-badge">N/A</span>
                            @endif
                        </td>
                        <td>{{ $shift->break_minutes }} min</td>
                        <td>
                            <span class="badge status-{{ $shift->status }}">
                                {{ ucfirst($shift->status) }}
                            </span>
                        </td>
                        <td>
                            <form action="{{ route('manager.rota.destroy', $shift->id)" method="POST" onsubmit="return confirm('Delete this shift?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="delete-btn">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="empty-text">
                            <i class="fas fa-clock"></i>
                            <p>No rota shifts found.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const shiftType = document.getElementById('shiftType');
    const normalTime = document.getElementById('normalTime');
    const splitTime = document.getElementById('splitTime');

    function toggleShiftFields() {
        if (shiftType.value === 'split') {
            normalTime.style.display = 'none';
            splitTime.style.display = 'grid';
        } else if (
            shiftType.value === 'day_off' ||
            shiftType.value === 'holiday' ||
            shiftType.value === 'sick'
        ) {
            normalTime.style.display = 'none';
            splitTime.style.display = 'none';
        } else {
            normalTime.style.display = 'grid';
            splitTime.style.display = 'none';
        }
    }

    shiftType.addEventListener('change', toggleShiftFields);
    toggleShiftFields();

    // Employee filter by department
    const departmentSelect = document.getElementById('departmentSelect');
    const employeeSelect = document.getElementById('employeeSelect');
    const originalEmployees = Array.from(employeeSelect.querySelectorAll('option'));

    departmentSelect.addEventListener('change', function () {
        const selectedDepartmentId = this.value;
        employeeSelect.innerHTML = '';

        originalEmployees.forEach(function (option) {
            if (option.value === '') {
                employeeSelect.appendChild(option.cloneNode(true));
                return;
            }

            if (option.dataset.department === selectedDepartmentId) {
                employeeSelect.appendChild(option.cloneNode(true));
            }
        });
    });
});
</script>

<style>
/* Page Header */
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

/* Form Card */
.form-card {
    background: var(--dark-secondary);
    border: 1px solid var(--gray);
    border-radius: 12px;
    padding: 24px;
    margin-bottom: 24px;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 18px;
}

.normal-time,
.split-time {
    grid-column: 1 / -1;
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 18px;
}

.split-time {
    grid-template-columns: repeat(4, 1fr);
}

.form-group.full {
    grid-column: 1 / -1;
}

.form-group label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    font-weight: 600;
    color: var(--text-muted);
    margin-bottom: 8px;
}

.form-group label i {
    color: var(--primary);
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    border: 1px solid var(--gray-light);
    border-radius: 10px;
    padding: 13px 14px;
    outline: none;
    font-size: 14px;
    background: var(--dark);
    color: var(--text);
}

.form-group input::placeholder,
.form-group textarea::placeholder {
    color: var(--text-dim);
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    border-color: var(--primary);
}

.form-group textarea {
    min-height: 90px;
    resize: vertical;
}

.save-btn {
    margin-top: 22px;
    border: none;
    background: var(--primary);
    color: white;
    padding: 14px 22px;
    border-radius: 10px;
    font-weight: 600;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    transition: all 0.3s;
}

.save-btn:hover {
    background: var(--primary-dark);
    transform: translateY(-2px);
}

/* Table Card */
.table-card {
    background: var(--dark-secondary);
    border: 1px solid var(--gray);
    border-radius: 12px;
    overflow: hidden;
}

.section-header {
    padding: 20px;
    border-bottom: 1px solid var(--gray);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 16px;
}

.section-header h2 {
    font-size: 18px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 10px;
}

.section-header h2 i {
    color: var(--primary);
}

/* Publish Form */
.publish-form {
    display: flex;
    gap: 14px;
    align-items: flex-end;
    flex-wrap: wrap;
}

.date-inputs {
    display: flex;
    gap: 12px;
}

.date-inputs div {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.date-inputs label {
    font-size: 12px;
    font-weight: 600;
    color: var(--text-muted);
}

.date-inputs input {
    padding: 10px 14px;
    border: 1px solid var(--gray-light);
    border-radius: 8px;
    background: var(--dark);
    color: var(--text);
    font-size: 14px;
}

.publish-btn {
    border: none;
    background: #22c55e;
    color: white;
    padding: 12px 18px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s;
}

.publish-btn:hover {
    background: #16a34a;
}

/* Table */
.table-wrapper {
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
}

.data-table tr:hover td {
    background: rgba(255, 255, 255, 0.02);
}

/* Badges */
.badge {
    display: inline-flex;
    align-items: center;
    padding: 6px 12px;
    font-size: 12px;
    font-weight: 600;
    border-radius: 100px;
}

.type-morning { background: rgba(59, 130, 246, 0.15); color: #3b82f6; }
.type-evening { background: rgba(234, 179, 8, 0.15); color: #eab308; }
.type-night { background: rgba(139, 92, 246, 0.15); color: #8b5cf6; }
.type-split { background: rgba(236, 72, 153, 0.15); color: #ec4899; }
.type-day_off { background: rgba(107, 114, 128, 0.15); color: #6b7280; }
.type-holiday { background: rgba(34, 197, 94, 0.15); color: #22c55e; }
.type-sick { background: rgba(220, 38, 38, 0.15); color: #ef4444; }

.status-draft { background: rgba(234, 179, 8, 0.15); color: #eab308; }
.status-published { background: rgba(34, 197, 94, 0.15); color: #22c55e; }

.time-badge {
    font-size: 13px;
    color: var(--text-muted);
}

/* Delete Button */
.delete-btn {
    width: 38px;
    height: 38px;
    border: none;
    border-radius: 8px;
    background: rgba(220, 38, 38, 0.15);
    color: var(--primary-light);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s;
}

.delete-btn:hover {
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

/* Responsive */
@media (max-width: 768px) {
    .form-grid,
    .normal-time,
    .split-time {
        grid-template-columns: 1fr;
    }
            .split-time {
        grid-template-columns: repeat(2, 1fr);
    }

    .section-header {
        flex-direction: column;
        align-items: flex-start;
    }

    .publish-form {
        width: 100%;
    }
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