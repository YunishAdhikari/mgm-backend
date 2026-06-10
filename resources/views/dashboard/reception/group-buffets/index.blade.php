@extends('dashboard.reception.layout')

@section('content')

<style>
    :root {
        --primary: #8b5cf6;
        --secondary: #ec4899;
        --bg-dark: #09090b;
        --bg-card: #18181b;
        --bg-input: #27272a;
        --text-main: #fafafa;
        --text-muted: #a1a1aa;
        --text-dim: #71717a;
        --border: #3f3f46;
        --success: #10b981;
        --danger: #ef4444;
        --warning: #f59e0b;
    }

    * { margin: 0; padding: 0; box-sizing: border-box; }

    .page-wrap {
        padding: 20px;
        width: 100%;
        max-width: 100vw;
        overflow-x: hidden;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 24px;
        gap: 16px;
        flex-wrap: wrap;
    }

    .header-content h1 {
        font-size: 26px;
        font-weight: 900;
        color: var(--text-main);
    }

    .header-content p {
        color: var(--text-muted);
        font-size: 14px;
        margin-top: 4px;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
        padding: 12px 18px;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 800;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        white-space: nowrap;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(139, 92, 246, 0.35);
    }

    .alert {
        padding: 14px 18px;
        border-radius: 12px;
        margin-bottom: 20px;
        font-size: 14px;
    }

    .alert-success {
        background: rgba(16, 185, 129, 0.15);
        border: 1px solid rgba(16, 185, 129, 0.4);
        color: #bbf7d0;
    }

    .alert-error {
        background: rgba(239, 68, 68, 0.15);
        border: 1px solid rgba(239, 68, 68, 0.4);
        color: #fecaca;
    }

    .filter-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 16px;
        margin-bottom: 20px;
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
    }

    .filter-card input,
    .filter-card select {
        background: var(--bg-input);
        border: 1px solid var(--border);
        color: var(--text-main);
        padding: 10px 14px;
        border-radius: 10px;
        min-width: 140px;
        flex: 1;
        max-width: 100%;
    }

    .btn-filter {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
        border: none;
        padding: 10px 16px;
        border-radius: 10px;
        font-weight: 800;
        cursor: pointer;
    }

    .btn-reset {
        background: var(--bg-input);
        color: var(--text-muted);
        border: 1px solid var(--border);
        padding: 10px 14px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 700;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
        margin-bottom: 20px;
    }

    .stat-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 16px;
    }

    .stat-title {
        color: var(--text-muted);
        font-size: 11px;
        text-transform: uppercase;
        font-weight: 800;
    }

    .stat-value {
        font-size: 26px;
        font-weight: 900;
        color: var(--text-main);
        margin-top: 4px;
    }

    .table-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 16px;
        overflow: hidden;
    }

    .table-container {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .responsive-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 600px;
    }

    .responsive-table th {
        background: var(--bg-input);
        color: var(--text-muted);
        text-align: left;
        padding: 12px 14px;
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 800;
    }

    .responsive-table td {
        padding: 14px;
        border-bottom: 1px solid var(--border);
        color: var(--text-main);
    }

    .responsive-table tr:last-child td {
        border-bottom: none;
    }

    .group-name {
        font-weight: 700;
        font-size: 14px;
    }

    .agent-name {
        font-size: 11px;
        color: var(--text-dim);
    }

    .badge {
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 800;
        display: inline-block;
        white-space: nowrap;
        margin: 2px;
    }

    .status-confirmed { background: rgba(20, 83, 45, 0.5); color: #bbf7d0; }
    .status-served { background: rgba(30, 58, 138, 0.5); color: #bfdbfe; }
    .status-completed { background: rgba(22, 101, 52, 0.5); color: #dcfce7; }
    .status-cancelled { background: rgba(127, 29, 29, 0.5); color: #fecaca; }

    .payment-pending { background: rgba(113, 63, 18, 0.5); color: #fef3c7; }
    .payment-paid { background: rgba(20, 83, 45, 0.5); color: #bbf7d0; }
    .payment-city_ledger { background: rgba(30, 58, 138, 0.5); color: #bfdbfe; }
    .payment-complimentary { background: rgba(88, 28, 135, 0.5); color: #e9d5ff; }

    .table-badge {
        background: var(--bg-input);
        color: #c4b5fd;
        border: 1px solid var(--primary);
    }

    .no-tables {
        color: var(--danger);
        font-size: 11px;
        font-weight: 700;
    }

    .btn-edit {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
        border: none;
        padding: 8px 12px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 12px;
        cursor: pointer;
        white-space: nowrap;
    }

    .modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.9);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        padding: 16px;
    }

    .modal-box {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 20px;
        width: 100%;
        max-width: 500px;
        max-height: 85vh;
        overflow-y: auto;
        padding: 20px;
    }

    .modal-title {
        color: var(--text-main);
        font-size: 20px;
        font-weight: 900;
        margin-bottom: 20px;
    }

    .modal-group {
        margin-bottom: 14px;
    }

    .modal-group label {
        color: var(--text-muted);
        font-size: 12px;
        font-weight: 700;
        display: block;
        margin-bottom: 6px;
    }

    .modal-group input,
    .modal-group select,
    .modal-group textarea {
        width: 100%;
        background: var(--bg-input);
        border: 1px solid var(--border);
        color: var(--text-main);
        padding: 12px;
        border-radius: 10px;
    }

    .modal-actions {
        display: flex;
        gap: 10px;
        margin-top: 18px;
    }

    .btn-cancel {
        flex: 1;
        background: var(--bg-input);
        color: var(--text-muted);
        border: 1px solid var(--border);
        padding: 12px;
        border-radius: 10px;
        font-weight: 800;
    }

    .btn-update {
        flex: 1;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
        border: none;
        padding: 12px;
        border-radius: 10px;
        font-weight: 900;
    }

    @media (max-width: 768px) {
        .page-wrap { padding: 16px; }

        .page-header {
            flex-direction: column;
            gap: 14px;
        }

        .header-content h1 { font-size: 22px; }

        .btn-primary {
            width: 100%;
            justify-content: center;
        }

        .filter-card { flex-direction: column; }

        .filter-card input,
        .filter-card select,
        .btn-filter,
        .btn-reset {
            width: 100%;
            text-align: center;
        }

        .stats-grid {
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }

        .stat-card { padding: 12px; }

        .stat-value { font-size: 20px; }

        .responsive-table { min-width: 500px; }

        .responsive-table th,
        .responsive-table td {
            padding: 10px 8px;
            font-size: 12px;
        }

        .badge {
            font-size: 9px;
            padding: 4px 8px;
        }

        .btn-edit {
            padding: 6px 10px;
            font-size: 11px;
        }
    }

    @media (max-width: 480px) {
        .stats-grid {
            grid-template-columns: 1fr 1fr;
        }

        .stat-title { font-size: 10px; }

        .stat-value { font-size: 18px; }
    }
</style>

<div class="page-wrap">

    <div class="page-header">
        <div class="header-content">
            <h1>Group Buffets</h1>
            <p>Manage group buffet bookings.</p>
        </div>

        <a href="{{ route('reception.group-buffets.create') }}" class="btn-primary">
            <i class="fa-solid fa-plus"></i> New Booking
        </a>

      <a href="{{ route('reception.group-buffets.daily-report', request()->query()) }}"
        target="_blank"
        class="btn-primary"
        style="background:linear-gradient(135deg,#10b981,#3b82f6);">
            F&B Daily Report
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-error">
            <strong>Errors:</strong> Please fix the errors below.
        </div>
    @endif

    <form method="GET" action="{{ route('reception.group-buffets.index') }}" class="filter-card">
        <input type="date" name="date" value="{{ request('date') }}">

        <select name="meal_type">
            <option value="">All Meals</option>
            <option value="breakfast" {{ request('meal_type') == 'breakfast' ? 'selected' : '' }}>Breakfast</option>
            <option value="lunch" {{ request('meal_type') == 'lunch' ? 'selected' : '' }}>Lunch</option>
            <option value="dinner" {{ request('meal_type') == 'dinner' ? 'selected' : '' }}>Dinner</option>
            <option value="afternoon_tea" {{ request('meal_type') == 'afternoon_tea' ? 'selected' : '' }}>Afternoon Tea</option>
            <option value="private_event" {{ request('meal_type') == 'private_event' ? 'selected' : '' }}>Private Event</option>
        </select>

        <select name="status">
            <option value="">Active Status</option>
            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
            <option value="served" {{ request('status') == 'served' ? 'selected' : '' }}>Served</option>
            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
        </select>

        <button type="submit" class="btn-filter">Filter</button>

        <a href="{{ route('reception.group-buffets.index') }}" class="btn-reset">Reset</a>
    </form>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-title">Total</div>
            <div class="stat-value">{{ $bookings->count() }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-title">Pax</div>
            <div class="stat-value">{{ $bookings->sum('pax') }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-title">Today</div>
            <div class="stat-value">
                {{ $bookings->filter(fn($b) => \Carbon\Carbon::parse($b->buffet_date)->isToday())->count() }}
            </div>
        </div>
    </div>

    <div class="table-card">
        <div class="table-container">
            <table class="responsive-table">
                <thead>
                    <tr>
                        <th>Group</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Pax</th>
                        <th>Meal</th>
                        <th>Tables</th>
                        <th>Pay</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($bookings as $booking)
                        <tr>
                            <td>
                                <div class="group-name">{{ $booking->group_name }}</div>
                                @if($booking->agent_name)
                                    <div class="agent-name">{{ $booking->agent_name }}</div>
                                @endif
                            </td>

                            <td>{{ \Carbon\Carbon::parse($booking->buffet_date)->format('d M') }}</td>
                            <td>{{ \Carbon\Carbon::parse($booking->buffet_time)->format('h:i A') }}</td>
                            <td><strong>{{ $booking->pax }}</strong></td>
                            <td>{{ ucfirst(str_replace('_', ' ', $booking->meal_type)) }}</td>

                            <td>
                                @if($booking->tables && $booking->tables->count() > 0)
                                    @foreach($booking->tables as $table)
                                        <span class="badge table-badge">{{ $table->table_name }}</span>
                                    @endforeach
                                @else
                                    <span class="no-tables">-</span>
                                @endif
                            </td>

                            <td>
                                <span class="badge payment-{{ $booking->payment_status }}">
                                    {{ ucfirst(str_replace('_', ' ', $booking->payment_status)) }}
                                </span>
                            </td>

                            <td>
                                <span class="badge status-{{ $booking->status }}">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </td>

                            <td>
                                <button class="btn-edit" onclick="openEditModal({{ $booking->id }})">
                                    Edit
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="text-align: center; padding: 30px; color: var(--text-muted);">
                                No bookings found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div style="padding: 16px 0; display: flex; justify-content: center;">
        {{ $bookings->appends(request()->query())->links() }}
    </div>

</div>

@foreach($bookings as $booking)
    <div class="modal-overlay" id="editModal{{ $booking->id }}">
        <div class="modal-box">
            <div class="modal-title">Edit Booking</div>

            <form action="{{ route('reception.group-buffets.update', $booking->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-group">
                    <label>Group Name</label>
                    <input type="text" name="group_name" value="{{ $booking->group_name }}" required>
                </div>

                <div class="modal-group">
                    <label>Agent Name</label>
                    <input type="text" name="agent_name" value="{{ $booking->agent_name }}">
                </div>

                <div class="modal-group">
                    <label>Date</label>
                    <input type="date" name="buffet_date" value="{{ \Carbon\Carbon::parse($booking->buffet_date)->format('Y-m-d') }}" required>
                </div>

                <div class="modal-group">
                    <label>Time</label>
                    <input type="time" name="buffet_time" value="{{ \Carbon\Carbon::parse($booking->buffet_time)->format('H:i') }}" required>
                </div>

                <div class="modal-group">
                    <label>Pax</label>
                    <input type="number" name="pax" min="1" value="{{ $booking->pax }}" required>
                </div>

                <div class="modal-group">
                    <label>Meal Type</label>
                    <select name="meal_type" required>
                        <option value="breakfast" {{ $booking->meal_type == 'breakfast' ? 'selected' : '' }}>Breakfast</option>
                        <option value="lunch" {{ $booking->meal_type == 'lunch' ? 'selected' : '' }}>Lunch</option>
                        <option value="dinner" {{ $booking->meal_type == 'dinner' ? 'selected' : '' }}>Dinner</option>
                        <option value="afternoon_tea" {{ $booking->meal_type == 'afternoon_tea' ? 'selected' : '' }}>Afternoon Tea</option>
                        <option value="private_event" {{ $booking->meal_type == 'private_event' ? 'selected' : '' }}>Private Event</option>
                    </select>
                </div>

                <div class="modal-group">
                    <label>Payment Status</label>
                    <select name="payment_status" required>
                        <option value="pending" {{ $booking->payment_status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="paid" {{ $booking->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="city_ledger" {{ $booking->payment_status == 'city_ledger' ? 'selected' : '' }}>City Ledger</option>
                        <option value="complimentary" {{ $booking->payment_status == 'complimentary' ? 'selected' : '' }}>Complimentary</option>
                    </select>
                </div>

                <div class="modal-group">
                    <label>Status</label>
                    <select name="status" required>
                        <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="served" {{ $booking->status == 'served' ? 'selected' : '' }}>Served</option>
                        <option value="completed" {{ $booking->status == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="closeEditModal({{ $booking->id }})">
                        Cancel
                    </button>

                    <button type="submit" class="btn-update">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
@endforeach

<script>
    function openEditModal(id) {
        document.getElementById('editModal' + id).style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeEditModal(id) {
        document.getElementById('editModal' + id).style.display = 'none';
        document.body.style.overflow = '';
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.modal-overlay').forEach(modal => {
                modal.style.display = 'none';
            });
            document.body.style.overflow = '';
        }
    });

    document.querySelectorAll('.modal-overlay').forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.style.display = 'none';
                document.body.style.overflow = '';
            }
        });
    });
</script>

@endsection