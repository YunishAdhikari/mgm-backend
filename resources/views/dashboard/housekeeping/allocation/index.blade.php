@extends('dashboard.housekeeping.layout')

@section('title', 'Room Allocation')
@section('page-title', 'Room Allocation')

@section('content')

<div class="page-wrapper">
    <!-- Background Effects -->
    <div class="ambient-glow"></div>
    <div class="dot-pattern"></div>

    <!-- Page Header -->
    <header class="page-header">
        <div class="header-left">
            <div class="icon-container">
                <i class="fas fa-clipboard-list"></i>
                <div class="icon-shine"></div>
            </div>
            <div class="header-text">
                <h1>Room Allocation</h1>
            </div>
        </div>
        
        <form method="GET" action="{{ route('housekeeping-supervisor.allocation.index') }}" class="date-control">
            <div class="date-input-group">
                <i class="fas fa-calendar"></i>
                <input type="date" name="date" value="{{ $date }}">
            </div>
            <button type="submit" class="btn-primary">
                <span>Load</span>
                <i class="fas fa-arrow-right"></i>
            </button>
        </form>
    </header>

    <!-- Alerts -->
    @if(session('success'))
        <div class="alert-box success">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="alert-box error">
            <i class="fas fa-exclamation-circle"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon staff">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-info">
                <span class="stat-value">{{ $staff->count() }}</span>
                <span class="stat-label">HK Staff</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon available">
                <i class="fas fa-bed"></i>
            </div>
            <div class="stat-info">
                <span class="stat-value">{{ $availableRooms->count() }}</span>
                <span class="stat-label">Available</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon allocated">
                <i class="fas fa-check-double"></i>
            </div>
            <div class="stat-info">
                <span class="stat-value">{{ $allocations->flatten()->count() }}</span>
                <span class="stat-label">Allocated</span>
            </div>
        </div>
    </div>

    <!-- Auto Allocate -->
    <form method="POST" action="{{ route('housekeeping-supervisor.allocation.auto-allocate') }}" class="auto-allocate-wrap">
        @csrf
        <input type="hidden" name="allocation_date" value="{{ $date }}">
        <button type="submit" class="btn-auto-allocate" onclick="return confirm('Auto allocate all rooms?')">
            <i class="fas fa-wand-magic-sparkles"></i>
            <span>Auto Allocate Rooms</span>
        </button>
    </form>

    <!-- Staff Section -->
    <div class="section-title">
        <h2><i class="fas fa-user-group"></i> Staff Assignments</h2>
    </div>

    <div class="staff-grid">
        @foreach($staff as $employee)
            @php
                $employeeAllocations = $allocations->get($employee->id, collect());
                $colors = ['#6366f1', '#8b5cf6', '#ec4899', '#f43f5e', '#14b8a6', '#f59e0b'];
                $color = $colors[$employee->id % count($colors)];
            @endphp

            <div class="staff-card" style="--card-accent: {{ $color }}">
                <div class="card-glow"></div>
                
                <div class="staff-header">
                    <div class="staff-avatar" style="background: linear-gradient(135deg, {{ $color }}, {{ $color }}cc)">
                        {{ strtoupper(substr($employee->name, 0, 2)) }}
                    </div>
                    <div class="staff-details">
                        <h3>{{ $employee->name }}</h3>
                        <p>{{ $employee->email }}</p>
                    </div>
                    <div class="room-counter">
                        <span class="count">{{ $employeeAllocations->count() }}</span>
                        <span class="label">rooms</span>
                    </div>
                </div>

                <div class="allocated-rooms">
                    @if($employeeAllocations->count())
                        <div class="rooms-flex">
                            @foreach($employeeAllocations as $allocation)
                                <form method="POST" action="{{ route('housekeeping-supervisor.allocation.remove', $allocation->id) }}" class="inline-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="room-chip" onclick="return confirm('Remove room?')">
                                        <span>{{ $allocation->room->room_number ?? 'N/A' }}</span>
                                        <i class="fas fa-xmark"></i>
                                    </button>
                                </form>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="fas fa-layer-plus"></i>
                            <span>No rooms yet</span>
                        </div>
                    @endif
                </div>

                <button type="button" class="btn-assign" onclick="openAssignModal('{{ $employee->id }}', '{{ addslashes($employee->name) }}')">
                    <i class="fas fa-plus"></i>
                    <span>Assign Rooms</span>
                </button>
            </div>
        @endforeach
    </div>
</div>

<!-- Assign Modal -->
<div id="assignModal" class="modal-container">
    <div class="modal-backdrop" onclick="closeAssignModal()"></div>
    <div class="modal-card">
        <div class="modal-header-custom">
            <div class="modal-title-area">
                <div class="modal-icon">
                    <i class="fas fa-user-plus"></i>
                </div>
                <div>
                    <h2>Assign Rooms</h2>
                    <p id="selectedStaffName">Select staff</p>
                </div>
            </div>
            <button type="button" class="modal-close-btn" onclick="closeAssignModal()">
                <i class="fas fa-xmark"></i>
            </button>
        </div>

        <form method="POST" action="{{ route('housekeeping-supervisor.allocation.assign') }}" class="modal-body-custom">
            @csrf
            <input type="hidden" name="assigned_to" id="assigned_to">
            <input type="hidden" name="allocation_date" value="{{ $date }}">

            @if($availableRooms->count())
                <div class="room-sections">
                    @foreach([
                        'departure' => ['label' => 'Departure', 'icon' => 'fa-sign-out', 'color' => '#10b981'],
                        'stay' => ['label' => 'Stayover', 'icon' => 'fa-moon', 'color' => '#3b82f6'],
                        'carry_forward' => ['label' => 'Carry Forward', 'icon' => 'fa-rotate', 'color' => '#a855f7'],
                        'room_move' => ['label' => 'Room Move', 'icon' => 'fa-right-left', 'color' => '#f59e0b'],
                    ] as $status => $info)
                        @if($availableRoomsByStatus->get($status, collect())->count())
                            <div class="room-section">
                                <h3 class="section-heading" style="--section-color: {{ $info['color'] }}">
                                    <i class="fas {{ $info['icon'] }}"></i>
                                    <span>{{ $info['label'] }}</span>
                                </h3>
                                <div class="room-options">
                                    @foreach($availableRoomsByStatus->get($status) as $roomStatus)
                                        <label class="room-option" style="--opt-color: {{ $info['color'] }}">
                                            <input type="checkbox" name="room_status_update_ids[]" value="{{ $roomStatus->id }}">
                                            <div class="option-inner">
                                                <span class="room-num">{{ $roomStatus->room->room_number ?? 'N/A' }}</span>
                                                <span class="room-type">{{ $roomStatus->room->roomType->name ?? 'Room' }}</span>
                                                <i class="fas fa-check check-mark"></i>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>

                <button type="submit" class="btn-save-assignment">
                    <i class="fas fa-check"></i>
                    <span>Save Assignment</span>
                </button>
            @else
                <div class="all-done">
                    <div class="done-icon">
                        <i class="fas fa-party-horn"></i>
                    </div>
                    <h3>All Rooms Allocated!</h3>
                    <p>No available rooms left for this date.</p>
                </div>
            @endif
        </form>
    </div>
</div>

<style>
/* ===== CSS Variables ===== */
:root {
    --bg-deep: #050507;
    --bg-surface: #0c0c10;
    --bg-card: #101014;
    --bg-raised: #16161b;
    --primary: #22c55e;
    --primary-hover: #16a34a;
    --accent: #6366f1;
    --text-primary: #fafafa;
    --text-secondary: #a1a1aa;
    --text-muted: #52525b;
    --border: rgba(255,255,255,0.08);
    --border-light: rgba(255,255,255,0.12);
    --danger: #ef4444;
    --glow-primary: rgba(34,197,94,0.25);
}

* { box-sizing: border-box; margin: 0; padding: 0; }
body { background: var(--bg-deep); color: var(--text-primary); font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; }
.page-wrapper { position: relative; z-index: 1; }

/* Background Effects */
.ambient-glow {
    position: fixed; top: -30%; left: 50%; transform: translateX(-50%);
    width: 80%; height: 60%; background: radial-gradient(ellipse, var(--glow-primary), transparent 70%);
    pointer-events: none; z-index: 0;
}
.dot-pattern {
    position: fixed; inset: 0;
    background-image: radial-gradient(rgba(255,255,255,0.03) 1px, transparent 1px);
    background-size: 24px 24px; pointer-events: none; z-index: 0;
}

/* Page Header */
.page-header {
    display: flex; justify-content: space-between; align-items: center;
    flex-wrap: wrap; gap: 24px; margin-bottom: 32px;
}
.header-left { display: flex; align-items: center; gap: 20px; }
.icon-container {
    position: relative; width: 60px; height: 60px;
    background: linear-gradient(135deg, var(--primary), var(--primary-hover));
    border-radius: 18px; display: flex; align-items: center; justify-content: center;
    font-size: 26px; color: white; box-shadow: 0 8px 32px var(--glow-primary);
}
.icon-shine {
    position: absolute; inset: 0; border-radius: 18px;
    background: linear-gradient(135deg, transparent 40%, rgba(255,255,255,0.15) 50%, transparent 60%);
    animation: shine 3s infinite;
}
@keyframes shine { 0%, 100% { transform: translateX(-100%); } 50% { transform: translateX(100%); } }
.header-text h1 { font-size: 30px; font-weight: 800; letter-spacing: -0.03em; margin-bottom: 4px; }
.header-text p { color: var(--text-secondary); font-size: 14px; }

/* Date Control */
.date-control { display: flex; gap: 12px; }
.date-input-group {
    display: flex; align-items: center; gap: 10px; padding: 12px 16px;
    background: var(--bg-card); border: 1px solid var(--border); border-radius: 12px;
}
.date-input-group i { color: var(--primary); }
.date-input-group input { background: none; border: none; color: var(--text-primary); font-size: 14px; outline: none; cursor: pointer; }
.btn-primary {
    display: flex; align-items: center; gap: 8px; padding: 12px 20px;
    background: linear-gradient(135deg, var(--primary), var(--primary-hover));
    border: none; border-radius: 12px; color: white; font-weight: 700; cursor: pointer; transition: all 0.2s;
}
.btn-primary:hover { transform: translateX(2px); box-shadow: 0 8px 24px var(--glow-primary); }

/* Alert Box */
.alert-box {
    display: flex; align-items: center; gap: 12px; padding: 14px 18px;
    border-radius: 12px; margin-bottom: 24px; font-weight: 500;
    animation: slideDown 0.3s ease;
}
@keyframes slideDown { from { opacity: 0; transform: translateY(-12px); } to { opacity: 1; transform: translateY(0); } }
.alert-box.success { background: rgba(34, 197, 94, 0.1); border: 1px solid rgba(34, 197, 94, 0.3); color: var(--primary); }
.alert-box.error { background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); color: var(--danger); }
.alert-box i { font-size: 18px; }

/* Stats Grid */
.stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 28px; }
@media (max-width: 720px) { .stats-grid { grid-template-columns: 1fr; } }
.stat-card {
    display: flex; align-items: center; gap: 16px; padding: 24px;
    background: var(--bg-card); border: 1px solid var(--border); border-radius: 20px; transition: all 0.3s;
}
.stat-card:hover { border-color: var(--border-light); transform: translateY(-3px); }
.stat-icon {
    width: 52px; height: 52px; border-radius: 14px;
    display: flex; align-items: center; justify-content: center; font-size: 22px;
}
.stat-icon.staff { background: linear-gradient(135deg, #6366f1, #8b5cf6); color: white; }
.stat-icon.available { background: linear-gradient(135deg, #3b82f6, #60a5fa); color: white; }
.stat-icon.allocated { background: linear-gradient(135deg, #22c55e, #4ade80); color: white; }
.stat-info { display: flex; flex-direction: column; }
.stat-value { font-size: 34px; font-weight: 800; line-height: 1; }
.stat-label { font-size: 13px; color: var(--text-secondary); margin-top: 2px; }

/* Auto Allocate */
.auto-allocate-wrap { margin-bottom: 32px; }
.btn-auto-allocate {
    display: inline-flex; align-items: center; gap: 10px; padding: 14px 28px;
    background: linear-gradient(135deg, var(--accent), #7c3aed);
    border: none; border-radius: 14px; color: white; font-size: 15px; font-weight: 700; cursor: pointer; transition: all 0.3s;
}
.btn-auto-allocate:hover { transform: translateY(-2px); box-shadow: 0 12px 32px rgba(99, 102, 241, 0.35); }

/* Section Title */
.section-title { margin-bottom: 20px; }
.section-title h2 { font-size: 20px; font-weight: 700; display: flex; align-items: center; gap: 10px; }
.section-title h2 i { color: var(--primary); }

/* Staff Grid */
.staff-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 20px; }
.staff-card {
    position: relative; background: var(--bg-card); border: 1px solid var(--border); border-radius: 22px;
    padding: 22px; transition: all 0.3s; overflow: hidden;
}
.staff-card::before {
    content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px;
    background: var(--card-accent, var(--primary)); opacity: 0; transition: opacity 0.3s;
}
.staff-card:hover { border-color: var(--border-light); transform: translateY(-4px); box-shadow: 0 16px 48px rgba(0,0,0,0.3); }
.staff-card:hover::before { opacity: 1; }
.card-glow {
        position: absolute; top: -50%; right: -50%; width: 200px; height: 200px;
    background: var(--card-accent, var(--primary)); filter: blur(60px); opacity: 0;
    transition: opacity 0.4s;
}
.staff-card:hover .card-glow { opacity: 0.2; }

/* Staff Header */
.staff-header { display: flex; align-items: center; gap: 14px; margin-bottom: 18px; }
.staff-avatar {
    width: 50px; height: 50px; border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 17px; font-weight: 800; color: white;
    box-shadow: 0 6px 20px rgba(0,0,0,0.3);
}
.staff-details { flex: 1; }
.staff-details h3 { font-size: 16px; font-weight: 700; margin-bottom: 2px; }
.staff-details p { font-size: 12px; color: var(--text-muted); }
.room-counter {
    display: flex; flex-direction: column; align-items: center;
    padding: 10px 14px; background: var(--bg-raised); border-radius: 12px;
    border: 1px solid var(--border);
}
.room-counter .count { font-size: 22px; font-weight: 800; line-height: 1; }
.room-counter .label { font-size: 10px; color: var(--text-muted); text-transform: uppercase; font-weight: 600; }

/* Allocated Rooms */
.allocated-rooms { margin-bottom: 18px; min-height: 60px; }
.rooms-flex { display: flex; flex-wrap: wrap; gap: 8px; }
.inline-form { display: contents; }
.room-chip {
    display: flex; align-items: center; gap: 6px; padding: 8px 12px;
    background: var(--bg-raised); border: 1px solid var(--border); border-radius: 10px;
    font-size: 13px; font-weight: 700; color: var(--text-primary); cursor: pointer; transition: all 0.2s;
}
.room-chip:hover { border-color: var(--danger); color: var(--danger); background: rgba(239,68,68,0.1); }
.room-chip i { font-size: 9px; opacity: 0.6; }
.empty-state {
    display: flex; align-items: center; gap: 8px; padding: 16px;
    background: var(--bg-raised); border-radius: 10px; border: 1px dashed var(--border);
    color: var(--text-muted); font-size: 13px;
}
.empty-state i { font-size: 16px; opacity: 0.5; }

/* Assign Button */
.btn-assign {
    width: 100%; padding: 12px;
    background: linear-gradient(135deg, var(--card-accent), #16a34a);
    border: none; border-radius: 12px; color: white;
    font-size: 14px; font-weight: 700; cursor: pointer;
    display: flex; align-items: center; justify-content: center; gap: 8px;
    transition: all 0.2s;
}
.btn-assign:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(34,197,94,0.3); }
.btn-assign i { font-size: 14px; }

/* Modal */
.modal-container {
    display: none; position: fixed; inset: 0;
    align-items: center; justify-content: center; z-index: 9999; padding: 20px;
}
.modal-backdrop {
    position: absolute; inset: 0;
    background: rgba(0,0,0,0.8); backdrop-filter: blur(8px);
    animation: fadeIn 0.2s;
}
.modal-card {
    position: relative; width: 100%; max-width: 900px; max-height: 90vh;
    background: var(--bg-card); border: 1px solid var(--border); border-radius: 24px;
    overflow: hidden; animation: scaleIn 0.3s;
}
@keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
@keyframes scaleIn { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }

.modal-header-custom {
    display: flex; justify-content: space-between; align-items: center;
    padding: 24px; border-bottom: 1px solid var(--border); background: var(--bg-raised);
}
.modal-title-area { display: flex; align-items: center; gap: 14px; }
.modal-icon {
    width: 44px; height: 44px; border-radius: 12px;
    background: linear-gradient(135deg, var(--primary), var(--primary-hover));
    display: flex; align-items: center; justify-content: center; font-size: 18px; color: white;
}
.modal-title-area h2 { font-size: 20px; font-weight: 700; margin-bottom: 2px; }
.modal-title-area p { font-size: 13px; color: var(--primary); font-weight: 600; }
.modal-close-btn {
    width: 40px; height: 40px; border-radius: 10px;
    background: var(--bg-surface); border: 1px solid var(--border));
    color: var(--text-secondary); font-size: 16px; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
}
.modal-close-btn:hover { background: var(--danger); color: white; border-color: var(--danger); }

/* Modal Body */
.modal-body-custom { padding: 24px; max-height: 60vh; overflow-y: auto; }
.room-sections { display: flex; flex-direction: column; gap: 24px; }
.room-section { }
.section-heading {
    display: flex; align-items: center; gap: 10px;
    font-size: 14px; font-weight: 700; margin-bottom: 14px;
    padding: 10px 14px; border-radius: 10px;
    background: color-mix(in srgb, var(--section-color) 15%, transparent);
    color: var(--section-color);
}
.section-heading i { font-size: 12px; }
.room-options { display: grid; grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); gap: 10px; }
.room-option { position: relative; cursor: pointer; }
.room-option input {
    position: absolute; inset: 0; opacity: 0; width: 100%; height: 100%; cursor: pointer;
}
.option-inner {
    padding: 16px; border-radius: 14px; display: flex; flex-direction: column;
    align-items: center; gap: 4px; transition: all 0.2s;
    background: linear-gradient(135deg, var(--opt-color), color-mix(in srgb, var(--opt-color) 70%, black));
}
.room-option:has(input:checked) .option-inner { transform: scale(1.03); box-shadow: 0 8px 24px rgba(0,0,0,0.3); }
.room-num { font-size: 22px; font-weight: 800; color: white; }
.room-type { font-size: 10px; color: rgba(255,255,255,0.7); }
.check-mark {
    position: absolute; top: 8px; right: 8px; font-size: 12px; color: white;
    opacity: 0; transition: opacity 0.2s;
}
.room-option:has(input:checked) .check-mark { opacity: 1; }

/* Save Button */
.btn-save-assignment {
    width: 100%; padding: 16px; margin-top: 20px;
    background: linear-gradient(135deg, var(--primary), var(--primary-hover));
    border: none; border-radius: 14px; color: white;
    font-size: 15px; font-weight: 700; cursor: pointer;
    display: flex; align-items: center; justify-content: center; gap: 10px;
    transition: all 0.2s;
}
.btn-save-assignment:hover { transform: translateY(-2px); box-shadow: 0 8px 24px var(--glow-primary); }

/* All Done */
.all-done { text-align: center; padding: 60px 20px; }
.done-icon { font-size: 56px; color: var(--primary); margin-bottom: 16px; }
.all-done h3 { font-size: 24px; margin-bottom: 8px; }
.all-done p { color: var(--text-muted); }

/* Responsive */
@media (max-width: 640px) {
    .page-header { flex-direction: column; align-items: flex-start; }
    .date-control { width: 100%; }
    .staff-grid { grid-template-columns: 1fr; }
    .header-text h1 { font-size: 24px; }
    .icon-container { width: 50px; height: 50px; font-size: 22px; }
}
</style>

<script>
function openAssignModal(staffId, staffName) {
    document.getElementById('assigned_to').value = staffId;
    document.getElementById('selectedStaffName').textContent = staffName;
    document.getElementById('assignModal').style.display = 'flex';
}

function closeAssignModal() {
    document.getElementById('assignModal').style.display = 'none';
}

document.getElementById('assignModal').addEventListener('click', function(e) {
    if (e.target === this) closeAssignModal();
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeAssignModal();
});
</script>

@endsection