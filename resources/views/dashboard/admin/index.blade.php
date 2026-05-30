@extends('dashboard.admin.layout')

@section('content')



<section class="dashboard-overview">

    <div class="dashboard-card total">
        <div>
            <p>Total Employees</p>
            <h2>{{ $totalEmployees ?? 0 }}</h2>
        </div>
        <i class="fa-solid fa-users"></i>
    </div>

    <div class="dashboard-card active">
        <div>
            <p>Active Employees</p>
            <h2>{{ $activeEmployees ?? 0 }}</h2>
        </div>
        <i class="fa-solid fa-user-check"></i>
    </div>

    <div class="dashboard-card maintenance">
        <div>
            <p>Open Maintenance</p>
            <h2>{{ $openMaintenance ?? 0 }}</h2>
        </div>
        <i class="fa-solid fa-screwdriver-wrench"></i>
    </div>

    <div class="dashboard-card news">
        <div>
            <p>Active News</p>
            <h2>{{ $activeNews ?? 0 }}</h2>
        </div>
        <i class="fa-solid fa-newspaper"></i>
    </div>

</section>

<style>
    :root {
        --bg-card: #27272a;
        --text-main: #fafafa;
        --text-muted: #a1a1aa;
        --border: #3f3f46;
        --purple: #8b5cf6;
    }

    .dashboard-overview {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 18px;
        margin-bottom: 25px;
    }

    .dashboard-card {
        background: var(--bg-card);
        padding: 24px;
        border-radius: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        border: 1px solid var(--border);
        transition: all 0.3s ease;
    }

    .dashboard-card:hover {
        transform: translateY(-4px);
        border-color: var(--purple);
        box-shadow: 0 0 20px rgba(139, 92, 246, 0.3);
    }

    .dashboard-card p {
        color: var(--text-muted);
        font-weight: 700;
        margin-bottom: 8px;
    }

    .dashboard-card h2 {
        font-size: 34px;
        color: var(--text-main);
    }

    .dashboard-card i {
        font-size: 34px;
        padding: 18px;
        border-radius: 18px;
        color: white;
    }

    .total i { background: linear-gradient(135deg, #3b82f6, #60a5fa); }
    .active i { background: linear-gradient(135deg, #10b981, #34d399); }
    .maintenance i { background: linear-gradient(135deg, #f59e0b, #fbbf24); }
    .news i { background: linear-gradient(135deg, #ec4899, #f472b6); }

    @media(max-width: 992px) {
        .dashboard-overview {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media(max-width: 600px) {
        .dashboard-overview {
            grid-template-columns: 1fr;
        }
    }
</style>

@endsection