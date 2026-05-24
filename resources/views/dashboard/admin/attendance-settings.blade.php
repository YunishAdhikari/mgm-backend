@extends('dashboard.admin.layout')

@section('content')

<div class="settings-page">

    @if(session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="settings-card">
        <h1>Attendance Settings</h1>
        <p>Manage hotel Wi-Fi IP and location restrictions for staff clock in/out.</p>

        <form method="POST" action="{{ route('admin.attendance.settings.update') }}">
            @csrf

            <div class="form-group">
                <label>Hotel Wi-Fi Public IP</label>
                <input
                    type="text"
                    name="hotel_wifi_ip"
                    value="{{ old('hotel_wifi_ip', $setting->hotel_wifi_ip) }}"
                    placeholder="e.g. 81.123.45.67">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Hotel Latitude</label>
                    <input
                        type="text"
                        name="hotel_latitude"
                        value="{{ old('hotel_latitude', $setting->hotel_latitude) }}"
                        placeholder="e.g. 55.8642">
                </div>

                <div class="form-group">
                    <label>Hotel Longitude</label>
                    <input
                        type="text"
                        name="hotel_longitude"
                        value="{{ old('hotel_longitude', $setting->hotel_longitude) }}"
                        placeholder="e.g. -4.2518">
                </div>
            </div>

            <div class="form-group">
                <label>Allowed Radius Meters</label>
                <input
                    type="number"
                    name="allowed_radius_meters"
                    min="10"
                    value="{{ old('allowed_radius_meters', $setting->allowed_radius_meters ?? 100) }}">
            </div>

            <div class="toggle-box">
                <label>
                    <input
                        type="checkbox"
                        name="is_ip_check_enabled"
                        {{ $setting->is_ip_check_enabled ? 'checked' : '' }}>
                    Enable Wi-Fi/IP Check
                </label>

                <label>
                    <input
                        type="checkbox"
                        name="is_location_check_enabled"
                        {{ $setting->is_location_check_enabled ? 'checked' : '' }}>
                    Enable Location Radius Check
                </label>
            </div>

            <button class="save-btn">
                Save Settings
            </button>
        </form>
    </div>

</div>

<style>
.settings-page {
    max-width: 850px;
}

.alert-success {
    background: #dcfce7;
    color: #166534;
    padding: 14px 16px;
    border-radius: 14px;
    font-weight: 800;
    margin-bottom: 18px;
}

.settings-card {
    background: white;
    border-radius: 22px;
    padding: 26px;
    box-shadow: 0 10px 28px rgba(0,0,0,0.06);
}

.settings-card h1 {
    margin: 0 0 8px;
}

.settings-card p {
    margin: 0 0 24px;
    color: #6b7280;
}

.form-group {
    margin-bottom: 16px;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}

label {
    display: block;
    font-weight: 800;
    margin-bottom: 7px;
    color: #374151;
}

input[type="text"],
input[type="number"] {
    width: 100%;
    border: 1px solid #d1d5db;
    border-radius: 12px;
    padding: 12px;
    outline: none;
}

.toggle-box {
    background: #f9fafb;
    border-radius: 16px;
    padding: 16px;
    margin-bottom: 22px;
    display: grid;
    gap: 12px;
}

.toggle-box label {
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.save-btn {
    border: none;
    background: #1583ff;
    color: white;
    padding: 13px 18px;
    border-radius: 13px;
    font-weight: 900;
    cursor: pointer;
    width: 100%;
}

@media(max-width: 700px) {
    .form-row {
        grid-template-columns: 1fr;
    }

    .settings-card {
        padding: 20px;
    }
}
</style>

@endsection