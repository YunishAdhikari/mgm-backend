@extends('dashboard.admin.layout')

@section('title', 'Restaurant Tables')
@section('page-title', 'Restaurant Tables')

@section('content')
<section class="rt-page">

    <div class="rt-hero">
        <div>
            <p>MGM One / {{ $hotel->name }}</p>
            <h1>{{ $restaurant->name }}</h1>
            <span>Manage restaurant tables, capacity, shapes and floor positions.</span>
        </div>

        <div class="rt-hero-actions">
            <a href="{{ route('admin.hotels.restaurants.index', $hotel) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                Back
            </a>

            <a href="{{ route('admin.restaurants.tables.floor-plan', [$hotel, $restaurant]) }}" class="btn btn-primary">
                <i class="fas fa-map"></i>
                Floor Plan
            </a>
        </div>
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

    <div class="rt-stats">
        <div class="rt-stat">
            <i class="fas fa-chair"></i>
            <div>
                <strong>{{ $tables->count() }}</strong>
                <span>Total Tables</span>
            </div>
        </div>

        <div class="rt-stat">
            <i class="fas fa-users"></i>
            <div>
                <strong>{{ $tables->sum('capacity') }}</strong>
                <span>Total Capacity</span>
            </div>
        </div>

        <div class="rt-stat">
            <i class="fas fa-circle-check"></i>
            <div>
                <strong>{{ $tables->where('is_active', true)->count() }}</strong>
                <span>Active Tables</span>
            </div>
        </div>
    </div>

    <div class="rt-layout">

        <div class="card rt-form-card">
            <div class="card-header">
                <h2>
                    <i class="fas fa-plus-circle"></i>
                    Add Table
                </h2>
            </div>

            <form method="POST" action="{{ route('admin.restaurants.tables.store', [$hotel, $restaurant]) }}">
                @csrf

                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Table Name *</label>
                        <input type="text" name="table_name" placeholder="T1" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Capacity *</label>
                        <input type="number" name="capacity" min="1" placeholder="4" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Shape *</label>
                        <select name="table_shape" required>
                            <option value="square">Square</option>
                            <option value="round">Round</option>
                            <option value="horizontal">Horizontal</option>
                            <option value="vertical">Vertical</option>
                            <option value="banquet">Banquet</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Status *</label>
                        <select name="status" required>
                            <option value="available">Available</option>
                            <option value="reserved">Reserved</option>
                            <option value="occupied">Occupied</option>
                            <option value="out_of_service">Out of Service</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Position X *</label>
                        <input type="number" name="position_x" min="0" value="0" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Position Y *</label>
                        <input type="number" name="position_y" min="0" value="0" required>
                    </div>

                    <div class="form-group full">
                        <label class="active-check">
                            <input type="checkbox" name="is_active" value="1" checked>
                            Active Table
                        </label>
                    </div>
                </div>

                <button class="btn btn-primary rt-submit">
                    <i class="fas fa-plus"></i>
                    Add Table
                </button>
            </form>
        </div>

        <div class="rt-grid">
            @forelse($tables as $table)
                <div class="rt-card">

                    <div class="rt-card-top">
                        <span class="table-status {{ $table->status }}">
                            {{ ucwords(str_replace('_', ' ', $table->status)) }}
                        </span>

                        <div class="table-icon shape-{{ $table->table_shape }}">
                            <i class="fas fa-chair"></i>
                        </div>
                    </div>

                    <div class="rt-card-body">
                        <h2>{{ $table->table_name }}</h2>

                        <div class="rt-info-grid">
                            <div>
                                <strong>{{ $table->capacity }}</strong>
                                <span>Guests</span>
                            </div>

                            <div>
                                <strong>{{ ucfirst($table->table_shape) }}</strong>
                                <span>Shape</span>
                            </div>

                            <div>
                                <strong>{{ $table->position_x }}, {{ $table->position_y }}</strong>
                                <span>Position</span>
                            </div>

                            <div>
                                <strong>{{ $table->is_active ? 'Yes' : 'No' }}</strong>
                                <span>Active</span>
                            </div>
                        </div>

                        <details class="rt-edit-panel">
                            <summary>
                                <i class="fas fa-pen"></i>
                                Edit Table
                            </summary>

                            <form method="POST" action="{{ route('admin.restaurants.tables.update', $table) }}">
                                @csrf
                                @method('PUT')

                                <input type="text" name="table_name" value="{{ $table->table_name }}" required>
                                <input type="number" name="capacity" value="{{ $table->capacity }}" min="1" required>

                                <select name="table_shape" required>
                                    @foreach(['square', 'round', 'horizontal', 'vertical', 'banquet'] as $shape)
                                        <option value="{{ $shape }}" {{ $table->table_shape === $shape ? 'selected' : '' }}>
                                            {{ ucfirst($shape) }}
                                        </option>
                                    @endforeach
                                </select>

                                <select name="status" required>
                                    @foreach(['available', 'reserved', 'occupied', 'out_of_service'] as $status)
                                        <option value="{{ $status }}" {{ $table->status === $status ? 'selected' : '' }}>
                                            {{ ucwords(str_replace('_', ' ', $status)) }}
                                        </option>
                                    @endforeach
                                </select>

                                <div class="mini-grid">
                                    <input type="number" name="position_x" value="{{ $table->position_x }}" min="0" required>
                                    <input type="number" name="position_y" value="{{ $table->position_y }}" min="0" required>
                                </div>

                                <label class="active-check">
                                    <input type="checkbox" name="is_active" value="1" {{ $table->is_active ? 'checked' : '' }}>
                                    Active Table
                                </label>

                                <button class="btn btn-primary" style="width:100%; margin-top:12px;">
                                    Update Table
                                </button>
                            </form>
                        </details>

                        <form method="POST"
                              action="{{ route('admin.restaurants.tables.destroy', $table) }}"
                              onsubmit="return confirm('Delete this table?')">
                            @csrf
                            @method('DELETE')

                            <button class="rt-delete">
                                <i class="fas fa-trash"></i>
                                Delete Table
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="rt-empty">
                    <i class="fas fa-chair"></i>
                    <h2>No tables yet</h2>
                    <p>Add the first table for this restaurant.</p>
                </div>
            @endforelse
        </div>
    </div>

</section>

<style>
.rt-hero {
    background:
        radial-gradient(circle at 20% 20%, rgba(232,45,45,.28), transparent 35%),
        linear-gradient(135deg, #2a0606, #101010 70%);
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

.rt-hero p {
    color: var(--primary);
    font-size: 12px;
    font-weight: 900;
    letter-spacing: 1.5px;
    text-transform: uppercase;
}

.rt-hero h1 {
    font-size: 36px;
    font-weight: 900;
    margin-top: 8px;
}

.rt-hero span {
    color: var(--text-muted);
    display: block;
    margin-top: 8px;
}

.rt-hero-actions {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
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
    color: #4ade80;
    border: 1px solid rgba(34,197,94,.35);
}

.error-message {
    background: rgba(239,68,68,.12);
    color: #f87171;
    border: 1px solid rgba(239,68,68,.35);
}

.rt-stats {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 18px;
    margin-bottom: 24px;
}

.rt-stat {
    background: var(--bg-card);
    border: 2px solid var(--border);
    border-radius: 18px;
    padding: 18px;
    display: flex;
    gap: 14px;
    align-items: center;
}

.rt-stat i {
    width: 52px;
    height: 52px;
    border-radius: 16px;
    background: rgba(232,45,45,.12);
    color: var(--primary);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
}

.rt-stat strong {
    display: block;
    font-size: 26px;
    font-weight: 900;
}

.rt-stat span {
    color: var(--text-muted);
    font-size: 12px;
    font-weight: 800;
    text-transform: uppercase;
}

.rt-layout {
    display: grid;
    grid-template-columns: 370px 1fr;
    gap: 24px;
    align-items: start;
}

.rt-form-card {
    position: sticky;
    top: 100px;
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 18px;
}

.form-group.full {
    grid-column: 1 / -1;
}

.active-check {
    display: flex;
    align-items: center;
    gap: 10px;
    color: var(--text-muted);
    font-weight: 900;
}

.rt-submit {
    width: 100%;
    margin-top: 20px;
}

.rt-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0,1fr));
    gap: 22px;
}

.rt-card {
    background: linear-gradient(180deg,#171717,#101010);
    border: 2px solid var(--border);
    border-radius: 22px;
    overflow: hidden;
    box-shadow: 0 25px 60px rgba(0,0,0,.35);
}

.rt-card-top {
    height: 115px;
    position: relative;
    background:
        radial-gradient(circle at 20% 20%, rgba(232,45,45,.35), transparent 35%),
        linear-gradient(135deg,#3a0909,#121212 70%);
}

.table-icon {
    position: absolute;
    left: 22px;
    bottom: -36px;
    width: 78px;
    height: 78px;
    border-radius: 22px;
    background: #101010;
    border: 4px solid #171717;
    color: var(--primary);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 30px;
}

.table-status {
    position: absolute;
    top: 16px;
    right: 16px;
    padding: 8px 12px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 900;
}

.table-status.available {
    background: rgba(34,197,94,.15);
    color: #4ade80;
}

.table-status.reserved {
    background: rgba(251,191,36,.15);
    color: #fbbf24;
}

.table-status.occupied {
    background: rgba(239,68,68,.15);
    color: #f87171;
}

.table-status.out_of_service {
    background: rgba(113,113,122,.18);
    color: #a1a1aa;
}

.rt-card-body {
    padding: 50px 22px 22px;
}

.rt-card-body h2 {
    font-size: 25px;
    font-weight: 900;
}

.rt-info-grid {
    margin-top: 18px;
    display: grid;
    grid-template-columns: 1fr 1fr;
    border: 1px solid var(--border);
    border-radius: 14px;
    overflow: hidden;
}

.rt-info-grid div {
    padding: 14px;
    background: rgba(0,0,0,.22);
    border-bottom: 1px solid var(--border);
}

.rt-info-grid div:nth-child(odd) {
    border-right: 1px solid var(--border);
}

.rt-info-grid div:nth-last-child(-n+2) {
    border-bottom: none;
}

.rt-info-grid strong {
    display: block;
    font-size: 18px;
    font-weight: 900;
}

.rt-info-grid span {
    color: var(--text-muted);
    font-size: 11px;
    font-weight: 800;
    text-transform: uppercase;
}

.rt-edit-panel {
    margin-top: 18px;
    border-top: 1px solid var(--border);
    padding-top: 16px;
}

.rt-edit-panel summary {
    cursor: pointer;
    color: var(--primary);
    font-weight: 900;
    margin-bottom: 12px;
}

.rt-edit-panel input,
.rt-edit-panel select {
    margin-bottom: 10px;
}

.mini-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
}

.rt-delete {
    margin-top: 14px;
    width: 100%;
    padding: 13px 12px;
    border-radius: 12px;
    background: rgba(239,68,68,.14);
    color: #f87171;
    border: 1px solid rgba(239,68,68,.35);
    font-weight: 900;
    text-transform: uppercase;
}

.rt-empty {
    grid-column: 1 / -1;
    padding: 60px;
    text-align: center;
    border: 2px dashed var(--border);
    border-radius: 22px;
    color: var(--text-muted);
}

.rt-empty i {
    font-size: 48px;
    color: var(--primary);
    margin-bottom: 14px;
}

.rt-empty h2 {
    color: white;
    font-weight: 900;
}

@media(max-width:1200px) {
    .rt-layout {
        grid-template-columns: 1fr;
    }

    .rt-form-card {
        position: static;
    }
}

@media(max-width:900px) {
    .rt-grid,
    .rt-stats {
        grid-template-columns: 1fr;
    }
}

@media(max-width:640px) {
    .form-grid,
    .rt-info-grid,
    .mini-grid {
        grid-template-columns: 1fr;
    }

    .rt-info-grid div {
        border-right: none !important;
    }

    .rt-hero-actions,
    .rt-hero-actions .btn {
        width: 100%;
    }
}
</style>
@endsection