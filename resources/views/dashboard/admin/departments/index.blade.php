@extends('dashboard.admin.layout')

@section('title', 'Departments')
@section('page-title', 'Departments')

@section('content')
<section class="department-page">

    <div class="department-top">
        <div>
            <h1>Departments</h1>
            <p>MGM One Platform Admin / Hotel Departments</p>
        </div>

        <a href="{{ route('admin.departments.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            Add Department
        </a>
    </div>

    @if(session('success'))
        <div class="success-message">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="error-message">
            @foreach($errors->all() as $error)
                <p>• {{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="GET" action="{{ route('admin.departments.index') }}" class="search-box">
        <div class="search-input">
            <i class="fas fa-search"></i>
            <input type="text"
                   name="search"
                   value="{{ $search ?? request('search') }}"
                   placeholder="Search department or hotel...">
        </div>

        <button class="search-btn">
            <i class="fas fa-search"></i>
            Search
        </button>

        <a href="{{ route('admin.departments.index') }}" class="reset-btn">
            <i class="fas fa-rotate-right"></i>
            Reset
        </a>
    </form>

    <div class="department-grid">
        @forelse($departments as $department)
            <div class="department-card">

                <div class="department-header">
                    <div class="department-icon">
                        <i class="fas fa-building"></i>
                    </div>

                    <span class="hotel-badge">
                        <i class="fas fa-hotel"></i>
                        {{ $department->hotel->name ?? 'No Hotel' }}
                    </span>
                </div>

                <div class="department-body">
                    <h2>{{ $department->name }}</h2>
                    <p>{{ $department->hotel->code ?? 'No hotel code' }}</p>

                    <div class="department-stats">
                        <div>
                            <strong>{{ $department->users_count }}</strong>
                            <span>Employees</span>
                        </div>

                        <div>
                            <strong>{{ $department->created_at ? $department->created_at->format('d M Y') : '-' }}</strong>
                            <span>Created</span>
                        </div>
                    </div>

                    <div class="department-actions">
                        <a href="{{ route('admin.departments.edit', $department) }}" class="department-btn edit">
                            <i class="fas fa-pen"></i>
                            Edit
                        </a>

                        <form method="POST"
                              action="{{ route('admin.departments.destroy', $department) }}"
                              onsubmit="return confirm('Delete this department?')">
                            @csrf
                            @method('DELETE')

                            <button class="department-btn delete">
                                <i class="fas fa-trash"></i>
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-departments">
                <i class="fas fa-building"></i>
                <h2>No departments found</h2>
                <p>Create departments for your hotels.</p>

                <a href="{{ route('admin.departments.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    Add Department
                </a>
            </div>
        @endforelse
    </div>

    <div class="pagination-wrapper">
        {{ $departments->links() }}
    </div>

</section>

<style>
.department-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
    margin-bottom: 24px;
}

.department-top h1 {
    font-size: 34px;
    font-weight: 900;
}

.department-top p {
    color: var(--text-muted);
    margin-top: 6px;
    font-size: 12px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.success-message,
.error-message {
    padding: 14px 18px;
    border-radius: 12px;
    margin-bottom: 20px;
    font-weight: 800;
}

.success-message {
    background: rgba(34,197,94,.12);
    border: 1px solid rgba(34,197,94,.35);
    color: #4ade80;
}

.error-message {
    background: rgba(239,68,68,.12);
    border: 1px solid rgba(239,68,68,.35);
    color: #f87171;
}

.search-box {
    background: var(--bg-card);
    border: 2px solid var(--border);
    border-radius: 20px;
    padding: 18px;
    display: flex;
    gap: 14px;
    flex-wrap: wrap;
    margin-bottom: 24px;
}

.search-input {
    flex: 1;
    min-width: 260px;
    display: flex;
    align-items: center;
    gap: 12px;
    background: var(--bg-input);
    border: 2px solid var(--border);
    border-radius: 14px;
    padding: 0 16px;
}

.search-input i {
    color: var(--primary);
}

.search-input input {
    border: none;
    background: transparent;
    box-shadow: none;
    padding: 15px 0;
}

.search-btn,
.reset-btn {
    padding: 14px 24px;
    border-radius: 14px;
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: .8px;
}

.search-btn {
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: white;
}

.reset-btn {
    background: #1c1c1c;
    color: var(--text-muted);
    border: 1px solid var(--border);
}

.department-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 24px;
}

.department-card {
    background: linear-gradient(180deg, #171717, #101010);
    border: 2px solid var(--border);
    border-radius: 22px;
    overflow: hidden;
    box-shadow: 0 25px 60px rgba(0,0,0,.35);
    transition: all .25s ease;
}

.department-card:hover {
    transform: translateY(-4px);
    border-color: var(--primary);
    box-shadow: 0 25px 70px rgba(232,45,45,.18);
}

.department-header {
    height: 135px;
    position: relative;
    background:
        radial-gradient(circle at 20% 20%, rgba(232,45,45,.35), transparent 35%),
        linear-gradient(135deg, #3a0909, #121212 70%);
    border-bottom: 1px solid var(--border);
}

.department-icon {
    position: absolute;
    left: 24px;
    bottom: -38px;
    width: 82px;
    height: 82px;
    border-radius: 24px;
    background: #101010;
    border: 4px solid #171717;
    color: var(--primary);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 34px;
    box-shadow: 0 0 38px rgba(232,45,45,.28);
}

.hotel-badge {
    position: absolute;
    right: 18px;
    top: 18px;
    max-width: 70%;
    padding: 8px 13px;
    border-radius: 999px;
    background: rgba(232,45,45,.14);
    color: #ff8a8a;
    border: 1px solid rgba(232,45,45,.35);
    font-size: 12px;
    font-weight: 900;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.department-body {
    padding: 52px 24px 24px;
}

.department-body h2 {
    font-size: 26px;
    font-weight: 900;
}

.department-body p {
    color: var(--text-muted);
    margin-top: 5px;
    font-weight: 700;
}

.department-stats {
    margin-top: 22px;
    display: grid;
    grid-template-columns: 1fr 1fr;
    border: 1px solid var(--border);
    border-radius: 16px;
    overflow: hidden;
}

.department-stats div {
    padding: 16px;
    background: rgba(0,0,0,.22);
}

.department-stats div:first-child {
    border-right: 1px solid var(--border);
}

.department-stats strong {
    display: block;
    font-size: 20px;
    font-weight: 900;
}

.department-stats span {
    display: block;
    color: var(--text-muted);
    font-size: 12px;
    font-weight: 800;
    margin-top: 4px;
    text-transform: uppercase;
}

.department-actions {
    margin-top: 22px;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
}

.department-btn {
    width: 100%;
    padding: 13px 12px;
    border-radius: 12px;
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: .7px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.department-btn.edit {
    background: #1c1c1c;
    color: white;
    border: 1px solid var(--border);
}

.department-btn.delete {
    background: rgba(239,68,68,.14);
    color: #f87171;
    border: 1px solid rgba(239,68,68,.35);
}

.empty-departments {
    grid-column: 1 / -1;
    text-align: center;
    padding: 60px;
    border: 2px dashed var(--border);
    border-radius: 22px;
    color: var(--text-muted);
}

.empty-departments i {
    font-size: 48px;
    color: var(--primary);
    margin-bottom: 14px;
}

.empty-departments h2 {
    color: white;
    font-size: 24px;
    font-weight: 900;
}

.empty-departments p {
    margin: 8px 0 20px;
}

.pagination-wrapper {
    margin-top: 24px;
}

.pagination-wrapper nav svg {
    width: 16px;
    height: 16px;
}

@media (max-width: 1100px) {
    .department-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 640px) {
    .search-input,
    .search-btn,
    .reset-btn,
    .department-top .btn {
        width: 100%;
    }

    .department-actions,
    .department-stats {
        grid-template-columns: 1fr;
    }

    .department-stats div:first-child {
        border-right: none;
        border-bottom: 1px solid var(--border);
    }
}
</style>
@endsection