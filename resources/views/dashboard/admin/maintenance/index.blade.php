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
        <div class="success-message">{{ session('success') }}</div>
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
.add-btn {
    text-decoration: none;
    background: linear-gradient(135deg,#1583ff,#ff15c4);
    color: white;
    padding: 11px 18px;
    border-radius: 12px;
    font-weight: 700;
    display: flex;
    gap: 8px;
    align-items: center;
}

.success-message {
    background: #dcfce7;
    color: #166534;
    padding: 14px 16px;
    border-radius: 12px;
    margin-bottom: 18px;
    font-weight: 700;
}

.table-wrapper {
    width: 100%;
    overflow-x: auto;
}

.job-img {
    width: 75px;
    height: 55px;
    object-fit: cover;
    border-radius: 10px;
}

.no-image {
    color: #888;
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
    background: #dcfce7;
    color: #166534;
}

.priority-medium {
    background: #dbeafe;
    color: #1d4ed8;
}

.priority-high {
    background: #fef3c7;
    color: #92400e;
}

.priority-urgent {
    background: #fee2e2;
    color: #991b1b;
}

.status-pending {
    background: #fef3c7;
    color: #92400e;
}

.status-in_progress {
    background: #dbeafe;
    color: #1d4ed8;
}

.status-completed {
    background: #dcfce7;
    color: #166534;
}

.status-cancelled {
    background: #e5e7eb;
    color: #374151;
}

.status-btn {
    border: none;
    cursor: pointer;
    padding: 9px 14px;
    border-radius: 10px;
    background: #eef6ff;
    color: #1583ff;
    font-weight: 700;
}

.empty-text {
    text-align: center;
    padding: 25px;
    color: #777;
    font-weight: 700;
}

@media(max-width: 768px) {
    .add-btn {
        width: 100%;
        justify-content: center;
    }

    table {
        min-width: 1100px;
    }
}
</style>

@endsection