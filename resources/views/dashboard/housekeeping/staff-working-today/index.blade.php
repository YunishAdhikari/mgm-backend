@extends('dashboard.housekeeping.layout')

@section('title', 'Staff Working Today')
@section('page-title', 'Staff Working Today')

@section('content')

<div class="staff-header">
    <div>
        <h2><i class="fas fa-users"></i> Staff Working Today</h2>
        <p>Manage actual housekeeping staff working for the selected date.</p>
    </div>

    <div class="header-actions">
        <form method="GET" action="{{ route('housekeeping-supervisor.staff-working-today.index') }}">
            <input type="date" name="date" value="{{ $date }}">
            <button class="btn-primary-small">Load</button>
        </form>

        <button type="button" class="btn-add" onclick="openExtraStaffModal()">
            <i class="fas fa-plus"></i> Add Extra Staff
        </button>
    </div>
</div>

@if(session('success'))
    <div class="alert success">{{ session('success') }}</div>
@endif

@if($errors->any())
    <div class="alert error">{{ $errors->first() }}</div>
@endif

<div class="summary-grid">
    <div class="summary-card working">
        <h3>{{ $workingShifts->whereNotIn('shift_type', ['sick', 'day_off', 'holiday'])->count() }}</h3>
        <p>Working</p>
    </div>

    <div class="summary-card sick">
        <h3>{{ $workingShifts->where('shift_type', 'sick')->count() }}</h3>
        <p>Sick</p>
    </div>

    <div class="summary-card off">
        <h3>{{ $workingShifts->where('shift_type', 'day_off')->count() }}</h3>
        <p>Day Off</p>
    </div>

    <div class="summary-card holiday">
        <h3>{{ $workingShifts->where('shift_type', 'holiday')->count() }}</h3>
        <p>Holiday</p>
    </div>
</div>

<div class="staff-grid">
    @forelse($workingShifts as $shift)
        <div class="staff-card {{ in_array($shift->shift_type, ['sick','day_off','holiday']) ? 'unavailable' : 'available' }}">
            <div class="staff-top">
                <div>
                    <h3>{{ $shift->user->name ?? 'Unknown Staff' }}</h3>
                    <p>{{ $shift->user->email ?? '' }}</p>
                </div>

                <span class="shift-badge {{ $shift->shift_type }}">
                    {{ ucwords(str_replace('_', ' ', $shift->shift_type)) }}
                </span>
            </div>

            <div class="shift-info">
                <span>
                    <i class="fas fa-clock"></i>
                    {{ $shift->start_time ?? '--:--' }} - {{ $shift->end_time ?? '--:--' }}
                </span>

                <span>
                    <i class="fas fa-circle-check"></i>
                    {{ ucfirst($shift->status) }}
                </span>
            </div>

            @if($shift->notes)
                <div class="notes">
                    {{ $shift->notes }}
                </div>
            @endif

            <div class="action-row">
                <form method="POST"
                      action="{{ route('housekeeping-supervisor.staff-working-today.mark-unavailable', $shift->id) }}">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="shift_type" value="sick">
                    <button class="btn-status sick-btn" type="submit">
                        Sick
                    </button>
                </form>

                <form method="POST"
                      action="{{ route('housekeeping-supervisor.staff-working-today.mark-unavailable', $shift->id) }}">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="shift_type" value="day_off">
                    <button class="btn-status off-btn" type="submit">
                        Day Off
                    </button>
                </form>

                <form method="POST"
                      action="{{ route('housekeeping-supervisor.staff-working-today.mark-unavailable', $shift->id) }}">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="shift_type" value="holiday">
                    <button class="btn-status holiday-btn" type="submit">
                        Holiday
                    </button>
                </form>
            </div>
        </div>
    @empty
        <div class="empty-state">
            <i class="fas fa-calendar-xmark"></i>
            <h3>No housekeeping staff found for this date.</h3>
            <p>Add staff from rota or add extra staff manually.</p>
        </div>
    @endforelse
</div>

{{-- Add Extra Staff Modal --}}
<div id="extraStaffModal" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header">
            <div>
                <h3><i class="fas fa-user-plus"></i> Add Extra Staff</h3>
                <p>Add someone directly as published for this date.</p>
            </div>

            <button class="modal-close" type="button" onclick="closeExtraStaffModal()">
                &times;
            </button>
        </div>

        <form method="POST" action="{{ route('housekeeping-supervisor.staff-working-today.add-extra') }}">
            @csrf

            <input type="hidden" name="shift_date" value="{{ $date }}">

            <div class="form-group">
                <label>Staff Member</label>
                <select name="user_id" required>
                    <option value="">Select staff</option>
                    @foreach($availableExtraStaff as $staff)
                        <option value="{{ $staff->id }}">
                            {{ $staff->name }} — {{ $staff->email }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Shift Type</label>
                <select name="shift_type" required>
                    <option value="morning">Morning</option>
                    <option value="evening">Evening</option>
                    <option value="night">Night</option>
                    <option value="split">Split</option>
                </select>
            </div>

            <div class="time-grid">
                <div class="form-group">
                    <label>Start Time</label>
                    <input type="time" name="start_time">
                </div>

                <div class="form-group">
                    <label>End Time</label>
                    <input type="time" name="end_time">
                </div>
            </div>

            <button type="submit" class="submit-btn">
                <i class="fas fa-save"></i> Save Extra Staff
            </button>
        </form>
    </div>
</div>

<style>
.staff-header {
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:20px;
    margin-bottom:22px;
}

.staff-header h2 {
    margin:0;
    color:#fff;
}

.staff-header p {
    color:#a1a1aa;
    margin:6px 0 0;
}

.header-actions {
    display:flex;
    gap:10px;
    align-items:center;
    flex-wrap:wrap;
}

.header-actions form {
    display:flex;
    gap:10px;
}

.header-actions input,
.form-group input,
.form-group select {
    background:#18181b;
    border:1px solid #3f3f46;
    color:#fff;
    border-radius:10px;
    padding:11px 13px;
}

.btn-primary-small,
.btn-add {
    border:none;
    border-radius:12px;
    color:#fff;
    font-weight:900;
    padding:11px 14px;
    cursor:pointer;
}

.btn-primary-small {
    background:#2563eb;
}

.btn-add {
    background:linear-gradient(135deg,#16a34a,#22c55e);
}

.alert {
    padding:14px 16px;
    border-radius:14px;
    margin-bottom:18px;
    font-weight:700;
}

.alert.success {
    background:#14532d;
    color:#dcfce7;
}

.alert.error {
    background:#7f1d1d;
    color:#fee2e2;
}

.summary-grid {
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(180px,1fr));
    gap:14px;
    margin-bottom:22px;
}

.summary-card {
    background:#18181b;
    border:1px solid #3f3f46;
    border-radius:18px;
    padding:20px;
}

.summary-card h3 {
    font-size:34px;
    margin:0;
}

.summary-card p {
    margin:6px 0 0;
    color:#a1a1aa;
    font-weight:800;
}

.summary-card.working h3 { color:#22c55e; }
.summary-card.sick h3 { color:#ef4444; }
.summary-card.off h3 { color:#f59e0b; }
.summary-card.holiday h3 { color:#a855f7; }

.staff-grid {
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(310px,1fr));
    gap:16px;
}

.staff-card {
    background:#18181b;
    border:1px solid #3f3f46;
    border-radius:20px;
    padding:18px;
}

.staff-card.unavailable {
    opacity:0.72;
}

.staff-top {
    display:flex;
    justify-content:space-between;
    align-items:flex-start;
    gap:12px;
}

.staff-top h3 {
    margin:0;
    color:#fff;
}

.staff-top p {
    margin:6px 0 0;
    color:#71717a;
    font-size:13px;
}

.shift-badge {
    padding:6px 10px;
    border-radius:999px;
    font-weight:900;
    font-size:12px;
    white-space:nowrap;
}

.shift-badge.morning { background:#22c55e22; color:#22c55e; border:1px solid #22c55e55; }
.shift-badge.evening { background:#3b82f622; color:#60a5fa; border:1px solid #3b82f655; }
.shift-badge.night { background:#6366f122; color:#818cf8; border:1px solid #6366f155; }
.shift-badge.split { background:#f59e0b22; color:#fbbf24; border:1px solid #f59e0b55; }
.shift-badge.sick { background:#ef444422; color:#f87171; border:1px solid #ef444455; }
.shift-badge.day_off { background:#f59e0b22; color:#fbbf24; border:1px solid #f59e0b55; }
.shift-badge.holiday { background:#a855f722; color:#c084fc; border:1px solid #a855f755; }

.shift-info {
    display:flex;
    flex-direction:column;
    gap:8px;
    margin:16px 0;
    color:#a1a1aa;
    font-size:13px;
}

.shift-info i {
    color:#22c55e;
    margin-right:6px;
}

.notes {
    background:#27272a;
    border:1px solid #3f3f46;
    color:#d4d4d8;
    padding:10px;
    border-radius:12px;
    margin-bottom:14px;
    font-size:13px;
}

.action-row {
    display:flex;
    gap:8px;
    flex-wrap:wrap;
}

.btn-status {
    border:none;
    color:#fff;
    padding:8px 10px;
    border-radius:10px;
    font-weight:800;
    cursor:pointer;
    font-size:12px;
}

.sick-btn { background:#dc2626; }
.off-btn { background:#d97706; }
.holiday-btn { background:#7c3aed; }

.empty-state {
    grid-column:1/-1;
    text-align:center;
    background:#18181b;
    border:1px solid #3f3f46;
    border-radius:20px;
    padding:40px;
    color:#a1a1aa;
}

.empty-state i {
    font-size:42px;
    color:#71717a;
}

.modal-overlay {
    display:none;
    position:fixed;
    inset:0;
    background:rgba(0,0,0,0.75);
    align-items:center;
    justify-content:center;
    z-index:9999;
    padding:20px;
}

.modal-box {
    width:100%;
    max-width:560px;
    background:#18181b;
    border:1px solid #3f3f46;
    border-radius:22px;
    padding:24px;
}

.modal-header {
    display:flex;
    justify-content:space-between;
    align-items:flex-start;
    margin-bottom:20px;
}

.modal-header h3 {
    margin:0;
    color:#fff;
}

.modal-header p {
    margin:6px 0 0;
    color:#a1a1aa;
}

.modal-close {
    background:none;
    border:none;
    color:#a1a1aa;
    font-size:32px;
    cursor:pointer;
}

.form-group {
    display:flex;
    flex-direction:column;
    gap:8px;
    margin-bottom:16px;
}

.form-group label {
    color:#a1a1aa;
    font-weight:800;
    font-size:13px;
}

.time-grid {
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:12px;
}

.submit-btn {
    width:100%;
    padding:14px;
    border:none;
    border-radius:14px;
    background:linear-gradient(135deg,#16a34a,#22c55e);
    color:#fff;
    font-weight:900;
    cursor:pointer;
}

@media(max-width:700px) {
    .staff-header {
        flex-direction:column;
        align-items:flex-start;
    }

    .header-actions,
    .header-actions form {
        width:100%;
    }

    .header-actions input,
    .header-actions button {
        width:100%;
    }

    .time-grid {
        grid-template-columns:1fr;
    }
}
</style>

<script>
function openExtraStaffModal() {
    document.getElementById('extraStaffModal').style.display = 'flex';
}

function closeExtraStaffModal() {
    document.getElementById('extraStaffModal').style.display = 'none';
}

document.getElementById('extraStaffModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeExtraStaffModal();
    }
});
</script>

@endsection