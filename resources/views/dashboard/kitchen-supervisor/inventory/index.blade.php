@extends('dashboard.kitchen-supervisor.layout')
{{-- @extends('layouts.kitchen') --}}

@section('page-title', 'Kitchen Inventory')
@section('page-subtitle', 'Manage kitchen stock, low stock alerts, and stock movement')

@section('content')

<!-- Success Alert -->
@if(session('success'))
    <div class="alert-success">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

<!-- Header -->
<div class="page-header">
    <div>
        <h1><i class="fas fa-boxes-stacked"></i> Kitchen Inventory</h1>
        <p>Manage kitchen stock, low stock alerts, and stock movement.</p>
    </div>
</div>

<!-- Low Stock Alert -->
@if($lowStockItems->count() > 0)
    <div class="alert-card">
        <div class="alert-header">
            <h3><i class="fas fa-triangle-exclamation"></i> Low Stock Alert</h3>
            <span class="alert-count">{{ $lowStockItems->count() }} items</span>
        </div>
        <div class="alert-items">
            @foreach($lowStockItems as $item)
                <span class="alert-item">
                    <strong>{{ $item->name }}</strong>
                    {{ $item->quantity }} {{ $item->unit }}
                </span>
            @endforeach
        </div>
    </div>
@endif

<div class="content-grid">
    
    <!-- Add New Item Form -->
    <div class="form-card">
        <h2><i class="fas fa-plus-circle"></i> Add New Item</h2>

        <form method="POST" action="{{ route('kitchen.inventory.store') }}">
            @csrf

            <div class="form-group">
                <label><i class="fas fa-tag"></i> Item Name</label>
                <input type="text" name="name" required placeholder="e.g. Chicken Breast">
            </div>

            <div class="form-group">
                <label><i class="fas fa-folder"></i> Category</label>
                <input type="text" name="category" placeholder="e.g. Meat, Dairy, Vegetables">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label><i class="fas fa-scale-balanced"></i> Opening Quantity</label>
                    <input type="number" name="quantity" step="0.01" min="0" required placeholder="0">
                </div>

                <div class="form-group">
                    <label><i class="fas fa-box"></i> Unit</label>
                    <select name="unit" required>
                        <option value="pcs">pcs</option>
                        <option value="kg">kg</option>
                        <option value="g">g</option>
                        <option value="ltr">ltr</option>
                        <option value="ml">ml</option>
                        <option value="pack">pack</option>
                        <option value="box">box</option>
                        <option value="bottle">bottle</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label><i class="fas fa-triangle-exclamation"></i> Minimum Stock</label>
                <input type="number" name="minimum_stock" step="0.01" min="0" required placeholder="Minimum stock level">
            </div>

            <button type="submit" class="submit-btn">
                <i class="fas fa-plus"></i> Add Item
            </button>
        </form>
    </div>

    <!-- Current Stock List -->
    <div class="items-card">
        <div class="card-header">
            <h2><i class="fas fa-list"></i> Current Stock</h2>
            <span class="item-count">{{ $items->count() }} items</span>
        </div>

        <div class="stock-list">
            @forelse($items as $item)
                <div class="item-row {{ $item->quantity <= $item->minimum_stock ? 'low-stock' : '' }}">
                    <div class="item-info">
                        <h3>{{ $item->name }}</h3>
                        <p>{{ $item->category ?? 'Uncategorised' }}</p>
                    </div>

                    <div class="item-qty">
                        <strong>{{ $item->quantity }}</strong>
                        <span>{{ $item->unit }}</span>
                        @if($item->quantity <= $item->minimum_stock)
                            <span class="low-badge">Low</span>
                        @endif
                    </div>

                    <details class="item-actions">
                        <summary>
                            <i class="fas fa-ellipsis-vertical"></i> Actions
                        </summary>

                        <div class="action-forms">
                            <form method="POST" action="{{ route('kitchen.inventory.stock', $item->id) }}">
                                @csrf
                                <div class="form-group">
                                    <label>Action</label>
                                    <select name="type" required>
                                        <option value="stock_in">Stock In</option>
                                        <option value="stock_out">Stock Out</option>
                                        <option value="adjustment">Adjustment</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Quantity</label>
                                    <input type="number" name="quantity" step="0.01" min="0.01" required>
                                </div>
                                <div class="form-group">
                                    <label>Note</label>
                                    <textarea name="note" rows="2" placeholder="Optional note"></textarea>
                                </div>
                                <button type="submit" class="update-btn">Update</button>
                            </form>

                            <form method="POST" action="{{ route('kitchen.inventory.destroy', $item->id) }}" onsubmit="return confirm('Remove this item?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="delete-btn">Remove Item</button>
                            </form>
                        </div>
                    </details>
                </div>
            @empty
                <div class="empty-state">
                    <i class="fas fa-boxes-stacked"></i>
                    <p>No inventory items found.</p>
                    <p>Add your first item using the form.</p>
                </div>
            @endforelse
        </div>
    </div>

</div>

<style>
/* Alert Success */
.alert-success {
    background: #dcfce7;
    color: #166534;
    padding: 14px 18px;
    border-radius: 12px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 24px;
}

/* Header */
.page-header h1 {
    font-size: 24px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 12px;
    color: #1e293b;
    margin-bottom: 6px;
}

.page-header h1 i {
    color: #dc2626;
}

.page-header p {
    color: #64748b;
    font-size: 14px;
}

/* Alert Card */
.alert-card {
    background: #fef2f2;
    border: 1px solid #fecaca;
    border-radius: 14px;
    padding: 20px;
    margin-bottom: 24px;
}

.alert-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 14px;
}

.alert-header h3 {
    margin: 0;
    color: #dc2626;
    font-size: 16px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.alert-count {
    background: #dc2626;
    color: white;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
}

.alert-items {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.alert-item {
    background: white;
    color: #dc2626;
    padding: 8px 14px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
    border: 1px solid #fecaca;
}

/* Content Grid */
.content-grid {
    display: grid;
    grid-template-columns: 380px 1fr;
    gap: 24px;
    align-items: start;
}

/* Form Card */
.form-card,
.items-card {
    background: white;
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
    border: 1px solid #e2e8f0;
}

.form-card h2,
.items-card h2 {
    font-size: 18px;
    font-weight: 700;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
    color: #1e293b;
}

.form-card h2 i,
.items-card h2 i {
    color: #dc2626;
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.card-header h2 {
    margin-bottom: 0;
}

.item-count {
    background: #f1f5f9;
    color: #64748b;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

/* Form Group */
.form-group {
    margin-bottom: 16px;
}

.form-group label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    font-weight: 600;
    color: #64748b;
    margin-bottom: 8px;
}

.form-group label i {
    font-size: 11px;
    color: #dc2626;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 12px 14px;
    font-size: 14px;
    outline: none;
    transition: all 0.2s;
    background: #f8fafc;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    border-color: #dc2626;
    background: white;
}

/* Form Row */
.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px;
}

/* Submit Button */
.submit-btn {
    width: 100%;
    border: none;
    background: linear-gradient(135deg, #f97316, #ea580c);
    color: white;
    padding: 14px 18px;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    transition: all 0.2s;
}

.submit-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(249, 115, 22, 0.3);
}

/* Stock List */
.stock-list {
    max-height: 600px;
    overflow-y: auto;
    padding-right: 8px;
}

.stock-list::-webkit-scrollbar {
    width: 6px;
}

.stock-list::-webkit-scrollbar-thumb {
    background: #e2e8f0;
    border-radius: 6px;
}

/* Item Row */
.item-row {
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 16px;
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    gap: 16px;
    transition: all 0.2s;
}

.item-row:hover {
    border-color: #cbd5e1;
}

.item-row.low-stock {
    border-color: #fecaca;
    background: #fefafafa;
}

.item-info {
    flex: 1;
}

.item-info h3 {
    margin: 0 0 4px;
    font-size: 15px;
    font-weight: 700;
    color: #1e293b;
}

.item-info p {
    margin: 0;
    font-size: 12px;
    color: #64748b;
}

.item-qty {
    text-align: right;
    min-width: 70px;
}

.item-qty strong {
    display: block;
    font-size: 22px;
    font-weight: 800;
    color: #1e293b;
}

.item-qty span {
    font-size: 12px;
    color: #64748b;
    font-weight: 600;
}

.low-badge {
    display: block;
    background: #fee2e2;
    color: #dc2626;
    padding: 2px 8px;
    border-radius: 10px;
    font-size: 10px;
    font-weight: 700;
    margin-top: 4px;
}

/* Item Actions */
.item-actions {
    flex-shrink: 0;
}

.item-actions summary {
    cursor: pointer;
    font-weight: 600;
    color: #64748b;
    list-style: none;
    padding: 8px 12px;
    background: #f1f5f9;
    border-radius: 8px;
    font-size: 12px;
}

.item-actions summary::-webkit-details-marker {
    display: none;
}

.action-forms {
    padding-top: 14px;
}

.update-btn,
.delete-btn {
    width: 100%;
    border: none;
    border-radius: 8px;
    padding: 10px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}

.update-btn {
    background: #2563eb;
    color: white;
    margin-top: 12px;
}

.update-btn:hover {
    background: #1d4ed8;
}

.delete-btn {
    background: #fee2e2;
    color: #dc2626;
    margin-top: 8px;
}

.delete-btn:hover {
    background: #dc2626;
    color: white;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 48px;
    color: #94a3b8;
}

.empty-state i {
    font-size: 40px;
    margin-bottom: 12px;
    opacity: 0.5;
}

.empty-state p {
    margin: 0;
    font-size: 14px;
}

/* Responsive */
@media (max-width: 1000px) {
    .content-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 500px) {
    .form-row {
        grid-template-columns: 1fr;
    }
}
</style>

@endsection