@extends('dashboard.admin.layout')

@section('page-title', 'Hotel Management')

@section('content')
<div class="animate-fade-in">

    <div class="d-flex justify-between items-center gap-4 mb-6" style="flex-wrap: wrap;">
        <div>
            <p style="color: var(--primary); font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; font-size: 12px;">
                MGM One / Platform Admin
            </p>
            <h1 style="font-size: 32px; font-weight: 900; margin-top: 6px;">Hotel Management</h1>
            <p style="color: var(--text-muted); margin-top: 8px;">
                Create, edit, deactivate and manage hotels across the MGM One platform.
            </p>
        </div>

        <a href="{{ route('admin.hotels.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            Add Hotel
        </a>
    </div>

    @if(session('success'))
        <div class="card" style="border-color: rgba(34,197,94,.35); background: rgba(34,197,94,.08); color:#4ade80;">
            <i class="fas fa-circle-check"></i>
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="card" style="border-color: rgba(239,68,68,.35); background: rgba(239,68,68,.08); color:#f87171;">
            @foreach($errors->all() as $error)
                <p>• {{ $error }}</p>
            @endforeach
        </div>
    @endif

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon red"><i class="fas fa-hotel"></i></div>
            <div class="stat-value">{{ $hotels->total() }}</div>
            <div class="stat-label">Total Hotels</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon green"><i class="fas fa-circle-check"></i></div>
            <div class="stat-value">{{ \App\Models\Hotel::where('is_active', true)->count() }}</div>
            <div class="stat-label">Active Hotels</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon orange"><i class="fas fa-circle-pause"></i></div>
            <div class="stat-value">{{ \App\Models\Hotel::where('is_active', false)->count() }}</div>
            <div class="stat-label">Inactive Hotels</div>
        </div>

        <div class="stat-card" style="background: linear-gradient(135deg, rgba(232,45,45,.22), rgba(20,20,20,1));">
            <div class="stat-icon red"><i class="fas fa-network-wired"></i></div>
            <div class="stat-value" style="font-size: 26px;">Multi Hotel</div>
            <div class="stat-label">Platform Mode</div>
        </div>
    </div>

    <div class="hotel-grid">
        @forelse($hotels as $hotel)
            <div class="hotel-card">
                <div class="hotel-cover">
                    <div class="hotel-glow"></div>

                    <div class="hotel-logo">
                        @if($hotel->logo)
                            <img src="{{ asset('uploads/hotels/' . $hotel->logo) }}" alt="{{ $hotel->name }}">
                        @else
                            <i class="fas fa-hotel"></i>
                        @endif
                    </div>

                    <div class="hotel-status">
                        @if($hotel->is_active)
                            <span class="badge badge-success">Active</span>
                        @else
                            <span class="badge badge-danger">Inactive</span>
                        @endif
                    </div>
                </div>

                <div class="hotel-body">
                    <h2>{{ $hotel->name }}</h2>
                    <p class="hotel-code">{{ $hotel->code }}</p>

                    <div class="hotel-info">
                        <div><span>Email</span><strong>{{ $hotel->email ?? '-' }}</strong></div>
                        <div><span>Phone</span><strong>{{ $hotel->phone ?? '-' }}</strong></div>
                        <div>
                            <span>Location</span>
                            <strong>{{ $hotel->city ?? '-' }}{{ $hotel->country ? ', '.$hotel->country : '' }}</strong>
                        </div>
                    </div>

                    <div class="hotel-mini-stats">
                        <div>
                            <strong>{{ $hotel->users_count ?? 0 }}</strong>
                            <span>Users</span>
                        </div>
                        <div>
                            <strong>{{ $hotel->rooms_count ?? 0 }}</strong>
                            <span>Rooms</span>
                        </div>
                        <div>
                            <strong>{{ $hotel->departments_count ?? 0 }}</strong>
                            <span>Depts</span>
                        </div>
                    </div>

                    <div class="hotel-actions">
                        <a href="{{ route('admin.hotels.setup', $hotel) }}" class="hotel-btn hotel-btn-dark">
                            <i class="fas fa-sliders"></i>
                            Setup
                        </a>

                        <button type="button"
                                class="hotel-btn hotel-btn-dark"
                                onclick="openHotelEditModal(
                                    '{{ $hotel->id }}',
                                    @js($hotel->name),
                                    @js($hotel->code),
                                    @js($hotel->email),
                                    @js($hotel->phone),
                                    @js($hotel->address),
                                    @js($hotel->city),
                                    @js($hotel->country),
                                    @js($hotel->postcode),
                                    '{{ $hotel->is_active ? 1 : 0 }}'
                                )">
                            <i class="fas fa-pen"></i>
                            Edit
                        </button>

                        <form action="{{ route('admin.hotels.destroy', $hotel) }}" method="POST">
                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                    class="hotel-btn {{ $hotel->is_active ? 'hotel-btn-danger' : 'hotel-btn-success' }}"
                                    onclick="return confirm('{{ $hotel->is_active ? 'Disable this hotel?' : 'Activate this hotel?' }}')">
                                <i class="fas {{ $hotel->is_active ? 'fa-ban' : 'fa-check' }}"></i>
                                {{ $hotel->is_active ? 'Disable' : 'Activate' }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-hotels">
                <div class="empty-icon"><i class="fas fa-hotel"></i></div>
                <h2>No hotels created yet</h2>
                <p>Create your first hotel to start building the multi-hotel platform.</p>

                <a href="{{ route('admin.hotels.create') }}" class="btn btn-primary mt-4">
                    <i class="fas fa-plus"></i>
                    Add First Hotel
                </a>
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $hotels->links() }}
    </div>
</div>

<div class="modal-backdrop" id="hotelEditModal">
    <div class="modal-card">
        <div class="modal-header">
            <h2><i class="fas fa-pen"></i> Edit Hotel</h2>
            <button type="button" onclick="closeHotelEditModal()">×</button>
        </div>

        <form method="POST" id="hotelEditForm" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="edit-grid">
                <div class="form-group">
                    <label class="form-label">Hotel Name *</label>
                    <input type="text" name="name" id="edit_name" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Code *</label>
                    <input type="text" name="code" id="edit_code" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" id="edit_email">
                </div>

                <div class="form-group">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" id="edit_phone">
                </div>

                <div class="form-group full">
                    <label class="form-label">Address</label>
                    <input type="text" name="address" id="edit_address">
                </div>

                <div class="form-group">
                    <label class="form-label">City</label>
                    <input type="text" name="city" id="edit_city">
                </div>

                <div class="form-group">
                    <label class="form-label">Country</label>
                    <input type="text" name="country" id="edit_country">
                </div>

                <div class="form-group">
                    <label class="form-label">Postcode</label>
                    <input type="text" name="postcode" id="edit_postcode">
                </div>

                <div class="form-group">
                    <label class="form-label">Logo</label>
                    <input type="file" name="logo" accept="image/*">
                </div>

                <div class="form-group full active-row">
                    <label>
                        <input type="checkbox" name="is_active" value="1" id="edit_is_active">
                        Active Hotel
                    </label>
                </div>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn btn-secondary" onclick="closeHotelEditModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    Update Hotel
                </button>
            </div>
        </form>
    </div>
</div>

<style>
.hotel-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 24px;
}

.hotel-card {
    background: linear-gradient(180deg, #171717, #101010);
    border: 2px solid var(--border);
    border-radius: 22px;
    overflow: hidden;
    position: relative;
    transition: all .3s ease;
    box-shadow: 0 20px 60px rgba(0,0,0,.35);
}

.hotel-card:hover {
    transform: translateY(-6px);
    border-color: var(--primary);
    box-shadow: 0 25px 70px rgba(232,45,45,.18);
}

.hotel-cover {
    height: 120px;
    position: relative;
    background:
        radial-gradient(circle at 20% 20%, rgba(232,45,45,.35), transparent 30%),
        linear-gradient(135deg, #220707, #101010 60%, #050505);
    border-bottom: 1px solid var(--border);
}

.hotel-glow {
    position: absolute;
    inset: 0;
    background: linear-gradient(90deg, rgba(232,45,45,.12), transparent);
}

.hotel-logo {
    position: absolute;
    left: 24px;
    bottom: -34px;
    width: 78px;
    height: 78px;
    border-radius: 20px;
    background: #1c1c1c;
    border: 4px solid #101010;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary);
    font-size: 30px;
    box-shadow: 0 0 30px rgba(232,45,45,.25);
    overflow: hidden;
}

.hotel-logo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.hotel-status {
    position: absolute;
    top: 18px;
    right: 18px;
}

.hotel-body {
    padding: 48px 24px 24px;
}

.hotel-body h2 {
    font-size: 22px;
    font-weight: 900;
    letter-spacing: -.4px;
    margin-bottom: 4px;
}

.hotel-code {
    color: var(--primary);
    font-size: 13px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.hotel-info {
    margin-top: 22px;
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.hotel-info div {
    display: flex;
    justify-content: space-between;
    gap: 16px;
    padding-bottom: 12px;
    border-bottom: 1px solid var(--border);
}

.hotel-info span {
    color: var(--text-dim);
    font-size: 12px;
    font-weight: 800;
    text-transform: uppercase;
}

.hotel-info strong {
    color: var(--text-muted);
    font-size: 13px;
    text-align: right;
    word-break: break-word;
}

.hotel-mini-stats {
    margin-top: 22px;
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
}

.hotel-mini-stats div {
    background: #0a0a0a;
    border: 1px solid var(--border);
    border-radius: 14px;
    padding: 14px 10px;
    text-align: center;
}

.hotel-mini-stats strong {
    display: block;
    font-size: 20px;
    font-weight: 900;
}

.hotel-mini-stats span {
    display: block;
    margin-top: 3px;
    font-size: 11px;
    color: var(--text-dim);
    font-weight: 800;
    text-transform: uppercase;
}

.hotel-actions {
    margin-top: 22px;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
}

.hotel-actions form {
    width: 100%;
}

.hotel-btn {
    width: 100%;
    min-height: 46px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    border-radius: 12px;
    font-weight: 900;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: .8px;
    transition: all .2s ease;
}

.hotel-btn-dark {
    background: #1c1c1c;
    border: 1px solid var(--border);
    color: white;
}

.hotel-btn-dark:hover {
    border-color: var(--primary);
    color: var(--primary);
    box-shadow: 0 0 20px rgba(232,45,45,.18);
}

.hotel-btn-danger {
    background: rgba(239,68,68,.16);
    border: 1px solid rgba(239,68,68,.35);
    color: #f87171;
}

.hotel-btn-success {
    background: rgba(34,197,94,.14);
    border: 1px solid rgba(34,197,94,.35);
    color: #4ade80;
}

.empty-hotels {
    grid-column: 1 / -1;
    background: var(--bg-card);
    border: 2px dashed var(--border);
    border-radius: 22px;
    padding: 60px 24px;
    text-align: center;
}

.empty-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 20px;
    border-radius: 22px;
    background: rgba(232,45,45,.12);
    color: var(--primary);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 34px;
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
    max-width: 760px;
    max-height: 90vh;
    overflow-y: auto;
    background: #111;
    border: 2px solid var(--border);
    border-radius: 22px;
    padding: 24px;
}

.modal-header {
    display: flex;
    justify-content: space-between;
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

.edit-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 18px;
}

.form-group.full {
    grid-column: 1 / -1;
}

.active-row label {
    display: flex;
    align-items: center;
    gap: 10px;
    color: var(--text-muted);
    font-weight: 900;
}

.modal-actions {
    margin-top: 24px;
    display: flex;
    justify-content: flex-end;
    gap: 12px;
}

@media (max-width: 768px) {
    .hotel-grid,
    .edit-grid {
        grid-template-columns: 1fr;
    }

    .hotel-actions {
        grid-template-columns: 1fr;
    }

    .hotel-info div {
        flex-direction: column;
        gap: 4px;
    }

    .hotel-info strong {
        text-align: left;
    }

    .modal-actions {
        flex-direction: column;
    }

    .modal-actions .btn {
        width: 100%;
    }
}
</style>

<script>
const hotelBaseUrl = "{{ url('/hotels') }}";

function openHotelEditModal(id, name, code, email, phone, address, city, country, postcode, isActive) {
    document.getElementById('edit_name').value = name ?? '';
    document.getElementById('edit_code').value = code ?? '';
    document.getElementById('edit_email').value = email ?? '';
    document.getElementById('edit_phone').value = phone ?? '';
    document.getElementById('edit_address').value = address ?? '';
    document.getElementById('edit_city').value = city ?? '';
    document.getElementById('edit_country').value = country ?? '';
    document.getElementById('edit_postcode').value = postcode ?? '';
    document.getElementById('edit_is_active').checked = isActive == 1;

    document.getElementById('hotelEditForm').action = `${hotelBaseUrl}/${id}`;
    document.getElementById('hotelEditModal').classList.add('active');
}

function closeHotelEditModal() {
    document.getElementById('hotelEditModal').classList.remove('active');
}

document.getElementById('hotelEditModal')?.addEventListener('click', function(e) {
    if (e.target === this) closeHotelEditModal();
});
</script>
@endsection