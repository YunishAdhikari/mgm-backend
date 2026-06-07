@extends('dashboard.reception.layout')

@section('title', 'Daily Room Status')
@section('page_title', 'Daily Room Status')

@section('content')

<div class="status-header">
    <div class="status-header-left">
        <div class="status-icon">
            <i class="fas fa-door-open"></i>
        </div>
        <div>
            <h1>Daily Room Status</h1>
            <p>Mark departure, stay, room move, or carry forward</p>
        </div>
    </div>
    
    <form method="GET" action="{{ route('reception.room-status.index') }}" class="date-form">
        <input type="date" name="date" value="{{ $date }}">
        <button type="submit" class="btn-load">Load</button>
    </form>
</div>

@if(session('success'))
    <div class="success-alert">
        <i class="fas fa-check-circle"></i>
        {{ session('success') }}
    </div>
@endif

<!-- Legend -->
<div class="status-legend">
    <span class="legend-badge departure"><i class="fas fa-circle"></i> Departure</span>
    <span class="legend-badge stay"><i class="fas fa-circle"></i> Stay</span>
    <span class="legend-badge room-move"><i class="fas fa-circle"></i> Room Move</span>
    <span class="legend-badge carry-forward"><i class="fas fa-circle"></i> Carry Forward</span>
    <span class="legend-badge out_of_order"><i class="fas fa-circle"></i> OOO</span>
    <span class="legend-badge out_of_inventory"><i class="fas fa-circle"></i> OOI</span>
    <span class="legend-badge not-set"><i class="fas fa-circle"></i> Not Set</span>
</div>

<!-- Room Grid by Floor -->
@foreach($rooms as $floor => $floorRooms)
    <div class="floor-container">
        <div class="floor-label">
            <i class="fas fa-layer-group"></i> Floor {{ $floor ?? 'Unknown' }}
        </div>
        
        <div class="room-cards">
            @foreach($floorRooms as $room)
                @php
                    $todayStatus = $room->statusUpdates->first();
                    $status = $todayStatus->status ?? 'empty';
                    $notes = $todayStatus->notes ?? '';
                @endphp

                <button type="button"
                        class="room-tile {{ $status }}"
                        onclick="openStatusModal('{{ $room->id }}', '{{ $room->room_number }}', '{{ $status }}', {{ json_encode($notes) }})">
                    
                    <div class="room-tile-header">
                        <span class="room-no">{{ $room->room_number }}</span>
                        <span class="room-icon">
                            @if($status === 'departure')
                                <i class="fas fa-sign-out-alt"></i>
                            @elseif($status === 'stay')
                                <i class="fas fa-bed"></i>
                            @elseif($status === 'room_move')
                                <i class="fas fa-exchange-alt"></i>
                            @elseif($status === 'carry_forward')
                                <i class="fas fa-forward"></i>
                            @elseif($status === 'out_of_order')
                                <i class="fas fa-tools"></i>
                            @elseif($status === 'out_of_inventory')
                                <i class="fas fa-warehouse"></i>
                            @else
                                <i class="fas fa-question"></i>
                            @endif
                        </span>
                    </div>
                    
                    <div class="room-tile-footer">
                        <span class="room-type-name">{{ $room->roomType->name ?? 'Room' }}</span>
                        <span class="room-status-text">
                            @if($status === 'empty')
                                Not Set
                            @else
                                {{ ucfirst(str_replace('_', ' ', $status)) }}
                            @endif
                        </span>
                    </div>
                </button>
            @endforeach
        </div>
    </div>
@endforeach

<!-- Status Modal -->
<div id="statusModal" class="modal-bg">
    <div class="modal-container">
        <div class="modal-head">
            <h3 id="modalRoomTitle">Update Room</h3>
            <button type="button" class="modal-x" onclick="closeStatusModal()">&times;</button>
        </div>

        <form method="POST" action="{{ route('reception.room-status.update') }}">
            @csrf
            
            <input type="hidden" name="room_id" id="room_id">
            <input type="hidden" name="status_date" value="{{ $date }}">

            <div class="status-select">
                <label class="status-btn">
                    <input type="radio" name="status" value="departure">
                    <span class="status-btn-inner departure"><i class="fas fa-sign-out-alt"></i> Departure</span>
                </label>
                
                <label class="status-btn">
                    <input type="radio" name="status" value="stay">
                    <span class="status-btn-inner stay"><i class="fas fa-bed"></i> Stay</span>
                </label>
                
                <label class="status-btn">
                    <input type="radio" name="status" value="room_move">
                    <span class="status-btn-inner room-move"><i class="fas fa-exchange-alt"></i> Room Move</span>
                </label>
                
                <label class="status-btn">
                    <input type="radio" name="status" value="carry_forward">
                    <span class="status-btn-inner carry-forward"><i class="fas fa-forward"></i> Carry Forward</span>
                </label>
                
                <label class="status-btn">
                    <input type="radio" name="status" value="OOO">
                    <span class="status-btn-inner out_of_order"><i class="fas fa-tools"></i> OOO</span>
                </label>
                
                <label class="status-btn">
                    <input type="radio" name="status" value="OOI">
                    <span class="status-btn-inner out_of_inventory"><i class="fas fa-warehouse"></i> OOI</span>
                </label>
            </div>

            <div class="form-field">
                <label>Notes</label>
                <textarea id="notes" name="notes" rows="3" placeholder="Optional notes..."></textarea>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="closeStatusModal()">Cancel</button>
                <button type="submit" class="btn-save">Save Status</button>
            </div>
        </form>
    </div>
</div>

<style>
/* ============ RED THEME VARIABLES ============ */
:root {
    --red-primary: #ef4444;
    --red-dark: #dc2626;
    --red-light: #f87171;
    --red-subtle: rgba(239, 68, 68, 0.15);
    --red-glow: rgba(239, 68, 68, 0.3);
    
    --bg-dark: #18181b;
    --bg-card: #27272a;
    --bg-input: #27272a;
    
    --border-default: #3f3f46;
    --border-hover: #52525b;
    
    --text-primary: #fafafa;
    --text-secondary: #a1a1aa;
    --text-muted: #71717a;
    
    --status-departure: #ef4444;
    --status-stay: #f97316;
    --status-room-move: #eab308;
    --status-carry-forward: #8b5cf6;
    --status-ooo: #64748b;
    --status-ooi: #475569;
}

/* ============ HEADER ============ */
.status-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
    flex-wrap: wrap;
    gap: 16px;
}

.status-header-left {
    display: flex;
    align-items: center;
    gap: 16px;
}

.status-icon {
    width: 52px;
    height: 52px;
    background: linear-gradient(135deg, #ef4444, #dc2626);
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    color: white;
    box-shadow: 0 8px 24px rgba(239, 68, 68, 0.35);
}

.status-header-left h1 {
    font-size: 26px;
    font-weight: 700;
    margin: 0;
    color: var(--text-primary);
}

.status-header-left p {
    font-size: 14px;
    color: var(--text-secondary);
    margin: 4px 0 0;
}

/* ============ DATE FORM ============ */
.date-form {
    display: flex;
    gap: 10px;
}

.date-form input {
    padding: 12px 16px;
    background: var(--bg-input);
    border: 1px solid var(--border-default);
    border-radius: 10px;
    color: var(--text-primary);
    font-size: 14px;
}

.date-form input:focus {
    outline: none;
    border-color: var(--red-primary);
}

.date-form .btn-load {
    padding: 12px 20px;
    background: linear-gradient(135deg, #ef4444, #dc2626);
    border: none;
    border-radius: 10px;
    color: white;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}

.date-form .btn-load:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
}

/* ============ SUCCESS ALERT ============ */
.success-alert {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 14px 18px;
    background: rgba(239, 68, 68, 0.15);
    border: 1px solid var(--red-primary);
    border-radius: 10px;
    color: var(--red-light);
    margin-bottom: 20px;
}

/* ============ LEGEND ============ */
.status-legend {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    margin-bottom: 24px;
    padding: 14px;
    background: var(--bg-dark);
    border-radius: 12px;
}

.legend-badge {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 14px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
    color: var(--text-primary);
}

.legend-badge i { font-size: 10px; }

.legend-badge.departure { background: rgba(239, 68, 68, 0.2); color: #f87171; }
.legend-badge.stay { background: rgba(249, 115, 22, 0.2); color: #fb923c; }
.legend-badge.room-move { background: rgba(234, 179, 8, 0.2); color: #facc15; }
.legend-badge.carry-forward { background: rgba(139, 92, 246, 0.2); color: #a78bfa; }
.legend-badge.out_of_order { background: rgba(100, 116, 139, 0.2); color: #94a3b8; }
.legend-badge.out_of_inventory { background: rgba(71, 85, 105, 0.2); color: #64748b; }
.legend-badge.not-set { background: rgba(113, 113, 122, 0.2); color: #71717a; }

/* ============ FLOOR CONTAINER ============ */
.floor-container {
    margin-bottom: 28px;
}

.floor-label {
    background: linear-gradient(90deg, #27272a, #18181b);
    padding: 14px 20px;
    text-align: center;
    font-size: 18px;
    font-weight: 700;
    color: var(--text-primary);
    border-radius: 12px;
    margin-bottom: 14px;
    border: 1px solid var(--border-default);
}

.floor-label i {
    margin-right: 8px;
    color: var(--red-primary);
}

/* ============ ROOM CARDS ============ */
.room-cards {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 12px;
}

.room-tile {
    min-height: 110px;
    padding: 14px;
    border-radius: 14px;
    background: var(--bg-card);
    border: 1px solid var(--border-default);
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.room-tile:hover {
    transform: translateY(-3px);
    border-color: var(--red-primary);
    box-shadow: 0 8px 24px rgba(239, 68, 68, 0.25);
}

.room-tile-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}

.room-no {
    font-size: 26px;
    font-weight: 800;
    color: var(--text-primary);
}

.room-icon {
    font-size: 18px;
    color: var(--text-muted);
}

.room-tile-footer {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.room-type-name {
    font-size: 12px;
    color: var(--text-secondary);
}

.room-status-text {
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--text-muted);
}

/* ============ ROOM TILE STATUS COLORS ============ */
.room-tile.departure { 
    background: rgba(239, 68, 68, 0.15); 
    border-color: rgba(239, 68, 68, 0.3); 
}
.room-tile.departure .room-icon { color: #f87171; }

.room-tile.stay { 
    background: rgba(249, 115, 22, 0.15); 
    border-color: rgba(249, 115, 22, 0.3); 
}
.room-tile.stay .room-icon { color: #fb923c; }

.room-tile.room_move { 
    background: rgba(234, 179, 8, 0.15); 
    border-color: rgba(234, 179, 8, 0.3); 
}
.room-tile.room_move .room-icon { color: #facc15; }

.room-tile.carry_forward { 
    background: rgba(139, 92, 246, 0.15); 
    border-color: rgba(139, 92, 246, 0.3); 
}
.room-tile.carry_forward .room-icon { color: #a78bfa; }

.room-tile.out_of_order { 
    background: rgba(100, 116, 139, 0.15); 
    border-color: rgba(100, 116, 139, 0.3); 
}
.room-tile.out_of_order .room-icon { color: #94a3b8; }

.room-tile.out_of_inventory { 
    background: rgba(71, 85, 105, 0.15); 
    border-color: rgba(71, 85, 105, 0.3); 
}
.room-tile.out_of_inventory .room-icon { color: #64748b; }

.room-tile.empty { 
    background: rgba(113, 113, 122, 0.1); 
    border-color: rgba(113, 113, 122, 0.2); 
}

/* ============ MODAL ============ */
.modal-bg {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.85);
    z-index: 9999;
    align-items: center;
    justify-content: center;
    padding: 20px;
    backdrop-filter: blur(4px);
}

.modal-container {
    width: 100%;
    max-width: 460px;
    background: var(--bg-dark);
    border: 1px solid var(--border-default);
    border-radius: 20px;
    padding: 24px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5);
}

.modal-head {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.modal-head h3 {
    margin: 0;
    color: var(--text-primary);
    font-size: 20px;
}

.modal-x {
    background: none;
    border: none;
    color: var(--text-muted);
    font-size: 28px;
    cursor: pointer;
    transition: color 0.2s;
}

.modal-x:hover {
    color: var(--red-primary);
}

/* ============ STATUS SELECT ============ */
.status-select {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 10px;
    margin-bottom: 18px;
}

.status-btn input {
    display: none;
}

.status-btn-inner {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 14px;
    background: var(--bg-card);
    border: 1px solid var(--border-default);
    border-radius: 12px;
    font-size: 14px;
    font-weight: 600;
    color: var(--text-secondary);
    cursor: pointer;
    transition: all 0.2s;
}

.status-btn-inner:hover {
    border-color: var(--border-hover);
    transform: translateY(-2px);
}

.status-btn input:checked + .status-btn-inner {
    border-color: var(--red-primary);
    background: rgba(239, 68, 68, 0.15);
    color: var(--text-primary);
}

.status-btn-inner.departure i { color: #ef4444; }
.status-btn-inner.stay i { color: #f97316; }
.status-btn-inner.room-move i { color: #eab308; }
.status-btn-inner.carry-forward i { color: #8b5cf6; }
.status-btn-inner.out_of_order i { color: #64748b; }
.status-btn-inner.out_of_inventory i { color: #475569; }

/* ============ FORM FIELD ============ */
.form-field {
    margin-bottom: 18px;
}

.form-field label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    color: var(--text-secondary);
    margin-bottom: 8px;
}

.form-field textarea {
    width: 100%;
    padding: 12px 14px;
    background: var(--bg-card);
    border: 1px solid var(--border-default);
    border-radius: 12px;
    color: var(--text-primary);
    font-size: 14px;
    resize: none;
    font-family: inherit;
}

.form-field textarea:focus {
    outline: none;
    border-color: var(--red-primary);
}

/* ============ MODAL ACTIONS ============ */
.modal-actions {
    display: flex;
    gap: 12px;
}

.btn-cancel {
    flex: 1;
    padding: 14px;
    background: transparent;
    border: 1px solid var(--border-default);
    border-radius: 12px;
    color: var(--text-secondary);
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-cancel:hover {
    border-color: var(--red-primary);
    color: var(--red-primary);
}

.btn-save {
    flex: 1;
    padding: 14px;
    background: linear-gradient(135deg, #ef4444, #dc2626);
    border: none;
    border-radius: 12px;
    color: white;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-save:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(239, 68, 68, 0.4);
}

/* ============ RESPONSIVE ============ */
@media (max-width: 640px) {
    .status-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .room-cards {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .status-legend {
        justify-content: center;
    }
    
    .floor-label {
        font-size: 16px;
    }
}
</style>

<script>
function openStatusModal(roomId, roomNumber, status, notes) {
    document.getElementById('room_id').value = roomId;
    document.getElementById('modalRoomTitle').innerText = 'Room ' + roomNumber;
    document.getElementById('notes').value = notes || '';
    
    // Default selection logic
    const defaultStatus = status === 'empty' ? 'stay' : status;
    const radio = document.querySelector('input[name="status"][value="' + defaultStatus + '"]');
    if (radio) {
        radio.checked = true;
    }
    
    document.getElementById('statusModal').style.display = 'flex';
    document.body.style.overflow = 'hidden'; // Prevent background scrolling
}

function closeStatusModal() {
    document.getElementById('statusModal').style.display = 'none';
    document.body.style.overflow = 'auto'; // Restore scrolling
}

// Close modal when clicking outside
document.getElementById('statusModal').addEventListener('click', function(e) {
    if (e.target === this) closeStatusModal();
});

// Close modal on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeStatusModal();
});
</script>

@endsection