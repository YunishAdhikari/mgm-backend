@extends('dashboard.reception.layout')

@section('content')

<style>
.form-card{
    background:#27272a;
    border:1px solid #3f3f46;
    border-radius:20px;
    padding:30px;
    max-width:900px;
}

.form-grid{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:20px;
}

.form-group{
    display:flex;
    flex-direction:column;
}

.form-group label{
    color:#a1a1aa;
    margin-bottom:8px;
}

.form-group input,
.form-group select,
.form-group textarea{
    background:#18181b;
    border:1px solid #3f3f46;
    color:white;
    padding:12px;
    border-radius:12px;
}

.full{
    grid-column:1 / -1;
}

.btn-save{
    background:linear-gradient(135deg,#8b5cf6,#ec4899);
    color:white;
    border:none;
    padding:14px 24px;
    border-radius:12px;
    font-weight:700;
    cursor:pointer;
}

.page-title{
    font-size:30px;
    font-weight:800;
    color:white;
    margin-bottom:25px;
}

.alert-error{
    background:rgba(239,68,68,.12);
    border:1px solid rgba(239,68,68,.45);
    color:#fecaca;
    padding:14px 18px;
    border-radius:14px;
    margin-bottom:20px;
}

.alert-success{
    background:rgba(16,185,129,.12);
    border:1px solid rgba(16,185,129,.45);
    color:#bbf7d0;
    padding:14px 18px;
    border-radius:14px;
    margin-bottom:20px;
}

.suggestion-card{
    background:rgba(139,92,246,.12);
    border:1px solid rgba(139,92,246,.45);
    color:#ddd6fe;
    padding:18px;
    border-radius:16px;
    margin-bottom:20px;
}

.suggestion-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:14px;
    margin-top:14px;
}

.slot-card{
    background:#18181b;
    border:1px solid #3f3f46;
    padding:14px;
    border-radius:14px;
}

.slot-time{
    font-size:22px;
    font-weight:900;
    color:white;
}

.slot-capacity{
    color:#a1a1aa;
    margin-top:6px;
}

.table-badge{
    display:inline-block;
    background:#27272a;
    color:#d8b4fe;
    border:1px solid #8b5cf6;
    padding:5px 9px;
    border-radius:999px;
    font-size:12px;
    margin:2px;
}

.btn-slot{
    margin-top:12px;
    background:#8b5cf6;
    color:white;
    border:none;
    padding:9px 12px;
    border-radius:10px;
    font-weight:800;
    cursor:pointer;
}

@media(max-width:768px){
    .form-grid{
        grid-template-columns:1fr;
    }

    .full{
        grid-column:auto;
    }
}
</style>

<div class="page-title">
    Create Group Buffet Booking
</div>

@if(session('error'))
    <div class="alert-error">
        {{ session('error') }}
    </div>
@endif

@if($errors->any())
    <div class="alert-error">
        <strong>Please fix these errors:</strong>
        <ul style="margin-top:8px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if(session('success'))
    <div class="alert-success">
        {{ session('success') }}
    </div>
@endif

@if(session('suggestedSlots') && count(session('suggestedSlots')) > 0)
    <div class="suggestion-card">
        <strong>Suggested Available Slots:</strong>

        <div class="suggestion-grid">
            @foreach(session('suggestedSlots') as $slot)
                <div class="slot-card">
                    <div class="slot-time">
                        {{ $slot['display_time'] }}
                    </div>

                    <div class="slot-capacity">
                        Capacity: {{ $slot['total_capacity'] }} pax
                    </div>

                    <div style="margin-top:8px;">
                        @foreach($slot['tables'] as $table)
                            <span class="table-badge">
                                {{ $table }}
                            </span>
                        @endforeach
                    </div>

                    <button type="button"
                            class="btn-slot"
                            onclick="document.querySelector('[name=buffet_time]').value='{{ $slot['time'] }}'">
                        Use This Slot
                    </button>
                </div>
            @endforeach
        </div>
    </div>
@endif

<form action="{{ route('reception.group-buffets.store') }}" method="POST">
    @csrf

    <div class="form-card">
        <div class="form-grid">

            <div class="form-group">
                <label>Group Name</label>
                <input type="text"
                       name="group_name"
                       value="{{ old('group_name') }}"
                       required>
            </div>

            <div class="form-group">
                <label>Agent Name</label>
                <input type="text"
                       name="agent_name"
                       value="{{ old('agent_name') }}">
            </div>

            <div class="form-group">
                <label>Date</label>
                <input type="date"
                       name="buffet_date"
                       value="{{ old('buffet_date') }}"
                       required>
            </div>

            <div class="form-group">
                <label>Time</label>
                <input type="time"
                       name="buffet_time"
                       value="{{ old('buffet_time') }}"
                       required>
            </div>

            <div class="form-group">
                <label>Pax</label>
                <input type="number"
                       name="pax"
                       min="1"
                       value="{{ old('pax') }}"
                       required>
            </div>

            <div class="form-group">
                <label>Meal Type</label>
                <select name="meal_type" required>
                    <option value="breakfast" {{ old('meal_type') == 'breakfast' ? 'selected' : '' }}>Breakfast</option>
                    <option value="lunch" {{ old('meal_type') == 'lunch' ? 'selected' : '' }}>Lunch</option>
                    <option value="dinner" {{ old('meal_type') == 'dinner' ? 'selected' : '' }}>Dinner</option>
                    <option value="afternoon_tea" {{ old('meal_type') == 'afternoon_tea' ? 'selected' : '' }}>Afternoon Tea</option>
                    <option value="private_event" {{ old('meal_type') == 'private_event' ? 'selected' : '' }}>Private Event</option>
                </select>
            </div>

            <div class="form-group">
                <label>Price Per Person</label>
                <input type="number"
                       step="0.01"
                       name="price_per_person"
                       value="{{ old('price_per_person') }}">
            </div>

            <div class="form-group">
                <label>Payment Status</label>
                <select name="payment_status" required>
                    <option value="pending" {{ old('payment_status', 'pending') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="paid" {{ old('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="city_ledger" {{ old('payment_status') == 'city_ledger' ? 'selected' : '' }}>City Ledger</option>
                    <option value="complimentary" {{ old('payment_status') == 'complimentary' ? 'selected' : '' }}>Complimentary</option>
                </select>
            </div>

            <div class="form-group full">
                <label>Notes</label>
                <textarea name="notes" rows="4">{{ old('notes') }}</textarea>
            </div>

        </div>

        <br>

        <button class="btn-save">
            Create Buffet Booking
        </button>
    </div>
</form>

@endsection