@extends('dashboard.kitchen-supervisor.layout')

@section('content')

<div class="buffet-page">

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <div class="header-card">
        <h1>Buffet Management</h1>
        <p>Create buffet menus and record buffet pax sales to deduct inventory automatically.</p>
    </div>

    <div class="grid">

        <div class="form-card">
            <h2>Add Buffet Menu</h2>

            <form method="POST" action="{{ route('kitchen.buffets.store') }}">
                @csrf

                <div class="form-group">
                    <label>Buffet Name</label>
                    <input type="text" name="name" required placeholder="e.g. Breakfast Buffet">
                </div>

                <div class="form-group">
                    <label>Service Type</label>
                    <select name="service_type">
                        <option value="">Select Type</option>
                        <option value="breakfast">Breakfast</option>
                        <option value="lunch">Lunch</option>
                        <option value="dinner">Dinner</option>
                        <option value="event">Event</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Notes</label>
                    <textarea name="notes" rows="3" placeholder="Optional notes"></textarea>
                </div>

                <div class="form-group">
                    <label>Select Recipes</label>

                    <div class="checkbox-list">
                        @foreach($menuItems as $menuItem)
                            <label class="check-row">
                                <input type="checkbox" name="menu_items[]" value="{{ $menuItem->id }}">
                                <span>{{ $menuItem->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <button class="primary-btn">
                    Create Buffet
                </button>
            </form>
        </div>

        <div class="buffet-card">
            <h2>Current Buffets</h2>

            @forelse($buffets as $buffet)
                <div class="buffet-item">
                    <div class="buffet-top">
                        <div>
                            <h3>{{ $buffet->name }}</h3>
                            <p>{{ ucfirst($buffet->service_type ?? 'General') }}</p>

                            @if($buffet->notes)
                                <small>{{ $buffet->notes }}</small>
                            @endif
                        </div>

                        <button type="button" class="sale-btn" onclick="openSaleModal({{ $buffet->id }})">
                            Add Pax Sale
                        </button>
                    </div>

                    <div class="recipe-list">
                        @foreach($buffet->items as $buffetItem)
                            <span>{{ $buffetItem->menuItem->name ?? 'N/A' }}</span>
                        @endforeach
                    </div>
                </div>

                <div class="modal" id="saleModal{{ $buffet->id }}">
                    <div class="modal-box">
                        <div class="modal-header">
                            <h2>Record Buffet Sale</h2>
                            <button type="button" onclick="closeSaleModal({{ $buffet->id }})">&times;</button>
                        </div>

                        <form method="POST" action="{{ route('kitchen.buffets.sale', $buffet->id) }}">
                            @csrf

                            <div class="form-group">
                                <label>Buffet</label>
                                <input type="text" value="{{ $buffet->name }}" disabled>
                            </div>

                            <div class="form-group">
                                <label>Sale Date</label>
                                <input type="date" name="sale_date" value="{{ now()->format('Y-m-d') }}" required>
                            </div>

                            <div class="form-group">
                                <label>Pax Sold</label>
                                <input type="number" name="pax" min="1" required placeholder="e.g. 45">
                            </div>

                            <div class="form-group">
                                <label>Note</label>
                                <textarea name="note" rows="3" placeholder="Optional note"></textarea>
                            </div>

                            <button class="primary-btn">
                                Save Sale & Deduct Inventory
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="empty">No buffet menus created yet.</p>
            @endforelse
        </div>

    </div>

</div>

<style>
.buffet-page {
    display: grid;
    gap: 22px;
}

.alert-success {
    background: #dcfce7;
    color: #166534;
    padding: 14px 16px;
    border-radius: 14px;
    font-weight: 800;
}

.header-card,
.form-card,
.buffet-card {
    background: white;
    border-radius: 22px;
    padding: 22px;
    box-shadow: 0 10px 28px rgba(0,0,0,0.06);
}

.header-card h1,
.form-card h2,
.buffet-card h2 {
    margin: 0 0 8px;
}

.header-card p {
    margin: 0;
    color: #6b7280;
}

.grid {
    display: grid;
    grid-template-columns: 420px 1fr;
    gap: 22px;
    align-items: start;
}

.form-group {
    margin-bottom: 14px;
}

label {
    display: block;
    font-weight: 800;
    margin-bottom: 7px;
    color: #374151;
}

input,
select,
textarea {
    width: 100%;
    border: 1px solid #d1d5db;
    border-radius: 12px;
    padding: 11px 12px;
    outline: none;
    font-size: 14px;
}

.checkbox-list {
    max-height: 260px;
    overflow-y: auto;
    border: 1px solid #e5e7eb;
    border-radius: 14px;
    padding: 10px;
}

.check-row {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 9px;
    border-radius: 10px;
    cursor: pointer;
}

.check-row:hover {
    background: #f9fafb;
}

.check-row input {
    width: auto;
}

.primary-btn,
.sale-btn {
    border: none;
    border-radius: 12px;
    padding: 11px 14px;
    font-weight: 900;
    cursor: pointer;
}

.primary-btn {
    width: 100%;
    background: #f97316;
    color: white;
}

.sale-btn {
    background: #1583ff;
    color: white;
    white-space: nowrap;
}

.buffet-item {
    border: 1px solid #e5e7eb;
    border-radius: 18px;
    padding: 16px;
    margin-bottom: 14px;
}

.buffet-top {
    display: flex;
    justify-content: space-between;
    gap: 14px;
    align-items: flex-start;
}

.buffet-item h3 {
    margin: 0 0 6px;
    font-size: 20px;
}

.buffet-item p {
    margin: 0 0 6px;
    color: #6b7280;
    font-weight: 800;
}

.buffet-item small {
    color: #6b7280;
}

.recipe-list {
    margin-top: 14px;
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.recipe-list span {
    background: #fff7ed;
    color: #ea580c;
    padding: 7px 10px;
    border-radius: 999px;
    font-weight: 800;
    font-size: 13px;
}

.empty {
    color: #6b7280;
    text-align: center;
    padding: 30px 0;
}

.modal {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.45);
    z-index: 9999;
    align-items: center;
    justify-content: center;
    padding: 18px;
}

.modal.active {
    display: flex;
}

.modal-box {
    width: 100%;
    max-width: 520px;
    background: white;
    border-radius: 22px;
    padding: 22px;
    box-shadow: 0 20px 45px rgba(0,0,0,0.18);
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 18px;
}

.modal-header h2 {
    margin: 0;
}

.modal-header button {
    border: none;
    background: #f3f4f6;
    font-size: 26px;
    width: 42px;
    height: 42px;
    border-radius: 50%;
    cursor: pointer;
}

@media(max-width: 1000px) {
    .grid {
        grid-template-columns: 1fr;
    }
}

@media(max-width: 700px) {
    .buffet-top {
        flex-direction: column;
    }

    .sale-btn {
        width: 100%;
    }
}
</style>

<script>
function openSaleModal(id) {
    document.getElementById('saleModal' + id).classList.add('active');
}

function closeSaleModal(id) {
    document.getElementById('saleModal' + id).classList.remove('active');
}
</script>

@endsection