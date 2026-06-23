@extends('dashboard.admin.layout')

@section('title', 'Restaurants')
@section('page-title', 'Restaurants')

@section('content')
<section class="restaurant-page">

    <div class="restaurant-top">
        <div>
            <p>MGM One / Hotel Setup / Restaurants</p>
            <h1>{{ $hotel->name }}</h1>
            <span>Create restaurants and manage tables, booking settings and capacity rules.</span>
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

    <div class="restaurant-layout">

        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-utensils"></i> Add Restaurant</h2>
            </div>

            <form method="POST" action="{{ route('admin.hotels.restaurants.store', $hotel) }}">
                @csrf

                <div class="form-grid">
                    <div class="form-group full">
                        <label class="form-label">Restaurant Name *</label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="River View Restaurant" required>
                    </div>

                    <div class="form-group full">
                        <label class="form-label">Description</label>
                        <textarea name="description" rows="4" placeholder="Optional restaurant description">{{ old('description') }}</textarea>
                    </div>

                    <div class="form-group full active-row">
                        <label>
                            <input type="checkbox" name="is_active" value="1" checked>
                            Active Restaurant
                        </label>
                    </div>
                </div>

                <button class="btn btn-primary restaurant-submit">
                    <i class="fas fa-plus"></i>
                    Create Restaurant
                </button>
            </form>
        </div>

        <div class="restaurant-grid">
            @forelse($restaurants as $restaurant)
                <div class="restaurant-card">
                    <div class="restaurant-cover">
                        <span class="status-pill {{ $restaurant->is_active ? 'active' : 'inactive' }}">
                            {{ $restaurant->is_active ? 'Active' : 'Inactive' }}
                        </span>

                        <div class="restaurant-icon">
                            <i class="fas fa-utensils"></i>
                        </div>
                    </div>

                    <div class="restaurant-body">
                        <h2>{{ $restaurant->name }}</h2>
                        <p>{{ $restaurant->description ?? 'No description added.' }}</p>

                        <div class="restaurant-stats">
                            <div>
                                <strong>{{ $restaurant->tables_count ?? 0 }}</strong>
                                <span>Tables</span>
                            </div>

                            <div>
                                <strong>{{ $restaurant->is_active ? 'Yes' : 'No' }}</strong>
                                <span>Active</span>
                            </div>
                        </div>

                        <div class="restaurant-actions">
                            <a href="{{ route('admin.restaurants.tables.index', [$hotel, $restaurant]) }}"
                               class="restaurant-btn">
                                <i class="fas fa-chair"></i>
                                Tables
                            </a>

                            <a href="{{ route('admin.restaurants.settings.index', [$hotel, $restaurant]) }}"
                               class="restaurant-btn">
                                <i class="fas fa-clock"></i>
                                Booking Settings
                            </a>
                        </div>

                        <div class="restaurant-actions single">
                            <form method="POST" action="{{ route('admin.restaurants.destroy', $restaurant) }}"
                                  onsubmit="return confirm('Delete this restaurant?')">
                                @csrf
                                @method('DELETE')

                                <button class="restaurant-btn danger">
                                    <i class="fas fa-trash"></i>
                                    Delete
                                </button>
                            </form>
                        </div>

                        <details class="edit-panel">
                            <summary>Edit Restaurant</summary>

                            <form method="POST" action="{{ route('admin.restaurants.update', $restaurant) }}">
                                @csrf
                                @method('PUT')

                                <input type="text" name="name" value="{{ $restaurant->name }}" required>

                                <textarea name="description" rows="3">{{ $restaurant->description }}</textarea>

                                <label class="edit-active">
                                    <input type="checkbox" name="is_active" value="1" {{ $restaurant->is_active ? 'checked' : '' }}>
                                    Active Restaurant
                                </label>

                                <button class="btn btn-primary" style="width:100%; margin-top:12px;">
                                    Update Restaurant
                                </button>
                            </form>
                        </details>
                    </div>
                </div>
            @empty
                <div class="empty-restaurants">
                    <i class="fas fa-utensils"></i>
                    <h2>No restaurants created yet</h2>
                    <p>Create the first restaurant for this hotel.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<style>
.restaurant-top {
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:16px;
    flex-wrap:wrap;
    margin-bottom:24px;
}

.restaurant-top p {
    color:var(--primary);
    font-weight:900;
    text-transform:uppercase;
    letter-spacing:1.5px;
    font-size:12px;
}

.restaurant-top h1 {
    font-size:34px;
    font-weight:900;
    margin-top:6px;
}

.restaurant-top span {
    color:var(--text-muted);
    display:block;
    margin-top:8px;
}

.success-message,
.error-message {
    padding:14px 18px;
    border-radius:12px;
    margin-bottom:20px;
    font-weight:800;
}

.success-message {
    background:rgba(34,197,94,.12);
    border:1px solid rgba(34,197,94,.35);
    color:#4ade80;
}

.error-message {
    background:rgba(239,68,68,.12);
    border:1px solid rgba(239,68,68,.35);
    color:#f87171;
}

.restaurant-layout {
    display:grid;
    grid-template-columns:390px 1fr;
    gap:24px;
    align-items:start;
}

.form-grid {
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:18px;
}

.form-group.full {
    grid-column:1 / -1;
}

textarea {
    width:100%;
    padding:14px 18px;
    background:var(--bg-input);
    border:2px solid var(--border);
    border-radius:10px;
    color:var(--text-main);
    font-weight:600;
    resize:vertical;
}

.active-row {
    display:flex;
    align-items:center;
}

.active-row label {
    display:flex;
    align-items:center;
    gap:10px;
    color:var(--text-muted);
    font-weight:900;
}

.restaurant-submit {
    width:100%;
    margin-top:20px;
}

.restaurant-grid {
    display:grid;
    grid-template-columns:repeat(2, minmax(0,1fr));
    gap:24px;
}

.restaurant-card {
    background:linear-gradient(180deg,#171717,#101010);
    border:2px solid var(--border);
    border-radius:22px;
    overflow:hidden;
    box-shadow:0 25px 60px rgba(0,0,0,.35);
}

.restaurant-cover {
    height:125px;
    position:relative;
    background:
        radial-gradient(circle at 20% 20%, rgba(232,45,45,.35), transparent 35%),
        linear-gradient(135deg,#3a0909,#121212 70%);
    border-bottom:1px solid var(--border);
}

.restaurant-icon {
    position:absolute;
    left:24px;
    bottom:-38px;
    width:82px;
    height:82px;
    border-radius:24px;
    background:#101010;
    border:4px solid #171717;
    color:var(--primary);
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:34px;
}

.status-pill {
    position:absolute;
    top:18px;
    right:18px;
    padding:8px 14px;
    border-radius:999px;
    font-size:12px;
    font-weight:900;
}

.status-pill.active {
    color:#4ade80;
    background:rgba(34,197,94,.15);
}

.status-pill.inactive {
    color:#f87171;
    background:rgba(239,68,68,.15);
}

.restaurant-body {
    padding:52px 22px 22px;
}

.restaurant-body h2 {
    font-size:24px;
    font-weight:900;
}

.restaurant-body p {
    color:var(--text-muted);
    margin-top:8px;
    line-height:1.5;
}

.restaurant-stats {
    margin-top:18px;
    display:grid;
    grid-template-columns:1fr 1fr;
    border:1px solid var(--border);
    border-radius:14px;
    overflow:hidden;
}

.restaurant-stats div {
    padding:14px;
    background:rgba(0,0,0,.22);
}

.restaurant-stats div:first-child {
    border-right:1px solid var(--border);
}

.restaurant-stats strong {
    display:block;
    font-size:22px;
    font-weight:900;
}

.restaurant-stats span {
    color:var(--text-muted);
    font-size:12px;
    font-weight:800;
    text-transform:uppercase;
}

.restaurant-actions {
    margin-top:20px;
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:12px;
}

.restaurant-actions.single {
    grid-template-columns:1fr;
    margin-top:12px;
}

.restaurant-btn {
    width:100%;
    padding:13px 12px;
    border-radius:12px;
    background:#1c1c1c;
    border:1px solid var(--border);
    color:white;
    display:flex;
    align-items:center;
    justify-content:center;
    gap:8px;
    font-weight:900;
}

.restaurant-btn.danger {
    background:rgba(239,68,68,.14);
    color:#f87171;
    border:1px solid rgba(239,68,68,.35);
}

.edit-panel {
    margin-top:18px;
    border-top:1px solid var(--border);
    padding-top:16px;
}

.edit-panel summary {
    cursor:pointer;
    color:var(--primary);
    font-weight:900;
    margin-bottom:12px;
}

.edit-panel input,
.edit-panel textarea {
    margin-bottom:10px;
}

.edit-active {
    display:flex;
    align-items:center;
    gap:10px;
    color:var(--text-muted);
    font-weight:800;
}

.empty-restaurants {
    grid-column:1 / -1;
    padding:60px;
    border:2px dashed var(--border);
    border-radius:22px;
    text-align:center;
    color:var(--text-muted);
}

.empty-restaurants i {
    font-size:48px;
    color:var(--primary);
    margin-bottom:14px;
}

.empty-restaurants h2 {
    color:white;
    font-weight:900;
}

@media(max-width:1200px) {
    .restaurant-layout {
        grid-template-columns:1fr;
    }
}

@media(max-width:900px) {
    .restaurant-grid {
        grid-template-columns:1fr;
    }
}

@media(max-width:640px) {
    .form-grid,
    .restaurant-actions {
        grid-template-columns:1fr;
    }
}
</style>
@endsection