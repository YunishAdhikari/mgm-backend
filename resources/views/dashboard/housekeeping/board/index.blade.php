@extends('dashboard.housekeeping.layout')

@section('title', 'Housekeeping Board')
@section('page-title', 'Housekeeping Board')

@section('content')

<div class="pageHeader">
    <div class="headerLeft">
        <div class="iconBox">
            <i class="fas fa-broom"></i>
        </div>
        <div>
            <h1>Housekeeping Board</h1>
        </div>
    </div>
    
    <form method="GET" action="" class="dateForm">
        <div class="datePicker">
            <i class="fas fa-calendar"></i>
            <input type="date" name="date" value="{{ $date }}">
        </div>
        <button type="submit" class="btnNeon">
            <i class="fas fa-arrow-right"></i>
            <span>Load</span>
        </button>
    </form>
</div>

<!-- Animated Summary Cards -->
<div class="summaryGrid">
    <div class="summaryCard departure">
        <div class="cardGlow"></div>
        <div class="cardInner">
            <div class="summaryIcon">
                <i class="fas fa-sign-out-alt"></i>
            </div>
            <div class="summaryContent">
                <span class="counter">{{ $statuses->get('departure', collect())->count() + $statuses->get('carry_forward', collect())->count() }}</span>
                <span class="label">Departure</span>
            </div>
        </div>
    </div>

    <div class="summaryCard stay">
        <div class="cardGlow"></div>
        <div class="cardInner">
            <div class="summaryIcon">
                <i class="fas fa-moon"></i>
            </div>
            <div class="summaryContent">
                <span class="counter">{{ $statuses->get('stay', collect())->count() }}</span>
                <span class="label">Stay</span>
            </div>
        </div>
    </div>

    <div class="summaryCard room_move">
        <div class="cardGlow"></div>
        <div class="cardInner">
            <div class="summaryIcon">
                <i class="fas fa-random"></i>
            </div>
            <div class="summaryContent">
                <span class="counter">{{ $statuses->get('room_move', collect())->count() }}</span>
                <span class="label">Moves</span>
            </div>
        </div>
    </div>

    <div class="summaryCard carry_forward">
        <div class="cardGlow"></div>
        <div class="cardInner">
            <div class="summaryIcon">
                <i class="fas fa-forward"></i>
            </div>
            <div class="summaryContent">
                <span class="counter">{{ $statuses->get('carry_forward', collect())->count() }}</span>
                <span class="label">Forward</span>
            </div>
        </div>
    </div>
</div>

<!-- Status Sections -->
@foreach(['departure' => 'Departures', 'stay' => 'Stay Rooms', 'room_move' => 'Room Moves', 'carry_forward' => 'Carry Forward'] as $key => $label)

    <div class="statusSection">
        <div class="sectionTitle {{ $key }}">
            <div class="titleIcon">
                @if($key === 'departure')
                    <i class="fas fa-door-open"></i>
                @elseif($key === 'stay')
                    <i class="fas fa-bed"></i>
                @elseif($key === 'room_move')
                    <i class="fas fa-exchange-alt"></i>
                @else
                    <i class="fas fa-clock"></i>
                @endif
            </div>
            <span class="titleText">{{ $label }}</span>
            <span class="countBadge">{{ $statuses->get($key, collect())->count() }}</span>
        </div>

        <div class="roomGrid">
            @forelse($statuses->get($key, collect()) as $index => $item)
                <div class="roomCard {{ $key }}" style="animation-delay: {{ $index * 0.03 }}s">
                    <div class="roomTop">
                        <span class="roomNumber">{{ $item->room->room_number ?? 'N/A' }}</span>
                        <span class="roomType">{{ $item->room->roomType->name ?? 'Room' }}</span>
                    </div>
                    @if($item->notes)
                        <div class="roomNotes">
                            <i class="fas fa-sticky-note"></i>
                            <span>{{ $item->notes }}</span>
                        </div>
                    @endif
                    <div class="roomBottom">
                        <span class="updatedBy">{{ $item->updatedBy->name ?? 'Reception' }}</span>
                    </div>
                </div>
            @empty
                <div class="emptyState">
                    <i class="fas fa-check"></i>
                    <p>No {{ strtolower($label) }}</p>
                </div>
            @endforelse
        </div>
    </div>

@endforeach

<style>
/* ===== Page Header ===== */
.pageHeader {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
    margin-bottom: 28px;
    flex-wrap: wrap;
}

.headerLeft {
    display: flex;
    align-items: center;
    gap: 16px;
}

.iconBox {
    width: 52px;
    height: 52px;
    background: linear-gradient(135deg, var(--primary), #16a34a);
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    color: white;
    box-shadow: 0 6px 24px rgba(34, 197, 94, 0.35);
}

.headerLeft h1 {
    font-size: 26px;
    font-weight: 700;
    color: var(--text-main);
    margin: 0;
}

.headerLeft p {
    font-size: 13px;
    color: var(--text-muted);
    margin: 4px 0 0;
}

.dateForm {
    display: flex;
    gap: 10px;
    align-items: center;
}

.datePicker {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 14px;
    background: var(--bg-elevated);
    border: 1px solid var(--border-subtle);
    border-radius: 10px;
}

.datePicker i {
    color: var(--primary);
    font-size: 14px;
}

.datePicker input {
    background: none;
    border: none;
    color: var(--text-main);
    font-size: 13px;
    width: 110px;
}

.btnNeon {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 10px 18px;
    background: linear-gradient(135deg, var(--primary), #16a34a);
    border: none;
    border-radius: 10px;
    color: white;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
}

/* ===== Summary Grid ===== */
.summaryGrid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 14px;
    margin-bottom: 28px;
}

@media (max-width: 900px) { .summaryGrid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 500px) { .summaryGrid { grid-template-columns: 1fr 1fr; } }

.summaryCard {
    position: relative;
    padding: 16px 20px;
    border-radius: 16px;
    color: white;
    overflow: hidden;
    animation: slideUp 0.5s ease backwards;
}

.summaryCard:hover {
    transform: translateY(-4px);
}

.cardInner {
    display: flex;
    align-items: center;
    gap: 14px;
}

.summaryIcon {
    width: 40px;
    height: 40px;
    background: rgba(255, 255, 255, 0.15);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
}

.summaryContent {
    display: flex;
    flex-direction: column;
}

.counter {
    font-size: 28px;
    font-weight: 800;
    line-height: 1;
}

.label {
    font-size: 12px;
    font-weight: 600;
    opacity: 0.85;
}

.summaryCard.departure { background: linear-gradient(135deg, #059669, #10b981); }
.summaryCard.stay { background: linear-gradient(135deg, #2563eb, #3b82f6); }
.summaryCard.room_move { background: linear-gradient(135deg, #d97706, #f59e0b); }
.summaryCard.carry_forward { background: linear-gradient(135deg, #7c3aed, #a855f7); }

/* ===== Status Section ===== */
.statusSection {
    margin-bottom: 24px;
}

.sectionTitle {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 14px;
    padding: 12px 18px;
    background: var(--bg-card);
    border: 1px solid var(--border-subtle);
    border-radius: 12px;
}

.titleIcon {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
}

.sectionTitle.departure .titleIcon { background: rgba(16, 185, 129, 0.2); color: #10b981; }
.sectionTitle.stay .titleIcon { background: rgba(59, 130, 246, 0.2); color: #3b82f6; }
.sectionTitle.room_move .titleIcon { background: rgba(245, 158, 11, 0.2); color: #f59e0b; }
.sectionTitle.carry_forward .titleIcon { background: rgba(168, 85, 247, 0.2); color: #a855f7; }

.titleText {
    font-size: 15px;
    font-weight: 600;
    color: var(--text-main);
}

.countBadge {
    margin-left: auto;
    padding: 4px 10px;
    background: var(--bg-elevated);
    border-radius: 16px;
    font-size: 12px;
    font-weight: 600;
}

/* ===== Room Grid ===== */
.roomGrid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
    gap: 10px;
}

.roomCard {
    padding: 14px;
    border-radius: 14px;
    color: white;
    transition: all 0.2s ease;
    animation: slideUp 0.4s ease backwards;
}

.roomCard:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
}

.roomTop {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 8px;
    margin-bottom: 8px;
}

.roomNumber {
    font-size: 22px;
    font-weight: 800;
}

.roomType {
    padding: 3px 8px;
    background: rgba(0, 0, 0, 0.2);
    border-radius: 12px;
    font-size: 9px;
    font-weight: 700;
    text-transform: uppercase;
}

.roomNotes {
    display: flex;
    align-items: flex-start;
    gap: 6px;
    padding: 8px 10px;
    background: rgba(0, 0, 0, 0.15);
    border-radius: 8px;
    font-size: 11px;
    margin-bottom: 8px;
}

.roomNotes i {
    font-size: 10px;
}

.roomBottom {
    font-size: 10px;
    opacity: 0.7;
    padding-top: 8px;
    border-top: 1px solid rgba(255, 255, 255, 0.15);
}

.roomCard.departure { background: linear-gradient(135deg, #059669, #10b981); }
.roomCard.stay { background: linear-gradient(135deg, #2563eb, #3b82f6); }
.roomCard.room_move { background: linear-gradient(135deg, #d97706, #f59e0b); }
.roomCard.carry_forward { background: linear-gradient(135deg, #7c3aed, #a855f7); }

/* ===== Empty State ===== */
.emptyState {
    grid-column: 1 / -1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 30px 16px;
    background: var(--bg-card);
    border: 1px dashed var(--border);
    border-radius: 14px;
    text-align: center;
}

.emptyState i {
    font-size: 24px;
    color: var(--primary);
    margin-bottom: 8px;
    opacity: 0.5;
}

.emptyState p {
    font-size: 12px;
    color: var(--text-muted);
}

/* ===== Responsive ===== */
@media (max-width: 640px) {
    .pageHeader {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .dateForm {
        width: 100%;
    }
    
    .roomGrid {
        grid-template-columns: repeat(2, 1fr);
    }
}
</style>

@endsection