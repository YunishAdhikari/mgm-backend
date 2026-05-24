{{-- @extends('layouts.kitchen') --}}
@extends('dashboard.kitchen-supervisor.layout')

@section('page-title', 'Kitchen Dashboard')
@section('page-subtitle', 'Control kitchen inventory, rota, stock movement, and daily handovers')

@section('content')

<!-- Welcome Banner -->
<div class="welcome-banner">
    <div class="welcome-content">
        <h1><i class="fas fa-hat-chef"></i> Kitchen Operations</h1>
        <p>Control kitchen inventory, rota, stock movement, and daily handovers.</p>
    </div>
    <div class="welcome-icon">
        <i class="fas fa-kitchen-set"></i>
    </div>
</div>

<!-- Quick Actions Grid -->
<div class="quick-actions">
    <a href="{{ route('kitchen.inventory.index') }}" class="action-card">
        <div class="action-icon green">
            <i class="fas fa-boxes-stacked"></i>
        </div>
        <div class="action-info">
            <h3>Inventory</h3>
            <p>Add items, manage stock, and monitor low stock alerts.</p>
        </div>
        <i class="fas fa-arrow-right arrow"></i>
    </a>

    <a href="{{ route('kitchen.inventory.history') }}" class="action-card">
        <div class="action-icon blue">
            <i class="fas fa-arrow-right-arrow-left"></i>
        </div>
        <div class="action-info">
            <h3>Stock In / Out</h3>
            <p>Record deliveries, kitchen usage, and stock deductions.</p>
        </div>
        <i class="fas fa-arrow-right arrow"></i>
    </a>

    <a href="#" class="action-card">
        <div class="action-icon orange">
            <i class="fas fa-clipboard-list"></i>
        </div>
        <div class="action-info">
            <h3>Kitchen Rota</h3>
            <p>Create kitchen staff rota drafts for your department.</p>
        </div>
        <i class="fas fa-arrow-right arrow"></i>
    </a>

    <a href="#" class="action-card">
        <div class="action-icon purple">
            <i class="fas fa-note-sticky"></i>
        </div>
        <div class="action-info">
            <h3>Handover Notes</h3>
            <p>Share important kitchen updates between shifts.</p>
        </div>
        <i class="fas fa-arrow-right arrow"></i>
    </a>
</div>

<!-- Low Stock Alert -->
@if($lowStockAlerts->count() > 0)
    <div class="alert-warning">
        <h3><i class="fas fa-triangle-exclamation"></i> Low Stock Alert</h3>
        <div class="alert-items">
            @foreach($lowStockAlerts as $item)
                <span class="alert-item">
                    <strong>{{ $item->name }}</strong>
                    {{ $item->quantity }} {{ $item->unit }}
                </span>
            @endforeach
        </div>
    </div>
@endif

<!-- Stats Grid -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon green">
            <i class="fas fa-boxes-stacked"></i>
        </div>
        <div class="stat-info">
            <h3>{{ $totalItems }}</h3>
            <p>Total Items</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon red">
            <i class="fas fa-triangle-exclamation"></i>
        </div>
        <div class="stat-info">
            <h3>{{ $lowStockCount }}</h3>
            <p>Low Stock</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon blue">
            <i class="fas fa-utensils"></i>
        </div>
        <div class="stat-info">
            <h3>{{ $totalRecipes }}</h3>
            <p>Recipes</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon purple">
            <i class="fas fa-bowl-food"></i>
        </div>
        <div class="stat-info">
            <h3>{{ $totalBuffets }}</h3>
            <p>Buffets</p>
        </div>
    </div>
</div>

<!-- Recent Activity Card -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-clock"></i>
            Recent Activity
        </h3>
    </div>
    <div class="activity-list">
        @forelse($recentWastages as $wastage)
            <div class="activity-item">
                <div class="activity-icon red">
                    <i class="fas fa-trash-can"></i>
                </div>
                <div class="activity-info">
                    <p><strong>{{ $wastage->item->name ?? 'Unknown' }}</strong></p>
                    <span>{{ $wastage->quantity }} {{ $wastage->item->unit ?? '' }} - {{ ucfirst($wastage->reason) }}</span>
                </div>
                <span class="activity-time">{{ $wastage->created_at->diffForHumans() }}</span>
            </div>
        @empty
            <div class="activity-item">
                <div class="activity-icon green">
                    <i class="fas fa-check"></i>
                </div>
                <div class="activity-info">
                    <p><strong>No recent activity</strong></p>
                    <span>All clear!</span>
                </div>
            </div>
        @endforelse
    </div>
</div>

<style>
/* Welcome Banner */
.welcome-banner {
    background: linear-gradient(135deg, #dc2626 0%, #991b1c 100%);
    color: white;
    padding: 32px 36px;
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 30px;
    margin-bottom: 28px;
    box-shadow: 0 12px 32px rgba(220, 38, 38, 0.25);
}

.welcome-content h1 {
    margin: 0 0 10px;
    font-size: 28px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 14px;
}

.welcome-content p {
    margin: 0;
    color: rgba(255, 255, 255, 0.85);
    font-size: 15px;
}

.welcome-icon {
    width: 80px;
    height: 80px;
    background: rgba(255, 255, 255, 0.15);
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 36px;
    flex-shrink: 0;
}

/* Alert Warning */
.alert-warning {
    background: #fef2f2;
    border: 1px solid #fecaca;
    border-radius: 12px;
    padding: 16px;
    margin-bottom: 24px;
}

.alert-warning h3 {
    color: #dc2626;
    font-size: 14px;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.alert-items {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.alert-item {
    background: white;
    color: #dc2626;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    border: 1px solid #fecaca;
}

/* Quick Actions */
.quick-actions {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 18px;
    margin-bottom: 28px;
}

.action-card {
    background: white;
    padding: 22px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    gap: 18px;
    text-decoration: none;
    color: #1e293b;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
    transition: all 0.25s;
}

.action-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 28px rgba(0, 0, 0, 0.08);
    border-color: #dc2626;
}

.action-icon {
    width: 54px;
    height: 54px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    flex-shrink: 0;
}

.action-icon.green { background: #dcfce7; color: #16a34a; }
.action-icon.blue { background: #dbeafe; color: #2563eb; }
.action-icon.orange { background: #ffedd5; color: #ea580c; }
.action-icon.purple { background: #f3e8ff; color: #9333ea; }

.action-info {
    flex: 1;
}

.action-info h3 {
    margin: 0 0 6px;
    font-size: 17px;
    font-weight: 700;
}

.action-info p {
    margin: 0;
    color: #64748b;
    font-size: 13px;
}

.arrow {
    color: #cbd5e1;
    font-size: 14px;
    transition: all 0.2s;
}

.action-card:hover .arrow {
    color: #dc2626;
    transform: translateX(4px);
}

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 18px;
    margin-bottom: 28px;
}

.stat-card {
    background: white;
    padding: 22px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    gap: 16px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
}

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

.stat-icon.green { background: #dcfce7; color: #16a34a; }
.stat-icon.red { background: #fee2e2; color: #dc2626; }
.stat-icon.blue { background: #dbeafe; color: #2563eb; }
.stat-icon.purple { background: #f3e8ff; color: #9333ea; }

.stat-info h3 {
    margin: 0;
    font-size: 26px;
    font-weight: 800;
    color: #1e293b;
}

.stat-info p {
    margin: 0;
    color: #64748b;
    font-size: 13px;
}

/* Activity List */
.activity-list {
    display: flex;
    flex-direction: column;
    gap: 14px;
}

.activity-item {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 14px;
    border-radius: 12px;
    background: #f8fafc;
}

.activity-icon {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    flex-shrink: 0;
}

.activity-icon.green { background: #dcfce7; color: #16a34a; }
.activity-icon.red { background: #fee2e2; color: #dc2626; }

.activity-info {
    flex: 1;
}

.activity-info p {
    margin: 0;
    font-size: 14px;
    color: #1e293b;
    font-weight: 600;
}

.activity-info span {
    font-size: 12px;
    color: #64748b;
}

.activity-time {
    font-size: 11px;
    color: #94a3b8;
    white-space: nowrap;
}

/* Responsive */
@media (max-width: 1100px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .quick-actions {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .welcome-banner {
        flex-direction: column;
        align-items: flex-start;
        text-align: left;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
}
</style>

@endsection