@extends('dashboard.supervisor.layout')

@section('content')

<div class="manager-page-card">
    <div class="page-header">
        <div class="header-content">
            <h1>Rota Maker</h1>
            <p>Create normal shifts, split shifts, day off, holiday and sick records.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="success-message">
            <i class="fa-solid fa-circle-check"></i>
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('supervisor.rota.store') }}" method="POST" class="rota-form">
        @csrf

        <div class="form-grid">
            <div class="form-group">
                <label><i class="fa-solid fa-user"></i> Employee</label>
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
                <label><i class="fa-regular fa-calendar"></i> Shift Date</label>
                <input type="date" name="shift_date" required>
            </div>

            <div class="form-group">
                <label><i class="fa-solid fa-clock"></i> Shift Type</label>
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

            <div class="form-group">
                <label><i class="fa-solid fa-utensils"></i> Break Minutes</label>
                <input type="number" name="break_minutes" value="0" min="0">
            </div>

            <div class="normal-time">
                <div class="form-group">
                    <label><i class="fa-solid fa-play"></i> Start Time</label>
                    <input type="time" name="start_time">
                </div>

                <div class="form-group">
                    <label><i class="fa-solid fa-stop"></i> End Time</label>
                    <input type="time" name="end_time">
                </div>
            </div>

            <div class="split-time">
                <div class="form-group">
                    <label><i class="fa-solid fa-1"></i> Split Start 1</label>
                    <input type="time" name="split_start_time_1">
                </div>

                <div class="form-group">
                    <label><i class="fa-solid fa-1"></i> Split End 1</label>
                    <input type="time" name="split_end_time_1">
                </div>

                <div class="form-group">
                    <label><i class="fa-solid fa-2"></i> Split Start 2</label>
                    <input type="time" name="split_start_time_2">
                </div>

                <div class="form-group">
                    <label><i class="fa-solid fa-2"></i> Split End 2</label>
                    <input type="time" name="split_end_time_2">
                </div>
            </div>

            <div class="form-group full">
                <label><i class="fa-solid fa-note-sticky"></i> Notes</label>
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
        <h2><i class="fa-solid fa-list"></i> Current Rota Shifts</h2>
    </div>

    <div class="table-wrapper">
        <form action="{{ route('manager.rota.publish') }}" method="POST" class="publish-form">
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
        </form>

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
                                {{ \Carbon\Carbon::parse($shift->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($shift->end_time)->format('H:i') }}
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
                        <td colspan="8" class="empty-text">
                            <i class="fa-regular fa-calendar-xmark"></i>
                            No rota shifts found.
                        </td>
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

        if (employeeSelect) {
            const originalEmployees = Array.from(employeeSelect.querySelectorAll('option'));

            if (departmentSelect) {
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
            }
        }
    });
</script>

<style>
    :root {
        --primary: #8b5cf6;
        --primary-hover: #a78bfa;
        --secondary: #ec4899;
        
        --bg-card: #27272a;
        --bg-input: #1c1c1f;
        
        --text-main: #fafafa;
        --text-muted: #a1a1aa;
        --text-dim: #71717a;
        
        --border: #3f3f46;
        --border-light: #52525b;
        
        --success: #10b981;
        --warning: #f59e0b;
        --danger: #ef4444;
        
        --glow: 0 0 20px rgba(139, 92, 246, 0.3);
        
        --radius-lg: 1.5rem;
        --radius-md: 1rem;
    }

    .manager-page-card {
        background: var(--bg-card);
        border-radius: var(--radius-lg);
        padding: 28px;
        border: 1px solid var(--border);
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        animation: fadeIn 0.4s ease-out;
    }

    .mt {
        margin-top: 28px;
    }

    .page-header {
        margin-bottom: 28px;
    }

    .page-header h1,
    .page-header h2 {
        margin: 0 0 6px;
        font-size: 26px;
        font-weight: 800;
        background: linear-gradient(135deg, #fff 0%, #a1a1aa 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .page-header p {
        margin: 0;
        color: var(--text-muted);
        font-size: 14px;
    }

    .success-message {
        display: flex;
        align-items: center;
        gap: 12px;
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.15), rgba(16, 185, 129, 0.05));
        border: 1px solid rgba(16, 185, 129, 0.3);
        color: #6ee7b7;
        padding: 16px 20px;
        border-radius: var(--radius-md);
        margin-bottom: 24px;
        font-weight: 600;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }

    .normal-time,
    .split-time {
        grid-column: 1 / -1;
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
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
        font-weight: 600;
        color: var(--text-muted);
        margin-bottom: 10px;
        font-size: 14px;
    }

    .form-group label i {
        width: 16px;
        color: var(--primary);
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        background: var(--bg-input);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        padding: 14px 16px;
        outline: none;
        font-size: 15px;
        color: var(--text-main);
        transition: all 0.2s ease;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.15);
    }

    .form-group select option {
        background: var(--bg-card);
    }

    .form-group textarea {
        min-height: 100px;
        resize: vertical;
    }

    .save-btn {
        margin-top: 28px;
        border: none;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
        padding: 16px 28px;
        border-radius: var(--radius-md);
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(139, 92, 246, 0.3);
    }

    .save-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(139, 92, 246, 0.4);
    }

    .publish-form {
        display: flex;
        gap: 16px;
        align-items: end;
        margin-bottom: 24px;
        flex-wrap: wrap;
        padding: 20px;
        background: var(--bg-input);
        border-radius: var(--radius-md);
        border: 1px solid var(--border);
    }

    .publish-form div {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .publish-form label {
        font-weight: 600;
        color: var(--text-muted);
        font-size: 13px;
    }

    .publish-form input {
        padding: 12px 16px;
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 10px;
        color: var(--text-main);
    }

    .publish-form button {
        border: none;
        background: linear-gradient(135deg, var(--success), #059669);
        color: white;
        padding: 14px 20px;
        border-radius: var(--radius-md);
        font-weight: 700;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
    }

    .publish-form button:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .table-wrapper {
        overflow-x: auto;
    }

    table {
        width: 100%;
        min-width: 900px;
        border-collapse: collapse;
    }

    th {
        background: var(--bg-input);
        padding: 16px;
        text-align: left;
        color: var(--text-muted);
        font-weight: 700;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    td {
        padding: 16px;
        border-bottom: 1px solid var(--border);
        vertical-align: middle;
        color: var(--text-main);
    }

    tr:hover td {
        background: rgba(255,255,255,0.02);
    }

    .badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        white-space: nowrap;
    }

    .type-morning { background: rgba(59, 130, 246, 0.15); color: #60a5fa; }
    .type-evening { background: rgba(245, 158, 11, 0.15); color: #fbbf24; }
    .type-night { background: rgba(139, 92, 246, 0.15); color: #a78bfa; }
    .type-split { background: rgba(236, 72, 153, 0.15); color: #f472b6; }
    .type-day_off { background: rgba(113, 113, 122, 0.15); color: #a1a1aa; }
    .type-holiday { background: rgba(16, 185, 129, 0.15); color: #6ee7b7; }
    .type-sick { background: rgba(239, 68, 68, 0.15); color: #fca5a5; }

    .status-draft { background: rgba(245, 158, 11, 0.15); color: #fbbf24; }
    .status-published { background: rgba(16, 185, 129, 0.15); color: #6ee7b7; }

    .delete-btn {
        width: 38px;
        height: 38px;
        border: none;
        border-radius: 10px;
        background: rgba(239, 68, 68, 0.1);
        color: #fca5a5;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }

    .delete-btn:hover {
        background: rgba(239, 68, 68, 0.2);
        transform: scale(1.05);
    }

    .empty-text {
        text-align: center;
        color: var(--text-dim);
        font-weight: 600;
        padding: 40px;
    }

    </style>

    @endsection