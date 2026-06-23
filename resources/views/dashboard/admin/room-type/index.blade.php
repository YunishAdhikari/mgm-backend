@extends('dashboard.admin.layout')

@section('title', $hotel->name . ' Room Categories')
@section('page-title', 'Room Categories')

@section('content')
<section class="room-types-page">

    <div class="types-hero">
        <div>
            <p>MGM One / {{ $hotel->name }}</p>
            <h1>Room Categories</h1>
            <span>Create categories such as Single, Twin, Double, Family and Suite.</span>
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

    <div class="types-layout">

        <div class="card form-card">
            <div class="card-header">
                <h2><i class="fas fa-plus-circle"></i> Add Category</h2>
            </div>

            <form method="POST" action="{{ route('admin.hotels.room-types.store', $hotel) }}">
                @csrf

                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Category Name *</label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Double Room" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Code</label>
                        <input type="text" name="code" value="{{ old('code') }}" placeholder="DBL">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Default Pax *</label>
                        <input type="number" name="default_pax" value="{{ old('default_pax', 2) }}" min="1" max="20" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Colour</label>
                        <input type="text" name="colour" value="{{ old('colour') }}" placeholder="#ef4444">
                    </div>

                    <div class="form-group full">
                        <label class="form-label">Description</label>
                        <textarea name="description" rows="3" placeholder="Optional description">{{ old('description') }}</textarea>
                    </div>

                    <div class="form-group full active-row">
                        <label>
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
                            Active Category
                        </label>
                    </div>
                </div>

                <button class="btn btn-primary submit-btn">
                    <i class="fas fa-save"></i>
                    Save Category
                </button>
            </form>
        </div>

        <div class="types-panel">
            <div class="panel-top">
                <div>
                    <h2>Categories</h2>
                    <p>{{ $roomTypes->count() }} categories in {{ $hotel->name }}</p>
                </div>

                <input type="text" id="typeSearch" placeholder="Search category...">
            </div>

            <div class="types-grid" id="typesGrid">
                @forelse($roomTypes as $roomType)
                    <div class="type-card"
                         data-search="{{ strtolower($roomType->name . ' ' . $roomType->code . ' ' . $roomType->default_pax) }}">

                        <div class="type-card-top">
                            <div class="type-icon" style="{{ $roomType->colour ? 'background: '.$roomType->colour.';' : '' }}">
                                <i class="fas fa-bed"></i>
                            </div>

                            <span class="type-active {{ $roomType->is_active ? 'yes' : 'no' }}">
                                {{ $roomType->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>

                        <h3>{{ $roomType->name }}</h3>

                        <div class="type-meta">
                            <div>
                                <small>Code</small>
                                <strong>{{ $roomType->code ?? '-' }}</strong>
                            </div>

                            <div>
                                <small>Default Pax</small>
                                <strong>{{ $roomType->default_pax }}</strong>
                            </div>

                            <div>
                                <small>Rooms</small>
                                <strong>{{ $roomType->rooms_count ?? $roomType->rooms()->count() }}</strong>
                            </div>
                        </div>

                        @if($roomType->description)
                            <p class="type-description">{{ $roomType->description }}</p>
                        @else
                            <p class="type-description muted">No description added.</p>
                        @endif

                        <div class="type-actions">
                            <button type="button"
                                    class="type-btn"
                                    onclick="openEditModal(
                                        '{{ $roomType->id }}',
                                        @js($roomType->name),
                                        @js($roomType->code),
                                        '{{ $roomType->default_pax }}',
                                        @js($roomType->colour),
                                        @js($roomType->description),
                                        '{{ $roomType->is_active ? 1 : 0 }}'
                                    )">
                                <i class="fas fa-pen"></i>
                                Edit
                            </button>

                            <button type="button"
                                    class="type-btn danger"
                                    onclick="openDeleteModal('{{ $roomType->id }}', @js($roomType->name))">
                                <i class="fas fa-trash"></i>
                                Delete
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <i class="fas fa-layer-group"></i>
                        <h2>No room categories found</h2>
                        <p>Add the first category for this hotel.</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</section>

<div class="modal-backdrop" id="editModal">
    <div class="modal-card">
        <div class="modal-header">
            <h2><i class="fas fa-pen"></i> Edit Category</h2>
            <button type="button" onclick="closeEditModal()">×</button>
        </div>

        <form method="POST" id="editForm">
            @csrf
            @method('PUT')

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Category Name *</label>
                    <input type="text" name="name" id="edit_name" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Code</label>
                    <input type="text" name="code" id="edit_code">
                </div>

                <div class="form-group">
                    <label class="form-label">Default Pax *</label>
                    <input type="number" name="default_pax" id="edit_default_pax" min="1" max="20" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Colour</label>
                    <input type="text" name="colour" id="edit_colour">
                </div>

                <div class="form-group full">
                    <label class="form-label">Description</label>
                    <textarea name="description" id="edit_description" rows="3"></textarea>
                </div>

                <div class="form-group full active-row">
                    <label>
                        <input type="checkbox" name="is_active" value="1" id="edit_is_active">
                        Active Category
                    </label>
                </div>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn btn-secondary" onclick="closeEditModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Update Category</button>
            </div>
        </form>
    </div>
</div>

<div class="modal-backdrop" id="deleteModal">
    <div class="modal-card small">
        <div class="modal-header">
            <h2><i class="fas fa-trash"></i> Delete Category</h2>
            <button type="button" onclick="closeDeleteModal()">×</button>
        </div>

        <p class="delete-text">
            Are you sure you want to delete <strong id="deleteTypeName"></strong>?
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
.types-hero {
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

.types-hero p {
    color: var(--primary);
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    font-size: 12px;
}

.types-hero h1 {
    font-size: 34px;
    font-weight: 900;
    margin-top: 8px;
}

.types-hero span {
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

.types-layout {
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

.types-panel {
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

#typeSearch {
    max-width: 280px;
}

.types-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 18px;
}

.type-card {
    background: #0d0d0d;
    border: 1px solid var(--border);
    border-radius: 18px;
    padding: 18px;
    transition: .25s ease;
}

.type-card:hover {
    border-color: var(--primary);
    transform: translateY(-4px);
    box-shadow: 0 18px 45px rgba(232,45,45,.14);
}

.type-card-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.type-icon {
    width: 58px;
    height: 58px;
    border-radius: 18px;
    background: rgba(232,45,45,.14);
    color: var(--primary);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
}

.type-card h3 {
    margin-top: 18px;
    font-size: 23px;
    font-weight: 900;
}

.type-active {
    padding: 6px 10px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 900;
    text-transform: uppercase;
}

.type-active.yes {
    color: #4ade80;
    background: rgba(34,197,94,.12);
}

.type-active.no {
    color: #f87171;
    background: rgba(239,68,68,.12);
}

.type-meta {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 10px;
    margin: 16px 0;
}

.type-meta div {
    background: #151515;
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 12px;
}

.type-meta small {
    display: block;
    color: var(--text-muted);
    font-weight: 800;
    text-transform: uppercase;
    font-size: 10px;
}

.type-meta strong {
    display: block;
    margin-top: 4px;
}

.type-description {
    color: var(--text-muted);
    line-height: 1.5;
    min-height: 42px;
}

.type-description.muted {
    color: var(--text-dim);
}

.type-actions {
    margin-top: 18px;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
}

.type-btn {
    padding: 11px;
    border-radius: 10px;
    background: #1b1b1b;
    border: 1px solid var(--border);
    color: white;
    font-weight: 900;
}

.type-btn.danger {
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
    .types-layout {
        grid-template-columns: 1fr;
    }

    .form-card {
        position: static;
    }
}

@media(max-width: 800px) {
    .types-grid {
        grid-template-columns: 1fr;
    }

    .form-grid {
        grid-template-columns: 1fr;
    }
}

@media(max-width: 560px) {
    .type-actions,
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
const hotelRoomTypeBaseUrl = "{{ url('/hotels/' . $hotel->id . '/room-types') }}";

function openEditModal(id, name, code, defaultPax, colour, description, isActive) {
    document.getElementById('edit_name').value = name ?? '';
    document.getElementById('edit_code').value = code ?? '';
    document.getElementById('edit_default_pax').value = defaultPax ?? 2;
    document.getElementById('edit_colour').value = colour ?? '';
    document.getElementById('edit_description').value = description ?? '';
    document.getElementById('edit_is_active').checked = isActive == 1;

    document.getElementById('editForm').action = `${hotelRoomTypeBaseUrl}/${id}`;
    document.getElementById('editModal').classList.add('active');
}

function closeEditModal() {
    document.getElementById('editModal').classList.remove('active');
}

function openDeleteModal(id, name) {
    document.getElementById('deleteTypeName').textContent = name;
    document.getElementById('deleteForm').action = `${hotelRoomTypeBaseUrl}/${id}`;
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

document.getElementById('typeSearch')?.addEventListener('input', function () {
    const value = this.value.toLowerCase();

    document.querySelectorAll('.type-card').forEach(card => {
        card.style.display = card.dataset.search.includes(value) ? '' : 'none';
    });
});
</script>
@endsection