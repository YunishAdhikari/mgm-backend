@extends('dashboard.admin.layout')

@section('title', $hotel->name . ' Setup')
@section('page-title', 'Hotel Setup')

@section('content')
<section class="hotel-setup-page">

    <div class="setup-hero">
        <div>
            <p>MGM One / Hotel Control Panel</p>
            <h1>{{ $hotel->name }}</h1>
            <span>
                {{ $hotel->code }}
                {{ $hotel->city ? '• '.$hotel->city : '' }}
                {{ $hotel->country ? '• '.$hotel->country : '' }}
            </span>
        </div>

        <a href="{{ route('admin.hotels.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Back to Hotels
        </a>
    </div>

    <div class="setup-section-title">Hotel Structure</div>

    <div class="setup-grid">

        <a href="{{ route('admin.departments.index') }}" class="setup-card">
            <div class="setup-icon">
                <i class="fas fa-building"></i>
            </div>
            <h2>Departments</h2>
            <p>Create and manage hotel departments such as Reception, Housekeeping, Maintenance and F&B.</p>
            <strong>{{ $hotel->departments_count ?? 0 }} Departments</strong>
        </a>

        <a href="{{ route('admin.hotels.room-types.index', $hotel) }}" class="setup-card">
            <div class="setup-icon">
                <i class="fas fa-layer-group"></i>
            </div>
            <h2>Room Categories</h2>
            <p>Create categories such as Single, Twin, Double, Family, Suite and Accessible.</p>
            <strong>Room Categories</strong>
        </a>

        <a href="{{ route('admin.hotels.rooms.index', $hotel) }}" class="setup-card">
            <div class="setup-icon">
                <i class="fas fa-door-open"></i>
            </div>
            <h2>Rooms</h2>
            <p>Manage room numbers, floors, occupancy, housekeeping and maintenance status.</p>
            <strong>Room Inventory</strong>
        </a>

        <a href="{{ route('admin.hotels.restaurants.index', $hotel) }}" class="setup-card">
            <div class="setup-icon">
                <i class="fas fa-utensils"></i>
            </div>
            <h2>Restaurants</h2>
            <p>Add restaurants, manage tables, floor plans and booking settings.</p>
            <strong>Restaurant Setup</strong>
        </a>

        <a href="{{ route('admin.hotels.spa', $hotel) }}" class="setup-card">
            <div class="setup-icon">
                <i class="fas fa-spa"></i>
            </div>
            <h2>Spa</h2>
            <p>Adjust spa timings, slot duration, services and maximum guests per slot.</p>
            <strong>Spa Setup</strong>
        </a>

    </div>

</section>

<style>
.hotel-setup-page {
    animation: fadeIn .35s ease;
}

.setup-hero {
    background:
        radial-gradient(circle at 20% 20%, rgba(232,45,45,.28), transparent 35%),
        linear-gradient(135deg, #2a0606, #101010 70%);
    border: 2px solid var(--border);
    border-radius: 24px;
    padding: 32px;
    margin-bottom: 26px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 18px;
    flex-wrap: wrap;
}

.setup-hero p {
    color: var(--primary);
    font-size: 12px;
    font-weight: 900;
    letter-spacing: 1.5px;
    text-transform: uppercase;
}

.setup-hero h1 {
    font-size: 38px;
    font-weight: 900;
    margin-top: 8px;
}

.setup-hero span {
    display: block;
    color: var(--text-muted);
    margin-top: 8px;
    font-weight: 700;
}

.setup-section-title {
    color: var(--text-muted);
    font-size: 13px;
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    margin: 8px 0 18px;
}

.setup-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 24px;
}

.setup-card {
    background: linear-gradient(180deg, #171717, #101010);
    border: 2px solid var(--border);
    border-radius: 24px;
    padding: 28px;
    color: white;
    min-height: 245px;
    position: relative;
    overflow: hidden;
    transition: all .25s ease;
}

.setup-card::after {
    content: '';
    position: absolute;
    right: -40px;
    top: -40px;
    width: 170px;
    height: 170px;
    background: radial-gradient(circle, rgba(232,45,45,.18), transparent 70%);
}

.setup-card:hover {
    transform: translateY(-5px);
    border-color: var(--primary);
    box-shadow: 0 25px 70px rgba(232,45,45,.18);
}

.setup-icon {
    width: 76px;
    height: 76px;
    border-radius: 22px;
    background: rgba(232,45,45,.13);
    color: var(--primary);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 34px;
    margin-bottom: 22px;
    box-shadow: 0 0 35px rgba(232,45,45,.22);
}

.setup-card h2 {
    font-size: 26px;
    font-weight: 900;
}

.setup-card p {
    color: var(--text-muted);
    margin-top: 10px;
    line-height: 1.6;
    max-width: 460px;
}

.setup-card strong {
    display: inline-flex;
    margin-top: 24px;
    padding: 8px 14px;
    border-radius: 999px;
    background: rgba(232,45,45,.12);
    color: #ff8a8a;
    border: 1px solid rgba(232,45,45,.35);
    font-size: 12px;
    font-weight: 900;
    text-transform: uppercase;
}

@media (max-width: 900px) {
    .setup-grid {
        grid-template-columns: 1fr;
    }

    .setup-hero h1 {
        font-size: 30px;
    }
}
</style>
@endsection