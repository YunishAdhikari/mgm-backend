@extends('dashboard.reception.layout')

@section('content')

<style>
:root{
    --card:#18181b;
    --card2:#1f1f23;
    --input:#27272a;
    --border:#3f3f46;
    --red:#dc2626;
    --red-dark:#991b1b;
    --text:#fafafa;
    --muted:#a1a1aa;
    --green:#22c55e;
}

.forecast-wrapper{
    padding:30px;
    color:var(--text);
}

.forecast-header{
    background:linear-gradient(135deg,#991b1b,#dc2626);
    border-radius:22px;
    padding:30px;
    margin-bottom:24px;
    color:white;
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:20px;
}

.forecast-header h2{
    margin:0;
    font-size:32px;
    font-weight:900;
}

.forecast-header p{
    margin:8px 0 0;
    color:rgba(255,255,255,.82);
}

.back-btn{
    background:#09090b;
    color:white;
    padding:13px 20px;
    border-radius:14px;
    font-weight:800;
    text-decoration:none;
    border:1px solid rgba(255,255,255,.2);
}

.back-btn:hover{
    color:white;
    background:#18181b;
}

.forecast-card{
    background:var(--card);
    border:1px solid var(--border);
    border-radius:22px;
    padding:24px;
    margin-bottom:22px;
}

.card-title-custom{
    color:var(--text);
    font-size:20px;
    font-weight:900;
    margin-bottom:18px;
}

.form-label-custom{
    color:var(--muted);
    font-size:13px;
    font-weight:800;
    margin-bottom:8px;
    display:block;
}

.form-control-custom{
    width:100%;
    height:48px;
    background:var(--input);
    border:1px solid var(--border);
    color:var(--text);
    border-radius:14px;
    padding:0 14px;
}

.form-control-custom:focus{
    outline:none;
    border-color:var(--red);
    box-shadow:0 0 0 4px rgba(220,38,38,.15);
}

.info-grid{
    display:grid;
    grid-template-columns:repeat(4, 1fr);
    gap:14px;
}

.info-card{
    background:var(--card2);
    border:1px solid var(--border);
    border-radius:18px;
    padding:18px;
}

.info-card span{
    display:block;
    color:var(--muted);
    font-size:13px;
    font-weight:800;
}

.info-card strong{
    display:block;
    color:var(--text);
    font-size:26px;
    margin-top:8px;
}

.info-card.green strong{
    color:var(--green);
}

.error-box{
    background:rgba(220,38,38,.12);
    border:1px solid rgba(220,38,38,.35);
    color:#fecaca;
    border-radius:14px;
    padding:14px 18px;
    margin-bottom:20px;
}

.success-box{
    background:rgba(34,197,94,.15);
    border:1px solid rgba(34,197,94,.35);
    color:#22c55e;
    border-radius:14px;
    padding:14px 18px;
    margin-bottom:20px;
    font-weight:800;
}

.add-btn{
    height:48px;
    width:100%;
    border:none;
    border-radius:14px;
    background:linear-gradient(135deg,var(--red),var(--red-dark));
    color:white;
    font-weight:900;
}

.preview-box{
    background:#09090b;
    border:1px dashed var(--border);
    border-radius:16px;
    padding:14px;
    color:#d4d4d8;
    font-size:13px;
    line-height:1.7;
}

.table-wrap{
    overflow-x:auto;
}

.group-table{
    width:100%;
    border-collapse:separate;
    border-spacing:0 10px;
}

.group-table thead th{
    background:#27272a;
    color:#fafafa;
    padding:14px;
    font-size:12px;
    text-transform:uppercase;
}

.group-table tbody tr{
    background:#1f1f23;
}

.group-table tbody td{
    padding:14px;
    color:#fafafa;
    border-top:1px solid var(--border);
    border-bottom:1px solid var(--border);
    vertical-align:middle;
}

.group-table tbody td:first-child{
    border-left:1px solid var(--border);
    border-radius:14px 0 0 14px;
    font-weight:900;
}

.group-table tbody td:last-child{
    border-right:1px solid var(--border);
    border-radius:0 14px 14px 0;
}

.package-pill{
    background:rgba(220,38,38,.15);
    color:#ef4444;
    padding:7px 12px;
    border-radius:999px;
    font-size:12px;
    font-weight:900;
}

.delete-btn{
    background:#7f1d1d;
    border:none;
    color:white;
    padding:9px 14px;
    border-radius:10px;
    font-weight:800;
}

.empty-row{
    text-align:center;
    color:var(--muted)!important;
    padding:30px!important;
}

@media(max-width:992px){
    .info-grid{
        grid-template-columns:repeat(2,1fr);
    }
}

@media(max-width:768px){
    .forecast-wrapper{
        padding:15px;
    }

    .forecast-header{
        flex-direction:column;
        align-items:flex-start;
    }

    .forecast-header h2{
        font-size:26px;
    }

    .back-btn{
        width:100%;
        text-align:center;
    }

    .info-grid{
        grid-template-columns:1fr;
    }

    .group-table{
        min-width:850px;
    }
}
</style>

@php
    $forecastDate = $mealForecast->forecast_date;

    $groupBreakfast = 0;
    $groupDinner = 0;

    foreach ($mealForecast->groups as $groupStay) {
        $checkIn = $groupStay->check_in_date;
        $checkOut = $groupStay->check_out_date;
        $package = $groupStay->package_type;
        $pax = $groupStay->pax;

        if (($package === 'bb' || $package === 'dbb')
            && $forecastDate->gt($checkIn)
            && $forecastDate->lte($checkOut)) {
            $groupBreakfast += $pax;
        }

        if (($package === 'dinner_only' || $package === 'dbb')
            && $forecastDate->gte($checkIn)
            && $forecastDate->lt($checkOut)) {
            $groupDinner += $pax;
        }
    }

    $fitBreakfast = $mealForecast->total_breakfast - $groupBreakfast;
    $fitDinner = $mealForecast->total_dinner - $groupDinner;
@endphp

<div class="forecast-wrapper">

    <div class="forecast-header">
        <div>
            <h2>Add Group Stay</h2>
            <p>
                Forecast Date:
                <strong>{{ $mealForecast->forecast_date->format('d/m/Y') }}</strong>
            </p>
        </div>

        <a href="{{ route('reception.meal-forecasts.index') }}" class="back-btn">
            Back to Forecast
        </a>
    </div>

    @if(session('success'))
        <div class="success-box">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="error-box">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="forecast-card">
        <div class="card-title-custom">Daily Forecast Summary</div>

        <div class="info-grid">
            <div class="info-card">
                <span>Total Breakfast</span>
                <strong>{{ $mealForecast->total_breakfast }}</strong>
            </div>

            <div class="info-card">
                <span>Group Breakfast</span>
                <strong id="groupBreakfast">{{ $groupBreakfast }}</strong>
            </div>

            <div class="info-card green">
                <span>FIT Breakfast</span>
                <strong id="fitBreakfast">{{ $fitBreakfast }}</strong>
            </div>

            <div class="info-card">
                <span>Total Dinner</span>
                <strong>{{ $mealForecast->total_dinner }}</strong>
            </div>

            <div class="info-card">
                <span>Group Dinner</span>
                <strong id="groupDinner">{{ $groupDinner }}</strong>
            </div>

            <div class="info-card green">
                <span>FIT Dinner</span>
                <strong id="fitDinner">{{ $fitDinner }}</strong>
            </div>

            <div class="info-card">
                <span>Notes</span>
                <strong style="font-size:16px;">{{ $mealForecast->notes ?: '-' }}</strong>
            </div>
        </div>
    </div>

    <div class="forecast-card">
        <div class="card-title-custom">Add Group Stay Details</div>

        <form action="{{ route('reception.meal-forecasts.groups.store', $mealForecast->id) }}" method="POST">
            @csrf

            <div class="row g-3 align-items-end">
                <div class="col-lg-3 col-md-6">
                    <label class="form-label-custom">Group Name</label>
                    <select name="forecast_group_id" id="forecast_group_id" class="form-control-custom" required>
                        <option value="">Select Group</option>
                        @foreach($groups as $group)
                            <option value="{{ $group->id }}">{{ $group->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-2 col-md-6">
                    <label class="form-label-custom">Package</label>
                    <select name="package_type" id="package_type" class="form-control-custom" required>
                        <option value="dbb">DBB</option>
                        <option value="bb">B&B</option>
                        <option value="dinner_only">Dinner Only</option>
                        <option value="room_only">Room Only</option>
                    </select>
                </div>

                <div class="col-lg-1 col-md-6">
                    <label class="form-label-custom">Pax</label>
                    <input type="number" name="pax" id="pax" class="form-control-custom" min="1" value="1" required>
                </div>

                <div class="col-lg-2 col-md-6">
                    <label class="form-label-custom">Check In</label>
                    <input type="date" name="check_in_date" id="check_in_date" class="form-control-custom" required>
                </div>

                <div class="col-lg-2 col-md-6">
                    <label class="form-label-custom">Check Out</label>
                    <input type="date" name="check_out_date" id="check_out_date" class="form-control-custom" required>
                </div>

                <div class="col-lg-2 col-md-6">
                    <label class="form-label-custom">&nbsp;</label>
                    <button type="submit" class="add-btn">Add Group</button>
                </div>

                <div class="col-md-12">
                    <label class="form-label-custom">Notes</label>
                    <input type="text" name="notes" class="form-control-custom" placeholder="Optional group note">
                </div>

                <div class="col-md-12">
                    <div id="mealPreview" class="preview-box">
                        Select package, pax, check-in and check-out to preview meal schedule.
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="forecast-card">
        <div class="card-title-custom">Existing Groups for This Date</div>

        <div class="table-wrap">
            <table class="group-table">
                <thead>
                    <tr>
                        <th>Group</th>
                        <th>Package</th>
                        <th>Pax</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>Included Today</th>
                        <th>Notes</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($mealForecast->groups as $groupStay)
                        @php
                            $checkIn = $groupStay->check_in_date;
                            $checkOut = $groupStay->check_out_date;
                            $package = $groupStay->package_type;
                            $pax = $groupStay->pax;

                            $todayBreakfast = false;
                            $todayDinner = false;

                            if (($package === 'bb' || $package === 'dbb')
                                && $forecastDate->gt($checkIn)
                                && $forecastDate->lte($checkOut)) {
                                $todayBreakfast = true;
                            }

                            if (($package === 'dinner_only' || $package === 'dbb')
                                && $forecastDate->gte($checkIn)
                                && $forecastDate->lt($checkOut)) {
                                $todayDinner = true;
                            }

                            $packageLabel = match($package) {
                                'dbb' => 'DBB',
                                'bb' => 'B&B',
                                'dinner_only' => 'Dinner Only',
                                'room_only' => 'Room Only',
                                default => strtoupper($package),
                            };
                        @endphp

                        <tr>
                            <td>{{ optional($groupStay->forecastGroup)->name }}</td>
                            <td><span class="package-pill">{{ $packageLabel }}</span></td>
                            <td>{{ $pax }}</td>
                            <td>{{ $checkIn->format('d/m/Y') }}</td>
                            <td>{{ $checkOut->format('d/m/Y') }}</td>
                            <td>
                                @if($todayBreakfast)
                                    Breakfast {{ $pax }}<br>
                                @endif

                                @if($todayDinner)
                                    Dinner {{ $pax }}
                                @endif

                                @if(!$todayBreakfast && !$todayDinner)
                                    -
                                @endif
                            </td>
                            <td>{{ $groupStay->notes ?: '-' }}</td>
                            <td>
                                <form action="{{ route('reception.meal-forecasts.groups.destroy', $groupStay->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Remove this group stay?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="delete-btn">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="empty-row">
                                No group stays added for this forecast date yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
const forecastDate = new Date(
    '{{ $mealForecast->forecast_date->format('Y-m-d') }}T00:00:00'
);

function parseDate(value){
    if(!value) return null;
    return new Date(value + 'T00:00:00');
}

function addDays(date, days){
    const result = new Date(date);
    result.setDate(result.getDate() + days);
    return result;
}

function formatDate(date){
    return date.toLocaleDateString('en-GB');
}

function buildPreview(){
    const packageType = document.getElementById('package_type').value;
    const pax = parseInt(document.getElementById('pax').value) || 0;
    const checkIn = parseDate(document.getElementById('check_in_date').value);
    const checkOut = parseDate(document.getElementById('check_out_date').value);

    const preview = document.getElementById('mealPreview');

    if(!checkIn || !checkOut || pax <= 0){
        preview.innerHTML = 'Select package, pax, check-in and check-out to preview meal schedule.';
        return;
    }

    if(checkOut <= checkIn){
        preview.innerHTML = '<span style="color:#ef4444;">Check-out date must be after check-in date.</span>';
        return;
    }

    if(packageType === 'room_only'){
        preview.innerHTML = '<strong>Meal Schedule Preview:</strong><br>Room Only package: no meals included.';
        return;
    }

    let html = '<strong>Meal Schedule Preview:</strong><br>';
    let current = new Date(checkIn);

    while(current <= checkOut){
        let breakfast = 0;
        let dinner = 0;

        if((packageType === 'bb' || packageType === 'dbb') && current > checkIn && current <= checkOut){
            breakfast = pax;
        }

        if((packageType === 'dinner_only' || packageType === 'dbb') && current >= checkIn && current < checkOut){
            dinner = pax;
        }

        if(breakfast > 0 || dinner > 0){
            html += `${formatDate(current)} — `;
            if(breakfast > 0) html += `Breakfast ${breakfast} `;
            if(dinner > 0) html += `Dinner ${dinner}`;
            html += '<br>';
        }

        current = addDays(current, 1);
    }

    preview.innerHTML = html;
}

document.addEventListener('input', buildPreview);
document.addEventListener('change', buildPreview);

buildPreview();
</script>

@endsection