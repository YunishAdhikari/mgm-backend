@extends('dashboard.manager.layout')

@section('content')

<style>
    :root {
        --bg-page: #09090b;
        --bg-card: #18181b;
        --bg-input: #27272a;
        --text-main: #fafafa;
        --text-muted: #a1a1aa;
        --text-dim: #71717a;
        --border: #3f3f46;
        --primary: #ef4444;
        --primary-light: #f87171;
    }

    .report-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        padding: 32px;
        border-radius: 24px;
        max-width: 700px;
    }

    .report-header {
        margin-bottom: 28px;
    }

    .report-header h1 {
        margin: 0 0 8px;
        color: var(--text-main);
        font-size: 26px;
        font-weight: 900;
    }

    .report-header p {
        margin: 0;
        color: var(--text-muted);
    }

    .form-group {
        margin-bottom: 22px;
    }

    .form-group label {
        display: block;
        margin-bottom: 10px;
        font-weight: 700;
        color: var(--text-muted);
        font-size: 13px;
    }

    .form-group select {
        width: 100%;
        height: 52px;
        border: 2px solid var(--border);
        border-radius: 14px;
        padding: 0 14px;
        font-size: 15px;
        background: var(--bg-input);
        color: var(--text-main);
        transition: all 0.3s ease;
    }

    .form-group select:focus {
        outline: none;
        border-color: var(--primary);
    }

    .generate-btn {
        border: none;
        background: linear-gradient(135deg, var(--primary), #dc2626);
        color: white;
        padding: 14px 24px;
        border-radius: 14px;
        font-weight: 800;
        cursor: pointer;
        font-size: 15px;
        display: flex;
        align-items: center;
        gap: 10px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
    }

    .generate-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(239, 68, 68, 0.4);
    }

    @media (max-width: 600px) {
        .report-card {
            padding: 20px;
            border-radius: 16px;
        }
    }
</style>

<div class="report-card">

    <div class="report-header">
        <h1>Generate Holiday PDF</h1>
        <p>Generate approved holiday report in official MGM format.</p>
    </div>

    <form action="{{ route('manager.reports.holiday.generate') }}" method="POST">
        @csrf

        <div class="form-group">
            <label>Select Year</label>
            <select name="year" required>
                @for($year = now()->year + 1; $year >= 2023; $year--)
                    <option value="{{ $year }}">{{ $year }}</option>
                @endfor
            </select>
        </div>

        <div class="form-group">
            <label>Select Month</label>
            <select name="month">
                <option value="">Full Year</option>
                @for($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}">{{ \Carbon\Carbon::create()->month($m)->format('F') }}</option>
                @endfor
            </select>
        </div>

        <div class="form-group">
            <label>Select Employee (Optional)</label>
            <select name="employee_id">
                <option value="">All Employees</option>
                @foreach($employees as $employee)
                    <option value="{{ $employee->id }}">
                        {{ $employee->name }} - {{ $employee->department->name ?? 'N/A' }}
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

@endsection