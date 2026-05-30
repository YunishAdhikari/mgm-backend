@extends('dashboard.admin.layout')

@section('content')

<div class="card">
    <div class="card-header">
        <h2><i class="fa-solid fa-screwdriver-wrench"></i> Maintenance Jobs</h2>

        <a href="{{ route('admin.maintenance.create') }}" class="add-btn">
            <i class="fa-solid fa-plus"></i>
            Add Job
        </a>
    </div>

    @if(session('success'))
        <div class="success-message">
            <i class="fa-solid fa-circle-check"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>S.N</th>
                    <th>Image</th>
                    <th>Title</th>
                    <th>Department</th>
                    <th>Room</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Assigned To</th>
                    <th>Reported By</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($jobs as $job)
                    <tr>
                        <td>{{ $loop->iteration }}</td>

                        <td>
                            @if($job->image)
                                <img src="{{ asset('uploads/maintenance/' . $job->image) }}" class="job-img">
                            @else
                                <span class="no-image">No Image</span>
                            @endif
                        </td>

                        <td>{{ $job->title }}</td>
                        <td>{{ $job->department->name ?? 'N/A' }}</td>
                        <td>{{ $job->room_number ?? 'N/A' }}</td>

                        <td>
                            <span class="badge priority-{{ $job->priority }}">
                                {{ ucfirst($job->priority) }}
                            </span>
                        </td>

                        <td>
                            <span class="badge status-{{ $job->status }}">
                                {{ ucwords(str_replace('_', ' ', $job->status)) }}
                            </span>
                        </td>

                        <td>{{ $job->assignedUser->name ?? 'Not Assigned' }}</td>
                        <td>{{ $job->reporter->name ?? 'N/A' }}</td>

                        <td>
                            <form action="{{ route('admin.maintenance.status', $job->id) }}" method="POST">
                                @csrf
                                @method('PATCH')

                                <button type="submit" class="status-btn">
                                    Change Status
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="empty-text">No maintenance jobs found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<style>
    :root {
        --bg-card: #27272a;
        --bg-input: #1c1c1f;
        --text-main: #fafafa;
        --text-muted: #a1a1aa;
        --border: #3f3f46;
        --purple: #8b5cf6;
        --pink: #ec4899;
    }

    .add-btn {
        text-decoration: none;
        background: linear-gradient(135deg, var(--purple), var(--pink));
        color: white;
        padding: 11px 18px;
        border-radius: 12px;
        font-weight: 700;
        display: flex;
        gap: 8px;
        align-items: center;
        transition: all 0.3s ease;
    }

    .add-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(139, 92, 246, 0.3);
    }

    .success-message {
        background: rgba(16, 185, 129, 0.15);
        border: 1px solid rgba(16, 185, 129, 0.3);
        color: #6ee7b7;
        padding: 14px 16px;
        border-radius: 12px;
        margin-bottom: 18px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    table {
        width: 100%;
        min-width: 1100px;
        border-collapse: collapse;
    }

    thead {
        background: var(--bg-input);
    }

    th {
        padding: 14px;
        text-align: left;
        color: var(--text-muted);
        font-weight: 700;
        font-size: 13px;
        border-bottom: 1px solid var(--border);
    }

    td {
        padding: 14px;
        color: var(--text-main);
        border-bottom: 1px solid var(--border);
    }

    tr:hover td {
        background: rgba(255, 255, 255, 0.02);
    }

    .job-img {
        width: 75px;
        height: 55px;
        object-fit: cover;
        border-radius: 10px;
    }

    .no-image {
        color: var(--text-muted);
        font-size: 13px;
    }

    .badge {
        padding: 7px 12px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 700;
        display: inline-block;
        white-space: nowrap;
    }

    .priority-low {
        background: rgba(16, 185, 129, 0.15);
        color: #6ee7b7;
    }

    .priority-medium {
        background: rgba(59, 130, 246, 0.15);
        color: #60a5fa;
    }

    .priority-high {
        background: rgba(245, 158, 11, 0.15);
        color: #fbbf24;
    }

    .priority-urgent {
        background: rgba(239, 68, 68, 0.15);
        color: #fca5a5;
    }

    .status-pending {
        background: rgba(245, 158, 11, 0.15);
        color: #fbbf24;
    }

    .status-in_progress {
        background: rgba(59, 130, 246, 0.15);
        color: #60a5fa;
    }

    .status-completed {
        background: rgba(16, 185, 129, 0.15);
        color: #6ee7b7;
    }

    .status-cancelled {
        background: rgba(113, 113, 122, 0.15);
        color: #a1a1aa;
    }

    .status-btn {
        border: none;
        cursor: pointer;
        padding: 9px 14px;
        border-radius: 10px;
        background: rgba(139, 92, 246, 0.15);
        color: #a78bfa;
        font-weight: 700;
        transition: all 0.3s ease;
    }

    .status-btn:hover {
        background: rgba(139, 92, 246, 0.25);
        transform: translateY(-2px);
    }

    .empty-text {
        text-align: center;
        padding: 25px;
        color: var(--text-muted);
        font-weight: 700;
    }

    @media(max-width: 768px) {
        .card-header {
            flex-direction: column;
            gap: 12px;
        }

        .add-btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>

@endsection