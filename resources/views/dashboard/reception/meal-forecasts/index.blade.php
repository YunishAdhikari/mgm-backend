@extends('dashboard.reception.layout')

@section('content')

<style>
:root {
    --bg: #09090b;
    --card: #18181b;
    --input: #27272a;
    --border: #3f3f46;
    --red: #dc2626;
    --red-dark: #991b1b;
    --text: #fafafa;
    --muted: #a1a1aa;
    --green: #22c55e;
}

/* Base Wrapper Structuring */
.index-forecast-wrapper {
    width: 100%;
    box-sizing: border-box;
}

.forecast-wrapper {
    padding: 30px;
    background: var(--bg);
    min-height: 100vh;
    color: var(--text);
}

/* Workspace Banner Styling */
.forecast-header {
    background: linear-gradient(135deg, #991b1b, #dc2626);
    border-radius: 16px;
    padding: 30px;
    margin-bottom: 25px;
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
}

.forecast-header h2 {
    margin: 0;
    font-size: 28px;
    font-weight: 800;
    letter-spacing: -0.5px;
}

.forecast-header p {
    margin: 6px 0 0;
    color: rgba(255, 255, 255, 0.85);
    font-size: 14px;
}

.header-actions {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

/* System Button Architecture */
.btn-dark-red,
.btn-soft {
    color: white;
    padding: 12px 20px;
    border-radius: 10px;
    font-weight: 700;
    text-decoration: none;
    border: 1px solid rgba(255, 255, 255, 0.15);
    cursor: pointer;
    font-size: 14px;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.btn-dark-red { background: #09090b; }
.btn-dark-red:hover { background: #18181b; }
.btn-soft { background: rgba(255, 255, 255, 0.1); }
.btn-soft:hover { background: rgba(255, 255, 255, 0.18); }
.btn-main { background: linear-gradient(135deg, #dc2626, #991b1b); border: none; }
.btn-main:hover { opacity: 0.95; }

/* Filter & Data Base Cards */
.forecast-card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 24px;
    margin-bottom: 22px;
}

.alert-success-custom {
    background: rgba(34, 197, 94, 0.1);
    border: 1px solid rgba(34, 197, 94, 0.3);
    color: #4ade80;
    border-radius: 10px;
    padding: 14px 18px;
    margin-bottom: 20px;
    font-weight: 600;
    font-size: 14px;
}

/* Filter Component Controls */
.filter-label {
    color: var(--text);
    font-size: 13px;
    font-weight: 700;
    margin-bottom: 8px;
    display: block;
}

.filter-input {
    width: 100%;
    height: 46px;
    background: var(--input);
    border: 1px solid var(--border);
    color: var(--text);
    border-radius: 10px;
    padding: 0 15px;
    box-sizing: border-box;
    font-size: 14px;
}

.quick-filter-row {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 20px;
    padding-top: 15px;
    border-top: 1px solid rgba(255, 255, 255, 0.05);
}

.quick-filter-btn {
    background: var(--input);
    color: var(--text);
    border: 1px solid var(--border);
    border-radius: 20px;
    padding: 8px 16px;
    text-decoration: none;
    font-size: 13px;
    font-weight: 600;
    transition: background 0.2s;
}

.quick-filter-btn:hover {
    background: var(--border);
}

/* High-Contrast Unified Report Table Architecture */
.report-table-container {
    width: 100%;
    overflow-x: auto;
}

.report-workspace-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
    text-align: left;
}

.report-workspace-table th {
    background: var(--input);
    color: var(--text) !important;
    padding: 14px 12px;
    font-weight: 700;
    text-transform: uppercase;
    font-size: 12px;
    letter-spacing: 0.5px;
    border: 1px solid var(--border);
}

.report-workspace-table td {
    padding: 14px 12px;
    border: 1px solid var(--border);
    color: #e4e4e7;
    vertical-align: middle;
}

.report-workspace-table tbody tr:nth-child(even) {
    background: rgba(255, 255, 255, 0.01);
}

.report-workspace-table tbody tr:hover {
    background: rgba(255, 255, 255, 0.03);
}

/* Custom Typography and Badges */
.day-text {
    font-weight: 700;
    color: #f87171;
}

.pax-count-bold {
    font-weight: 700;
    font-size: 15px;
    color: var(--text);
}

.pax-fit-highlight {
    font-weight: 700;
    font-size: 15px;
    color: #4ade80;
}

.group-list {
    color: #d4d4d8;
    font-size: 13px;
    line-height: 1.5;
}

.no-group {
    color: #71717a;
    font-style: italic;
}

.notes-text {
    color: var(--muted);
    font-size: 13px;
    line-height: 1.4;
    max-width: 180px;
}

/* Action Control Array Elements */
.action-btn-group {
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
}

.action-btn {
    padding: 8px 12px;
    border-radius: 8px;
    color: white;
    text-decoration: none;
    font-size: 12px;
    font-weight: 700;
    border: none;
    cursor: pointer;
    transition: opacity 0.15s;
}

.action-btn:hover { opacity: 0.9; }
.action-add { background: var(--red); }
.action-edit { background: var(--input); border: 1px solid var(--border); }

.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: var(--muted);
}

.empty-state h4 {
    color: var(--text);
    font-weight: 700;
    margin: 0 0 8px 0;
}

/* Modal Engine Configurations */
.report-modal {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.8);
    z-index: 9999;
    align-items: center;
    justify-content: center;
    padding: 20px;
    backdrop-filter: blur(4px);
}

.report-modal.show { display: flex; }

.report-modal-box {
    width: 100%;
    max-width: 480px;
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 28px;
    box-sizing: border-box;
}

.report-modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 22px;
}

.report-modal-header h3 {
    margin: 0;
    color: var(--text);
    font-size: 20px;
    font-weight: 800;
}

.report-modal-close {
    width: 32px;
    height: 32px;
    border: none;
    border-radius: 50%;
    background: var(--input);
    color: var(--text);
    font-size: 20px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
}

.report-form-group {
    margin-bottom: 18px;
}

.report-form-group label {
    color: var(--text);
    font-size: 13px;
    font-weight: 700;
    margin-bottom: 8px;
    display: block;
}

.report-form-group input,
.report-form-group textarea {
    width: 100%;
    background: var(--input);
    border: 1px solid var(--border);
    color: var(--text);
    border-radius: 10px;
    padding: 12px 14px;
    box-sizing: border-box;
    font-size: 14px;
}

.report-form-group input { height: 46px; }

.report-modal-actions {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    margin-top: 24px;
}

.modal-cancel-btn,
.modal-generate-btn {
    padding: 12px 20px;
    border-radius: 10px;
    font-weight: 700;
    cursor: pointer;
    font-size: 14px;
}

.modal-cancel-btn {
    background: var(--input);
    color: var(--text);
    border: 1px solid var(--border);
}

.modal-generate-btn {
    background: linear-gradient(135deg, #dc2626, #991b1b);
    color: white;
    border: none;
}

/* Responsiveness Strategies */
@media(max-width: 992px) {
    .report-workspace-table th:nth-child(9),
    .report-workspace-table td:nth-child(9) { display: none; } /* Dynamic clipping of notes column on small tables */
}

@media(max-width: 768px) {
    .forecast-wrapper { padding: 15px; }
    .forecast-header { flex-direction: column; align-items: flex-start; padding: 20px; }
    .header-actions { width: 100%; }
    .btn-dark-red, .btn-soft { width: 100%; }
    .report-modal-actions { flex-direction: column; }
    .modal-cancel-btn, .modal-generate-btn { width: 100%; }
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
                            <button type="submit" class="btn-soft btn-main">Filter Workspace</button>
                            <a href="{{ route('reception.meal-forecasts.index') }}" class="btn-soft">Reset Parameters</a>
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
                <div class="report-table-container">
                    <table class="report-workspace-table">
                        <thead>
                            <tr>
                                <th style="width: 10%;">Date</th>
                                <th style="width: 10%;">Day</th>
                                <th style="width: 9%;">B/F Total</th>
                                <th style="width: 20%;">B/F Group Allocation</th>
                                <th style="width: 9%;">B/F FIT</th>
                                <th style="width: 9%;">Dinner</th>
                                <th style="width: 20%;">Dinner Group Allocation</th>
                                <th style="width: 9%;">Dinner FIT</th>
                                <th style="width: 14%;">Notes</th>
                                <th style="width: 10%;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($forecasts as $forecast)
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

                                <tr>
                                    <td><strong>{{ $forecast->forecast_date->format('d/m/Y') }}</strong></td>
                                    <td><span class="day-text">{{ $forecast->forecast_date->format('l') }}</span></td>
                                    <td><span class="pax-count-bold">{{ $forecast->total_breakfast }}</span></td>
                                    <td>
                                        @if($bfGroups)
                                            <div class="group-list">{{ $bfGroups }}</div>
                                        @else
                                            <span class="no-group">No Group</span>
                                        @endif
                                    </td>
                                    <td><span class="pax-fit-highlight">{{ $bfFit }}</span></td>
                                    <td><span class="pax-count-bold">{{ $forecast->total_dinner }}</span></td>
                                    <td>
                                        @if($dnGroups)
                                            <div class="group-list">{{ $dnGroups }}</div>
                                        @else
                                            <span class="no-group">No Group</span>
                                        @endif
                                    </td>
                                    <td><span class="pax-fit-highlight">{{ $dnFit }}</span></td>
                                    <td><div class="notes-text">{{ $forecast->notes ?: '-' }}</div></td>
                                    <td>
                                        <div class="action-btn-group">
                                            <a href="{{ route('reception.meal-forecasts.groups.create', $forecast->id) }}" class="action-btn action-add">
                                                + Group
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
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <h4>No forecast added yet</h4>
                    <p>Create the first daily dinner and breakfast forecast parameters above.</p>
                </div>
            @endif
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