@extends('dashboard.admin.layout')

@section('content')

<div class="breadcrumb">
    <span>Home</span> &nbsp;/&nbsp; <span>Dashboard</span>
</div>

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


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const maintenanceChart = document.getElementById('maintenanceChart');

new Chart(maintenanceChart, {
    type: 'doughnut',
    data: {
        labels: ['Pending', 'In Progress', 'Completed', 'Cancelled'],
        datasets: [{
            data: [
                {{ $pendingJobs ?? 0 }},
                {{ $inProgressJobs ?? 0 }},
                {{ $completedJobs ?? 0 }},
                {{ $cancelledJobs ?? 0 }}
            ],
            backgroundColor: ['#f59e0b', '#1583ff', '#22c55e', '#ef4444']
        }]
    }
});

const departmentChart = document.getElementById('departmentChart');

new Chart(departmentChart, {
    type: 'bar',
    data: {
        labels: {!! json_encode($departmentNames ?? []) !!},
        datasets: [{
            label: 'Employees',
            data: {!! json_encode($departmentCounts ?? []) !!},
            backgroundColor: '#1583ff',
            borderRadius: 10
        }]
    }
});
</script>

<style>
.dashboard-overview {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 18px;
    margin-bottom: 25px;
}

.dashboard-card {
    background: white;
    padding: 24px;
    border-radius: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 10px 30px rgba(0,0,0,0.06);
}

.dashboard-card p {
    color: #6b7280;
    font-weight: 700;
    margin-bottom: 8px;
}

.dashboard-card h2 {
    font-size: 34px;
    color: #111827;
}

.dashboard-card i {
    font-size: 34px;
    padding: 18px;
    border-radius: 18px;
    color: white;
}

.total i { background: #1583ff; }
.active i { background: #22c55e; }
.maintenance i { background: #f59e0b; }
.news i { background: #ff15c4; }

.chart-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.chart-card {
    background: white;
    padding: 24px;
    border-radius: 22px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.06);
}

.chart-card h3 {
    margin-bottom: 20px;
    color: #111827;
}

@media(max-width: 992px) {
    .dashboard-overview {
        grid-template-columns: repeat(2, 1fr);
    }

    .chart-grid {
        grid-template-columns: 1fr;
    }
}

@media(max-width: 600px) {
    .dashboard-overview {
        grid-template-columns: 1fr;
    }
}
</style>

@endsection