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

{{-- @forelse($news as $item) --}}
@forelse($news as $item)

<tr>

    <td>{{ $loop->iteration }}</td>

    <td>
        @if($item->image)
            <img src="{{ asset('uploads/news/'.$item->image) }}" width="70">
        @endif
    </td>

    <td>{{ $item->title }}</td>

    <td>{{ Str::limit($item->description, 80) }}</td>

    <td>{{ $item->status }}</td>

    <td>

        <form action="{{ route('admin.news.status', $item->id) }}" method="POST">
            {{-- {{ dd($item) }} --}}
    @csrf
    @method('PATCH')

    <button type="submit"
        class="status-btn {{ $item->status == 'active' ? 'deactivate' : 'activate' }}">

        {{ $item->status == 'active' ? 'Make Inactive' : 'Make Active' }}

    </button>
</form>

    </td>

    <td>{{ $item->created_at->format('d M Y') }}</td>

</tr>

@empty

<tr>
    <td colspan="7">No News Found</td>
</tr>

@endforelse

</tbody>
        </table>
    </div>

</div>

<style>
.add-news-btn {
    text-decoration: none;
    background: linear-gradient(135deg, #1583ff, #ff15c4);
    color: white;
    padding: 11px 18px;
    border-radius: 12px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 8px;
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

.news-img {
    width: 75px;
    height: 55px;
    object-fit: cover;
    border-radius: 10px;
}

.no-image {
    color: #888;
    font-size: 13px;
}

.status-badge {
    padding: 7px 12px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 700;
}

.active-status {
    background: #dcfce7;
    color: #166534;
}

.inactive-status {
    background: #fee2e2;
    color: #991b1b;
}

.status-btn {
    border: none;
    cursor: pointer;
    padding: 9px 14px;
    border-radius: 10px;
    font-weight: 700;
    transition: 0.3s;
}

.status-btn.activate {
    background: #dcfce7;
    color: #166534;
}

.status-btn.deactivate {
    background: #fee2e2;
    color: #991b1b;
}

.status-btn:hover {
    transform: translateY(-2px);
}

.empty-text {
    text-align: center;
    color: #777;
    font-weight: 700;
    padding: 25px;
}

@media(max-width: 768px) {
    .card-header {
        align-items: stretch;
    }

    .add-news-btn {
        justify-content: center;
        width: 100%;
    }

    table {
        min-width: 900px;
    }
}
</style>

@endsection