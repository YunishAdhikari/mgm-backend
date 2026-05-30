@extends('dashboard.admin.layout')

@section('content')

<div class="card">

    <div class="card-header">
        <h2>
            <i class="fa-solid fa-newspaper"></i>
            News / Blog List
        </h2>

        <a href="{{ route('admin.news.create') }}" class="add-news-btn">
            <i class="fa-solid fa-plus"></i>
            Add News
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
                    <th>Description</th>
                    <th>Status</th>
                    <th>Change Status</th>
                    <th>Created At</th>
                </tr>
            </thead>

            <tbody>

@forelse($news as $item)

<tr>
    <td>{{ $loop->iteration }}</td>

    <td>
        @if($item->image)
            <img src="{{ asset('uploads/news/'.$item->image) }}" class="news-img">
        @endif
    </td>

    <td>{{ $item->title }}</td>

    <td>{{ Str::limit($item->description, 80) }}</td>

    <td>
        <span class="status-badge {{ $item->status == 'active' ? 'active-status' : 'inactive-status' }}">
            {{ ucfirst($item->status) }}
        </span>
    </td>

    <td>
        <form action="{{ route('admin.news.status', $item->id) }}" method="POST">
            @csrf
            @method('PATCH')

            <button type="submit" class="status-btn {{ $item->status == 'active' ? 'deactivate' : 'activate' }}">
                {{ $item->status == 'active' ? 'Make Inactive' : 'Make Active' }}
            </button>
        </form>
    </td>

    <td>{{ $item->created_at->format('d M Y') }}</td>

</tr>

@empty

<tr>
    <td colspan="7" class="empty-text">No News Found</td>
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
        --success: #10b981;
    }

    .add-news-btn {
        text-decoration: none;
        background: linear-gradient(135deg, var(--purple), var(--pink));
        color: white;
        padding: 11px 18px;
        border-radius: 12px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }

    .add-news-btn:hover {
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
        min-width: 900px;
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

    .news-img {
        width: 70px;
        height: 55px;
        object-fit: cover;
        border-radius: 10px;
    }

    .status-badge {
        padding: 7px 12px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 700;
    }

    .active-status {
        background: rgba(16, 185, 129, 0.15);
        color: #6ee7b7;
    }

    .inactive-status {
        background: rgba(239, 68, 68, 0.15);
        color: #fca5a5;
    }

    .status-btn {
        border: none;
        cursor: pointer;
        padding: 9px 14px;
        border-radius: 10px;
        font-weight: 700;
        transition: all 0.3s ease;
    }

    .status-btn.activate {
        background: rgba(16, 185, 129, 0.15);
        color: #6ee7b7;
    }

    .status-btn.deactivate {
        background: rgba(239, 68, 68, 0.15);
        color: #fca5a5;
    }

    .status-btn:hover {
        transform: translateY(-2px);
    }

    .empty-text {
        text-align: center;
        color: var(--text-muted);
        font-weight: 700;
        padding: 25px;
    }

    @media(max-width: 768px) {
        .card-header {
            flex-direction: column;
            gap: 12px;
        }

        .add-news-btn {
            justify-content: center;
            width: 100%;
        }
    }
</style>

@endsection