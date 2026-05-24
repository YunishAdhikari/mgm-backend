{{-- @extends('dashboard.supervisor.layout') --}}
@extends('dashboard.kitchen-supervisor.layout')

@section('page-title', 'Kitchen Dashboard')

@section('content')

<div class="manager-page-card">

    <div class="page-header">
        <div>
            <h1>Rota Maker</h1>
            <p>Create normal shifts, split shifts, day off, holiday and sick records.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="success-message">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('supervisor.rota.store') }}" method="POST" class="rota-form">
        @csrf

        <div class="form-grid">
         

<div class="form-group">
    <label>Employee</label>
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
                <label>Shift Date</label>
                <input type="date" name="shift_date" required>
            </div>

            <div class="form-group">
                <label>Shift Type</label>
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

            <div class="normal-time">
                <div class="form-group">
                    <label>Start Time</label>
                    <input type="time" name="start_time">
                </div>

                <div class="form-group">
                    <label>End Time</label>
                    <input type="time" name="end_time">
                </div>
            </div>

            <div class="split-time" style="display:none;">
                <div class="form-group">
                    <label>Split Start 1</label>
                    <input type="time" name="split_start_time_1">
                </div>

                <div class="form-group">
                    <label>Split End 1</label>
                    <input type="time" name="split_end_time_1">
                </div>

                <div class="form-group">
                    <label>Split Start 2</label>
                    <input type="time" name="split_start_time_2">
                </div>

                <div class="form-group">
                    <label>Split End 2</label>
                    <input type="time" name="split_end_time_2">
                </div>
            </div>

            <div class="form-group">
                <label>Break Minutes</label>
                <input type="number" name="break_minutes" value="0" min="0">
            </div>

            <div class="form-group full">
                <label>Notes</label>
                <textarea name="notes" placeholder="Optional rota note"></textarea>
            </div>

        </div>

        <button type="submit" class="save-btn">
            <i class="fa-solid fa-plus"></i>
            Add Shift
        </button>
    </form>

</div>

<div class="manager-page-card mt">

    <div class="page-header">
        <h2>Current Rota Shifts</h2>
    </div>

    <div class="table-wrapper">
        {{-- <form action="{{ route('manager.rota.publish') }}" method="POST" class="publish-form">
    @csrf
    @method('PATCH')

    <div>
        <label>From Date</label>
        <input type="date" name="from_date" required>
    </div>

    <div>
        <label>To Date</label>
        <input type="date" name="to_date" required>
    </div>

    <button type="submit">
        <i class="fa-solid fa-paper-plane"></i>
        Publish Rota
    </button>
</form> --}}
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Employee</th>
                    <th>Department</th>
                    <th>Type</th>
                    <th>Time</th>
                    <th>Break</th>
                    <th>Status</th>
                    <th>Action</th>
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
                                {{ \Carbon\Carbon::parse($shift->start_time)->format('H:i') }}
                                -
                                {{ \Carbon\Carbon::parse($shift->end_time)->format('H:i') }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td>{{ $shift->break_minutes }} min</td>
                        <td>
                            <span class="badge status-{{ $shift->status }}">
                                {{ ucfirst($shift->status) }}
                            </span>
                        </td>
                        <td>
                            <form action="{{ route('manager.rota.destroy', $shift->id) }}" method="POST" onsubmit="return confirm('Delete this shift?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="delete-btn">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="empty-text">No rota shifts found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

<script>
    const shiftType = document.getElementById('shiftType');
    const normalTime = document.querySelector('.normal-time');
    const splitTime = document.querySelector('.split-time');

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

    document.addEventListener('DOMContentLoaded', function () {
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
.manager-page-card {
    background: white;
    border-radius: 24px;
    padding: 24px;
    box-shadow: 0 10px 28px rgba(0,0,0,0.06);
}

.mt {
    margin-top: 24px;
}

.page-header {
    margin-bottom: 22px;
}

.page-header h1,
.page-header h2 {
    margin: 0 0 6px;
    color: #111827;
}

.publish-form {
    display: flex;
    gap: 14px;
    align-items: end;
    margin-bottom: 20px;
    flex-wrap: wrap;
}

.publish-form div {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.publish-form label {
    font-weight: 800;
    color: #111827;
}

.publish-form input {
    padding: 12px 14px;
    border: 1px solid #d1d5db;
    border-radius: 12px;
}

.publish-form button {
    border: none;
    background: #22c55e;
    color: white;
    padding: 13px 18px;
    border-radius: 12px;
    font-weight: 900;
    cursor: pointer;
}
.page-header p {
    margin: 0;
    color: #6b7280;
}

.success-message {
    background: #dcfce7;
    color: #166534;
    padding: 14px 16px;
    border-radius: 14px;
    margin-bottom: 18px;
    font-weight: 800;
}

.rota-form {
    margin-top: 10px;
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
    display: block;
    font-weight: 800;
    color: #111827;
    margin-bottom: 8px;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    border: 1px solid #d1d5db;
    border-radius: 14px;
    padding: 13px 14px;
    outline: none;
    font-size: 15px;
}

.form-group textarea {
    min-height: 90px;
    resize: vertical;
}

.save-btn {
    margin-top: 22px;
    border: none;
    background: linear-gradient(135deg, #1583ff, #ff15c4);
    color: white;
    padding: 14px 22px;
    border-radius: 14px;
    font-weight: 900;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 10px;
}

.table-wrapper {
    overflow-x: auto;
}

table {
    width: 100%;
    min-width: 1000px;
    border-collapse: collapse;
}

th {
    background: #f9fafb;
    padding: 16px;
    text-align: left;
    color: #111827;
}

td {
    padding: 16px;
    border-bottom: 1px solid #eef2f7;
    vertical-align: middle;
}

.badge {
    padding: 7px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 800;
    white-space: nowrap;
}

.type-morning { background: #dbeafe; color: #1d4ed8; }
.type-evening { background: #fef3c7; color: #92400e; }
.type-night { background: #ede9fe; color: #6d28d9; }
.type-split { background: #fae8ff; color: #a21caf; }
.type-day_off { background: #e5e7eb; color: #374151; }
.type-holiday { background: #dcfce7; color: #166534; }
.type-sick { background: #fee2e2; color: #991b1b; }

.status-draft { background: #fef3c7; color: #92400e; }
.status-published { background: #dcfce7; color: #166534; }

.delete-btn {
    width: 38px;
    height: 38px;
    border: none;
    border-radius: 12px;
    background: #fee2e2;
    color: #991b1b;
    cursor: pointer;
}

.empty-text {
    text-align: center;
    color: #777;
    font-weight: 800;
}

@media(max-width: 900px) {
    .form-grid,
    .normal-time,
    .split-time {
        grid-template-columns: 1fr;
    }
}
</style>

@endsection