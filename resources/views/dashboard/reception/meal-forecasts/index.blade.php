@extends('dashboard.reception.layout')

@section('content')

<style>
:root{
    --bg:#09090b;
    --card:#18181b;
    --input:#27272a;
    --border:#3f3f46;
    --red:#dc2626;
    --red-dark:#991b1b;
    --text:#fafafa;
    --muted:#a1a1aa;
    --green:#22c55e;
}

.index-forecast-wrapper{width:100%;box-sizing:border-box;}
.forecast-wrapper{padding:30px;}

.forecast-header{
    background:linear-gradient(135deg,#991b1b,#dc2626);
    border-radius:22px;
    padding:30px;
    margin-bottom:25px;
    color:white;
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:20px;
}

.forecast-header h2{margin:0;font-size:32px;font-weight:900;}
.forecast-header p{margin:8px 0 0;color:rgba(255,255,255,.8);}

.header-actions{display:flex;gap:12px;flex-wrap:wrap;}

.btn-dark-red,
.btn-soft{
    color:white;
    padding:13px 20px;
    border-radius:14px;
    font-weight:800;
    text-decoration:none;
    border:1px solid rgba(255,255,255,.2);
    cursor:pointer;
}

.btn-dark-red{background:#09090b;}
.btn-soft{background:rgba(255,255,255,.12);}
.btn-main{background:linear-gradient(135deg,#dc2626,#991b1b);border:none;}

.forecast-card{
    background:var(--card);
    border:1px solid var(--border);
    border-radius:22px;
    padding:24px;
    margin-bottom:22px;
}

.alert-success-custom{
    background:rgba(34,197,94,.15);
    border:1px solid rgba(34,197,94,.35);
    color:#22c55e;
    border-radius:14px;
    padding:14px 18px;
    margin-bottom:20px;
    font-weight:700;
}

.filter-label{
    color:#a1a1aa;
    font-size:13px;
    font-weight:800;
    margin-bottom:8px;
    display:block;
}

.filter-input{
    width:100%;
    height:50px;
    background:#27272a;
    border:1px solid #3f3f46;
    color:#fafafa;
    border-radius:14px;
    padding:0 15px;
}

.quick-filter-row{display:flex;flex-wrap:wrap;gap:10px;margin-top:16px;}

.quick-filter-btn{
    background:#27272a;
    color:#fafafa;
    border:1px solid #3f3f46;
    border-radius:999px;
    padding:9px 14px;
    text-decoration:none;
    font-size:13px;
    font-weight:800;
}

.forecast-grid-header,
.forecast-grid-row{
    display:grid;
    grid-template-columns:1.1fr .9fr .8fr .9fr .8fr .8fr .9fr .8fr 1.3fr 1.2fr;
    gap:10px;
    align-items:center;
}

.forecast-grid-header{
    background:#27272a;
    border-radius:14px;
    padding:16px;
}

.forecast-grid-header div{
    color:#fafafa;
    font-size:12px;
    text-transform:uppercase;
    font-weight:800;
}

.forecast-grid-row{
    background:#1f1f23;
    border:1px solid var(--border);
    border-radius:14px;
    padding:16px;
    margin-top:12px;
}

.forecast-grid-cell{
    color:#fafafa;
    font-size:14px;
    word-break:break-word;
}

.day-pill,
.total-pill,
.fit-pill{
    display:inline-block;
    border-radius:10px;
    font-weight:900;
    padding:8px 12px;
    text-align:center;
}

.day-pill{
    background:rgba(220,38,38,.15);
    color:#ef4444;
    border-radius:999px;
    font-size:12px;
}

.total-pill{background:rgba(255,255,255,.08);color:#fff;}
.fit-pill{background:rgba(34,197,94,.13);color:#22c55e;}

.group-list{color:#d4d4d8;font-size:13px;line-height:1.6;}
.no-group{color:#71717a;font-style:italic;}
.notes-text{color:#a1a1aa;font-size:13px;}

.action-btn{
    display:inline-block;
    padding:9px 12px;
    border-radius:10px;
    color:white;
    text-decoration:none;
    font-size:12px;
    font-weight:900;
    margin:3px;
}

.action-add{background:#dc2626;}
.action-edit{background:#27272a;border:1px solid #3f3f46;}

.empty-state{text-align:center;padding:60px 20px;color:#a1a1aa;}
.empty-state h4{color:#fafafa;font-weight:800;}

.report-modal{
    display:none;
    position:fixed;
    inset:0;
    background:rgba(0,0,0,.78);
    z-index:9999;
    align-items:center;
    justify-content:center;
    padding:20px;
}

.report-modal.show{display:flex;}

.report-modal-box{
    width:100%;
    max-width:470px;
    background:#18181b;
    border:1px solid #3f3f46;
    border-radius:24px;
    padding:24px;
}

.report-modal-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:22px;
}

.report-modal-header h3{
    margin:0;
    color:#fafafa;
    font-size:22px;
    font-weight:900;
}

.report-modal-close{
    width:38px;
    height:38px;
    border:none;
    border-radius:50%;
    background:#27272a;
    color:#fafafa;
    font-size:25px;
    cursor:pointer;
}

.report-form-group{margin-bottom:16px;}

.report-form-group label{
    color:#a1a1aa;
    font-size:13px;
    font-weight:800;
    margin-bottom:8px;
    display:block;
}

.report-form-group input,
.report-form-group textarea{
    width:100%;
    background:#27272a;
    border:1px solid #3f3f46;
    color:#fafafa;
    border-radius:14px;
    padding:14px 15px;
}

.report-form-group input{height:50px;}

.report-modal-actions{
    display:flex;
    justify-content:flex-end;
    gap:12px;
    margin-top:22px;
}

.modal-cancel-btn,
.modal-generate-btn{
    padding:12px 16px;
    border-radius:12px;
    font-weight:900;
    cursor:pointer;
}

.modal-cancel-btn{
    background:#27272a;
    color:#fafafa;
    border:1px solid #3f3f46;
}

.modal-generate-btn{
    background:linear-gradient(135deg,#dc2626,#991b1b);
    color:white;
    border:none;
}

@media(max-width:1200px){
    .forecast-grid-header{display:none;}
    .forecast-grid-row{grid-template-columns:repeat(3,1fr);}
}

@media(max-width:768px){
    .forecast-wrapper{padding:15px;}
    .forecast-header{flex-direction:column;align-items:flex-start;}
    .header-actions{width:100%;}
    .btn-dark-red,.btn-soft,.btn-main{width:100%;text-align:center;}
    .forecast-grid-row{grid-template-columns:repeat(2,1fr);}
    .report-modal-actions{flex-direction:column;}
    .modal-cancel-btn,.modal-generate-btn{width:100%;}
}
</style>

<div class="index-forecast-wrapper">
    <div class="forecast-wrapper">

        <div class="forecast-header">
            <div>
                <h2>Dinner & Breakfast Forecast</h2>
                <p>Daily meal forecast report for Reception, F&B and Kitchen.</p>
            </div>

            <div class="header-actions">
                <button type="button" class="btn-soft" onclick="openReportModal()">
                    Generate Report
                </button>

                <button type="button" class="btn-dark-red" onclick="openDailyForecastModal()">
                    + Add Daily Forecast
                </button>
            </div>
        </div>

        <div class="forecast-card">
            <form method="GET" action="{{ route('reception.meal-forecasts.index') }}">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="filter-label">From Date</label>
                        <input type="date" name="from_date" value="{{ request('from_date') }}" class="filter-input">
                    </div>

                    <div class="col-md-4">
                        <label class="filter-label">To Date</label>
                        <input type="date" name="to_date" value="{{ request('to_date') }}" class="filter-input">
                    </div>

                    <div class="col-md-4">
                        <div class="header-actions">
                            <button type="submit" class="btn-soft btn-main">Filter</button>
                            <a href="{{ route('reception.meal-forecasts.index') }}" class="btn-soft">Reset</a>
                        </div>
                    </div>
                </div>

                <div class="quick-filter-row">
                    <a class="quick-filter-btn" href="{{ route('reception.meal-forecasts.index', ['from_date'=>now()->format('Y-m-d'),'to_date'=>now()->format('Y-m-d')]) }}">Today</a>
                    <a class="quick-filter-btn" href="{{ route('reception.meal-forecasts.index', ['from_date'=>now()->addDay()->format('Y-m-d'),'to_date'=>now()->addDay()->format('Y-m-d')]) }}">Tomorrow</a>
                    <a class="quick-filter-btn" href="{{ route('reception.meal-forecasts.index', ['from_date'=>now()->format('Y-m-d'),'to_date'=>now()->addDays(7)->format('Y-m-d')]) }}">Next 7 Days</a>
                    <a class="quick-filter-btn" href="{{ route('reception.meal-forecasts.index', ['from_date'=>now()->startOfMonth()->format('Y-m-d'),'to_date'=>now()->endOfMonth()->format('Y-m-d')]) }}">This Month</a>
                </div>
            </form>
        </div>

        @if(session('success'))
            <div class="alert-success-custom">{{ session('success') }}</div>
        @endif

        <div class="forecast-card">
            @if($forecasts->isNotEmpty())
                <div class="forecast-grid-header">
                    <div>Date</div>
                    <div>Day</div>
                    <div>B/F Total</div>
                    <div>B/F Group</div>
                    <div>B/F FIT</div>
                    <div>Dinner</div>
                    <div>Dinner Group</div>
                    <div>Dinner FIT</div>
                    <div>Notes</div>
                    <div>Actions</div>
                </div>
            @endif

            @forelse($forecasts as $forecast)
                @php
                    $forecastDate = $forecast->forecast_date;

                    $bfGroupTotal = 0;
                    $dnGroupTotal = 0;
                    $bfGroupList = [];
                    $dnGroupList = [];

                    foreach ($forecast->groups as $groupStay) {
                        $checkIn = $groupStay->check_in_date;
                        $checkOut = $groupStay->check_out_date;
                        $pax = $groupStay->pax;
                        $package = $groupStay->package_type;
                        $name = optional($groupStay->forecastGroup)->name ?? 'Unknown Group';

                        $hasBreakfast = false;
                        $hasDinner = false;

                        if (($package === 'bb' || $package === 'dbb')
                            && $forecastDate->gt($checkIn)
                            && $forecastDate->lte($checkOut)) {
                            $hasBreakfast = true;
                        }

                        if (($package === 'dinner_only' || $package === 'dbb')
                            && $forecastDate->gte($checkIn)
                            && $forecastDate->lt($checkOut)) {
                            $hasDinner = true;
                        }

                        if ($hasBreakfast) {
                            $bfGroupTotal += $pax;
                            $bfGroupList[] = $name . ' (' . $pax . ')';
                        }

                        if ($hasDinner) {
                            $dnGroupTotal += $pax;
                            $dnGroupList[] = $name . ' (' . $pax . ')';
                        }
                    }

                    $bfFit = $forecast->total_breakfast - $bfGroupTotal;
                    $dnFit = $forecast->total_dinner - $dnGroupTotal;

                    $bfGroups = implode(', ', $bfGroupList);
                    $dnGroups = implode(', ', $dnGroupList);
                @endphp

                <div class="forecast-grid-row">
                    <div class="forecast-grid-cell">{{ $forecast->forecast_date->format('d/m/Y') }}</div>
                    <div class="forecast-grid-cell"><span class="day-pill">{{ $forecast->forecast_date->format('l') }}</span></div>
                    <div class="forecast-grid-cell"><span class="total-pill">{{ $forecast->total_breakfast }}</span></div>

                    <div class="forecast-grid-cell">
                        @if($bfGroups)
                            <div class="group-list">{{ $bfGroups }}</div>
                        @else
                            <span class="no-group">No Group</span>
                        @endif
                    </div>

                    <div class="forecast-grid-cell"><span class="fit-pill">{{ $bfFit }}</span></div>
                    <div class="forecast-grid-cell"><span class="total-pill">{{ $forecast->total_dinner }}</span></div>

                    <div class="forecast-grid-cell">
                        @if($dnGroups)
                            <div class="group-list">{{ $dnGroups }}</div>
                        @else
                            <span class="no-group">No Group</span>
                        @endif
                    </div>

                    <div class="forecast-grid-cell"><span class="fit-pill">{{ $dnFit }}</span></div>
                    <div class="forecast-grid-cell"><div class="notes-text">{{ $forecast->notes ?: '-' }}</div></div>

                    <div class="forecast-grid-cell">
                        <a href="{{ route('reception.meal-forecasts.groups.create', $forecast->id) }}" class="action-btn action-add">
                            Add Group
                        </a>

                        <button type="button"
                                class="action-btn action-edit"
                                onclick="openDailyForecastModal(
                                    '{{ $forecast->forecast_date->format('Y-m-d') }}',
                                    '{{ $forecast->total_breakfast }}',
                                    '{{ $forecast->total_dinner }}',
                                    @js($forecast->notes)
                                )">
                            Edit
                        </button>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <h4>No forecast added yet</h4>
                    <p>Create the first daily dinner and breakfast forecast.</p>
                </div>
            @endforelse
        </div>
    </div>

    <div id="dailyForecastModal" class="report-modal">
        <div class="report-modal-box">
            <div class="report-modal-header">
                <h3 id="dailyModalTitle">Add Daily Forecast</h3>
                <button type="button" class="report-modal-close" onclick="closeDailyForecastModal()">×</button>
            </div>

            <form action="{{ route('reception.meal-forecasts.store-daily-total') }}" method="POST">
                @csrf

                <div class="report-form-group">
                    <label>Forecast Date</label>
                    <input type="date" id="daily_forecast_date" name="forecast_date" required>
                </div>

                <div class="report-form-group">
                    <label>Total Breakfast Pax</label>
                    <input type="number" id="daily_total_breakfast" name="total_breakfast" min="0" value="0" required>
                </div>

                <div class="report-form-group">
                    <label>Total Dinner Pax</label>
                    <input type="number" id="daily_total_dinner" name="total_dinner" min="0" value="0" required>
                </div>

                <div class="report-form-group">
                    <label>Notes</label>
                    <textarea id="daily_notes" name="notes" rows="3"></textarea>
                </div>

                <div class="report-modal-actions">
                    <button type="button" onclick="closeDailyForecastModal()" class="modal-cancel-btn">Cancel</button>
                    <button type="submit" class="modal-generate-btn">Save Forecast</button>
                </div>
            </form>
        </div>
    </div>

    <div id="reportModal" class="report-modal">
        <div class="report-modal-box">
            <div class="report-modal-header">
                <h3>Generate Forecast Report</h3>
                <button type="button" class="report-modal-close" onclick="closeReportModal()">×</button>
            </div>

            <form action="{{ route('reception.meal-forecasts.report') }}" method="GET">
                <div class="report-form-group">
                    <label>From Date</label>
                    <input type="date" name="from_date" required>
                </div>

                <div class="report-form-group">
                    <label>To Date</label>
                    <input type="date" name="to_date" required>
                </div>

                <div class="report-modal-actions">
                    <button type="button" onclick="closeReportModal()" class="modal-cancel-btn">Cancel</button>
                    <button type="submit" class="modal-generate-btn">Generate Report</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openDailyForecastModal(date = '', breakfast = 0, dinner = 0, notes = '') {
    document.getElementById('dailyModalTitle').innerText = date ? 'Edit Daily Forecast' : 'Add Daily Forecast';
    document.getElementById('daily_forecast_date').value = date;
    document.getElementById('daily_total_breakfast').value = breakfast;
    document.getElementById('daily_total_dinner').value = dinner;
    document.getElementById('daily_notes').value = notes ?? '';
    document.getElementById('dailyForecastModal').classList.add('show');
}

function closeDailyForecastModal(){
    document.getElementById('dailyForecastModal').classList.remove('show');
}

function openReportModal(){
    document.getElementById('reportModal').classList.add('show');
}

function closeReportModal(){
    document.getElementById('reportModal').classList.remove('show');
}

document.addEventListener('keydown', function(e){
    if(e.key === 'Escape'){
        closeDailyForecastModal();
        closeReportModal();
    }
});

document.getElementById('dailyForecastModal').addEventListener('click', function(e){
    if(e.target === this){ closeDailyForecastModal(); }
});

document.getElementById('reportModal').addEventListener('click', function(e){
    if(e.target === this){ closeReportModal(); }
});
</script>

@endsection