@extends('dashboard.kitchen-supervisor.layout')
{{-- @extends('layouts.kitchen') --}}

@section('page-title', 'Wastage Tracking')
@section('page-subtitle', 'Record wasted, expired, damaged, or spoiled kitchen stock')

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
        <h1><i class="fas fa-trash-can"></i> Wastage Tracking</h1>
        <p>Record wasted, expired, damaged, or spoiled kitchen stock.</p>
    </div>
</div>

<div class="content-grid">
    
    <!-- Add Wastage Form -->
    <div class="form-card">
        <h2><i class="fas fa-plus-circle"></i> Add Wastage</h2>

        <form method="POST" action="{{ route('kitchen.wastage.store') }}">
            @csrf

            <div class="form-group">
                <label><i class="fas fa-box"></i> Inventory Item</label>
                <select name="inventory_item_id" required>
                    <option value="">Select Item</option>
                    @foreach($items as $item)
                        <option value="{{ $item->id }}">
                            {{ $item->name }} — {{ $item->quantity }} {{ $item->unit }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label><i class="fas fa-scale-balanced"></i> Quantity Wasted</label>
                <input type="number" name="quantity" step="0.01" min="0.01" required placeholder="Enter quantity">
            </div>

            <div class="form-group">
                <label><i class="fas fa-question-circle"></i> Reason</label>
                <select name="reason" required>
                    <option value="">Select Reason</option>
                    <option value="expired">Expired</option>
                    <option value="spoiled">Spoiled</option>
                    <option value="damaged">Damaged</option>
                    <option value="burnt">Burnt</option>
                    <option value="overproduction">Overproduction</option>
                    <option value="staff_meal">Staff Meal</option>
                    <option value="other">Other</option>
                </select>
            </div>

            <div class="form-group">
                <label><i class="fas fa-sticky-note"></i> Note</label>
                <textarea name="note" rows="3" placeholder="Optional note..."></textarea>
            </div>

            <button type="submit" class="submit-btn">
                <i class="fas fa-save"></i> Record Wastage
            </button>
        </form>
    </div>

    <!-- Recent Wastage History -->
    <div class="history-card">
        <div class="card-header">
            <h2><i class="fas fa-history"></i> Recent Wastage</h2>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th><i class="fas fa-calendar"></i> Date</th>
                        <th><i class="fas fa-box"></i> Item</th>
                        <th><i class="fas fa-scale-balanced"></i> Qty</th>
                        <th><i class="fas fa-question-circle"></i> Reason</th>
                        <th><i class="fas fa-user"></i> Recorded By</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($wastages as $wastage)
                        <tr>
                            <td>{{ $wastage->created_at->format('d M Y H:i') }}</td>
                            <td>{{ $wastage->item->name ?? 'N/A' }}</td>
                            <td>
                                <span class="qty-badge">
                                    {{ $wastage->quantity }} {{ $wastage->item->unit ?? '' }}
                                </span>
                            </td>
                            <td>
                                <span class="reason-badge {{ $wastage->reason }}">
                                    {{ ucwords(str_replace('_', ' ', $wastage->reason ?? 'N/A')) }}
                                </span>
                            </td>
                            <td>{{ $wastage->user->name ?? 'System' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="empty-text">
                                <i class="fas fa-trash-can"></i>
                                <p>No wastage records found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<style>
/* Alert */
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

/* Content Grid */
.content-grid {
    display: grid;
    grid-template-columns: 380px 1fr;
    gap: 24px;
    align-items: start;
}

/* Form Card */
.form-card,
.history-card {
    background: white;
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
    border: 1px solid #e2e8f0;
}

.form-card h2,
.history-card h2 {
    font-size: 18px;
    font-weight: 700;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
    color: #1e293b;
}

.form-card h2 i,
.history-card h2 i {
    color: #dc2626;
}

/* Form Group */
.form-group {
    margin-bottom: 18px;
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
    font-size: 12px;
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

.form-group textarea {
    resize: vertical;
    min-height: 80px;
}

/* Submit Button */
.submit-btn {
    width: 100%;
    border: none;
    background: #dc2626;
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
    background: #b91c1c;
    transform: translateY(-2px);
}

/* Table */
.table-wrap {
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th {
    background: #f8fafc;
    text-align: left;
    padding: 12px 14px;
    font-size: 11px;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 1px solid #e2e8f0;
}

th i {
    color: #dc2626;
    margin-right: 6px;
}

td {
    padding: 14px;
    font-size: 13px;
    color: #334155;
    border-bottom: 1px solid #f1f5f9;
}

tr:hover td {
    background: #f8fafc;
}

/* Badges */
.qty-badge {
    display: inline-block;
    padding: 4px 10px;
    background: #f1f5f9;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
    color: #334155;
}

.reason-badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
}

.reason-badge.expired {
    background: #fee2e2;
    color: #dc2626;
}

.reason-badge.spoiled {
    background: #fce7f3;
    color: #be185d;
}

.reason-badge.damaged {
    background: #fef3c7;
    color: #d97706;
}

.reason-badge.burnt {
    background: #fee2e2;
    color: #991b1b;
}

.reason-badge.overproduction {
    background: #dbeafe;
    color: #2563eb;
}

.reason-badge.staff_meal {
    background: #dcfce7;
    color: #16a34a;
}

.reason-badge.other {
    background: #f1f5f9;
    color: #64748b;
}

/* Empty State */
.empty-text {
    text-align: center;
    padding: 40px;
    color: #94a3b8;
}

.empty-text i {
    font-size: 36px;
    margin-bottom: 10px;
    opacity: 0.5;
}

.empty-text p {
    font-size: 14px;
}

/* Responsive */
@media (max-width: 1000px) {
    .content-grid {
        grid-template-columns: 1fr;
    }
}
</style>

@endsection