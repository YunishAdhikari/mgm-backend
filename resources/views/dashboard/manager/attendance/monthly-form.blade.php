@extends('dashboard.manager.layout')

@section('content')

<div class="report-card">
    <h1>Monthly Attendance Report</h1>
    <p>Generate individual or full monthly attendance forecast report.</p>

    <form method="GET" action="{{ route('manager.attendance.monthly.pdf') }}" target="_blank">

        <div class="form-group">
            <label>Report Type</label>
            <select name="report_type" id="reportType" required onchange="toggleStaffField()">
                <option value="individual">Individual Staff Report</option>
                <option value="all">Full Monthly Report</option>
            </select>
        </div>

        <div class="form-group">
            <label>Month</label>
            <input type="month" name="month" value="{{ now()->format('Y-m') }}" required>
        </div>

        <div class="form-group" id="staffField">
            <label>Staff</label>
            <select name="user_id">
                <option value="">Select Staff</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">
                        {{ $user->name }} — {{ $user->department->name ?? 'N/A' }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Department Optional</label>
            <select name="department_id">
                <option value="">All Departments</option>
                @foreach($departments as $department)
                    <option value="{{ $department->id }}">
                        {{ $department->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button class="generate-btn">
            Generate PDF Report
        </button>
    </form>
</div>

<style>
.report-card {
    background: white;
    border-radius: 22px;
    padding: 26px;
    max-width: 720px;
    box-shadow: 0 10px 28px rgba(0,0,0,0.06);
}

.report-card h1 {
    margin: 0 0 8px;
}

.report-card p {
    margin: 0 0 24px;
    color: #6b7280;
}

.form-group {
    margin-bottom: 16px;
}

label {
    display: block;
    font-weight: 800;
    margin-bottom: 7px;
    color: #374151;
}

input,
select {
    width: 100%;
    border: 1px solid #d1d5db;
    border-radius: 12px;
    padding: 12px;
}

.generate-btn {
    border: none;
    background: #1583ff;
    color: white;
    padding: 13px 18px;
    border-radius: 13px;
    font-weight: 900;
    cursor: pointer;
    width: 100%;
}
</style>

<script>
function toggleStaffField() {
    const reportType = document.getElementById('reportType').value;
    const staffField = document.getElementById('staffField');

    if (reportType === 'individual') {
        staffField.style.display = 'block';
    } else {
        staffField.style.display = 'none';
    }
}

toggleStaffField();
</script>

@endsection