@extends('dashboard.housekeeping.layout')

@section('content')

<div class="page-wrapper">

    <div class="page-header">
        <div>
            <h1>HK Rota Draft</h1>
        </div>
    </div>

    @if(session('success'))
        <div class="success-alert">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="error-alert">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="error-alert">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="filter-card">
        <form method="GET" action="{{ route('housekeeping-supervisor.hk-rota.index') }}">
            <label>Select Date</label>
            <div class="date-row">
                <input type="date" name="date" value="{{ $selectedDate }}">
                <button type="submit">Load Staff</button>
            </div>
        </form>
    </div>

    <form method="POST" action="{{ route('housekeeping-supervisor.hk-rota.save-draft') }}">
        @csrf

        <input type="hidden" name="date" value="{{ $selectedDate }}">

        <div class="rota-layout">

            <div class="staff-card">
                <div class="section-title">
                    <h2>Housekeeping Staff</h2>
                    <span>{{ $hkStaff->count() }} Staff</span>
                </div>

                <div class="select-actions">
                    <button type="button" onclick="selectAllStaff()">Select All</button>
                    <button type="button" onclick="clearAllStaff()">Clear</button>
                </div>

                <div class="staff-list">
                    @foreach($hkStaff as $staff)
                        @php
                            $shift = $existingShifts[$staff->id] ?? null;
                        @endphp

                        <div class="staff-item">
                            <label class="staff-check">
                                <input type="checkbox" name="staff_ids[]" value="{{ $staff->id }}">

                                <div class="staff-info">
                                    <strong>{{ $staff->name }}</strong>

                                    @if($shift)
                                        <small>
                                            Existing:
                                            {{ ucfirst(str_replace('_', ' ', $shift->shift_type)) }}

                                            @if($shift->start_time && $shift->end_time)
                                                {{ \Carbon\Carbon::parse($shift->start_time)->format('H:i') }}
                                                -
                                                {{ \Carbon\Carbon::parse($shift->end_time)->format('H:i') }}
                                            @endif

                                            <span class="draft-badge {{ $shift->status === 'published' ? 'published-badge' : '' }}">
                                                {{ ucfirst($shift->status ?? 'draft') }}
                                            </span>
                                        </small>
                                    @else
                                        <small>No shift saved</small>
                                    @endif
                                </div>
                            </label>

                            @if($shift && $shift->status === 'draft')
                                <button type="button"
                                        class="remove-draft-btn"
                                        onclick="removeDraft({{ $shift->id }})">
                                    Remove from Draft
                                </button>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="shift-card">
                <h2>Shift Details</h2>

                <label>Shift Type</label>
                <select name="shift_type" id="shift_type" onchange="toggleTimeFields()" required>
                    <option value="morning">Morning</option>
                    <option value="evening">Evening</option>
                    <option value="night">Night</option>
                    <option value="split">Split</option>
                    <option value="day_off">Day Off</option>
                    <option value="holiday">Holiday</option>
                    <option value="sick">Sick</option>
                </select>

                <div id="time-fields">
                    <label>Start Time</label>
                    <input type="time" name="start_time" value="08:00">

                    <label>End Time</label>
                    <input type="time" name="end_time" value="16:00">
                </div>

                <div class="info-box">
                    <strong>Important:</strong>
                    This rota will be saved as <b>Draft</b>. It will not be visible to staff until a manager publishes it.
                </div>

                <button type="submit" class="save-btn">
                    Save Rota as Draft
                </button>
            </div>

        </div>
    </form>

    <form id="removeDraftForm" method="POST" style="display:none;">
        @csrf
        @method('DELETE')
    </form>

</div>

<style>
.page-wrapper {
    padding: 24px;
}

.page-header {
    margin-bottom: 22px;
}

.page-header h1 {
    color: #fafafa;
    font-size: 30px;
    font-weight: 800;
    margin: 0;
}

.page-header p {
    color: #a1a1aa;
    margin-top: 8px;
}

.success-alert {
    background: rgba(34,197,94,.12);
    color: #22c55e;
    border: 1px solid rgba(34,197,94,.4);
    padding: 14px 16px;
    border-radius: 14px;
    margin-bottom: 18px;
}

.error-alert {
    background: rgba(239,68,68,.12);
    color: #ef4444;
    border: 1px solid rgba(239,68,68,.4);
    padding: 14px 16px;
    border-radius: 14px;
    margin-bottom: 18px;
}

.filter-card,
.staff-card,
.shift-card {
    background: #18181b;
    border: 1px solid #3f3f46;
    border-radius: 20px;
    padding: 22px;
}

.filter-card {
    margin-bottom: 20px;
}

.filter-card label,
.shift-card label {
    display: block;
    color: #a1a1aa;
    margin-bottom: 8px;
    font-weight: 700;
}

.date-row {
    display: flex;
    gap: 12px;
}

input[type="date"],
input[type="time"],
select {
    background: #27272a;
    border: 1px solid #3f3f46;
    color: #fafafa;
    padding: 12px 14px;
    border-radius: 12px;
    width: 100%;
}

.date-row button {
    background: #8b5cf6;
    color: white;
    border: none;
    border-radius: 12px;
    padding: 0 20px;
    font-weight: 800;
    cursor: pointer;
}

.rota-layout {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 20px;
}

.section-title {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 14px;
}

.section-title h2,
.shift-card h2 {
    color: #fafafa;
    margin: 0;
    font-size: 22px;
}

.section-title span {
    color: #22c55e;
    background: rgba(34,197,94,.12);
    padding: 7px 12px;
    border-radius: 999px;
    font-weight: 800;
}

.select-actions {
    display: flex;
    gap: 10px;
    margin-bottom: 14px;
}

.select-actions button {
    background: #27272a;
    color: #fafafa;
    border: 1px solid #3f3f46;
    border-radius: 10px;
    padding: 9px 14px;
    cursor: pointer;
}

.staff-list {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 12px;
}

.staff-item {
    background: #27272a;
    border: 1px solid #3f3f46;
    border-radius: 14px;
    padding: 14px;
}

.staff-check {
    display: flex;
    gap: 12px;
    align-items: flex-start;
    cursor: pointer;
}

.staff-check input {
    margin-top: 4px;
}

.staff-info strong {
    color: #fafafa;
    display: block;
}

.staff-info small {
    color: #a1a1aa;
    display: block;
    margin-top: 5px;
}

.draft-badge {
    display: inline-block;
    margin-left: 6px;
    color: #f59e0b;
    background: rgba(245,158,11,.12);
    padding: 3px 8px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 800;
}

.published-badge {
    color: #22c55e;
    background: rgba(34,197,94,.12);
}

.remove-draft-btn {
    margin-top: 10px;
    background: rgba(239,68,68,.15);
    color: #ef4444;
    border: 1px solid rgba(239,68,68,.4);
    border-radius: 10px;
    padding: 7px 10px;
    font-size: 12px;
    font-weight: 800;
    cursor: pointer;
}

.shift-card label {
    margin-top: 16px;
}

.info-box {
    margin-top: 20px;
    background: rgba(245,158,11,.12);
    border: 1px solid rgba(245,158,11,.35);
    color: #fbbf24;
    padding: 14px;
    border-radius: 14px;
    line-height: 1.5;
}

.save-btn {
    width: 100%;
    margin-top: 18px;
    background: #22c55e;
    color: white;
    border: none;
    border-radius: 14px;
    padding: 14px;
    font-weight: 900;
    cursor: pointer;
}

@media (max-width: 1000px) {
    .rota-layout {
        grid-template-columns: 1fr;
    }

    .staff-list {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
function selectAllStaff() {
    document.querySelectorAll('input[name="staff_ids[]"]').forEach(function (checkbox) {
        checkbox.checked = true;
    });
}

function clearAllStaff() {
    document.querySelectorAll('input[name="staff_ids[]"]').forEach(function (checkbox) {
        checkbox.checked = false;
    });
}

function toggleTimeFields() {
    const shiftType = document.getElementById('shift_type').value;
    const timeFields = document.getElementById('time-fields');

    if (shiftType === 'day_off' || shiftType === 'holiday' || shiftType === 'sick') {
        timeFields.style.display = 'none';
    } else {
        timeFields.style.display = 'block';
    }
}

function removeDraft(id) {
    if (!confirm('Remove this staff from draft rota?')) {
        return;
    }

    const form = document.getElementById('removeDraftForm');
    form.action = '/housekeeping-supervisor/hk-rota/' + id + '/remove';
    form.submit();
}

toggleTimeFields();
</script>

@endsection