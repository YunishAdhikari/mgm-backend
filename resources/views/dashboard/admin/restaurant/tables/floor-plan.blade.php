@extends('dashboard.admin.layout')

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
}

.floor-page {
    padding: 24px;
    background: var(--bg-page);
    min-height: 100vh;
    overflow-x: hidden;
}

.floor-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
    flex-wrap: wrap;
    gap: 18px;
}

.header-left h1 {
    font-size: 32px;
    font-weight: 900;
    color: var(--text-main);
    margin: 0;
}

.header-left p {
    color: var(--text-muted);
    margin-top: 6px;
}

.back-btn {
    background: var(--primary);
    color: #fff;
    padding: 13px 24px;
    border-radius: 14px;
    text-decoration: none;
    font-weight: 800;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s ease;
    box-shadow: 0 0 15px rgba(139, 92, 246, 0.3);
}

.back-btn:hover {
    transform: translateY(-2px);
    background: var(--primary-hover);
    color: #fff;
}

.floor-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 28px;
    padding: 24px;
    box-shadow: 0 25px 50px rgba(0,0,0,0.4);
    position: relative;
}

.floor-plan-wrapper {
    width: 100%;
    height: 650px;
    overflow: auto;
    border-radius: 20px;
    border: 4px solid var(--primary);
    background:
        radial-gradient(circle at 20px 20px, rgba(139, 92, 246, 0.10) 0 2px, transparent 3px),
        linear-gradient(135deg, #111113, #09090b);
    background-size: 44px 44px, 100% 100%;
    position: relative;
    scrollbar-width: thin;
}

.floor-plan {
    position: relative;
    min-width: 100%;
    min-height: 100%;
    transform-origin: top left;
}

.floor-label {
    position: sticky;
    top: 20px;
    left: 20px;
    background: var(--primary);
    color: white;
    padding: 12px 20px;
    border-radius: 50px;
    font-size: 14px;
    font-weight: 800;
    z-index: 100;
    box-shadow: 0 0 15px rgba(139, 92, 246, 0.4);
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.floor-label::before {
    content: '';
    width: 8px;
    height: 8px;
    background: #10b981;
    border-radius: 50%;
    animation: pulse 2s infinite;
}

@keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.5; } }

/* Bigger unit gives chairs enough room but keeps them visually close to the table */
.table-unit {
    position: absolute;
    width: 170px;
    height: 170px;
    transition: all 0.25s ease;
    cursor: pointer;
    z-index: 10;
}

.table-unit:hover {
    transform: scale(1.04);
    z-index: 100;
}

.table-visual {
    position: absolute;
    inset: 0;
}

/* Actual table */
.restaurant-table {
    position: absolute;
    top: 50%;
    left: 50%;
    z-index: 20;
    background: linear-gradient(145deg, #3f3f46 0%, #27272a 100%);
    border: 3px solid var(--primary);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: var(--text-main);
    text-align: center;
    box-shadow:
        inset 0 1px 0 rgba(255,255,255,0.08),
        0 14px 30px rgba(0,0,0,0.35);
}

/* IMPORTANT: table shape classes must match the Blade output */
.restaurant-table.table-square {
    width: 84px;
    height: 84px;
    border-radius: 18px;
    transform: translate(-50%, -50%);
}

.restaurant-table.table-round {
    width: 92px;
    height: 92px;
    border-radius: 999px;
    transform: translate(-50%, -50%);
}

.restaurant-table.table-horizontal {
    width: 136px;
    height: 76px;
    border-radius: 22px;
    transform: translate(-50%, -50%);
}

.restaurant-table.table-vertical {
    width: 76px;
    height: 136px;
    border-radius: 22px;
    transform: translate(-50%, -50%);
}

.restaurant-table.table-banquet {
    width: 150px;
    height: 86px;
    border-radius: 24px;
    transform: translate(-50%, -50%);
}

.table-name {
    font-size: 20px;
    font-weight: 900;
    margin-bottom: 4px;
    line-height: 1;
}

.restaurant-table span {
    font-size: 11px;
    font-weight: 800;
    text-transform: uppercase;
    color: var(--text-muted);
    background: var(--bg-input);
    padding: 4px 10px;
    border-radius: 999px;
}

/* Chairs now sit close to the table edge, not far outside the unit */
.chair {
    position: absolute;
    width: 34px;
    height: 34px;
    background: linear-gradient(180deg, #52525b 0%, #3f3f46 55%, #27272a 100%);
    border: 2px solid #63636f;
    border-radius: 10px;
    box-shadow: 0 7px 0 #18181b, 0 12px 16px rgba(0,0,0,0.38);
    z-index: 8;
}

.chair::before {
    content: '';
    position: absolute;
    top: 4px;
    left: 5px;
    right: 5px;
    height: 7px;
    background: linear-gradient(180deg, rgba(255,255,255,0.18), transparent);
    border-radius: 6px;
}

/* Square / round chair positions */
.chair-top-center { top: 26px; left: 50%; transform: translateX(-50%); }
.chair-bottom-center { bottom: 26px; left: 50%; transform: translateX(-50%) rotate(180deg); }
.chair-left-center { left: 26px; top: 50%; transform: translateY(-50%) rotate(-90deg); }
.chair-right-center { right: 26px; top: 50%; transform: translateY(-50%) rotate(90deg); }

.chair-top-left { top: 22px; left: 42px; transform: rotate(-12deg); }
.chair-top-right { top: 22px; right: 42px; transform: rotate(12deg); }
.chair-bottom-left { bottom: 22px; left: 42px; transform: rotate(192deg); }
.chair-bottom-right { bottom: 22px; right: 42px; transform: rotate(168deg); }

/* Long table positions */
.chair-long-top-1 { top: 28px; left: 23%; transform: translateX(-50%); }
.chair-long-top-2 { top: 28px; left: 41%; transform: translateX(-50%); }
.chair-long-top-3 { top: 28px; left: 59%; transform: translateX(-50%); }
.chair-long-top-4 { top: 28px; left: 77%; transform: translateX(-50%); }

.chair-long-bottom-1 { bottom: 28px; left: 23%; transform: translateX(-50%) rotate(180deg); }
.chair-long-bottom-2 { bottom: 28px; left: 41%; transform: translateX(-50%) rotate(180deg); }
.chair-long-bottom-3 { bottom: 28px; left: 59%; transform: translateX(-50%) rotate(180deg); }
.chair-long-bottom-4 { bottom: 28px; left: 77%; transform: translateX(-50%) rotate(180deg); }

.chair-long-left-1 { left: 28px; top: 23%; transform: translateY(-50%) rotate(-90deg); }
.chair-long-left-2 { left: 28px; top: 41%; transform: translateY(-50%) rotate(-90deg); }
.chair-long-left-3 { left: 28px; top: 59%; transform: translateY(-50%) rotate(-90deg); }
.chair-long-left-4 { left: 28px; top: 77%; transform: translateY(-50%) rotate(-90deg); }

.chair-long-right-1 { right: 28px; top: 23%; transform: translateY(-50%) rotate(90deg); }
.chair-long-right-2 { right: 28px; top: 41%; transform: translateY(-50%) rotate(90deg); }
.chair-long-right-3 { right: 28px; top: 59%; transform: translateY(-50%) rotate(90deg); }
.chair-long-right-4 { right: 28px; top: 77%; transform: translateY(-50%) rotate(90deg); }

/* Round table: place chairs around circle */
.table-unit.shape-round .chair-top-center { top: 18px; }
.table-unit.shape-round .chair-bottom-center { bottom: 18px; }
.table-unit.shape-round .chair-left-center { left: 18px; }
.table-unit.shape-round .chair-right-center { right: 18px; }
.table-unit.shape-round .chair-top-left { top: 28px; left: 32px; transform: rotate(-35deg); }
.table-unit.shape-round .chair-top-right { top: 28px; right: 32px; transform: rotate(35deg); }
.table-unit.shape-round .chair-bottom-left { bottom: 28px; left: 32px; transform: rotate(215deg); }
.table-unit.shape-round .chair-bottom-right { bottom: 28px; right: 32px; transform: rotate(145deg); }

.status-available .restaurant-table { border-color: #10b981; box-shadow: 0 0 18px rgba(16, 185, 129, 0.18); }
.status-reserved .restaurant-table { border-color: #f59e0b; box-shadow: 0 0 18px rgba(245, 158, 11, 0.18); }
.status-occupied .restaurant-table { border-color: #ef4444; box-shadow: 0 0 18px rgba(239, 68, 68, 0.18); }
.status-out_of_service .restaurant-table { border-color: #71717a; border-style: dashed; opacity: 0.65; }

.legend {
    display: flex;
    gap: 12px;
    margin-top: 24px;
    flex-wrap: wrap;
    padding-top: 20px;
    border-top: 1px solid var(--border);
    justify-content: center;
}

.legend-item {
    background: var(--bg-input);
    border: 1px solid var(--border);
    padding: 10px 16px;
    border-radius: 14px;
    font-size: 13px;
    font-weight: 700;
    color: var(--text-muted);
    display: flex;
    align-items: center;
    gap: 8px;
}

.legend-dot { width: 14px; height: 14px; border-radius: 6px; }

.empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 500px;
    color: var(--text-dim);
    font-weight: 800;
}

.scale-controls {
    position: absolute;
    bottom: 24px;
    right: 24px;
    z-index: 200;
    display: flex;
    gap: 8px;
    background: var(--bg-input);
    padding: 6px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.3);
    border: 1px solid var(--border);
}

.scale-btn {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: var(--bg-input);
    border: 1px solid var(--border);
    color: var(--text-muted);
    font-size: 20px;
    font-weight: 800;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
}

.scale-btn:hover {
    background: var(--primary);
    color: white;
}

@media (max-width: 768px) {
    .floor-page { padding: 14px; }
    .floor-card { padding: 14px; border-radius: 20px; }
    .floor-plan-wrapper { height: 520px; }
    .header-left h1 { font-size: 24px; }
}
</style>

<div class="floor-page">
    <div class="floor-header">
        <div class="header-left">
            <h1>Restaurant Floor Plan</h1>
            <p>Interactive layout with accurate table shapes and close chair placement</p>
        </div>
        <a href="{{ route('restaurant.tables.index') }}" class="back-btn">
            ← Back to Tables
        </a>
    </div>

    <div class="floor-card">
        <div class="floor-plan-wrapper" id="planWrapper">
            <div class="floor-plan" id="floorPlan">
                <div class="floor-label"><span>Live Layout</span></div>

                @forelse($tables as $table)
                    <?php
                        $capacity = (int) $table->capacity;

                        /*
                            Normalise database values so round tables do not fall back to square.
                            This supports: round, circle, circular, oval, rectangle, rectangular, etc.
                        */
                        $rawShape = strtolower(trim($table->table_shape ?? 'square'));

                        $shapeMap = [
                            'circle' => 'round',
                            'circular' => 'round',
                            'round_table' => 'round',
                            'rectangle' => 'horizontal',
                            'rectangular' => 'horizontal',
                            'long' => 'horizontal',
                            'long_table' => 'horizontal',
                        ];

                        $shape = $shapeMap[$rawShape] ?? $rawShape;

                        if (!in_array($shape, ['square', 'round', 'horizontal', 'vertical', 'banquet'])) {
                            $shape = 'square';
                        }

                        $statusClass = 'status-' . ($table->status ?? 'available');
                        $shapeClass = 'table-' . $shape;
                        $chairPositions = [];

                        if ($capacity > 0) {
                            if ($shape === 'square' || $shape === 'round') {
                                if ($capacity === 1) {
                                    $chairPositions = ['chair-top-center'];
                                } elseif ($capacity === 2) {
                                    $chairPositions = ['chair-top-center', 'chair-bottom-center'];
                                } elseif ($capacity === 3) {
                                    $chairPositions = ['chair-top-center', 'chair-left-center', 'chair-right-center'];
                                } elseif ($capacity === 4) {
                                    $chairPositions = ['chair-top-center', 'chair-right-center', 'chair-bottom-center', 'chair-left-center'];
                                } else {
                                    $chairPositions = [
                                        'chair-top-left', 'chair-top-right',
                                        'chair-right-center',
                                        'chair-bottom-right', 'chair-bottom-left',
                                        'chair-left-center'
                                    ];
                                }
                            } elseif ($shape === 'horizontal') {
                                if ($capacity === 1) {
                                    $chairPositions = ['chair-top-center'];
                                } elseif ($capacity === 2) {
                                    $chairPositions = ['chair-left-center', 'chair-right-center'];
                                } elseif ($capacity === 4) {
                                    $chairPositions = ['chair-long-top-1', 'chair-long-top-4', 'chair-long-bottom-1', 'chair-long-bottom-4'];
                                } else {
                                    $chairPositions = [
                                        'chair-long-top-1', 'chair-long-top-2', 'chair-long-top-3', 'chair-long-top-4',
                                        'chair-long-bottom-1', 'chair-long-bottom-2', 'chair-long-bottom-3', 'chair-long-bottom-4'
                                    ];
                                }
                            } elseif ($shape === 'vertical') {
                                if ($capacity === 1) {
                                    $chairPositions = ['chair-left-center'];
                                } elseif ($capacity === 2) {
                                    $chairPositions = ['chair-top-center', 'chair-bottom-center'];
                                } elseif ($capacity === 4) {
                                    $chairPositions = ['chair-long-left-1', 'chair-long-left-4', 'chair-long-right-1', 'chair-long-right-4'];
                                } else {
                                    $chairPositions = [
                                        'chair-long-left-1', 'chair-long-left-2', 'chair-long-left-3', 'chair-long-left-4',
                                        'chair-long-right-1', 'chair-long-right-2', 'chair-long-right-3', 'chair-long-right-4'
                                    ];
                                }
                            } elseif ($shape === 'banquet') {
                                $chairPositions = [
                                    'chair-long-top-1', 'chair-long-top-2', 'chair-long-top-3', 'chair-long-top-4',
                                    'chair-long-bottom-1', 'chair-long-bottom-2', 'chair-long-bottom-3', 'chair-long-bottom-4'
                                ];
                            }
                        }

                        $chairPositions = array_slice($chairPositions, 0, $capacity);
                    ?>

                    <div
                        class="table-unit {{ $statusClass }} shape-{{ $shape }}"
                        style="left: {{ 60 + ($table->position_x * 170) }}px; top: {{ 60 + ($table->position_y * 170) }}px;"
                        title="{{ $table->table_name }} - {{ ucfirst($table->status) }}"
                    >
                        <div class="table-visual">
                            @foreach($chairPositions as $pos)
                                <div class="chair {{ $pos }}"></div>
                            @endforeach

                            <div class="restaurant-table {{ $shapeClass }}">
                                <div class="table-name">{{ $table->table_name }}</div>
                                <span>{{ $capacity }} Pax</span>
                            </div>
                        </div>
                    </div>

                @empty
                    <div class="empty-state">
                        <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="#52525b" stroke-width="1.5">
                            <rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/>
                        </svg>
                        <p style="margin-top: 16px; font-size: 18px;">No tables configured</p>
                        <p style="font-size: 13px; color: var(--text-dim);">Add tables from the admin panel</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="scale-controls">
            <button class="scale-btn" onclick="scalePlan(0.85)">−</button>
            <button class="scale-btn" onclick="scalePlan(1.15)">+</button>
            <button class="scale-btn" onclick="fitToScreen()">⟲</button>
        </div>

        <div class="legend">
            <div class="legend-item"><span class="legend-dot" style="background:#10b981"></span> Available</div>
            <div class="legend-item"><span class="legend-dot" style="background:#f59e0b"></span> Reserved</div>
            <div class="legend-item"><span class="legend-dot" style="background:#ef4444"></span> Occupied</div>
            <div class="legend-item"><span class="legend-dot" style="background:#71717a; border: 2px dashed #52525b"></span> Out of Service</div>
            <div class="legend-item"><span class="legend-dot" style="background: linear-gradient(180deg, #52525b, #27272a)"></span> Chairs = Capacity</div>
        </div>
    </div>
</div>

<script>
let currentScale = 1;

document.addEventListener("DOMContentLoaded", function() {
    const plan = document.getElementById('floorPlan');
    let maxX = 0, maxY = 0;

    @foreach($tables as $table)
        maxX = Math.max(maxX, {{ 60 + ($table->position_x * 170) }} + 170);
        maxY = Math.max(maxY, {{ 60 + ($table->position_y * 170) }} + 170);
    @endforeach

    @if(count($tables) > 0)
        plan.style.minWidth = (maxX + 120) + 'px';
        plan.style.minHeight = (maxY + 120) + 'px';
    @endif

    setTimeout(fitToScreen, 150);
});

function fitToScreen() {
    const wrapper = document.getElementById('planWrapper');
    const plan = document.getElementById('floorPlan');

    const wrapperRect = wrapper.getBoundingClientRect();
    const scaleX = (wrapperRect.width - 80) / plan.scrollWidth;
    const scaleY = (wrapperRect.height - 80) / plan.scrollHeight;

    let targetScale = Math.min(scaleX, scaleY, 1);
    targetScale = Math.max(0.45, Math.min(1.5, targetScale));

    applyScale(targetScale);
}

function applyScale(scale) {
    currentScale = scale;
    document.getElementById('floorPlan').style.transform = `scale(${scale})`;
}

function scalePlan(factor) {
    let newScale = currentScale * factor;
    newScale = Math.max(0.35, Math.min(2.5, newScale));
    applyScale(newScale);
}
</script>

@endsection
