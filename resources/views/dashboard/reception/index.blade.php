@extends('dashboard.reception.layout')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    :root {
        --primary: #8b5cf6;
        --secondary: #ec4899;
        --bg-card: #18181b;
        --text-main: #fafafa;
        --text-muted: #a1a1aa;
        --text-dim: #71717a;
        --border: #3f3f46;
        --success: #10b981;
        --warning: #f59e0b;
        --danger: #ef4444;
        --info: #3b82f6;
    }

    .page-wrap { 
        padding: 24px; 
        max-width: 100%;
        overflow-x: hidden;
    }

    * { box-sizing: border-box; }

    /* Hero Section */
    .hero-panel {
        background: linear-gradient(135deg, rgba(139,92,246,0.15), rgba(236,72,153,0.1)),
                    var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 28px;
        padding: 32px;
        margin-bottom: 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
        box-shadow: 0 25px 60px rgba(0,0,0,0.4);
    }

    .hero-content h1 {
        font-size: 32px;
        font-weight: 900;
        background: linear-gradient(135deg, #fff, #c4b5fd);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 8px;
    }

    .hero-content p { color: var(--text-muted); font-size: 15px; }

    .hero-emoji { font-size: 52px; animation: bounce 2s infinite; }

    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-12px); }
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 18px;
        margin-bottom: 24px;
    }

    .stat-card {
        background: linear-gradient(180deg, rgba(255,255,255,0.04), rgba(255,255,255,0.01)),
                    var(--bg-card);
        padding: 24px;
        border-radius: 24px;
        border: 1px solid var(--border);
        box-shadow: 0 20px 45px rgba(0,0,0,0.3);
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-6px);
        border-color: var(--primary);
        box-shadow: 0 0 30px rgba(139,92,246,0.2);
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        border-radius: 24px 24px 0 0;
    }

    .stat-card.blue::before { background: linear-gradient(90deg, var(--info), #60a5fa); }
    .stat-card.green::before { background: linear-gradient(90deg, var(--success), #34d399); }
    .stat-card.purple::before { background: linear-gradient(90deg, var(--primary), var(--secondary)); }
    .stat-card.red::before { background: linear-gradient(90deg, var(--danger), #f87171); }

    .stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 18px;
        display: grid;
        place-items: center;
        font-size: 28px;
        margin-bottom: 16px;
        background: rgba(255,255,255,0.05);
    }

    .stat-label { color: var(--text-muted); font-size: 13px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.6px; margin-bottom: 6px; }
    .stat-value { color: var(--text-main); font-size: 36px; font-weight: 900; margin-bottom: 4px; }
    .stat-change { color: var(--text-dim); font-size: 13px; font-weight: 700; }
    .stat-change.up { color: var(--success); }
    .stat-change.down { color: var(--danger); }

    /* Alert Box */
    .alert-box {
        background: linear-gradient(135deg, rgba(239,68,68,0.1), rgba(239,68,68,0.05));
        border: 1px solid rgba(239,68,68,0.3);
        color: #fecaca;
        padding: 18px 22px;
        border-radius: 18px;
        margin-bottom: 24px;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 12px;
        animation: pulse-alert 2s infinite;
    }

    @keyframes pulse-alert {
        0%, 100% { box-shadow: 0 0 0 0 rgba(239,68,68,0.3); }
        50% { box-shadow: 0 0 20px 5px rgba(239,68,68,0.1); }
    }

    .alert-icon { font-size: 22px; }

    /* Dashboard Grid */
    .dashboard-grid {
        display: grid;
        grid-template-columns: 1.5fr 1fr;
        gap: 20px;
        margin-bottom: 24px;
    }

    .panel-section {
        background: linear-gradient(180deg, rgba(255,255,255,0.03), rgba(255,255,255,0.01)),
                    var(--bg-card);
        padding: 26px;
        border-radius: 24px;
        border: 1px solid var(--border);
        box-shadow: 0 20px 45px rgba(0,0,0,0.3);
    }

    .panel-header { display: flex; justify-content: space-between; margin-bottom: 24px; }
    .panel-title { color: var(--text-main); font-size: 20px; font-weight: 850; }
    .panel-subtitle { color: var(--text-muted); font-size: 13px; margin-top: 4px; }

    .chart-container { height: 300px; position: relative; }
    .chart-container.small { height: 240px; }

    /* Table */
    .table-panel {
        background: linear-gradient(180deg, rgba(255,255,255,0.03), rgba(255,255,255,0.01)),
                    var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 24px;
        padding: 26px;
        box-shadow: 0 20px 45px rgba(0,0,0,0.3);
        margin-bottom: 24px;
        overflow-x: auto;
    }

    .data-table { width: 100%; border-collapse: collapse; min-width: 700px; }
    .data-table th, .data-table td { padding: 16px 14px; border-bottom: 1px solid var(--border); color: var(--text-main); font-size: 14px; }
    .data-table th { color: var(--text-muted); font-size: 11px; text-transform: uppercase; letter-spacing: 0.8px; font-weight: 800; }
    .data-table tr:hover td { background: rgba(255,255,255,0.02); }

    /* Badges */
    .badge { padding: 6px 12px; border-radius: 999px; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; }
    .priority-urgent { background: rgba(127,29,29,0.5); color: #fecaca; }
    .priority-high { background: rgba(120,53,15,0.5); color: #fde68a; }
    .priority-medium { background: rgba(30,58,138,0.5); color: #bfdbfe; }
    .priority-low { background: rgba(20,83,45,0.5); color: #bbf7d0; }
    .status-pending { background: rgba(113,63,18,0.5); color: #fef3c7; }
    .status-in_progress { background: rgba(30,64,175,0.5); color: #dbeafe; }
    .status-completed { background: rgba(22,101,52,0.5); color: #dcfce7; }
    .status-cancelled { background: rgba(63,63,70,0.5); color: #d4d4d8; }

    /* Quick Actions */
    .quick-actions { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }

    .action-card {
        background: linear-gradient(180deg, rgba(255,255,255,0.03), rgba(255,255,255,0.01)),
                    var(--bg-card);
        padding: 24px;
        border-radius: 20px;
        text-decoration: none;
        text-align: center;
        border: 1px solid var(--border);
        transition: all 0.3s ease;
    }

    .action-card:hover {
        border-color: var(--primary);
        transform: translateY(-6px);
        box-shadow: 0 15px 40px rgba(139,92,246,0.2);
        background: linear-gradient(180deg, rgba(139,92,246,0.08), rgba(236,72,153,0.04));
    }

    .action-icon { font-size: 36px; margin-bottom: 12px; }
    .action-label { color: var(--text-main); font-size: 15px; font-weight: 800; }

    /* Responsive */
    @media (max-width: 1200px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
        .dashboard-grid { grid-template-columns: 1fr; }
    }

    @media (max-width: 768px) {
        .page-wrap { padding: 16px; }
        .stats-grid { grid-template-columns: 1fr; }
        .quick-actions { grid-template-columns: 1fr; }
        .hero-panel { flex-direction: column; text-align: center; }
        .hero-content h1 { font-size: 26px; }
    }

    @media (max-width: 480px) {
        .stat-card { padding: 20px; }
        .stat-value { font-size: 28px; }
    }
</style>

<div class="page-wrap">

    <!-- Hero Panel -->
    <div class="hero-panel">
        <div class="hero-content">
            <h1>Good {{ now()->hour < 12 ? 'Morning' : (now()->hour < 17 ? 'Afternoon' : 'Evening') }}! 👋</h1>
            <p>Reception operations, bookings and maintenance overview for today.</p>
        </div>
        <div class="hero-emoji">🛎️</div>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card blue">
            <div class="stat-icon">🍽️</div>
            <div class="stat-label">Today's Restaurant</div>
            <div class="stat-value">{{ $todayRestaurant ?? 0 }}</div>
            <div class="stat-change {{ ($restaurantChange ?? 0) >= 0 ? 'up' : 'down' }}">
                {{ ($restaurantChange ?? 0) >= 0 ? '↑' : '↓' }} {{ abs($restaurantChange ?? 0) }}% from yesterday
            </div>
        </div>

        <div class="stat-card green">
            <div class="stat-icon">📋</div>
            <div class="stat-label">Confirmed Today</div>
            <div class="stat-value">{{ $pendingRequests ?? 0 }}</div>
            <div class="stat-change">Needs service follow-up</div>
        </div>

        <div class="stat-card purple">
            <div class="stat-icon">🛠️</div>
            <div class="stat-label">Open Maintenance</div>
            <div class="stat-value">{{ $openMaintenance ?? 0 }}</div>
            <div class="stat-change">Pending / In progress</div>
        </div>

        <div class="stat-card red">
            <div class="stat-icon">🚨</div>
            <div class="stat-label">Urgent Maintenance</div>
            <div class="stat-value">{{ $urgentMaintenance ?? 0 }}</div>
            <div class="stat-change down">Needs attention</div>
        </div>
    </div>

    <!-- Maintenance Alert -->
    @if(isset($activeMaintenanceRooms) && $activeMaintenanceRooms->count() > 0)
        <div class="alert-box">
            <span class="alert-icon">⚠️</span>
            <span><strong>Rooms with active maintenance:</strong> {{ $activeMaintenanceRooms->join(', ') }}</span>
        </div>
    @endif

    <!-- Charts Grid -->
    <div class="dashboard-grid">
        <div class="panel-section">
            <div class="panel-header">
                <div>
                    <h3 class="panel-title">Weekly Bookings Overview</h3>
                    <p class="panel-subtitle">Afternoon tea, dinner and spa booking trend</p>
                </div>
            </div>
            <div class="chart-container">
                <canvas id="weeklyBookingChart"></canvas>
            </div>
        </div>

        <div class="panel-section">
            <div class="panel-header">
                <div>
                    <h3 class="panel-title">Maintenance Status</h3>
                    <p class="panel-subtitle">Overall task status breakdown</p>
                </div>
            </div>
            <div class="chart-container small">
                <canvas id="maintenanceStatusChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Table & Priority Chart -->
    <div class="dashboard-grid">
        <div class="table-panel">
            <div class="panel-header">
                <div>
                    <h3 class="panel-title">Recent Maintenance Tasks</h3>
                    <p class="panel-subtitle">Latest issues reported around the hotel</p>
                </div>
            </div>

            <table class="data-table">
                <thead>
                    <tr>
                        <th>Room</th>
                        <th>Issue</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Reported</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentMaintenance as $job)
                        <tr>
                            <td>{{ $job->room_number ?? '-' }}</td>
                            <td>{{ $job->title }}</td>
                            <td><span class="badge priority-{{ $job->priority }}">{{ ucfirst($job->priority) }}</span></td>
                            <td><span class="badge status-{{ $job->status }}">{{ ucfirst(str_replace('_', ' ', $job->status)) }}</span></td>
                            <td>{{ $job->created_at ? $job->created_at->format('d M, H:i') : '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; color: var(--text-muted);">No maintenance tasks found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="panel-section">
            <div class="panel-header">
                <div>
                    <h3 class="panel-title">Open Priority Split</h3>
                    <p class="panel-subtitle">Active tasks by priority</p>
                </div>
            </div>
            <div class="chart-container small">
                <canvas id="maintenancePriorityChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
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
            <div class="action-icon">💆</div>
            <div class="action-label">Spa Coming Soon</div>
        </a>
    </div>

</div>

<script>
    const textMuted = '#a1a1aa';
    const textDim = '#71717a';
    const border = '#3f3f46';

    new Chart(document.getElementById('weeklyBookingChart'), {
        type: 'bar',
        data: {
            labels: @json($labels),
            datasets: [
                {
                    label: 'Afternoon Tea',
                    data: @json($afternoonTea),
                    backgroundColor: 'rgba(59, 130, 246, 0.7)',
                    borderColor: '#3b82f6',
                    borderWidth: 2,
                    borderRadius: 10
                },
                {
                    label: 'Dinner',
                    data: @json($dinner),
                    backgroundColor: 'rgba(16, 185, 129, 0.7)',
                    borderColor: '#10b981',
                    borderWidth: 2,
                    borderRadius: 10
                },
                                {
                    label: 'Spa',
                    data: @json($spa),
                    backgroundColor: 'rgba(139, 92, 246, 0.7)',
                    borderColor: '#8b5cf6',
                    borderWidth: 2,
                    borderRadius: 10
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { 
                    position: 'top',
                    labels: { 
                        color: textMuted,
                        usePointStyle: true,
                        padding: 20,
                        font: { size: 12 }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0, color: textDim },
                    grid: { color: border }
                },
                x: {
                    ticks: { color: textDim },
                    grid: { display: false }
                }
            }
        }
    });

    new Chart(document.getElementById('maintenanceStatusChart'), {
        type: 'doughnut',
        data: {
            labels: @json($maintenanceStatusLabels),
            datasets: [{
                data: @json($maintenanceStatusData),
                backgroundColor: [
                    '#f59e0b',
                    '#3b82f6',
                    '#10b981',
                    '#71717a'
                ],
                borderColor: '#18181b',
                borderWidth: 4,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '65%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        color: textMuted,
                        padding: 18,
                        usePointStyle: true,
                        font: { size: 12 }
                    }
                }
            }
        }
    });

    new Chart(document.getElementById('maintenancePriorityChart'), {
        type: 'pie',
        data: {
            labels: @json($maintenancePriorityLabels),
            datasets: [{
                data: @json($maintenancePriorityData),
                backgroundColor: [
                    '#10b981',
                    '#3b82f6',
                    '#f59e0b',
                    '#ef4444'
                ],
                borderColor: '#18181b',
                borderWidth: 4,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        color: textMuted,
                        padding: 16,
                        usePointStyle: true,
                        font: { size: 11 }
                    }
                }
            }
        }
    });
</script>

@endsection