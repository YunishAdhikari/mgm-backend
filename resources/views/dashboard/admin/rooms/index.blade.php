@extends('dashboard.admin.layout')

@section('title', 'Room Inventory')
@section('page-title', 'Room Inventory')

@section('content')

<div class="pageHeader">
    <div class="headerLeft">
        <div class="iconBox doors">
            <i class="fas fa-door-open"></i>
        </div>
        <div>
            <h1>Room Inventory</h1>
            <p>Manage your hotel rooms and availability</p>
        </div>
    </div>
    
    <button type="button" class="btnNeon" onclick="openAddRoomModal()">
        <i class="fas fa-plus"></i>
        <span>Add Room</span>
    </button>
</div>

<!-- Alert Messages -->
@if(session('success'))
    <div class="alertToast success">
        <div class="icon"><i class="fas fa-check-circle"></i></div>
        <div class="text"><strong>Success!</strong> {{ session('success') }}</div>
        <button class="closeAlert"><i class="fas fa-times"></i></button>
    </div>
@endif

@if($errors->any())
    <div class="alertToast error">
        <div class="icon"><i class="fas fa-exclamation-triangle"></i></div>
        <div class="text"><strong>Error!</strong> {{ $errors->first() }}</div>
        <button class="closeAlert"><i class="fas fa-times"></i></button>
    </div>
@endif

<!-- Data Table -->
<div class="glassCard">
    <div class="tableHeader">
        <div class="searchBox">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Search rooms...">
        </div>
        <span class="recordCount">{{ $rooms->count() }} rooms</span>
    </div>
    
    <div class="tableWrapper">
        <table class="neonTable">
            <thead>
                <tr>
                    <th><i class="fas fa-hash"></i> ID</th>
                    <th><i class="fas fa-door-open"></i> Room No.</th>
                    <th><i class="fas fa-bed"></i> Room Type</th>
                    <th><i class="fas fa-layer-group"></i> Floor</th>
                    <th><i class="fas fa-users"></i> Occupancy</th>
                    <th><i class="fas fa-toggle-on"></i> Status</th>
                    <th><i class="fas fa-bolt"></i> Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rooms as $room)
                    <tr>
                        <td class="idCell">#{{ str_pad($room->id, 3, '0', STR_PAD_LEFT) }}</td>
                        <td>
                            <div class="roomNumber">
                                <span>{{ $room->room_number }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="roomTypeBadge">{{ $room->roomType->name ?? 'N/A' }}</span>
                        </td>
                        <td>{{ $room->floor ?? '-' }}</td>
                        <td>
                            <div class="occupancy">
                                <i class="fas fa-user"></i>
                                <span>{{ $room->max_occupancy }}</span>
                            </div>
                        </td>
                        <td>
                            @if($room->is_active)
                                <span class="badgeNeon active"><span class="dot"></span>Available</span>
                            @else
                                <span class="badgeNeon inactive"><span class="dot"></span>Unavailable</span>
                            @endif
                        </td>
                        <td>
                            <div class="actionButtons">
                                <button type="button" class="btnIcon edit" onclick="openEditRoomModal('{{ $room->id }}', '{{ addslashes($room->room_number) }}', '{{ $room->room_type_id }}', '{{ addslashes($room->floor ?? '') }}', '{{ $room->max_occupancy }}', '{{ $room->is_active ? 1 : 0 }}', `{{ addslashes($room->notes ?? '') }}`)">
                                    <i class="fas fa-pen"></i>
                                </button>
                                <button type="button" class="btnIcon delete" onclick="openDeleteRoomModal('{{ $room->id }}', '{{ addslashes($room->room_number) }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            <div class="emptyState">
                                <i class="fas fa-door-closed"></i>
                                <h3>No Rooms Found</h3>
                                <p>Get started by adding your first room</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Add Room Modal -->
<div id="addRoomModal" class="modalOverlay">
    <div class="modalContent">
        <div class="modalGlow"></div>
        <div class="modalHeader">
            <div class="modalTitle">
                <i class="fas fa-plus-circle"></i>
                <h2>Add New Room</h2>
            </div>
            <button type="button" class="modalClose" onclick="closeAddRoomModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('rooms.store') }}">
            @csrf
            <div class="formRow">
                <div class="inputGroup">
                    <label><i class="fas fa-door-open"></i> Room Number</label>
                    <div class="inputWrapper">
                        <input type="text" name="room_number" value="{{ old('room_number') }}" placeholder="e.g. 101" required>
                    </div>
                </div>
                <div class="inputGroup">
                    <label><i class="fas fa-layer-group"></i> Floor</label>
                    <div class="inputWrapper">
                        <input type="text" name="floor" value="{{ old('floor') }}" placeholder="e.g. 1st Floor">
                    </div>
                </div>
            </div>
            <div class="formRow">
                <div class="inputGroup">
                    <label><i class="fas fa-bed"></i> Room Type</label>
                    <div class="inputWrapper">
                        <select name="room_type_id">
                            <option value="">Select Room Type</option>
                            @foreach($roomTypes as $roomType)
                                <option value="{{ $roomType->id }}" {{ old('room_type_id') == $roomType->id ? 'selected' : '' }}>
                                    {{ $roomType->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="inputGroup">
                    <label><i class="fas fa-users"></i> Max Occupancy</label>
                    <div class="inputWrapper">
                        <input type="number" name="max_occupancy" value="{{ old('max_occupancy', 2) }}" min="1" required>
                    </div>
                </div>
            </div>
            <div class="inputGroup fullWidth">
                <label><i class="fas fa-sticky-note"></i> Notes</label>
                <div class="inputWrapper">
                    <textarea name="notes" rows="3" placeholder="Optional notes...">{{ old('notes') }}</textarea>
                </div>
            </div>
            <div class="toggleGroup">
                <div class="toggleInfo">
                    <i class="fas fa-power-off"></i>
                    <div>
                        <span class="toggleLabel">Active Status</span>
                        <span class="toggleDesc">Room is available for booking</span>
                    </div>
                </div>
                <label class="toggleSwitch">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
                    <span class="toggleSlider"></span>
                </label>
            </div>
            <div class="modalFooter">
                <button type="button" class="btnGhost" onclick="closeAddRoomModal()">Cancel</button>
                <button type="submit" class="btnNeon"><i class="fas fa-save"></i> <span>Save Room</span></button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Room Modal -->
<div id="editRoomModal" class="modalOverlay">
    <div class="modalContent">
        <div class="modalGlow"></div>
        <div class="modalHeader">
            <div class="modalTitle">
                <i class="fas fa-edit"></i>
                <h2>Edit Room</h2>
            </div>
            <button type="button" class="modalClose" onclick="closeEditRoomModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form method="POST" id="editRoomForm">
            @csrf
            @method('PUT')
            <div class="formRow">
                <div class="inputGroup">
                    <label><i class="fas fa-door-open"></i> Room Number</label>
                    <div class="inputWrapper">
                        <input type="text" name="room_number" id="edit_room_number" required>
                    </div>
                </div>
                <div class="inputGroup">
                    <label><i class="fas fa-layer-group"></i> Floor</label>
                    <div class="inputWrapper">
                        <input type="text" name="floor" id="edit_floor">
                    </div>
                </div>
            </div>
            <div class="formRow">
                <div class="inputGroup">
                    <label><i class="fas fa-bed"></i> Room Type</label>
                    <div class="inputWrapper">
                        <select name="room_type_id" id="edit_room_type_id">
                            <option value="">Select Room Type</option>
                            @foreach($roomTypes as $roomType)
                                <option value="{{ $roomType->id }}">{{ $roomType->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="inputGroup">
                    <label><i class="fas fa-users"></i> Max Occupancy</label>
                    <div class="inputWrapper">
                        <input type="number" name="max_occupancy" id="edit_max_occupancy" min="1" required>
                    </div>
                </div>
            </div>
            <div class="inputGroup fullWidth">
                <label><i class="fas fa-sticky-note"></i> Notes</label>
                <div class="inputWrapper">
                    <textarea name="notes" id="edit_notes" rows="3"></textarea>
                </div>
            </div>
            <div class="toggleGroup">
                <div class="toggleInfo">
                    <i class="fas fa-power-off"></i>
                    <div>
                        <span class="toggleLabel">Active Status</span>
                        <span class="toggleDesc">Room is available for booking</span>
                    </div>
                </div>
                <label class="toggleSwitch">
                    <input type="checkbox" name="is_active" value="1" id="edit_is_active">
                    <span class="toggleSlider"></span>
                </label>
            </div>
            <div class="modalFooter">
                <button type="button" class="btnGhost" onclick="closeEditRoomModal()">Cancel</button>
                <button type="submit" class="btnNeon"><i class="fas fa-save"></i> <span>Update Room</span></button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Room Modal -->
<div id="deleteRoomModal" class="modalOverlay">
    <div class="modalContent">
        <div class="modalGlow danger"></div>
        <div class="modalHeader">
            <div class="modalTitle">
                <i class="fas fa-trash" style="color: var(--danger);"></i>
                <h2>Delete Room</h2>
            </div>
            <button type="button" class="modalClose" onclick="closeDeleteRoomModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <p class="deleteConfirmText">
            Are you sure you want to delete room <strong id="deleteRoomNumber"></strong>?<br>
            <span class="warningText">This action cannot be undone.</span>
        </p>
        <form method="POST" id="deleteRoomForm">
            @csrf
            @method('DELETE')
            <div class="modalFooter">
                <button type="button" class="btnGhost" onclick="closeDeleteRoomModal()">Cancel</button>
                <button type="submit" class="btnNeon danger"><i class="fas fa-trash"></i> <span>Yes, Delete</span></button>
            </div>
        </form>
    </div>
</div>

<style>
/* ===== CSS Variables ===== */
:root {
    --bg-dark: #0a0a0f;
    --bg-card: #12121a;
    --bg-elevated: #1a1a25;
    --border-subtle: rgba(255,255,255,0.06);
    --border-light: rgba(255,255,255,0.1);
    --primary: #6366f1;
    --primary-glow: rgba(99, 102, 241, 0.4);
    --accent: #22d3ee;
    --accent-glow: rgba(34, 211, 238, 0.3);
    --success: #10b981;
    --danger: #ef4444;
    --warning: #f59e0b;
    --text-main: #f1f5f9;
    --text-muted: #64748b;
    --text-dim: #475569;
    --font-main: 'Inter', -apple-system, sans-serif;
    --radius: 12px;
    --radius-lg: 20px;
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

* { box-sizing: border-box; }

body {
    background: var(--bg-dark);
    color: var(--text-main);
    font-family: var(--font-main);
}

/* ===== Page Header ===== */
.pageHeader {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 28px;
    animation: fadeSlideDown 0.5s ease;
}

.headerLeft { display: flex; align-items: center; gap: 16px; }

.iconBox {
    width: 56px;
    height: 56px;
    background: linear-gradient(135deg, var(--primary), #8b5cf6);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: white;
    box-shadow: 0 8px 32px var(--primary-glow);
}

.iconBox.doors {
    background: linear-gradient(135deg, #10b981, #34d399);
    box-shadow: 0 8px 32px rgba(16, 185, 129, 0.4);
}

.headerLeft h1 {
    font-size: 28px;
    font-weight: 700;
    color: var(--text-main);
    margin: 0;
    letter-spacing: -0.5px;
}

.headerLeft p {
    font-size: 14px;
    color: var(--text-muted);
    margin: 4px 0 0;
}

/* ===== Buttons ===== */
.btnNeon {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    background: linear-gradient(135deg, var(--primary), #818cf8);
    border: none;
    border-radius: 12px;
    color: white;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
    box-shadow: 0 4px 20px var(--primary-glow);
}

.btnNeon:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 32px var(--primary-glow);
}

.btnNeon.danger {
    background: linear-gradient(135deg, var(--danger), #f87171);
    box-shadow: 0 4px 20px rgba(239, 68, 68, 0.4);
}

.btnNeon.danger:hover {
    box-shadow: 0 8px 32px rgba(239, 68, 68, 0.5);
}

.btnGhost {
    padding: 12px 24px;
    background: transparent;
    border: 1px solid var(--border-light);
    border-radius: 12px;
    color: var(--text-muted);
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
}

.btnGhost:hover {
    background: var(--bg-elevated);
    color: var(--text-main);
}

/* ===== Alerts ===== */
.alertToast {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px 20px;
    background: var(--bg-elevated);
    border: 1px solid var(--border-subtle);
    border-radius: 14px;
    margin-bottom: 20px;
    animation: slideIn 0.4s ease;
}

.alertToast.success { border-left: 3px solid var(--success); }
.alertToast.success .icon { color: var(--success); }
.alertToast.error { border-left: 3px solid var(--danger); }
.alertToast .icon { color: var(--danger); }

.alertToast .text {
    flex: 1;
    color: var(--text-main);
    font-size: 14px;
}

.closeAlert {
    background: none;
    border: none;
    color: var(--text-muted);
    cursor: pointer;
    padding: 4px;
    transition: var(--transition);
}

.closeAlert:hover { color: var(--text-main); }

/* ===== Glass Card ===== */
.glassCard {
    background: var(--bg-card);
    border: 1px solid var(--border-subtle);
    border-radius: var(--radius-lg);
    overflow: hidden;
    animation: fadeSlideUp 0.6s ease 0.1s both;
}

.tableHeader {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 24px;
    border-bottom: 1px solid var(--border-subtle);
}

.searchBox {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 16px;
    background: var(--bg-elevated);
    border: 1px solid var(--border-subtle);
    border-radius: 10px;
    transition: var(--transition);
}

.searchBox:focus-within {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px var(--primary-glow);
}

.searchBox i { color: var(--text-muted); }

.searchBox input {
    background: none;
    border: none;
    color: var(--text-main);
    font-size: 14px;
    width: 200px;
    outline: none;
}

.recordCount {
    font-size: 13px;
    color: var(--text-muted);
    background: var(--bg-elevated);
    padding: 6px 12px;
    border-radius: 20px;
}

/* ===== Neon Table ===== */
.neonTable {
    width: 100%;
    border-collapse: collapse;
}

.neonTable thead { background: var(--bg-elevated); }

.neonTable th {
    padding: 16px 24px;
    text-align: left;
    font-size: 12px;
    font-weight: 600;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.neonTable th i { margin-right: 8px; opacity: 0.6; }

.neonTable td {
    padding: 16px 24px;
    border-bottom: 1px solid var(--border-subtle);
    color: var(--text-main);
    font-size: 14px;
}

.neonTable tbody tr { transition: var(--transition); }
.neonTable tbody tr:hover { background: rgba(99, 102, 241, 0.04); }
.neonTable tbody tr:last-child td { border-bottom: none; }

.idCell {
    font-family: 'JetBrains Mono', monospace;
    color: var(--text-muted);
    font-size: 13px;
}

.roomNumber {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 48px;
    padding: 6px 12px;
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.15), rgba(52, 211, 153, 0.15));
    border: 1px solid rgba(16, 185, 129, 0.3);
    border-radius: 8px;
    font-weight: 700;
    font-size: 14px;
    color: #34d399;
}

.roomTypeBadge {
    display: inline-block;
    padding: 4px 10px;
    background: var(--bg-elevated);
    border: 1px solid var(--border-subtle);
    border-radius: 6px;
    font-size: 13px;
    color: var(--text-main);
}

.occupancy {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    color: var(--text-muted);
}

.occupancy i { color: var(--accent); }

/* ===== Badges ===== */
.badgeNeon {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.badgeNeon .dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    animation: pulse 2s infinite;
}

.badgeNeon.active {
    background: rgba(16, 185, 129, 0.15);
    color: var(--success);
}

.badgeNeon.active .dot {
    background: var(--success);
    box-shadow: 0 0 8px var(--success);
}

.badgeNeon.inactive {
    background: rgba(239, 68, 68, 0.15);
    color: var(--danger);
}

.badgeNeon.inactive .dot { background: var(--danger); }

/* ===== Action Buttons ===== */
.actionButtons {
    display: flex;
    gap: 8px;
}

.btnIcon {
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    transition: var(--transition);
    font-size: 14px;
}

.btnIcon.edit {
    background: rgba(245, 158, 11, 0.15);
    color: #f59e0b;
}

.btnIcon.edit:hover {
    background: rgba(245, 158, 11, 0.25);
    transform: scale(1.1);
}

.btnIcon.delete {
    background: rgba(239, 68, 68, 0.15);
    color: var(--danger);
}

.btnIcon.delete:hover {
    background: rgba(239, 68, 68, 0.25);
    transform: scale(1.1);
}

/* ===== Empty State ===== */
.emptyState {
    padding: 60px 20px;
    text-align: center;
}

.emptyState i {
    font-size: 48px;
    color: var(--text-dim);
    margin-bottom: 16px;
}

.emptyState h3 {
    font-size: 18px;
    color: var(--text-main);
    margin: 0 0 8px;
}

.emptyState p {
    font-size: 14px;
    color: var(--text-muted);
    margin: 0;
}

/* ===== Modal ===== */
.modalOverlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.8);
    backdrop-filter: blur(8px);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    padding: 20px;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.modalOverlay.active { display: flex; opacity: 1; }

.modalContent {
    width: 100%;
    max-width: 560px;
    background: var(--bg-card);
    border: 1px solid var(--border-subtle);
    border-radius: 24px;
    padding: 32px;
    position: relative;
    overflow: hidden;
    transform: scale(0.9) translateY(20px);
    transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    max-height: 90vh;
    overflow-y: auto;
}

.modalOverlay.active .modalContent { transform: scale(1) translateY(0); }

.modalGlow {
    position: absolute;
    top: -100px;
    left: 50%;
    transform: translateX(-50%);
    width: 200px;
    height: 200px;
    background: var(--primary);
    filter: blur(100px);
    opacity: 0.3;
    pointer-events: none;
}

.modalGlow.danger { background: var(--danger); }

.modalHeader {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
    position: relative;
}

.modalTitle {
    display: flex;
    align-items: center;
    gap: 12px;
}

.modalTitle i {
    font-size: 24px;
    color: var(--primary);
}

.modalTitle h2 {
    font-size: 20px;
    font-weight: 600;
    color: var(--text-main);
    margin: 0;
}

.modalClose {
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--bg-elevated);
    border: 1px solid var(--border-subtle);
    border-radius: 10px;
    color: var(--text-muted);
    cursor: pointer;
    transition: var(--transition);
}

.modalClose:hover {
    background: var(--danger);
    border-color: var(--danger);
    color: white;
}

/* ===== Form ===== */
.formRow {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    margin-bottom: 16px;
}

.inputGroup {
    margin-bottom: 16px;
}

.inputGroup.fullWidth { grid-column: span 2; }

.inputGroup label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    font-weight: 600;
    color: var(--text-muted);
    margin-bottom: 8px;
}

.inputWrapper { position: relative; }

.inputWrapper input,
.inputWrapper select,
.inputWrapper textarea {
    width: 100%;
    padding: 12px 16px;
    background: var(--bg-elevated);
    border: 1px solid var(--border-subtle);
    border-radius: 12px;
    color: var(--text-main);
    font-size: 14px;
    outline: none;
    transition: var(--transition);
}

.inputWrapper input:focus,
.inputWrapper select:focus,
.inputWrapper textarea:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px var(--primary-glow);
}

.inputWrapper input::placeholder,
.inputWrapper textarea::placeholder { color: var(--text-dim); }

.inputWrapper select {
    cursor: pointer;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%2364748b' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 16px center;
}

/* ===== Toggle ===== */
.toggleGroup {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px;
    background: var(--bg-elevated);
    border: 1px solid var(--border-subtle);
    border-radius: 12px;
    margin-bottom: 24px;
}

.toggleInfo {
    display: flex;
    align-items: center;
    gap: 12px;
}

.toggleInfo i {
    font-size: 20px;
    color: var(--primary);
}

.toggleLabel {
    display: block;
    font-size: 14px;
    font-weight: 600;
    color: var(--text-main);
}

.toggleDesc {
    display: block;
    font-size: 12px;
    color: var(--text-muted);
    margin-top: 2px;
}

.toggleSwitch {
    position: relative;
    width: 52px;
    height: 28px;
    cursor: pointer;
}

.toggleSwitch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.toggleSlider {
    position: absolute;
    inset: 0;
    background: var(--bg-dark);
    border: 1px solid var(--border-subtle);
    border-radius: 28px;
    transition: var(--transition);
}

.toggleSlider::before {
    content: '';
    position: absolute;
    width: 20px;
    height: 20px;
    left: 3px;
    top: 3px;
    background: var(--text-muted);
    border-radius: 50%;
    transition: var(--transition);
}

.toggleSwitch input:checked + .toggleSlider {
    background: var(--primary);
    border-color: var(--primary);
}

.toggleSwitch input:checked + .toggleSlider::before {
    transform: translateX(24px);
    background: white;
}

/* ===== Delete Confirm ===== */
.deleteConfirmText {
    font-size: 14px;
    color: var(--text-muted);
    margin-bottom: 24px;
    line-height: 1.6;
}

.deleteConfirmText strong {
    color: var(--text-main);
    font-weight: 600;
}

.warningText {
    display: block;
    margin-top: 8px;
    font-size: 12px;
    color: var(--danger);
}

/* ===== Modal Footer ===== */
.modalFooter {
    display: flex;
    gap: 12px;
    justify-content: flex-end;
}

.modalFooter .btnNeon { flex: 1; justify-content: center; }

/* ===== Animations ===== */
@keyframes fadeSlideDown {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes fadeSlideUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes slideIn {
    from { opacity: 0; transform: translateX(-20px); }
    to { opacity: 1; transform: translateX(0); }
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

/* ===== Responsive ===== */
@media (max-width: 768px) {
    .formRow { grid-template-columns: 1fr; }
    .inputGroup.fullWidth { grid-column: span 1; }
    .neonTable { display: block; overflow-x: auto; }
}
</style>

<script>
function openAddRoomModal() {
    const modal = document.getElementById('addRoomModal');
    const input = modal.querySelector('input[name="room_number"]');
    modal.style.display = 'flex';
    setTimeout(() => modal.classList.add('active'), 10);
    setTimeout(() => input.focus(), 400);
}

function closeAddRoomModal() {
    const modal = document.getElementById('addRoomModal');
    modal.classList.remove('active');
    setTimeout(() => modal.style.display = 'none', 300);
}

function openEditRoomModal(id, roomNumber, roomTypeId, floor, maxOccupancy, isActive, notes) {
    const modal = document.getElementById('editRoomModal');
    document.getElementById('edit_room_number').value = roomNumber;
    document.getElementById('edit_room_type_id').value = roomTypeId;
    document.getElementById('edit_floor').value = floor;
    document.getElementById('edit_max_occupancy').value = maxOccupancy;
    document.getElementById('edit_notes').value = notes || '';
    document.getElementById('edit_is_active').checked = isActive == 1;
    document.getElementById('editRoomForm').action = `/rooms/${id}/update`;
    modal.style.display = 'flex';
    setTimeout(() => modal.classList.add('active'), 10);
}

function closeEditRoomModal() {
    const modal = document.getElementById('editRoomModal');
    modal.classList.remove('active');
    setTimeout(() => modal.style.display = 'none', 300);
}

function openDeleteRoomModal(id, roomNumber) {
    const modal = document.getElementById('deleteRoomModal');
    document.getElementById('deleteRoomNumber').textContent = roomNumber;
    document.getElementById('deleteRoomForm').action = `/admin/rooms/${id}/delete`;
    modal.style.display = 'flex';
    setTimeout(() => modal.classList.add('active'), 10);
}

function closeDeleteRoomModal() {
    const modal = document.getElementById('deleteRoomModal');
    modal.classList.remove('active');
    setTimeout(() => modal.style.display = 'none', 300);
}

// Close modal when clicking outside
document.querySelectorAll('.modalOverlay').forEach(modal => {
    modal.addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.remove('active');
            setTimeout(() => this.style.display = 'none', 300);
        }
    });
});

// Close alert toasts
document.querySelectorAll('.closeAlert').forEach(btn => {
    btn.addEventListener('click', function() {
        this.parentElement.style.display = 'none';
    });
});

// Auto-hide alerts after 5 seconds
setTimeout(() => {
    document.querySelectorAll('.alertToast').forEach(alert => {
        alert.style.opacity = '0';
        alert.style.transform = 'translateX(20px)';
        setTimeout(() => alert.style.display = 'none', 300);
    });
}, 5000);

// Re-open modal if validation errors exist
@if($errors->any())
    openAddRoomModal();
@endif
</script>

@endsection