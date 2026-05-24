@extends('dashboard.manager.layout')

@section('content')

<div class="report-card">

    <div class="report-header">
        <h1>Generate Holiday PDF</h1>

        <p>
            Generate approved holiday report in official MGM format.
        </p>
    </div>

    <form action="{{ route('manager.reports.holiday.generate') }}" method="POST">

        @csrf

        <div class="form-group">
            <label>Select Year</label>

            <select name="year" required>
                @for($year = now()->year + 1; $year >= 2023; $year--)
                    <option value="{{ $year }}">
                        {{ $year }}
                    </option>
                @endfor
            </select>
        </div>
        <div class="form-group">
    <label>Select Month</label>

        <select name="month">
            <option value="">Full Year</option>
            @for($m = 1; $m <= 12; $m++)
                <option value="{{ $m }}">
                    {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                </option>
            @endfor
        </select>
    </div>

        <div class="form-group">
            <label>Select Employee (Optional)</label>

            <select name="employee_id">
                <option value="">
                    All Employees
                </option>

                @foreach($employees as $employee)
                    <option value="{{ $employee->id }}">
                        {{ $employee->name }}
                        -
                        {{ $employee->department->name ?? 'N/A' }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="generate-btn">
            <i class="fa-solid fa-file-pdf"></i>
            Generate PDF
        </button>

    </form>

</div>

<style>

.report-card{
    background:white;
    padding:32px;
    border-radius:24px;
    box-shadow:0 10px 28px rgba(0,0,0,0.06);
    max-width:700px;
}

.report-header{
    margin-bottom:24px;
}

.report-header h1{
    margin:0 0 8px;
    color:#111827;
}

.report-header p{
    margin:0;
    color:#6b7280;
}

.form-group{
    margin-bottom:22px;
}

.form-group label{
    display:block;
    margin-bottom:10px;
    font-weight:800;
    color:#111827;
}

.form-group select{
    width:100%;
    height:52px;
    border:1px solid #d1d5db;
    border-radius:14px;
    padding:0 14px;
    font-size:15px;
    outline:none;
}

.generate-btn{
    border:none;
    background:#dc2626;
    color:white;
    padding:14px 22px;
    border-radius:14px;
    font-weight:800;
    cursor:pointer;
    font-size:15px;
    display:flex;
    align-items:center;
    gap:10px;
}

.generate-btn:hover{
    opacity:0.92;
}

</style>

@endsection