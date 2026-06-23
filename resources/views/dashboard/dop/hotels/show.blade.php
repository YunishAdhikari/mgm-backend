@extends('dashboard.dop.layout')

@section('title', $hotel->name . ' Detail')
@section('page-title', $hotel->name)

@section('content')
<section class="hotel-detail-page">

    <div class="detail-hero">
        <div>
            <p>MGM One / Hotel Detail</p>
            <h1>{{ $hotel->name }}</h1>
            <span>{{ $hotel->code }} • {{ $hotel->city ?? 'No city' }} {{ $hotel->country ? '• '.$hotel->country : '' }}</span>
        </div>

        <a href="{{ route('dop.hotels.overview') }}" class="back-btn">
            <i class="fas fa-arrow-left"></i>
            Back to Hotels Overview
        </a>
    </div>

    <div class="summary-grid">
        <div class="summary-card">
            <i class="fas fa-users"></i>
            <strong>{{ $hotel->users_count }}</strong>
            <span>Staff</span>
        </div>

        <div class="summary-card">
            <i class="fas fa-bed"></i>
            <strong>{{ $hotel->rooms_count }}</strong>
            <span>Rooms</span>
        </div>

        <div class="summary-card">
            <i class="fas fa-building"></i>
            <strong>{{ $hotel->departments_count }}</strong>
            <span>Departments</span>
        </div>

        <div class="summary-card">
            <i class="fas fa-utensils"></i>
            <strong>{{ $hotel->restaurants_count }}</strong>
            <span>Restaurants</span>
        </div>

        <div class="summary-card danger">
            <i class="fas fa-screwdriver-wrench"></i>
            <strong>{{ $hotel->open_maintenance_count }}</strong>
            <span>Open Maintenance</span>
        </div>

        <div class="summary-card warning">
            <i class="fas fa-comments"></i>
            <strong>{{ $hotel->pending_complaints_count }}</strong>
            <span>Pending Complaints</span>
        </div>
    </div>

    <div class="detail-grid">

        <div class="detail-card">
            <div class="card-title">
                <h2><i class="fas fa-screwdriver-wrench"></i> Recent Maintenance</h2>
            </div>

            @forelse($recentMaintenance as $job)
                <div class="list-row">
                    <div>
                        <strong>{{ $job->title }}</strong>
                        <p>
                            {{ $job->department->name ?? 'No Department' }}
                            • Room {{ $job->room_number ?? '-' }}
                            • {{ ucfirst(str_replace('_', ' ', $job->status)) }}
                        </p>
                    </div>

                    <span class="pill {{ $job->priority }}">
                        {{ ucfirst($job->priority) }}
                    </span>
                </div>
            @empty
                <div class="empty-box">
                    No recent maintenance jobs.
                </div>
            @endforelse
        </div>

        <div class="detail-card">
            <div class="card-title">
                <h2><i class="fas fa-comments"></i> Recent Complaints</h2>
            </div>

            @forelse($recentComplaints as $complaint)
                <div class="list-row">
                    <div>
                        <strong>{{ $complaint->title }}</strong>
                        <p>
                            {{ $complaint->guest_name ?? 'Guest' }}
                            • Room {{ $complaint->room_number ?? '-' }}
                            • {{ ucfirst(str_replace('_', ' ', $complaint->status)) }}
                        </p>
                    </div>

                    <span class="pill {{ $complaint->priority }}">
                        {{ ucfirst($complaint->priority) }}
                    </span>
                </div>
            @empty
                <div class="empty-box">
                    No recent complaints.
                </div>
            @endforelse
        </div>

    </div>

</section>

<style>
.detail-hero {
    background:
        radial-gradient(circle at 15% 15%, rgba(139,92,246,.3), transparent 35%),
        radial-gradient(circle at 85% 20%, rgba(236,72,153,.2), transparent 35%),
        linear-gradient(135deg, rgba(18,18,20,.96), rgba(8,8,10,.96));
    border: 1px solid var(--border);
    border-radius: 28px;
    padding: 34px;
    margin-bottom: 28px;
    display: flex;
    justify-content: space-between;
    gap: 18px;
    align-items: center;
    flex-wrap: wrap;
}

.detail-hero p {
    color: var(--primary);
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    font-size: 12px;
}

.detail-hero h1 {
    font-size: 38px;
    font-weight: 900;
    margin-top: 8px;
}

.detail-hero span {
    display: block;
    color: var(--text-muted);
    margin-top: 8px;
}

.back-btn {
    padding: 12px 18px;
    border-radius: 999px;
    background: rgba(139,92,246,.14);
    border: 1px solid rgba(139,92,246,.35);
    color: #c4b5fd;
    font-weight: 900;
    display: flex;
    gap: 10px;
    align-items: center;
}

.back-btn:hover {
    background: linear-gradient(135deg, #8b5cf6, #ec4899);
    color: white;
}

.summary-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 18px;
    margin-bottom: 28px;
}

.summary-card {
    background: linear-gradient(180deg, rgba(23,23,23,.98), rgba(10,10,10,.98));
    border: 1px solid var(--border);
    border-radius: 22px;
    padding: 24px;
    text-align: center;
}

.summary-card i {
    font-size: 24px;
    color: var(--primary);
    margin-bottom: 14px;
}

.summary-card strong {
    display: block;
    font-size: 30px;
    font-weight: 900;
}

.summary-card span {
    display: block;
    margin-top: 5px;
    color: var(--text-muted);
    font-size: 12px;
    font-weight: 900;
    text-transform: uppercase;
}

.summary-card.danger i {
    color: #f87171;
}

.summary-card.warning i {
    color: #fb923c;
}

.detail-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
}

.detail-card {
    background: linear-gradient(180deg, rgba(23,23,23,.98), rgba(10,10,10,.98));
    border: 1px solid var(--border);
    border-radius: 24px;
    padding: 24px;
}

.card-title {
    margin-bottom: 18px;
    padding-bottom: 14px;
    border-bottom: 1px solid var(--border);
}

.card-title h2 {
    font-size: 22px;
    font-weight: 900;
    display: flex;
    gap: 10px;
    align-items: center;
}

.card-title i {
    color: var(--primary);
}

.list-row {
    display: flex;
    justify-content: space-between;
    gap: 16px;
    align-items: center;
    padding: 14px 0;
    border-bottom: 1px solid var(--border);
}

.list-row:last-child {
    border-bottom: none;
}

.list-row strong {
    display: block;
    font-weight: 900;
}

.list-row p {
    color: var(--text-muted);
    margin-top: 5px;
    font-size: 13px;
}

.pill {
    padding: 7px 11px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 900;
    text-transform: uppercase;
}

.pill.low {
    background: rgba(34,197,94,.14);
    color: #4ade80;
}

.pill.medium {
    background: rgba(59,130,246,.14);
    color: #60a5fa;
}

.pill.high {
    background: rgba(249,115,22,.14);
    color: #fb923c;
}

.pill.urgent {
    background: rgba(239,68,68,.14);
    color: #f87171;
}

.empty-box {
    padding: 30px;
    border-radius: 18px;
    background: rgba(255,255,255,.04);
    color: var(--text-muted);
    text-align: center;
    font-weight: 800;
}

@media(max-width: 900px) {
    .detail-grid {
        grid-template-columns: 1fr;
    }

    .detail-hero h1 {
        font-size: 30px;
    }
}
</style>
@endsection