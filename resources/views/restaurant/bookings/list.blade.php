@php
    $role = strtolower(auth()->user()->role->slug ?? '');

    $layout = str_contains($role, 'supervisor')
        ? 'dashboard.supervisor.layout'
        : 'dashboard.reception.layout';
@endphp

@extends($layout)

@section('content')

<style>
    :root {
        --bg-page: #09090b;
        --bg-card: #27272a;
        --bg-input: #1c1c1f;
        --text-main: #fafafa;
        --text-muted: #a1a1aa;
        --text-dim: #71717a;
        --border: #3f3f46;
        --primary: #8b5cf6;
        --primary-hover: #a78bfa;
        --pink: #ec4899;
    }

    .page-wrap { 
        padding: 24px; 
        background: var(--bg-page);
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 16px;
    }

    .page-title-wrap h1 {
        font-size: 28px;
        font-weight: 900;
        color: var(--text-main);
        margin-bottom: 4px;
    }

    .page-title-wrap p { 
        color: var(--text-muted); 
        font-size: 14px;
    }

    .btn-new {
        background: linear-gradient(135deg, var(--primary), var(--pink));
        color: white;
        border: none;
        padding: 14px 24px;
        border-radius: 14px;
        font-weight: 800;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(139, 92, 246, 0.3);
    }

    .btn-new:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(139, 92, 246, 0.4);
    }

    /* Filter Card */
    .filter-card {
        background: var(--bg-card);
        padding: 24px;
        border-radius: 20px;
        margin-bottom: 24px;
        border: 1px solid var(--border);
    }

    .filter-form {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 16px;
        align-items: end;
    }

    .filter-field label {
        font-weight: 700;
        font-size: 12px;
        margin-bottom: 8px;
        display: block;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .filter-field input, 
    .filter-field select {
        width: 100%;
        padding: 12px 14px;
        border: 2px solid var(--border);
        border-radius: 12px;
        background: var(--bg-input);
        color: var(--text-main);
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .filter-field input:focus, 
    .filter-field select:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(139, 92, 246, 0.15);
    }

    .filter-actions {
        display: flex;
        gap: 10px;
        align-items: flex-end;
    }

    .btn-filter {
        background: var(--primary);
        color: white;
        border: none;
        padding: 12px 20px;
        border-radius: 12px;
        font-weight: 800;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }

    .btn-filter:hover { background: var(--primary-hover); }

    .btn-reset {
        background: var(--bg-input);
        color: var(--text-main);
        border: 1px solid var(--border);
        padding: 12px 20px;
        border-radius: 12px;
        font-weight: 800;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    /* Bookings Container */
    .bookings-container {
        display: grid;
        gap: 16px;
    }

    .booking-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 20px;
        padding: 24px;
        transition: all 0.3s ease;
        animation: slideIn 0.4s ease;
    }

    @keyframes slideIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .booking-card:hover {
        border-color: var(--primary);
        box-shadow: 0 0 20px rgba(139, 92, 246, 0.15);
    }

    .booking-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 20px;
    }

    .booking-datetime {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .booking-date {
        font-size: 18px;
        font-weight: 900;
        color: var(--text-main);
    }

    .booking-time {
        font-size: 14px;
        color: var(--text-muted);
    }

    .booking-time i { margin-right: 6px; }

    .type-badge {
        padding: 8px 16px;
        border-radius: 25px;
        font-size: 13px;
        font-weight: 800;
    }

    .badge-tea { 
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.2), rgba(37, 99, 235, 0.2)); 
        color: #60a5fa; 
    }

    .badge-dinner { 
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.2), rgba(5, 150, 105, 0.2)); 
        color: #34d399; 
    }

    .booking-info {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 20px;
    }

    .info-item {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .info-label {
        font-size: 11px;
        font-weight: 700;
        color: var(--text-dim);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .info-value {
        font-size: 15px;
        font-weight: 700;
        color: var(--text-main);
    }

    .status-value {
        display: inline-block;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 800;
    }

    .status-confirmed { background: rgba(14, 165, 233, 0.2); color: #38bdf8; }
    .status-seated { background: rgba(16, 185, 129, 0.2); color: #34d399; }
    .status-completed { background: rgba(113, 113, 122, 0.2); color: #a1a1aa; }
    .status-cancelled { background: rgba(239, 68, 68, 0.2); color: #f87171; }
    .status-no_show { background: rgba(249, 115, 22, 0.2); color: #fb923c; }

    .overbook-badge {
        background: rgba(239, 68, 68, 0.2);
        color: #f87171;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 800;
    }

    .booking-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 16px;
        border-top: 1px solid var(--border);
    }

    .action-buttons {
        display: flex;
        gap: 10px;
    }

    .btn-action {
        padding: 10px 16px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.3s ease;
    }

    .btn-view { 
        background: rgba(59, 130, 246, 0.15); 
        color: #60a5fa; 
    }

    .btn-edit { 
        background: rgba(245, 158, 11, 0.15); 
        color: #fbbf24; 
    }

    .btn-cancel-action { 
        background: rgba(239, 68, 68, 0.15); 
        color: #f87171; 
        border: none;
        cursor: pointer;
    }

    .btn-action:hover { transform: translateY(-2px); }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: var(--text-dim);
    }

    .empty-state i {
        font-size: 50px;
        margin-bottom: 16px;
        opacity: 0.5;
    }

    .success-message {
        background: rgba(16, 185, 129, 0.15);
        border: 1px solid rgba(16, 185, 129, 0.3);
        color: #6ee7b7;
        padding: 16px 20px;
        border-radius: 14px;
        margin-bottom: 20px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .loading {
        display: none;
        text-align: center;
        padding: 40px;
        color: var(--text-muted);
    }

    .loading i {
        font-size: 30px;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Responsive */
    @media (max-width: 1000px) {
        .filter-form { grid-template-columns: repeat(3, 1fr); }
        .booking-info { grid-template-columns: repeat(2, 1fr); }
    }

    @media (max-width: 700px) {
        .page-wrap { padding: 16px; }
        
        .page-header { flex-direction: column; align-items: stretch; }
        .btn-new { width: 100%; justify-content: center; }
        
        .filter-form { grid-template-columns: 1fr 1fr; }
        .filter-actions { grid-column: span 2; }
        
        .booking-info { grid-template-columns: 1fr 1fr; }
        .booking-footer { flex-direction: column; gap: 16px; }
        .action-buttons { width: 100%; }
        .btn-action { flex: 1; justify-content: center; }
    }

    @media (max-width: 500px) {
        .filter-form { grid-template-columns: 1fr; }
        .filter-actions { grid-column: span 1; flex-direction: column; }
        .booking-info { grid-template-columns: 1fr; }
    }
</style>

<div class="page-wrap">

    <div class="page-header">
        <div class="page-title-wrap">
            <h1>Restaurant Bookings</h1>
            <p>Manage and view all restaurant reservations</p>
        </div>
        <a href="{{ route('reception.restaurant.bookings.index') }}" class="btn-new">
            <i class="fas fa-plus"></i> New Booking
        </a>
    </div>

    @if(session('success'))
        <div class="success-message" id="successMsg">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="filter-card">
        <form id="filterForm" class="filter-form">
            <div class="filter-field">
                <label>Date</label>
                <input type="date" name="date" value="{{ request('date', today()->toDateString()) }}">
            </div>

            <div class="filter-field">
                <label>Search</label>
                <input type="text" name="guest_name" placeholder="Search guest..." value="{{ request('guest_name') }}">
            </div>

            <div class="filter-field">
                <label>Type</label>
                <select name="booking_type">
                    <option value="">All Types</option>
                    <option value="afternoon_tea" {{ request('booking_type') == 'afternoon_tea' ? 'selected' : '' }}>Afternoon Tea</option>
                    <option value="dinner" {{ request('booking_type') == 'dinner' ? 'selected' : '' }}>Dinner</option>
                </select>
            </div>

            <div class="filter-field">
                <label>Status</label>
                <select name="status">
                    <option value="">All Status</option>
                    @foreach(['confirmed', 'seated', 'completed', 'cancelled', 'no_show'] as $status)
                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                            {{ ucwords(str_replace('_', ' ', $status)) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-actions">
                <button type="submit" class="btn-filter">
                    <i class="fas fa-filter"></i> Filter
                </button>
                <a href="{{ route('reception.restaurant.bookings.list') }}" class="btn-reset">
                    <i class="fas fa-rotate-right"></i> Reset
                </a>
            </div>
        </form>
    </div>

    <div class="bookings-container" id="bookingsContainer">
        @forelse($bookings as $booking)
            <div class="booking-card" style="animation-delay: {{ $loop->index * 0.05 }}s">
                <div class="booking-top">
                    <div class="booking-datetime">
                        <div class="booking-date">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}</div>
                        <div class="booking-time">
                            <i class="fas fa-clock"></i>
                            {{ \Carbon\Carbon::parse($booking->slot_start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($booking->slot_end_time)->format('g:i A') }}
                        </div>
                    </div>
                    <span class="type-badge {{ $booking->booking_type === 'afternoon_tea' ? 'badge-tea' : 'badge-dinner' }}">
                        {{ $booking->booking_type === 'afternoon_tea' ? '🍵 Afternoon Tea' : '🌙 Dinner' }}
                    </span>
                </div>

                <div class="booking-info">
                    <div class="info-item">
                        <span class="info-label">Guest</span>
                        <span class="info-value">{{ $booking->guest_name }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Phone</span>
                        <span class="info-value">{{ $booking->guest_phone ?? '-' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Pax</span>
                        <span class="info-value">{{ $booking->pax }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Table</span>
                        <span class="info-value">{{ $booking->table->table_name ?? '-' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Voucher</span>
                        <span class="info-value">{{ $booking->voucher_code ?? '-' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Status</span>
                        <span class="status-value status-{{ $booking->status }}">
                            {{ ucwords(str_replace('_', ' ', $booking->status)) }}
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Overbooking</span>
                        @if($booking->is_overbooking)
                            <span class="overbook-badge">⚠ Yes</span>
                        @else
                            <span class="info-value">No</span>
                        @endif
                    </div>
                </div>

                <div class="booking-footer">
                    <a href="{{ route('reception.restaurant.bookings.show', $booking) }}" class="btn-action btn-view">
                        <i class="fas fa-eye"></i> View
                    </a>

                    <div class="action-buttons">
                        <a href="{{ route('reception.restaurant.bookings.edit', $booking) }}" class="btn-action btn-edit">
                            <i class="fas fa-pen"></i> Edit
                        </a>

                        @if(!in_array($booking->status, ['cancelled', 'completed']))
                            <form method="POST" action="{{ route('reception.restaurant.bookings.cancel', $booking) }}" onsubmit="return confirm('Cancel this booking?')">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn-action btn-cancel-action">
                                    <i class="fas fa-xmark"></i> Cancel
                                </button>
                            </form>
                        @endif
                    </div>
                                    </div>
            </div>
        @empty
            <div class="empty-state">
                <i class="fas fa-calendar-xmark"></i>
                <p>No bookings found for selected filters</p>
            </div>
        @endforelse
    </div>

    <div class="loading" id="loading">
        <i class="fas fa-spinner"></i>
        <p>Loading bookings...</p>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.getElementById('filterForm');
    const bookingsContainer = document.getElementById('bookingsContainer');
    const loading = document.getElementById('loading');
    const successMsg = document.getElementById('successMsg');

    // Hide success message after 3 seconds
    if (successMsg) {
        setTimeout(() => {
            successMsg.style.display = 'none';
        }, 3000);
    }

    // Debounce function
    function debounce(func, wait) {
        let timeout;
        return function(...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func(...args), wait);
        };
    }

    // Fetch bookings with AJAX
    function fetchBookings() {
        const formData = new FormData(filterForm);
        const params = new URLSearchParams(formData);

        loading.style.display = 'block';
        bookingsContainer.style.display = 'none';

        fetch('{{ route("reception.restaurant.bookings.list") }}?' + params.toString(), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newContainer = doc.getElementById('bookingsContainer');
            
            if (newContainer) {
                bookingsContainer.innerHTML = newContainer.innerHTML;
                bookingsContainer.style.display = 'grid';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to load bookings');
        })
        .finally(() => {
            loading.style.display = 'none';
        });
    }

    // Filter form submission
    filterForm.addEventListener('submit', function(e) {
        e.preventDefault();
        fetchBookings();
    });

    // Debounced search on input change
    const inputs = filterForm.querySelectorAll('input, select');
    inputs.forEach(input => {
        input.addEventListener('change', debounce(fetchBookings, 500));
    });
});
</script>

@endsection