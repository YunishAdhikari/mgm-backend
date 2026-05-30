@extends('dashboard.fb.layout')

@section('content')
<div style="padding:30px;">
    <h1 style="font-size:32px;font-weight:800;">F&B Dashboard</h1>
    <p style="color:#6b7280;">Manage restaurant operations and today’s service.</p>

    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:20px;margin-top:25px;">
        <a href="{{ route('restaurant.bookings.index') }}" style="text-decoration:none;">
            <div style="background:white;padding:28px;border-radius:20px;box-shadow:0 10px 30px rgba(0,0,0,.08);">
                <h3 style="color:#111827;">Restaurant Bookings</h3>
                <p style="color:#6b7280;">View and manage restaurant reservations.</p>
            </div>
        </a>

        <a href="{{ route('restaurant.tables.floor-plan') }}" style="text-decoration:none;">
            <div style="background:white;padding:28px;border-radius:20px;box-shadow:0 10px 30px rgba(0,0,0,.08);">
                <h3 style="color:#111827;">Floor Plan</h3>
                <p style="color:#6b7280;">View restaurant table layout and status.</p>
            </div>
        </a>
    </div>
</div>
@endsection