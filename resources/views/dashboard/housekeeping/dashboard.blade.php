@extends('dashboard.housekeeping.layout')

@section('content')

<div class="hk-dashboard">

    <div class="header">
        <div class="header-content">
            <h1>HK Supervisor</h1>
            <p>Today's Overview</p>
        </div>
        <div class="date-badge">
            <i class="fas fa-calendar"></i>
            {{ now()->format('d M Y') }}
        </div>
    </div>

    <div class="main-stats">
        <div class="stat-box red">
            <div class="stat-icon"><i class="fas fa-door-open"></i></div>
            <div class="stat-text">
                <span class="stat-num">{{ $totalRooms }}</span>
                <span class="stat-label">Rooms</span>
            </div>
        </div>

        <div class="stat-box orange">
            <div class="stat-icon"><i class="fas fa-clock"></i></div>
            <div class="stat-text">
                <span class="stat-num">{{ $allocatedHours }}h</span>
                <span class="stat-label">Hours</span>
            </div>
        </div>

        <div class="stat-box green">
            <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
            <div class="stat-text">
                <span class="stat-num">{{ $inspected }}</span>
                <span class="stat-label">Inspected</span>
            </div>
        </div>

        <div class="stat-box yellow">
            <div class="stat-icon"><i class="fas fa-broom"></i></div>
            <div class="stat-text">
                <span class="stat-num">{{ $cleaned }}</span>
                <span class="stat-label">Pending</span>
            </div>
        </div>
    </div>

    <div class="mini-stats">
        <div class="mini-item"><span>{{ $departureRooms }}</span><small>Depts</small></div>
        <div class="mini-item"><span>{{ $stayRooms }}</span><small>Stays</small></div>
        <div class="mini-item"><span>{{ $pending }}</span><small>Pending</small></div>
        <div class="mini-item blue"><span>{{ $inProgress }}</span><small>Active</small></div>
        <div class="mini-item danger"><span>{{ $dnd }}</span><small>DND</small></div>
        <div class="mini-item danger"><span>{{ $refused }}</span><small>Refused</small></div>
    </div>

    <div class="charts-row">
        <div class="chart-box">
            <h3><i class="fas fa-chart-pie"></i> Room Status</h3>
            <div class="chart-wrap">
                <canvas id="progressChart"></canvas>
            </div>
        </div>

        <div class="chart-box">
            <h3><i class="fas fa-chart-bar"></i> Weekly Hours</h3>
            <div class="chart-wrap">
                <canvas id="weeklyChart"></canvas>
            </div>
        </div>
    </div>

    <div class="staff-section">
        <h3><i class="fas fa-users"></i> Staff Allocation</h3>
        <p>Today's workload per housekeeper</p>

        <div class="staff-list">
            @forelse($staffSummary as $staff)
                <div class="staff-row">
                    <div class="staff-main">
                        <div class="staff-avatar">
                            {{ strtoupper(substr($staff['name'], 0, 2)) }}
                        </div>
                        <div class="staff-details">
                            <strong>{{ $staff['name'] }}</strong>
                            <small>{{ $staff['rooms'] }} rooms • {{ round($staff['minutes'] / 60, 1) }}h</small>
                        </div>
                    </div>

                    <div class="staff-badges">
                        <span class="badge-done">{{ $staff['cleaned'] }} done</span>
                        <span class="badge-pending">{{ $staff['pending'] }} pending</span>
                    </div>
                </div>
            @empty
                <div class="no-staff">
                    <i class="fas fa-user-slash"></i>
                    No staff allocated today
                </div>
            @endforelse
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const progressChart = document.getElementById('progressChart');

new Chart(progressChart, {
    type: 'doughnut',
    data: {
        labels: ['Pending', 'In Progress', 'Cleaned', 'Inspected', 'DND', 'Refused'],
        datasets: [{
            data: [
                {{ $pending }},
                {{ $inProgress }},
                {{ $cleaned }},
                {{ $inspected }},
                {{ $dnd }},
                {{ $refused }}
            ],
            backgroundColor: [
                '#f59e0b',
                '#3b82f6',
                '#22c55e',
                '#14b8a6',
                '#ef4444',
                '#dc2626'
            ],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    color: '#a1a1aa',
                    padding: 10,
                    font: {
                        size: 10
                    }
                }
            }
        }
    }
});

const weeklyChart = document.getElementById('weeklyChart');

new Chart(weeklyChart, {
    type: 'bar',
    data: {
        labels: [
            @foreach($weekDays as $d)
                '{{ $d['day'] }}',
            @endforeach
        ],
        datasets: [{
            data: [
                @foreach($weekDays as $d)
                    {{ $d['hours'] }},
                @endforeach
            ],
            backgroundColor: '#ef4444',
            borderRadius: 4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            x: {
                ticks: {
                    color: '#71717a',
                    font: { size: 9 }
                },
                grid: { display: false }
            },
            y: {
                ticks: {
                    color: '#71717a'
                },
                grid: {
                    color: '#27272a'
                }
            }
        },
        plugins: {
            legend: {
                display: false
            }
        }
    }
});
</script>

<style>
html,
body {
    max-width: 100%;
    overflow-x: hidden !important;
}

* {
    box-sizing: border-box;
}

.hk-dashboard,
.hk-dashboard * {
    max-width: 100%;
}

.hk-dashboard {
    width: 100%;
    padding: 16px;
    overflow-x: hidden;
}

.header {
    width: 100%;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
    margin-bottom: 20px;
    background: #18181b;
    border: 1px solid #3f3f46;
    border-radius: 16px;
    padding: 16px 20px;
}

.header-content {
    min-width: 0;
}

.header-content h1 {
    font-size: 22px;
    font-weight: 900;
    color: #fff;
    margin: 0;
    word-break: break-word;
}

.header-content p {
    color: #a1a1aa;
    font-size: 12px;
    margin-top: 2px;
}

.date-badge {
    flex-shrink: 0;
    background: rgba(239, 68, 68, 0.15);
    color: #f87171;
    padding: 8px 14px;
    border-radius: 20px;
    font-weight: 700;
    font-size: 13px;
    display: flex;
    align-items: center;
    gap: 6px;
    white-space: nowrap;
}

.main-stats {
    width: 100%;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
    gap: 12px;
    margin-bottom: 14px;
}

.stat-box {
    min-width: 0;
    background: #18181b;
    border: 1px solid #3f3f46;
    border-radius: 14px;
    padding: 16px;
    display: flex;
    align-items: center;
    gap: 12px;
}

.stat-box.red .stat-icon {
    background: rgba(239, 68, 68, 0.15);
    color: #ef4444;
}

.stat-box.green .stat-icon {
    background: rgba(34, 197, 94, 0.15);
    color: #22c55e;
}

.stat-box.yellow .stat-icon {
    background: rgba(245, 158, 11, 0.15);
    color: #f59e0b;
}

.stat-box.orange .stat-icon {
    background: rgba(249, 115, 22, 0.15);
    color: #f97316;
}

.stat-icon {
    flex: 0 0 40px;
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
}

.stat-text {
    min-width: 0;
    flex: 1;
}

.stat-num {
    display: block;
    font-size: 22px;
    font-weight: 900;
    color: #fff;
    line-height: 1.1;
}

.stat-label {
    font-size: 11px;
    color: #71717a;
}

.mini-stats {
    width: 100%;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(95px, 1fr));
    gap: 10px;
    margin-bottom: 16px;
}

.mini-item {
    min-width: 0;
    background: #27272a;
    border-radius: 10px;
    padding: 14px 10px;
    text-align: center;
    border: 1px solid #3f3f46;
}

.mini-item span {
    display: block;
    font-size: 20px;
    font-weight: 800;
    color: #fff;
}

.mini-item small {
    font-size: 9px;
    color: #71717a;
    text-transform: uppercase;
}

.mini-item.danger span {
    color: #ef4444;
}

.mini-item.blue span {
    color: #3b82f6;
}

.charts-row {
    width: 100%;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 14px;
    margin-bottom: 16px;
}

.chart-box {
    min-width: 0;
    background: #18181b;
    border: 1px solid #3f3f46;
    border-radius: 14px;
    padding: 16px;
    overflow: hidden;
}

.chart-box h3 {
    font-size: 14px;
    color: #fff;
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.chart-box h3 i {
    color: #ef4444;
    font-size: 12px;
}

.chart-wrap {
    position: relative;
    width: 100%;
    height: 180px;
    overflow: hidden;
}

canvas {
    max-width: 100% !important;
}

.staff-section {
    width: 100%;
    background: #18181b;
    border: 1px solid #3f3f46;
    border-radius: 14px;
    overflow: hidden;
}

.staff-section > h3 {
    font-size: 16px;
    color: #fff;
    padding: 16px 16px 6px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.staff-section > h3 i {
    color: #ef4444;
}

.staff-section > p {
    padding: 0 16px 14px;
    color: #71717a;
    font-size: 12px;
}

.staff-row {
    width: 100%;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 14px;
    padding: 14px 16px;
    border-top: 1px solid #3f3f46;
}

.staff-main {
    min-width: 0;
    display: flex;
    align-items: center;
    gap: 12px;
}

.staff-avatar {
    flex: 0 0 38px;
    width: 38px;
    height: 38px;
    background: linear-gradient(135deg, #ef4444, #b91c1c);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 800;
    font-size: 12px;
    color: #fff;
}

.staff-details {
    min-width: 0;
}

.staff-details strong {
    display: block;
    font-size: 14px;
    color: #fff;
    word-break: break-word;
}

.staff-details small {
    font-size: 11px;
    color: #71717a;
}

.staff-badges {
    flex-shrink: 0;
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    justify-content: flex-end;
}

.badge-done,
.badge-pending {
    padding: 5px 10px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 700;
    white-space: nowrap;
}

.badge-done {
    background: rgba(34, 197, 94, 0.15);
    color: #22c55e;
}

.badge-pending {
    background: rgba(245, 158, 11, 0.15);
    color: #f59e0b;
}

.no-staff {
    padding: 30px;
    text-align: center;
    color: #71717a;
    border-top: 1px solid #3f3f46;
}

.no-staff i {
    font-size: 24px;
    margin-bottom: 8px;
    display: block;
}

@media (max-width: 700px) {
    .hk-dashboard {
        padding: 12px;
    }

    .header {
        flex-direction: column;
        align-items: flex-start;
    }

    .date-badge {
        width: 100%;
        justify-content: center;
    }

    .stat-box {
        flex-direction: column;
        text-align: center;
    }

    .stat-icon {
        margin: 0 auto;
    }

    .staff-row {
        flex-direction: column;
        align-items: flex-start;
    }

    .staff-main {
        width: 100%;
    }

    .staff-badges {
        width: 100%;
        justify-content: flex-start;
    }
}
</style>

@endsection