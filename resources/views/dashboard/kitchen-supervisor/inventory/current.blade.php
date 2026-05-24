@extends('dashboard.kitchen-supervisor.layout')

@section('content')

<div class="page-card">

    @if(session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="page-header">
        <div>
            <h1>Current Inventory</h1>
            <p>View and edit all kitchen inventory items.</p>
        </div>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Category</th>
                    <th>Quantity</th>
                    <th>Minimum Stock</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($items as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->category ?? 'N/A' }}</td>
                        <td>{{ $item->quantity }} {{ $item->unit }}</td>
                        <td>{{ $item->minimum_stock }} {{ $item->unit }}</td>
                        <td>
                            @if($item->quantity <= $item->minimum_stock)
                                <span class="badge low">Low Stock</span>
                            @else
                                <span class="badge good">In Stock</span>
                            @endif
                        </td>
                        <td>
                            <button class="edit-btn" onclick="openEditModal({{ $item->id }})">
                                Edit
                            </button>
                        </td>
                    </tr>

                    <div class="modal" id="editModal{{ $item->id }}">
                        <div class="modal-box">
                            <div class="modal-header">
                                <h2>Edit Item</h2>
                                <button onclick="closeEditModal({{ $item->id }})">&times;</button>
                            </div>

                            <form method="POST" action="{{ route('kitchen.inventory.update', $item->id) }}">
                                @csrf
                                @method('PUT')

                                <label>Item Name</label>
                                <input type="text" name="name" value="{{ $item->name }}" required>

                                <label>Category</label>
                                <input type="text" name="category" value="{{ $item->category }}">

                                <label>Quantity</label>
                                <input type="number" name="quantity" step="0.01" min="0" value="{{ $item->quantity }}" required>

                                <label>Unit</label>
                                <select name="unit" required>
                                    @foreach(['pcs','kg','g','ltr','ml','pack','box','bottle'] as $unit)
                                        <option value="{{ $unit }}" {{ $item->unit == $unit ? 'selected' : '' }}>
                                            {{ $unit }}
                                        </option>
                                    @endforeach
                                </select>

                                <label>Minimum Stock</label>
                                <input type="number" name="minimum_stock" step="0.01" min="0" value="{{ $item->minimum_stock }}" required>

                                <button class="save-btn">Save Changes</button>
                            </form>
                        </div>
                    </div>

                @empty
                    <tr>
                        <td colspan="6" class="empty">No inventory items found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

<style>
.page-card {
    background: white;
    border-radius: 22px;
    padding: 24px;
    box-shadow: 0 10px 28px rgba(0,0,0,0.06);
}

.alert-success {
    background: #dcfce7;
    color: #166534;
    padding: 14px 16px;
    border-radius: 14px;
    font-weight: 800;
    margin-bottom: 18px;
}

.page-header h1 {
    margin: 0 0 6px;
}

.page-header p {
    margin: 0 0 20px;
    color: #6b7280;
}

.table-wrap {
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th {
    background: #f9fafb;
    color: #374151;
    text-align: left;
    padding: 14px;
    font-size: 14px;
}

td {
    padding: 14px;
    border-bottom: 1px solid #e5e7eb;
    font-weight: 600;
}

.badge {
    padding: 7px 12px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 900;
}

.badge.low {
    background: #fee2e2;
    color: #991b1b;
}

.badge.good {
    background: #dcfce7;
    color: #166534;
}

.edit-btn {
    border: none;
    background: #1583ff;
    color: white;
    padding: 9px 14px;
    border-radius: 11px;
    font-weight: 900;
    cursor: pointer;
}

.empty {
    text-align: center;
    color: #6b7280;
    padding: 30px;
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

label {
    display: block;
    font-weight: 800;
    margin-bottom: 7px;
    color: #374151;
}

input,
select {
    width: 100%;
    border: 1px solid #d1d5db;
    border-radius: 12px;
    padding: 11px 12px;
    margin-bottom: 14px;
}

.save-btn {
    width: 100%;
    border: none;
    background: #f97316;
    color: white;
    padding: 12px;
    border-radius: 13px;
    font-weight: 900;
    cursor: pointer;
}

@media(max-width: 700px) {
    .page-card {
        padding: 18px;
    }

    th,
    td {
        white-space: nowrap;
    }
}
</style>

<script>
function openEditModal(id) {
    document.getElementById('editModal' + id).classList.add('active');
}

function closeEditModal(id) {
    document.getElementById('editModal' + id).classList.remove('active');
}
</script>

@endsection