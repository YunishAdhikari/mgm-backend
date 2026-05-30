@extends('dashboard.supervisor.layout')

@section('content')

<div class="dashboard-grid">

    <a href="{{ route('supervisor.holidays.calendar') }}" class="dash-card">
        <div class="card-icon">
            <i class="fa-solid fa-calendar-days"></i>
        </div>
        <div class="card-content">
            <h3>Holiday Calendar</h3>
            <p>View approved and pending holidays.</p>
        </div>
        <div class="card-arrow">
            <i class="fa-solid fa-arrow-right"></i>
        </div>
    </a>

    <a href="{{ route('supervisor.rota.index') }}" class="dash-card">
        <div class="card-icon">
            <i class="fa-solid fa-clipboard-list"></i>
        </div>
        <div class="card-content">
            <h3>Rota Maker</h3>
            <p>Create rota drafts for staff.</p>
        </div>
        <div class="card-arrow">
            <i class="fa-solid fa-arrow-right"></i>
        </div>
    </a>

</div>

<style>
    :root {
        --primary: #8b5cf6;
        --primary-hover: #a78bfa;
        --secondary: #ec4899;
        --accent: #06b6d4;
        
        --bg-card: #27272a;
        --bg-card-hover: #3f3f46;
        
        --text-main: #fafafa;
        --text-muted: #a1a1aa;
        --text-dim: #71717a;
        
        --border: #3f3f46;
        
        --glow: 0 0 20px rgba(139, 92, 246, 0.3);
        --glow-accent: 0 0 20px rgba(6, 182, 212, 0.3);
        
        --radius-lg: 1.5rem;
        --radius-md: 1rem;
    }

    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 24px;
    }

    .dash-card {
        display: flex;
        align-items: center;
        gap: 20px;
        background: var(--bg-card);
        padding: 28px 30px;
        border-radius: var(--radius-lg);
        text-decoration: none;
        color: var(--text-main);
        border: 1px solid var(--border);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .dash-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--primary);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .dash-card:hover {
        background: var(--bg-card-hover);
        border-color: rgba(139, 92, 246, 0.4);
        transform: translateY(-4px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    }

    .dash-card:hover::before {
        opacity: 1;
    }

    .card-icon {
        width: 64px;
        height: 64px;
        background: linear-gradient(135deg, rgba(139, 92, 246, 0.15), rgba(236, 72, 153, 0.1));
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: all 0.3s ease;
    }

    .dash-card:hover .card-icon {
        background: linear-gradient(135deg, rgba(139, 92, 246, 0.25), rgba(236, 72, 153, 0.15));
        box-shadow: var(--glow);
    }

    .card-icon i {
        font-size: 28px;
        color: var(--primary-hover);
        transition: transform 0.3s ease;
    }

    .dash-card:hover .card-icon i {
        transform: scale(1.1);
    }

    .card-content {
        flex: 1;
    }

    .card-content h3 {
        margin: 0 0 6px;
        font-size: 20px;
        font-weight: 700;
        letter-spacing: -0.01em;
    }

    .card-content p {
        margin: 0;
        color: var(--text-muted);
        font-size: 14px;
        font-weight: 500;
        line-height: 1.5;
    }

    .card-arrow {
        width: 40px;
        height: 40px;
        background: rgba(255,255,255,0.05);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-dim);
        transition: all 0.3s ease;
    }

    .dash-card:hover .card-arrow {
        background: var(--primary);
        color: white;
        transform: translateX(4px);
    }

    /* Responsive */
    @media (max-width: 900px) {
        .dashboard-grid {
            grid-template-columns: 1fr;
        }

        .dash-card {
            padding: 24px;
        }

        .card-icon {
            width: 56px;
            height: 56px;
        }

        .card-icon i {
            font-size: 24px;
        }

        .card-content h3 {
            font-size: 18px;
        }

        .card-arrow {
            display: none;
        }
    }

    /* Animation */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .dash-card {
        animation: fadeInUp 0.5s ease-out forwards;
        opacity: 0;
    }

    .dash-card:nth-child(1) {
        animation-delay: 0.1s;
    }

    .dash-card:nth-child(2) {
        animation-delay: 0.2s;
    }
</style>

@endsection