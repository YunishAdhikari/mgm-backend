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

    .page-wrap { padding: 24px; max-width: 1000px; }

    .page-title {
        font-size: 28px;
        font-weight: 900;
        margin-bottom: 6px;
        color: var(--text-main);
    }

    .page-subtitle { color: var(--text-muted); margin-bottom: 24px; }

    .menu-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 20px;
    }

    .menu-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 20px;
        padding: 26px;
        color: var(--text-main);
        text-decoration: none;
        transition: all 0.3s ease;
        position: relative;
    }

    .menu-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--primary);
        opacity: 0;
        transition: all 0.3s ease;
        border-radius: 20px 0 0 20px;
    }

    .menu-card:hover {
        border-color: var(--primary);
        transform: translateY(-4px);
        box-shadow: 0 12px 40px rgba(239, 68, 68, 0.25);
    }

    .menu-card:hover::before { opacity: 1; }

    .menu-card.disabled {
        opacity: 0.4;
        cursor: not-allowed;
    }

    .menu-card.disabled:hover { transform: none; border-color: var(--border); box-shadow: none; }

    .menu-icon {
        width: 50px;
        height: 50px;
        background: rgba(239, 68, 68, 0.15);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 16px;
    }

    .menu-icon i { font-size: 22px; color: var(--primary); }

    .menu-card h3 { font-size: 18px; font-weight: 800; margin-bottom: 8px; }

    .menu-card p { font-size: 14px; color: var(--text-muted); margin: 0; }

    .menu-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        background: rgba(239, 68, 68, 0.15);
        color: var(--primary-light);
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        margin-top: auto;
    }

    .coming-badge { background: rgba(113, 113, 122, 0.2); color: var(--text-dim); }

    @media (max-width: 600px) {
        .page-wrap { padding: 16px; }
        .page-title { font-size: 22px; }
        .menu-grid { grid-template-columns: 1fr; }
        .menu-card { padding: 20px; }
    }
</style>

<div class="page-wrap">
    <h1 class="page-title">Manager Reports</h1>
    <p class="page-subtitle">Generate and review hotel operational reports.</p>

    <div class="menu-grid">
        <a href="{{ route('manager.reports.maintenance') }}" class="menu-card">
            <div class="menu-icon"><i class="fas fa-tools"></i></div>
            <h3>Maintenance Report</h3>
            <p>Pending, in-progress, completed and urgent jobs.</p>
        </a>

        <a href="{{ route('manager.attendance.monthly.form') }}" class="menu-card">
            <div class="menu-icon"><i class="fas fa-user-check"></i></div>
            <h3>Attendance Report</h3>
            <p>Staff attendance, leaves and monthly summaries.</p>
        </a>

        <div class="menu-card disabled">
            <div class="menu-icon" style="background: rgba(113,113,122,0.2);">
                <i class="fas fa-boxes" style="color: var(--text-dim);"></i>
            </div>
            <h3>Inventory Report</h3>
            <p>Stock levels and supplies tracking.</p>
            <span class="menu-badge coming-badge"><i class="fas fa-clock"></i> Coming Soon</span>
        </div>

        <div class="menu-card disabled">
            <div class="menu-icon" style="background: rgba(113,113,122,0.2);">
                <i class="fas fa-utensils" style="color: var(--text-dim);"></i>
            </div>
            <h3>Kitchen Sales Report</h3>
            <p>Daily food sales and revenue tracking.</p>
            <span class="menu-badge coming-badge"><i class="fas fa-clock"></i> Coming Soon</span>
        </div>

        <div class="menu-card disabled">
            <div class="menu-icon" style="background: rgba(113,113,122,0.2);">
                <i class="fas fa-bed" style="color: var(--text-dim);"></i>
            </div>
            <h3>Room Occupancy Report</h3>
            <p>Occupancy rates and guest details.</p>
            <span class="menu-badge coming-badge"><i class="fas fa-clock"></i> Coming Soon</span>
        </div>

        <div class="menu-card disabled">
            <div class="menu-icon" style="background: rgba(113,113,122,0.2);">
                <i class="fas fa-dollar-sign" style="color: var(--text-dim);"></i>
            </div>
            <h3>Revenue Report</h3>
            <p>Income tracking across departments.</p>
            <span class="menu-badge coming-badge"><i class="fas fa-clock"></i> Coming Soon</span>
        </div>
    </div>
</div>

@endsection