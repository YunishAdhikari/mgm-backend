@extends('dashboard.reception.layout')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
        
        --success: #10b981;
        --warning: #f59e0b;
        --danger: #ef4444;
        --info: #3b82f6;
        
        --glow: 0 0 20px rgba(139, 92, 246, 0.3);
        
        --radius-lg: 1.5rem;
        --radius-md: 1rem;
    }

    .dashboard-header { margin-bottom: 32px; }
    .dashboard-header h1 { 
        font-size: 32px; 
        font-weight: 800; 
        background: linear-gradient(135deg, #fff 0%, #a1a1aa 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 4px; 
    }
    .dashboard-header p { color: var(--text-muted); font-size: 15px; }

    .stats-grid { 
        display: grid; 
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); 
        gap: 20px; 
        margin-bottom: 28px; 
    }

    .stat-card { 
        background: var(--bg-card); 
        padding: 24px; 
        border-radius: var(--radius-lg); 
        box-shadow: 0 10px 30px rgba(0,0,0,0.3); 
        border: 1px solid var(--border); 
        position: relative; 
        overflow: hidden; 
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.4);
    }

    .stat-card::before { 
        content: ''; 
        position: absolute; 
        top: 0; 
        left: 0; 
        width: 100%; 
        height: 4px; 
    }
    .stat-card.blue::before { background: linear-gradient(90deg, var(--info), #60a5fa); }
    .stat-card.green::before { background: linear-gradient(90deg, var(--success), #4ade80); }
    .stat-card.orange::before { background: linear-gradient(90deg, var(--warning), #fbbf24); }

    .stat-icon { 
        width: 48px; 
        height: 48px; 
        border-radius: 14px; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        font-size: 22px; 
        margin-bottom: 16px; 
    }
    .stat-card.blue .stat-icon { background: rgba(59, 130, 246, 0.15); }
    .stat-card.green .stat-icon { background: rgba(16, 185, 129, 0.15); }
    .stat-card.orange .stat-icon { background: rgba(245, 158, 11, 0.15); }

    .stat-label { font-size: 13px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; }
    .stat-value { font-size: 36px; font-weight: 800; color: var(--text-main); margin: 4px 0; }
    .stat-change { font-size: 13px; font-weight: 600; }
    .stat-change.up { color: var(--success); }
    .stat-change.down { color: var(--danger); }

    .chart-section { 
        background: var(--bg-card); 
        padding: 28px; 
        border-radius: var(--radius-lg); 
        box-shadow: 0 10px 30px rgba(0,0,0,0.3); 
        border: 1px solid var(--border); 
        margin-bottom: 28px; 
    }
    .chart-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
    .chart-title { font-size: 20px; font-weight: 700; color: var(--text-main); }
    .chart-container { height: 320px; position: relative; }

    .quick-actions { 
        display: grid; 
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); 
        gap: 16px; 
    }

    .action-card { 
        background: var(--bg-card); 
        padding: 24px; 
        border-radius: var(--radius-lg); 
        text-decoration: none; 
        text-align: center; 
        border: 2px solid var(--border); 
        transition: all 0.25s ease; 
    }

    .action-card:hover { 
        border-color: var(--primary); 
        transform: translateY(-4px); 
        box-shadow: var(--glow);
        background: linear-gradient(135deg, rgba(139, 92, 246, 0.1), rgba(236, 72, 153, 0.05));
    }

    .action-icon { font-size: 32px; margin-bottom: 12px; }
    .action-label { font-size: 14px; font-weight: 700; color: var(--text-main); }

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

    .stat-card {
        animation: fadeInUp 0.5s ease-out forwards;
        opacity: 0;
    }

    .stat-card:nth-child(1) { animation-delay: 0.1s; }
    .stat-card:nth-child(2) { animation-delay: 0.2s; }
    .stat-card:nth-child(3) { animation-delay: 0.3s; }

    .chart-section {
        animation: fadeInUp 0.5s ease-out 0.4s forwards;
        opacity: 0;
    }

    .quick-actions {
        animation: fadeInUp 0.5s ease-out 0.5s forwards;
        opacity: 0;
    }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }

        .quick-actions {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>

<div style="padding: 28px;">
    <div class="dashboard-header">
        <h1>Good {{ now()->hour < 12 ? 'Morning' : (now()->hour < 17 ? 'Afternoon' : 'Evening') }}! 👋</h1>
        <p>Here's what's happening at the hotel today.</p>
    </div>

    <div class="stats-grid">
        <div class="stat-card blue">
            <div class="stat-icon">🍽️</div>
            <div class="stat-label">Today's Restaurant</div>
            <div class="stat-value">{{ $todayRestaurant }}</div>
            <div class="stat-change {{ $restaurantChange >= 0 ? 'up' : 'down' }}">
                {{ $restaurantChange >= 0 ? '↑' : '↓' }} {{ abs($restaurantChange) }}% from yesterday
            </div>
        </div>

        <div class="stat-card green">
            <div class="stat-icon">💆</div>
            <div class="stat-label">Spa Bookings</div>
            <div class="stat-value">0</div>
            <div class="stat-change up">Coming soon</div>
        </div>

        <div class="stat-card orange">
            <div class="stat-icon">🔧</div>
            <div class="stat-label">Confirmed Today</div>
            <div class="stat-value">{{ $pendingRequests }}</div>
            <div class="stat-change">Needs service follow-up</div>
        </div>
    </div>

    <div class="chart-section">
        <div class="chart-header">
            <h3 class="chart-title">Weekly Bookings Overview</h3>
        </div>

        <div class="chart-container">
            <canvas id="weeklyBookingChart"></canvas>
        </div>
    </div>

    <div class="quick-actions">
        <a href="{{ route('reception.restaurant.bookings.index') }}" class="action-card">
            <div class="action-icon">🆕</div>
            <div class="action-label">New Booking</div>
        </a>

        <a href="{{ route('reception.restaurant.bookings.index') }}" class="action-card">
            <div class="action-icon">📋</div>
            <div class="action-label">Restaurant Bookings</div>
        </a>

        <a href="#" class="action-card">
            <div class="action-icon">📅</div>
            <div class="action-label">Schedule</div>
        </a>

        <a href="#" class="action-card">
            <div class="action-icon">💆</div>
            <div class="action-label">Spa Coming Soon</div>
        </a>
    </div>
</div>

<script>
    const ctx = document.getElementById('weeklyBookingChart').getContext('2d');

    const labels = @json($labels);
    const afternoonTeaData = @json($afternoonTea);
    const dinnerData = @json($dinner);
    const spaData = @json($spa);

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Afternoon Tea',
                    data: afternoonTeaData,
                    backgroundColor: 'rgba(59, 130, 246, 0.65)',
                    borderColor: '#3b82f6',
                    borderWidth: 2,
                    borderRadius: 8
                },
                {
                    label: 'Dinner',
                    data: dinnerData,
                    backgroundColor: 'rgba(34, 197, 94, 0.65)',
                    borderColor: '#22c55e',
                    borderWidth: 2,
                    borderRadius: 8
                },
                {
                    label: 'Spa',
                    data: spaData,
                    backgroundColor: 'rgba(139, 92, 246, 0.65)',
                    borderColor: '#8b5cf6',
                    borderWidth: 2,
                    borderRadius: 8
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    labels: {
                        color: '#a1a1aa'
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { 
                        precision: 0,
                        color: '#71717a'
                    },
                    grid: {
                        color: '#3f3f46'
                    }
                },
                x: {
                    grid: { display: false },
                    ticks: {
                        color: '#71717a'
                    }
                }
            }
        }
    });
</script>
@endsection