@extends('dashboard.admin.layout')

@section('title', $hotel->name . ' Rooms')
@section('page-title', 'Room Inventory')

@section('content')
<section class="rooms-page">

    <div class="rooms-hero">
        <div>
            <p>MGM One / {{ $hotel->name }}</p>
            <h1>Room Inventory</h1>
            <span>Manage rooms, room categories, housekeeping and maintenance status.</span>
        </div>

        <a href="{{ route('admin.hotels.setup', $hotel) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Back to Setup
        </a>
    </div>

    @if(session('success'))
        <div class="success-message">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="error-message">
            @foreach($errors->all() as $error)
                <p>• {{ $error }}</p>
            @endforeach
        </div>
    @endif

    <div class="rooms-layout">

        <div class="card form-card">
            <div class="card-header">
                <h2><i class="fas fa-plus-circle"></i> Add Room</h2>
            </div>

            <form method="POST" action="{{ route('admin.hotels.rooms.store', $hotel) }}">
                @csrf

                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Room Number *</label>
                        <input type="text" name="room_number" value="{{ old('room_number') }}" placeholder="101" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Floor</label>
                        <input type="text" name="floor" value="{{ old('floor') }}" placeholder="1st Floor">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Room Category</label>
                        <select name="room_type_id">
                            <option value="">Select Category</option>
                            @foreach($roomTypes as $roomType)
                                <option value="{{ $roomType->id }}" {{ old('room_type_id') == $roomType->id ? 'selected' : '' }}>
                                    {{ $roomType->name }} {{ $roomType->code ? '(' . $roomType->code . ')' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Max Occupancy *</label>
                        <input type="number" name="max_occupancy" value="{{ old('max_occupancy', 2) }}" min="1" max="20" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Room Status *</label>
                        <select name="status" required>
                            <option value="available" {{ old('status', 'available') == 'available' ? 'selected' : '' }}>Available</option>
                            <option value="occupied" {{ old('status') == 'occupied' ? 'selected' : '' }}>Occupied</option>
                            <option value="dirty" {{ old('status') == 'dirty' ? 'selected' : '' }}>Dirty</option>
                            <option value="inspected" {{ old('status') == 'inspected' ? 'selected' : '' }}>Inspected</option>
                            <option value="out_of_order" {{ old('status') == 'out_of_order' ? 'selected' : '' }}>Out of Order</option>
                            <option value="out_of_service" {{ old('status') == 'out_of_service' ? 'selected' : '' }}>Out of Service</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Housekeeping *</label>
                        <select name="housekeeping_status" required>
                            <option value="clean" {{ old('housekeeping_status', 'clean') == 'clean' ? 'selected' : '' }}>Clean</option>
                            <option value="dirty" {{ old('housekeeping_status') == 'dirty' ? 'selected' : '' }}>Dirty</option>
                            <option value="in_progress" {{ old('housekeeping_status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="inspection_pending" {{ old('housekeeping_status') == 'inspection_pending' ? 'selected' : '' }}>Inspection Pending</option>
                            <option value="inspected" {{ old('housekeeping_status') == 'inspected' ? 'selected' : '' }}>Inspected</option>
                            <option value="rejected" {{ old('housekeeping_status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            <option value="dnd" {{ old('housekeeping_status') == 'dnd' ? 'selected' : '' }}>DND</option>
                            <option value="refused_service" {{ old('housekeeping_status') == 'refused_service' ? 'selected' : '' }}>Refused Service</option>
                        </select>
                    </div>

                    <div class="form-group full">
                        <label class="form-label">Maintenance Status *</label>
                        <select name="maintenance_status" required>
                            <option value="clear" {{ old('maintenance_status', 'clear') == 'clear' ? 'selected' : '' }}>Clear</option>
                            <option value="maintenance_required" {{ old('maintenance_status') == 'maintenance_required' ? 'selected' : '' }}>Maintenance Required</option>
                            <option value="out_of_order" {{ old('maintenance_status') == 'out_of_order' ? 'selected' : '' }}>Out of Order</option>
                            <option value="out_of_service" {{ old('maintenance_status') == 'out_of_service' ? 'selected' : '' }}>Out of Service</option>
                        </select>
                    </div>

                    <div class="form-group full">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" rows="3" placeholder="Optional notes">{{ old('notes') }}</textarea>
                    </div>

                    <div class="form-group full active-row">
                        <label>
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
                            Active Room
                        </label>
                    </div>
                </div>

                <button class="btn btn-primary submit-btn">
                    <i class="fas fa-save"></i>
                    Save Room
                </button>
            </form>
        </div>

        <div class="rooms-panel">
            <div class="panel-top">
                <div>
                    <h2>Rooms</h2>
                    <p>{{ $rooms->count() }} rooms in {{ $hotel->name }}</p>
                </div>

                <input type="text" id="roomSearch" placeholder="Search room...">
            </div>

            <div class="rooms-grid" id="roomsGrid">
                @forelse($rooms as $room)
                    <div class="room-card"
                         data-search="{{ strtolower($room->room_number . ' ' . ($room->roomType->name ?? '') . ' ' . $room->floor . ' ' . $room->status . ' ' . $room->housekeeping_status . ' ' . $room->maintenance_status) }}">

                        <div class="room-card-top">
                            <div>
                                <h3>Room {{ $room->room_number }}</h3>
                                <span>{{ $room->roomType->name ?? 'No Category' }}</span>
                            </div>

                            <span class="room-active {{ $room->is_active ? 'yes' : 'no' }}">
                                {{ $room->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>

                        <div class="room-meta">
                            <div>
                                <small>Floor</small>
                                <strong>{{ $room->floor ?? '-' }}</strong>
                            </div>
                            <div>
                                <small>Pax</small>
                                <strong>{{ $room->max_occupancy }}</strong>
                            </div>
                        </div>

                        <div class="status-stack">
                            <span class="status-chip status-{{ $room->status }}">
                                {{ ucwords(str_replace('_', ' ', $room->status)) }}
                            </span>

                            <span class="status-chip hk-{{ $room->housekeeping_status }}">
                                HK: {{ ucwords(str_replace('_', ' ', $room->housekeeping_status)) }}
                            </span>

                            <span class="status-chip maint-{{ $room->maintenance_status }}">
                                Maint: {{ ucwords(str_replace('_', ' ', $room->maintenance_status)) }}
                            </span>
                        </div>

                        @if($room->notes)
                            <p class="room-notes">{{ $room->notes }}</p>
                        @endif

                        <div class="room-actions">
                            <button type="button"
                                    class="room-btn"
                                    onclick="openEditModal(
                                        '{{ $room->id }}',
                                        @js($room->room_number),
                                        '{{ $room->room_type_id }}',
                                        @js($room->floor),
                                        '{{ $room->max_occupancy }}',
                                        '{{ $room->status }}',
                                        '{{ $room->housekeeping_status }}',
                                        '{{ $room->maintenance_status }}',
                                        '{{ $room->is_active ? 1 : 0 }}',
                                        @js($room->notes)
                                    )">
                                <i class="fas fa-pen"></i>
                                Edit
                            </button>

                            <button type="button"
                                    class="room-btn danger"
                                    onclick="openDeleteModal('{{ $room->id }}', @js($room->room_number))">
                                <i class="fas fa-trash"></i>
                                Delete
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <i class="fas fa-door-closed"></i>
                        <h2>No rooms found</h2>
                        <p>Add the first room for this hotel.</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>

</section>

<div class="modal-backdrop" id="editModal">
    <div class="modal-card">
        <div class="modal-header">
            <h2><i class="fas fa-pen"></i> Edit Room</h2>
            <button type="button" onclick="closeEditModal()">×</button>
        </div>

        <form method="POST" id="editForm">
            @csrf
            @method('PUT')

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Room Number *</label>
                    <input type="text" name="room_number" id="edit_room_number" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Floor</label>
                    <input type="text" name="floor" id="edit_floor">
                </div>

                <div class="form-group">
                    <label class="form-label">Room Category</label>
                    <select name="room_type_id" id="edit_room_type_id">
                        <option value="">Select Category</option>
                        @foreach($roomTypes as $roomType)
                            <option value="{{ $roomType->id }}">{{ $roomType->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Max Occupancy *</label>
                    <input type="number" name="max_occupancy" id="edit_max_occupancy" min="1" max="20" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Room Status *</label>
                    <select name="status" id="edit_status" required>
                        <option value="available">Available</option>
                        <option value="occupied">Occupied</option>
                        <option value="dirty">Dirty</option>
                        <option value="inspected">Inspected</option>
                        <option value="out_of_order">Out of Order</option>
                        <option value="out_of_service">Out of Service</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Housekeeping *</label>
                    <select name="housekeeping_status" id="edit_housekeeping_status" required>
                        <option value="clean">Clean</option>
                        <option value="dirty">Dirty</option>
                        <option value="in_progress">In Progress</option>
                        <option value="inspection_pending">Inspection Pending</option>
                        <option value="inspected">Inspected</option>
                        <option value="rejected">Rejected</option>
                        <option value="dnd">DND</option>
                        <option value="refused_service">Refused Service</option>
                    </select>
                </div>

                <div class="form-group full">
                    <label class="form-label">Maintenance Status *</label>
                    <select name="maintenance_status" id="edit_maintenance_status" required>
                        <option value="clear">Clear</option>
                        <option value="maintenance_required">Maintenance Required</option>
                        <option value="out_of_order">Out of Order</option>
                        <option value="out_of_service">Out of Service</option>
                    </select>
                </div>

                <div class="form-group full">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" id="edit_notes" rows="3"></textarea>
                </div>

                <div class="form-group full active-row">
                    <label>
                        <input type="checkbox" name="is_active" value="1" id="edit_is_active">
                        Active Room
                    </label>
                </div>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn btn-secondary" onclick="closeEditModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Update Room</button>
            </div>
        </form>
    </div>
</div>

<div class="modal-backdrop" id="deleteModal">
    <div class="modal-card small">
        <div class="modal-header">
            <h2><i class="fas fa-trash"></i> Delete Room</h2>
            <button type="button" onclick="closeDeleteModal()">×</button>
        </div>

        <p class="delete-text">
            Are you sure you want to delete room <strong id="deleteRoomNumber"></strong>?
        </p>

        <form method="POST" id="deleteForm">
            @csrf
            @method('DELETE')

            <div class="modal-actions">
                <button type="button" class="btn btn-secondary" onclick="closeDeleteModal()">Cancel</button>
                <button type="submit" class="btn btn-primary danger-btn">Delete</button>
            </div>
        </form>
    </div>
</div>

<style>
.rooms-hero {
    background: radial-gradient(circle at 20% 20%, rgba(232,45,45,.28), transparent 35%), linear-gradient(135deg, #2a0606, #101010 70%);
    border: 2px solid var(--border);
    border-radius: 24px;
    padding: 28px;
    margin-bottom: 24px;
    display: flex;
    justify-content: space-between;
    gap: 18px;
    align-items: center;
    flex-wrap: wrap;
}

.rooms-hero p {
    color: var(--primary);
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    font-size: 12px;
}

.rooms-hero h1 {
    font-size: 34px;
    font-weight: 900;
    margin-top: 8px;
}

.rooms-hero span {
    color: var(--text-muted);
    display: block;
    margin-top: 8px;
}

.success-message,
.error-message {
    padding: 14px 18px;
    border-radius: 12px;
    margin-bottom: 20px;
    font-weight: 800;
}

.success-message {
    background: rgba(34,197,94,.12);
    border: 1px solid rgba(34,197,94,.35);
    color: #4ade80;
}

.error-message {
    background: rgba(239,68,68,.12);
    border: 1px solid rgba(239,68,68,.35);
    color: #f87171;
}

.rooms-layout {
    display: grid;
    grid-template-columns: 390px 1fr;
    gap: 24px;
    align-items: start;
}

.form-card {
    position: sticky;
    top: 96px;
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 18px;
}

.form-group.full {
    grid-column: 1 / -1;
}

textarea {
    width: 100%;
    padding: 14px 18px;
    background: var(--bg-input);
    border: 2px solid var(--border);
    border-radius: 10px;
    color: var(--text-main);
    font-weight: 600;
    resize: vertical;
}

.active-row label {
    display: flex;
    align-items: center;
    gap: 10px;
    color: var(--text-muted);
    font-weight: 900;
}

.submit-btn {
    width: 100%;
    margin-top: 20px;
}

.rooms-panel {
    background: linear-gradient(180deg, #171717, #101010);
    border: 2px solid var(--border);
    border-radius: 24px;
    padding: 22px;
}

.panel-top {
    display: flex;
    justify-content: space-between;
    gap: 16px;
    align-items: center;
    flex-wrap: wrap;
    margin-bottom: 20px;
}

.panel-top h2 {
    font-size: 26px;
    font-weight: 900;
}

.panel-top p {
    color: var(--text-muted);
    margin-top: 5px;
}

#roomSearch {
    max-width: 280px;
}

.rooms-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 18px;
}

.room-card {
    background: #0d0d0d;
    border: 1px solid var(--border);
    border-radius: 18px;
    padding: 18px;
    transition: .25s ease;
}

.room-card:hover {
    border-color: var(--primary);
    transform: translateY(-4px);
    box-shadow: 0 18px 45px rgba(232,45,45,.14);
}

.room-card-top {
    display: flex;
    justify-content: space-between;
    gap: 12px;
}

.room-card h3 {
    font-size: 22px;
    font-weight: 900;
}

.room-card-top span {
    color: var(--text-muted);
    font-size: 13px;
    font-weight: 800;
}

.room-active {
    height: fit-content;
    padding: 6px 10px;
    border-radius: 999px;
    font-size: 11px !important;
    text-transform: uppercase;
}

.room-active.yes {
    color: #4ade80 !important;
    background: rgba(34,197,94,.12);
}

.room-active.no {
    color: #f87171 !important;
    background: rgba(239,68,68,.12);
}

.room-meta {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    margin: 16px 0;
}

.room-meta div {
    background: #151515;
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 12px;
}

.room-meta small {
    display: block;
    color: var(--text-muted);
    font-weight: 800;
    text-transform: uppercase;
    font-size: 10px;
}

.room-meta strong {
    display: block;
    margin-top: 4px;
}

.status-stack {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.status-chip {
    padding: 6px 10px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 900;
    text-transform: uppercase;
}

.status-available,
.hk-clean,
.maint-clear {
    background: rgba(34,197,94,.12);
    color: #4ade80;
}

.status-occupied,
.hk-in_progress {
    background: rgba(59,130,246,.12);
    color: #60a5fa;
}

.status-dirty,
.hk-dirty,
.maint-maintenance_required {
    background: rgba(245,158,11,.12);
    color: #fbbf24;
}

.status-inspected,
.hk-inspected {
    background: rgba(168,85,247,.12);
    color: #c084fc;
}

.status-out_of_order,
.status-out_of_service,
.maint-out_of_order,
.maint-out_of_service,
.hk-rejected,
.hk-dnd,
.hk-refused_service {
    background: rgba(239,68,68,.12);
    color: #f87171;
}

.hk-inspection_pending {
    background: rgba(236,72,153,.12);
    color: #f9a8d4;
}

.room-notes {
    color: var(--text-muted);
    margin-top: 14px;
    line-height: 1.5;
}

.room-actions {
    margin-top: 18px;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
}

.room-btn {
    padding: 11px;
    border-radius: 10px;
    background: #1b1b1b;
    border: 1px solid var(--border);
    color: white;
    font-weight: 900;
}

.room-btn.danger {
    background: rgba(239,68,68,.12);
    color: #f87171;
    border-color: rgba(239,68,68,.35);
}

.empty-state {
    grid-column: 1 / -1;
    padding: 60px;
    text-align: center;
    color: var(--text-muted);
}

.empty-state i {
    color: var(--primary);
    font-size: 44px;
    margin-bottom: 14px;
}

.modal-backdrop {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.78);
    backdrop-filter: blur(8px);
    z-index: 9999;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

.modal-backdrop.active {
    display: flex;
}

.modal-card {
    width: 100%;
    max-width: 720px;
    max-height: 90vh;
    overflow-y: auto;
    background: #111;
    border: 2px solid var(--border);
    border-radius: 22px;
    padding: 24px;
}

.modal-card.small {
    max-width: 460px;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    gap: 16px;
    align-items: center;
    margin-bottom: 20px;
}

.modal-header h2 {
    font-size: 24px;
    font-weight: 900;
}

.modal-header button {
    width: 34px;
    height: 34px;
    border-radius: 999px;
    background: rgba(239,68,68,.12);
    color: #f87171;
    font-size: 24px;
}

.modal-actions {
    margin-top: 22px;
    display: flex;
    justify-content: flex-end;
    gap: 12px;
}

.danger-btn {
    background: linear-gradient(135deg, #ef4444, #7f1d1d) !important;
}

.delete-text {
    color: var(--text-muted);
    line-height: 1.6;
}

.delete-text strong {
    color: white;
}

@media(max-width: 1200px) {
    .rooms-layout {
        grid-template-columns: 1fr;
    }

    .form-card {
        position: static;
    }
}

@media(max-width: 800px) {
    .rooms-grid {
        grid-template-columns: 1fr;
    }

    .form-grid {
        grid-template-columns: 1fr;
    }
}

@media(max-width: 560px) {
    .room-actions,
    .modal-actions {
        grid-template-columns: 1fr;
        flex-direction: column;
    }

    .modal-actions .btn {
        width: 100%;
    }
}
</style>

<script>
const hotelRoomBaseUrl = "{{ url('/hotels/' . $hotel->id . '/rooms') }}";

function openEditModal(id, roomNumber, roomTypeId, floor, maxOccupancy, status, housekeepingStatus, maintenanceStatus, isActive, notes) {
    document.getElementById('edit_room_number').value = roomNumber ?? '';
    document.getElementById('edit_room_type_id').value = roomTypeId ?? '';
    document.getElementById('edit_floor').value = floor ?? '';
    document.getElementById('edit_max_occupancy').value = maxOccupancy ?? 1;
    document.getElementById('edit_status').value = status ?? 'available';
    document.getElementById('edit_housekeeping_status').value = housekeepingStatus ?? 'clean';
    document.getElementById('edit_maintenance_status').value = maintenanceStatus ?? 'clear';
    document.getElementById('edit_is_active').checked = isActive == 1;
    document.getElementById('edit_notes').value = notes ?? '';

    document.getElementById('editForm').action = `${hotelRoomBaseUrl}/${id}`;
    document.getElementById('editModal').classList.add('active');
}

function closeEditModal() {
    document.getElementById('editModal').classList.remove('active');
}

function openDeleteModal(id, roomNumber) {
    document.getElementById('deleteRoomNumber').textContent = roomNumber;
    document.getElementById('deleteForm').action = `${hotelRoomBaseUrl}/${id}`;
    document.getElementById('deleteModal').classList.add('active');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.remove('active');
}

document.querySelectorAll('.modal-backdrop').forEach(modal => {
    modal.addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.remove('active');
        }
    });
});

document.getElementById('roomSearch')?.addEventListener('input', function () {
    const value = this.value.toLowerCase();

    document.querySelectorAll('.room-card').forEach(card => {
        card.style.display = card.dataset.search.includes(value) ? '' : 'none';
    });
});
</script>
@endsection