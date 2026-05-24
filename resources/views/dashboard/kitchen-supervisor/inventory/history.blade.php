@extends('dashboard.kitchen-supervisor.layout')

@section('content')

<div class="history-card">
    <h1>Inventory History</h1>
    <p>Track all stock in, stock out, and adjustment records.</p>
    <form method="GET" action="{{ route('kitchen.inventory.history') }}" class="filter-form">
    <input type="date" name="from_date" value="{{ request('from_date') }}">
    <input type="date" name="to_date" value="{{ request('to_date') }}">

    <select name="item_id">
        <option value="">All Items</option>
        @foreach($items as $item)
            <option value="{{ $item->id }}" {{ request('item_id') == $item->id ? 'selected' : '' }}>
                {{ $item->name }}
            </option>
        @endforeach
    </select>

    <select name="type">
        <option value="">All Actions</option>
        <option value="stock_in" {{ request('type') == 'stock_in' ? 'selected' : '' }}>Stock In</option>
        <option value="stock_out" {{ request('type') == 'stock_out' ? 'selected' : '' }}>Stock Out</option>
        <option value="adjustment" {{ request('type') == 'adjustment' ? 'selected' : '' }}>Adjustment</option>
    </select>

    <button type="submit">Filter</button>

    <a href="{{ route('kitchen.inventory.history') }}" class="clear-btn">Clear</a>

    <a href="{{ route('kitchen.inventory.history.pdf', request()->query()) }}" class="pdf-btn">
        Download PDF
    </a>
</form>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Item</th>
                    <th>Action</th>
                    <th>Quantity</th>
                    <th>Updated By</th>
                    <th>Note</th>
                </tr>
            </thead>

            <tbody>
                @forelse($transactions as $transaction)
                    <tr>
                        <td>{{ $transaction->created_at->format('d M Y H:i') }}</td>
                        <td>{{ $transaction->item->name ?? 'N/A' }}</td>
                        <td>
                            <span class="badge {{ $transaction->type }}">
                                {{ ucwords(str_replace('_', ' ', $transaction->type)) }}
                            </span>
                        </td>
                        <td>{{ $transaction->quantity }} {{ $transaction->item->unit ?? '' }}</td>
                        <td>{{ $transaction->user->name ?? 'System' }}</td>
                        <td>{{ $transaction->note ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="empty">No inventory history found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<style>
.history-card {
    background: white;
    border-radius: 22px;
    padding: 24px;
    box-shadow: 0 10px 28px rgba(0,0,0,0.06);
}


.filter-form {
    display: grid;
    grid-template-columns: 150px 150px 1fr 160px auto auto auto;
    gap: 10px;
    margin-bottom: 20px;
}

.filter-form input,
.filter-form select {
    border: 1px solid #d1d5db;
    border-radius: 12px;
    padding: 10px 12px;
}

.filter-form button,
.clear-btn,
.pdf-btn {
    border: none;
    border-radius: 12px;
    padding: 10px 14px;
    font-weight: 900;
    text-decoration: none;
    text-align: center;
}

.filter-form button {
    background: #1583ff;
    color: white;
}

.clear-btn {
    background: #f3f4f6;
    color: #374151;
}

.pdf-btn {
    background: #dc2626;
    color: white;
}

@media(max-width: 900px) {
    .filter-form {
        grid-template-columns: 1fr;
    }
}
.history-card h1 {
    margin: 0 0 6px;
}

.history-card p {
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
    text-align: left;
    padding: 14px;
    color: #374151;
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

.stock_in {
    background: #dcfce7;
    color: #166534;
}

.stock_out {
    background: #fee2e2;
    color: #991b1b;
}

.adjustment {
    background: #dbeafe;
    color: #1d4ed8;
}

.empty {
    text-align: center;
    color: #6b7280;
    padding: 30px;
}

@media(max-width: 700px) {
    .history-card {
        padding: 18px;
    }

    th,
    td {
        white-space: nowrap;
    }
}
</style>

@endsection