@extends('dashboard.admin.layout')

@section('title', 'Restaurant Floor Plan')
@section('page-title', 'Restaurant Floor Plan')

@section('content')
<section class="fp-page">

    <div class="fp-hero">
        <div>
            <p>MGM One / {{ $hotel->name }}</p>
            <h1>{{ $restaurant->name }} Floor Plan</h1>
            <span>Drag tables, walls, doors, bars and buffet objects around the floor.</span>
        </div>

        <a href="{{ route('admin.restaurants.tables.index', [$hotel, $restaurant]) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Back to Tables
        </a>
    </div>

    <div class="fp-toolbar">
        <div class="status-indicators">
            <div><span class="dot available"></span> Available</div>
            <div><span class="dot reserved"></span> Reserved</div>
            <div><span class="dot occupied"></span> Occupied</div>
            <div><span class="dot out_of_service"></span> Out of Service</div>
        </div>

        <div class="object-tools">
            @foreach([
                'wall' => 'Wall',
                'door' => 'Door',
                'window' => 'Window',
                'bar' => 'Bar',
                'buffet' => 'Buffet',
                'cashier' => 'Cashier',
                'toilet' => 'Toilet',
                'plant' => 'Plant',
                'sofa' => 'Sofa',
                'note' => 'Note',
            ] as $type => $label)
                <form method="POST" action="{{ route('admin.restaurants.floor-objects.store', [$hotel, $restaurant]) }}">
                    @csrf
                    <input type="hidden" name="type" value="{{ $type }}">
                    <button type="submit" class="object-btn">{{ $label }}</button>
                </form>
            @endforeach
        </div>

        <div class="scale-controls">
            <button type="button" id="zoomOut" class="btn-zoom"><i class="fas fa-search-minus"></i></button>
            <span id="zoomLabel">100%</span>
            <button type="button" id="zoomIn" class="btn-zoom"><i class="fas fa-search-plus"></i></button>
            <button type="button" id="zoomFit" class="btn-zoom-fit">Fit</button>
        </div>
    </div>

    <div class="floor-outer-frame">
        <div class="floor-wrapper" id="floorWrapper">
            <div class="floor-grid" id="floorGrid">

                @foreach($objects ?? [] as $object)
                    @php
                        $icons = [
                            'wall' => '▰',
                            'door' => '🚪',
                            'window' => '🪟',
                            'bar' => '🍸',
                            'buffet' => '🍽',
                            'cashier' => '💳',
                            'toilet' => '🚻',
                            'plant' => '🌿',
                            'sofa' => '🛋',
                            'note' => '📝',
                        ];
                    @endphp

                    <div class="floor-object object-{{ $object->type }}"
                         data-id="{{ $object->id }}"
                         data-kind="object"
                         data-update-url="{{ route('admin.restaurants.floor-objects.update-position', $object) }}"
                         style="
                            left: {{ $object->position_x }}px;
                            top: {{ $object->position_y }}px;
                            width: {{ $object->width }}px;
                            height: {{ $object->height }}px;
                            transform: rotate({{ $object->rotation }}deg);
                         ">
                        <div class="object-inner">
                            <strong>{{ $icons[$object->type] ?? '⬛' }}</strong>
                            <span>{{ $object->label ?? ucfirst($object->type) }}</span>
                        </div>

                        <form method="POST"
                              action="{{ route('admin.restaurants.floor-objects.destroy', $object) }}"
                              class="object-delete"
                              onsubmit="return confirm('Remove this object?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit">×</button>
                        </form>
                    </div>
                @endforeach

                @forelse($tables as $table)
                    <div class="floor-table status-{{ $table->status }} shape-{{ $table->table_shape }}"
                         data-id="{{ $table->id }}"
                         data-kind="table"
                         data-update-url="{{ route('admin.restaurants.tables.update-position', $table) }}"
                         style="left: {{ $table->position_x }}px; top: {{ $table->position_y }}px;">

                        <div class="table-shape">
                            <strong>{{ $table->table_name }}</strong>
                            <span>{{ $table->capacity }} pax</span>
                        </div>
                    </div>
                @empty
                    @if(empty($objects) || count($objects) === 0)
                        <div class="empty-floor">
                            <i class="fas fa-chair"></i>
                            <h2>No active tables or objects found</h2>
                            <p>Add tables or floor objects first to build the floor plan.</p>
                        </div>
                    @endif
                @endforelse

            </div>
        </div>
    </div>

</section>

<style>
.fp-page {
    width: 100%;
    max-width: 100%;
    overflow: hidden;
}

.fp-hero {
    background: radial-gradient(circle at 20% 20%, rgba(232,45,45,.28), transparent 35%), linear-gradient(135deg, #2a0606, #101010 70%);
    border: 2px solid var(--border);
    border-radius: 24px;
    padding: 28px;
    margin-bottom: 20px;
    display: flex;
    justify-content: space-between;
    gap: 18px;
    align-items: center;
    flex-wrap: wrap;
}

.fp-hero p {
    color: var(--primary);
    font-size: 12px;
    font-weight: 900;
    letter-spacing: 1.5px;
    text-transform: uppercase;
}

.fp-hero h1 {
    font-size: 34px;
    font-weight: 900;
    margin-top: 8px;
}

.fp-hero span {
    color: var(--text-muted);
    display: block;
    margin-top: 8px;
}

.fp-toolbar {
    background: var(--bg-card);
    border: 2px solid var(--border);
    border-radius: 18px;
    padding: 16px;
    margin-bottom: 20px;
    display: flex;
    flex-direction: column;
    gap: 16px;
    color: var(--text-muted);
    font-weight: 800;
}

.status-indicators,
.object-tools,
.scale-controls {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    align-items: center;
}

.object-tools {
    border-top: 1px solid var(--border);
    border-bottom: 1px solid var(--border);
    padding: 12px 0;
}

.dot {
    width: 13px;
    height: 13px;
    border-radius: 50%;
    display: inline-block;
    margin-right: 7px;
}

.dot.available { background: #22c55e; }
.dot.reserved { background: #fbbf24; }
.dot.occupied { background: #ef4444; }
.dot.out_of_service { background: #71717a; }

.object-btn,
.btn-zoom,
.btn-zoom-fit {
    background: #181818;
    border: 1px solid var(--border);
    color: white;
    padding: 8px 14px;
    border-radius: 10px;
    cursor: pointer;
    font-weight: 900;
    font-size: 12px;
    transition: all 0.2s ease;
}

.object-btn:hover,
.btn-zoom:hover,
.btn-zoom-fit:hover {
    background: rgba(232,45,45,.18);
    border-color: var(--primary);
}

#zoomLabel {
    min-width: 52px;
    text-align: center;
    color: white;
}

/* FIX: Ensures the transformation doesn't cause overflow on modern browser engines */
.floor-outer-frame {
    width: 100%;
    max-width: 100%;
    overflow: hidden;
    border: 2px solid var(--border);
    border-radius: 24px;
    background: #090909;
}

.floor-wrapper {
    width: 100%;
    height: calc(100vh - 380px);
    min-height: 500px;
    max-height: 850px;
    overflow: auto;
    position: relative;
}

/* FIX: Establish rigid coordinate scaling canvas anchoring */
.floor-grid {
    position: absolute;
    top: 0;
    left: 0;
    width: 1800px;
    height: 1200px;
    background-image: radial-gradient(rgba(255,255,255,.055) 1.5px, transparent 1.5px);
    background-size: 20px 20px;
    transform-origin: top left;
}

.floor-table,
.floor-object {
    position: absolute;
    cursor: grab;
    user-select: none;
    z-index: 5;
    touch-action: none; /* Prevents viewport jumping on touch panels */
}

.floor-table:active,
.floor-object:active {
    cursor: grabbing;
    z-index: 80;
}

.floor-table {
    width: 120px;
    height: 120px;
}

.table-shape {
    width: 100%;
    height: 100%;
    background: linear-gradient(180deg, #1f1f1f, #111);
    border: 3px solid #22c55e;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    gap: 6px;
    color: white;
    box-shadow: 0 15px 35px rgba(0,0,0,.45);
}

.table-shape strong {
    font-size: 20px;
    font-weight: 900;
}

.table-shape span {
    font-size: 11px;
    color: var(--text-muted);
    font-weight: 800;
}

.shape-round .table-shape { border-radius: 50%; }
.shape-square .table-shape { border-radius: 22px; }

.shape-horizontal { width: 170px; height: 95px; }
.shape-horizontal .table-shape { border-radius: 24px; }

.shape-vertical { width: 95px; height: 170px; }
.shape-vertical .table-shape { border-radius: 24px; }

.shape-banquet { width: 210px; height: 105px; }
.shape-banquet .table-shape { border-radius: 28px; }

.status-available .table-shape { border-color: #22c55e; }
.status-reserved .table-shape { border-color: #fbbf24; }
.status-occupied .table-shape { border-color: #ef4444; }
.status-out_of_service .table-shape {
    border-color: #71717a;
    border-style: dashed;
    opacity: .65;
}

.floor-object {
    border: 2px solid rgba(232,45,45,.5);
    background: rgba(24,24,24,.92);
    border-radius: 14px;
    box-shadow: 0 15px 35px rgba(0,0,0,.45);
}

.object-inner {
    width: 100%;
    height: 100%;
    display: flex;
    gap: 6px;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    color: white;
    text-align: center;
    padding: 8px;
}

.object-inner strong { font-size: 24px; }
.object-inner span { font-size: 11px; font-weight: 900; color: var(--text-muted); text-transform: uppercase; }

.object-wall { border-radius: 8px; background: linear-gradient(90deg, #3f3f46, #111); }
.object-door { border-color: #fbbf24; }
.object-window { border-color: #38bdf8; }
.object-bar, .object-buffet { border-color: #a855f7; }

.object-delete {
    position: absolute;
    right: -10px;
    top: -10px;
    z-index: 100;
}

.object-delete button {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    border: 1px solid rgba(239,68,68,.5);
    background: #7f1d1d;
    color: white;
    font-weight: 900;
    cursor: pointer;
}

.empty-floor {
    position: absolute;
    inset: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: var(--text-muted);
    text-align: center;
}

.empty-floor i {
    font-size: 54px;
    color: var(--primary);
    margin-bottom: 14px;
}

@media (max-width: 768px) {
    .fp-hero { padding: 20px; }
    .fp-hero h1 { font-size: 25px; }
    .floor-wrapper { height: 55vh; min-height: 400px; }
    
    .status-indicators,
    .object-tools,
    .scale-controls { width: 100%; }

    .object-tools form {
        flex: 1 1 calc(33.33% - 10px);
        display: flex;
    }
    
    .object-btn { width: 100%; text-align: center; }
}

@media (max-width: 480px) {
    .object-tools form { flex: 1 1 calc(50% - 10px); }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const wrapper = document.getElementById('floorWrapper');
    const floor = document.getElementById('floorGrid');
    const items = document.querySelectorAll('.floor-table, .floor-object');

    let currentScale = 1;

    function updateZoom(scale) {
        currentScale = Math.max(0.25, Math.min(2, scale));
        floor.style.transform = `scale(${currentScale})`;
        document.getElementById('zoomLabel').textContent = Math.round(currentScale * 100) + '%';
        
        // FIX: Readjust outer scroll constraints so overflow scroll mechanics work on smaller viewports
        const scaledWidth = 1800 * currentScale;
        const scaledHeight = 1200 * currentScale;
        
        // Ensure parent container handles overflow calculations properly
        if (scaledWidth < wrapper.clientWidth) {
            wrapper.style.overflowX = 'hidden';
        } else {
            wrapper.style.overflowX = 'auto';
        }
    }

    function fitToScreen() {
        const scaleNeeded = Math.min((wrapper.clientWidth - 10) / 1800, 1);
        updateZoom(scaleNeeded);
    }

    document.getElementById('zoomIn').addEventListener('click', () => updateZoom(currentScale + 0.1));
    document.getElementById('zoomOut').addEventListener('click', () => updateZoom(currentScale - 0.1));
    document.getElementById('zoomFit').addEventListener('click', fitToScreen);

    fitToScreen();
    
    // Smooth responsive re-calculations on window adjustment rules
    let resizeTimeout;
    window.addEventListener('resize', () => {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(fitToScreen, 150);
    });

    items.forEach(item => {
        let isDragging = false;
        let startX = 0;
        let startY = 0;
        let initialLeft = 0;
        let initialTop = 0;

        item.addEventListener('mousedown', startDrag);
        item.addEventListener('touchstart', startDrag, { passive: false });

        function startDrag(e) {
            if (e.target.closest('button') || e.target.closest('form')) {
                return;
            }

            e.preventDefault();
            isDragging = true;

            const event = e.touches ? e.touches[0] : e;

            startX = event.clientX;
            startY = event.clientY;

            initialLeft = parseInt(item.style.left) || 0;
            initialTop = parseInt(item.style.top) || 0;

            document.addEventListener('mousemove', drag, { passive: false });
            document.addEventListener('mouseup', stopDrag);
            document.addEventListener('touchmove', drag, { passive: false });
            document.addEventListener('touchend', stopDrag);
        }

        function drag(e) {
            if (!isDragging) return;
            e.preventDefault();

            const event = e.touches ? e.touches[0] : e;

            // FIX: Adjust delta scaling factors against workspace container values
            let deltaX = (event.clientX - startX) / currentScale;
            let deltaY = (event.clientY - startY) / currentScale;

            let x = initialLeft + deltaX;
            let y = initialTop + deltaY;

            // Grid snapping calculations
            x = Math.max(0, Math.round(x / 20) * 20);
            y = Math.max(0, Math.round(y / 20) * 20);

            // FIX: Read width/height attributes explicitly from CSS definitions 
            // instead of using offsetWidth, which calculates wrong dimensions under active transform scaling.
            const itemWidth = parseFloat(item.style.width) || item.offsetWidth || 120;
            const itemHeight = parseFloat(item.style.height) || item.offsetHeight || 120;

            const maxX = 1800 - itemWidth;
            const maxY = 1200 - itemHeight;

            item.style.left = Math.min(Math.max(0, x), maxX) + 'px';
            item.style.top = Math.min(Math.max(0, y), maxY) + 'px';
        }

        function stopDrag() {
            if (!isDragging) return;
            isDragging = false;

            document.removeEventListener('mousemove', drag);
            document.removeEventListener('mouseup', stopDrag);
            document.removeEventListener('touchmove', drag);
            document.removeEventListener('touchend', stopDrag);

            savePosition(item);
        }
    });

    function savePosition(item) {
        const url = item.dataset.updateUrl;
        if (!url) return;

        fetch(url, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                position_x: parseInt(item.style.left) || 0,
                position_y: parseInt(item.style.top) || 0
            })
        })
        .then(res => res.json())
        .then(data => {
            if (!data.success) console.error('Position save failed.');
        })
        .catch(() => console.error('Network error saving position.'));
    }
});
</script>
@endsection