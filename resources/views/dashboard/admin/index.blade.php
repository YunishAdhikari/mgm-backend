@extends('dashboard.admin.layout')

@section('content')

<!-- Force Full Width & No Scroll Wrapper -->
<div class="full-width-wrapper" style="width: 100%; max-width: 100%; overflow-x: hidden;">

    <section class="admin-command-center" style="width: 100%; max-width: 100%;">

        <!-- Hero Panel -->
        <div class="hero-panel">
            <div class="hero-text">
                <p class="eyebrow">MGRH Admin Panel</p>
                <h1>Hotel Operations Command Centre</h1>
                <span>Live overview of staff, maintenance, departments, news and system activity.</span>
            </div>

            <div class="hero-badge">
                <i class="fa-solid fa-shield-halved"></i>
                <div>
                    <strong>System Active</strong>
                    <small>{{ now()->format('d M Y, h:i A') }}</small>
                </div>
            </div>
        </div>

        <!-- Main Stats Cards -->
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

        <!-- Mini Stats Cards -->
        <section class="dashboard-overview mini">
            <div class="mini-card purple">
                <span>Total Maintenance Jobs</span>
                <strong>{{ $totalMaintenanceJobs ?? 0 }}</strong>
            </div>

            <div class="mini-card orange">
                <span>Pending Jobs</span>
                <strong>{{ $pendingJobs ?? 0 }}</strong>
            </div>

            <div class="mini-card blue">
                <span>In Progress</span>
                <strong>{{ $inProgressJobs ?? 0 }}</strong>
            </div>

            <div class="mini-card green">
                <span>Completed</span>
                <strong>{{ $completedJobs ?? 0 }}</strong>
            </div>
        </section>

        <!-- Charts Grid -->
        <section class="charts-grid">
            <div class="chart-card">
                <div class="section-title">
                    <h3>Maintenance Status</h3>
                    <p>Pending, in-progress, completed and cancelled jobs.</p>
                </div>
                <div class="chart-container">
                    <canvas id="maintenanceStatusChart"></canvas>
                </div>
            </div>

            <div class="chart-card">
                <div class="section-title">
                    <h3>Employees by Department</h3>
                    <p>Department-wise staff distribution.</p>
                </div>
                <div class="chart-container">
                    <canvas id="departmentChart"></canvas>
                </div>
            </div>
        </section>

        <!-- Bottom Grid -->
        <section class="bottom-grid">
            <div class="activity-panel">
                <div class="section-title">
                    <h3>Recent Activity Logs</h3>
                    <p>Latest actions recorded in MGM Ops.</p>
                </div>

                <div class="activity-list">
                    @forelse($recentActivities ?? [] as $activity)
                        <div class="activity-item">
                            <div class="activity-icon">
                                <i class="fa-solid fa-bolt"></i>
                            </div>
                            <div>
                                <h4>{{ $activity->user->name ?? 'System' }}</h4>
                                <p>{{ $activity->action }}</p>
                                <span>{{ $activity->module ?? 'General' }} • {{ $activity->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="empty-box">No recent activity found.</div>
                    @endforelse
                </div>
            </div>

            <div class="system-panel">
                <div class="section-title">
                    <h3>System Health</h3>
                    <p>Quick admin health overview.</p>
                </div>

                <div class="health-content">
                    <div class="health-row">
                        <span>Application</span>
                        <strong class="good">Online</strong>
                    </div>
                    <div class="health-row">
                        <span>Database</span>
                        <strong class="good">Connected</strong>
                    </div>
                    <div class="health-row">
                        <span>Activities Today</span>
                        <strong>{{ $totalActivitiesToday ?? 0 }}</strong>
                    </div>
                    <div class="health-row">
                        <span>Cancelled Jobs</span>
                        <strong>{{ $cancelledJobs ?? 0 }}</strong>
                    </div>
                </div>
            </div>
        </section>

    </section>

</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const maintenanceStatusData = [
        {{ $pendingJobs ?? 0 }},
        {{ $inProgressJobs ?? 0 }},
        {{ $completedJobs ?? 0 }},
        {{ $cancelledJobs ?? 0 }}
    ];

    const departmentLabels = {!! json_encode($departmentNames ?? []) !!};
    const departmentCounts = {!! json_encode($departmentCounts ?? []) !!};

    new Chart(document.getElementById('maintenanceStatusChart'), {
        type: 'doughnut',
        data: {
            labels: ['Pending', 'In Progress', 'Completed', 'Cancelled'],
            datasets: [{
                data: maintenanceStatusData,
                backgroundColor: ['#f59e0b', '#3b82f6', '#10b981', '#71717a'],
                borderColor: '#18181b',
                borderWidth: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { color: '#fafafa', padding: 15, usePointStyle: true }
                }
            }
        }
    });

    new Chart(document.getElementById('departmentChart'), {
        type: 'bar',
        data: {
            labels: departmentLabels,
            datasets: [{
                label: 'Employees',
                data: departmentCounts,
                backgroundColor: '#8b5cf6',
                borderRadius: 12
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: { ticks: { color: '#a1a1aa' }, grid: { display: false } },
                y: { beginAtZero: true, ticks: { color: '#a1a1aa', precision: 0 }, grid: { color: '#27272a' } }
            },
            plugins: { legend: { display: false } }
        }
    });
</script>

<style>
    /* CRITICAL: Reset everything to prevent overflow */
    html, body {
        overflow-x: hidden !important;
        max-width: 100vw !important;
    }

    :root {
        --bg-card: #18181b;
        --text-main: #fafafa;
        --text-muted: #a1a1aa;
        --text-dim: #71717a;
        --border: #3f3f46;
        --purple: #8b5cf6;
        --pink: #ec4899;
    }

    /* Core Container */
    .full-width-wrapper {
        width: 100%;
        max-width: 100%;
        overflow-x: hidden;
        padding: 16px;
    }

    *, *::before, *::after {
        box-sizing: border-box;
        max-width: 100%;
    }

    .admin-command-center {
        display: flex;
        flex-direction: column;
        gap: 16px;
        width: 100%;
    }

    /* Hero Panel */
    .hero-panel {
        background: radial-gradient(circle at top left, rgba(139,92,246,.35), transparent 35%),
                    radial-gradient(circle at bottom right, rgba(236,72,153,.25), transparent 35%),
                    var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
    }

    .eyebrow {
        color: #c084fc;
        font-size: 10px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 4px;
    }

    .hero-panel h1 {
        color: var(--text-main);
        font-size: 20px;
        margin: 0 0 4px;
    }

    .hero-panel span, .hero-panel small { color: var(--text-muted); font-size: 12px; }

    .hero-badge {
        background: rgba(255,255,255,.06);
        border: 1px solid rgba(255,255,255,.1);
        border-radius: 10px;
        padding: 10px 14px;
        display: flex;
        gap: 10px;
        align-items: center;
        color: white;
    }

    .hero-badge i { color: #6ee7b7; font-size: 18px; }
    .hero-badge strong { display: block; color: white; font-size: 13px; }
    .hero-badge small { font-size: 10px; }

    /* Dashboard Grids */
    .dashboard-overview {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 12px;
    }

    .dashboard-card, .mini-card, .chart-card, .activity-panel, .system-panel {
        background: linear-gradient(180deg, rgba(255,255,255,.04), rgba(255,255,255,.01)), var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 14px;
        box-shadow: 0 8px 20px rgba(0,0,0,.2);
    }

    .dashboard-card {
        padding: 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .dashboard-card p { color: var(--text-muted); font-weight: 800; font-size: 11px; margin-bottom: 2px; }
    .dashboard-card h2 { font-size: 24px; color: var(--text-main); margin: 0; }
    
    .dashboard-card i {
        font-size: 20px;
        padding: 10px;
        border-radius: 10px;
        color: white;
    }

    .total i { background: linear-gradient(135deg, #3b82f6, #60a5fa); }
    .active i { background: linear-gradient(135deg, #10b981, #34d399); }
    .maintenance i { background: linear-gradient(135deg, #f59e0b, #fbbf24); }
    .news i { background: linear-gradient(135deg, #ec4899, #f472b6); }

    /* Mini Cards */
    .mini-card { padding: 14px; border-left: 4px solid var(--purple); }
    .mini-card span { color: var(--text-muted); font-weight: 800; font-size: 11px; }
    .mini-card strong { display: block; margin-top: 2px; color: white; font-size: 20px; }
    .mini-card.orange { border-left-color: #f59e0b; }
    .mini-card.blue { border-left-color: #3b82f6; }
    .mini-card.green { border-left-color: #10b981; }

    /* Charts Grid */
    .charts-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }

    .chart-card {
        padding: 16px;
        min-height: 250px;
        display: flex;
        flex-direction: column;
    }

    .chart-container {
        flex: 1;
        position: relative;
        width: 100%;
        min-height: 180px;
    }

    /* Bottom Grid */
    .bottom-grid {
        display: grid;
        grid-template-columns: 1.5fr 1fr;
        gap: 12px;
    }

    .activity-panel, .system-panel {
        padding: 16px;
        display: flex;
        flex-direction: column;
    }

    .section-title h3 { color: white; margin: 0; font-size: 16px; }
    .section-title p { color: var(--text-muted); margin-top: 2px; margin-bottom: 12px; font-size: 11px; }

    /* Activity List */
    .activity-list { flex: 1; max-height: 250px; overflow-y: auto; }
    .activity-item {
        display: flex;
        gap: 10px;
        padding: 10px 0;
        border-bottom: 1px solid var(--border);
    }
    .activity-item:last-child { border-bottom: none; }
    .activity-icon {
        width: 32px; height: 32px;
        border-radius: 8px;
        background: linear-gradient(135deg, var(--purple), var(--pink));
        color: white;
        display: grid;
        place-items: center;
        flex-shrink: 0;
        font-size: 12px;
    }
    .activity-item h4 { color: white; margin: 0; font-size: 13px; }
    .activity-item p { color: #d4d4d8; margin: 2px 0; font-size: 12px; }
    .activity-item span { color: var(--text-dim); font-size: 10px; }

    /* System Health */
    .health-content { margin-top: 6px; }
    .health-row {
        display: flex;
        justify-content: space-between;
        border-bottom: 1px solid var(--border);
        padding: 10px 0;
        color: var(--text-muted);
        font-weight: 700;
        font-size: 12px;
    }
    .health-row:last-child { border-bottom: none; }
    .health-row strong { color: white; }
    .health-row .good { color: #6ee7b7; }
    .empty-box {
        color: var(--text-muted);
        padding: 12px;
        text-align: center;
        border: 1px dashed var(--border);
        border-radius: 10px;
        font-size: 12px;
    }

    /* RESPONSIVE: Tablet */
    @media (max-width: 1100px) {
        .dashboard-overview { grid-template-columns: repeat(2, 1fr); }
        .bottom-grid { grid-template-columns: 1fr; }
    }

    /* RESPONSIVE: Mobile */
    @media (max-width: 700px) {
        .hero-panel {
            flex-direction: column;
            align-items: flex-start;
            padding: 14px;
        }
        
        .hero-panel h1 { font-size: 18px; }
        
        .dashboard-overview { grid-template-columns: 1fr 1fr; }
        
        .charts-grid { grid-template-columns: 1fr; }
        
        .bottom-grid { grid-template-columns: 1fr; }
    }

    /* RESPONSIVE: Small Mobile */
    @media (max-width: 500px) {
        .dashboard-overview { grid-template-columns: 1fr; }
        
        .dashboard-card {
            flex-direction: row;
        }
        
        .dashboard-card h2 { font-size: 20px; }
        
        .hero-badge { width: 100%; justify-content: center; }
    }
</style>

@endsection