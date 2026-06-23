@extends('dashboard.admin.layout')

@section('title', 'Employee List')
@section('page-title', 'Employees')

@section('content')
<section class="employee-page">

    <div class="employee-top">
        <div>
            <h1>Employees</h1>
            <p>MGM One Platform Admin / Employees</p>
        </div>

        <a href="{{ route('addemp') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            Add Employee
        </a>
    </div>

    @if(session('success'))
        <div class="success-message" id="success-message">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="stats-grid employee-stats">
        <div class="stat-card">
            <div class="stat-icon red"><i class="fas fa-users"></i></div>
            <div>
                <div class="stat-value">{{ $users->total() }}</div>
                <div class="stat-label">Total Employees</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon green"><i class="fas fa-circle-check"></i></div>
            <div>
                <div class="stat-value">{{ $users->where('status', 'active')->count() }}</div>
                <div class="stat-label">Active</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon orange"><i class="fas fa-circle-xmark"></i></div>
            <div>
                <div class="stat-value">{{ $users->where('status', 'inactive')->count() }}</div>
                <div class="stat-label">Inactive</div>
            </div>
        </div>
    </div>

    <div class="employee-panel">

        <div class="search-box">
            <div class="search-input">
                <i class="fas fa-search"></i>
                <input type="text"
                       id="search-input"
                       value="{{ request('search') }}"
                       placeholder="Search name, email, hotel, role, department, code...">
            </div>

            <button type="button" id="search-btn" class="search-btn">
                <i class="fas fa-search"></i>
                Search
            </button>

            <button type="button" id="reset-btn" class="reset-btn">
                <i class="fas fa-rotate-right"></i>
                Reset
            </button>
        </div>

        <div class="loading-indicator" id="loading" style="display:none;">
            <div class="spinner"></div>
            <span>Searching...</span>
        </div>

        <div class="employee-grid" id="employee-table-body">
            @forelse($users as $user)
                <div class="employee-card">

                    <div class="employee-cover">
                        <span class="status-pill {{ ($user->status ?? 'active') === 'active' ? 'active' : 'inactive' }}">
                            {{ ucfirst($user->status ?? 'active') }}
                        </span>

                        <div class="employee-photo-wrap">
                            @if($user->image)
                                <img src="{{ asset('storage/' . $user->image) }}" alt="{{ $user->name }}" class="employee-photo">
                            @else
                                <div class="employee-photo fallback">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="employee-body">
                        <h2>{{ $user->name }}</h2>

                        <span class="role-badge">
                            {{ ucfirst($user->role->name ?? 'N/A') }}
                        </span>

                        <div class="employee-mini-grid">
                            <div>
                                <i class="fas fa-id-card"></i>
                                <strong>{{ $user->employee_code ?? 'No Code' }}</strong>
                                <span>Employee Code</span>
                            </div>

                            <div>
                                <i class="fas fa-hotel"></i>
                                <strong>{{ $user->hotel->name ?? 'Platform Admin' }}</strong>
                                <span>Hotel</span>
                            </div>
                        </div>

                        <div class="employee-details">
                            <div>
                                <span><i class="fas fa-envelope"></i> Email</span>
                                <strong>{{ $user->email }}</strong>
                            </div>

                            <div>
                                <span><i class="fas fa-phone"></i> Phone</span>
                                <strong>{{ $user->phone ?? '-' }}</strong>
                            </div>

                            <div>
                                <span><i class="fas fa-building"></i> Department</span>
                                <strong>{{ $user->department->name ?? 'N/A' }}</strong>
                            </div>

                            <div>
                                <span><i class="fas fa-briefcase"></i> Job Title</span>
                                <strong>{{ $user->job_title ?? '-' }}</strong>
                            </div>

                            <div>
                                <span><i class="fas fa-calendar"></i> Joined</span>
                                <strong>{{ $user->created_at ? $user->created_at->format('d M Y') : 'N/A' }}</strong>
                            </div>
                        </div>

                        <div class="employee-actions">
                            <form method="POST" action="{{ route('admin.users.status', $user->id) }}">
                                @csrf
                                @method('PATCH')

                                <button type="submit"
                                        class="employee-btn {{ ($user->status ?? 'active') === 'active' ? 'warning' : 'success' }}">
                                    <i class="fas {{ ($user->status ?? 'active') === 'active' ? 'fa-pause-circle' : 'fa-check-circle' }}"></i>
                                    {{ ($user->status ?? 'active') === 'active' ? 'Deactivate' : 'Activate' }}
                                </button>
                            </form>

                            <form method="POST"
                                  action="{{ route('admin.users.destroy', $user->id) }}"
                                  onsubmit="return confirm('Delete this employee?')">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="employee-btn danger">
                                    <i class="fas fa-trash"></i>
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-employees">
                    <i class="fas fa-users"></i>
                    <h2>No employees found</h2>
                    <p>Create your first employee for MGM One.</p>
                </div>
            @endforelse
        </div>

        <div class="pagination-wrapper" id="pagination-wrapper">
            {{ $users->links() }}
        </div>
    </div>
</section>


<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('search-input');
    const searchBtn = document.getElementById('search-btn');
    const resetBtn = document.getElementById('reset-btn');
    const loading = document.getElementById('loading');
    const employeeGrid = document.getElementById('employee-table-body');
    const paginationWrapper = document.getElementById('pagination-wrapper');

    let timer = null;

    function setLoading(state) {
        loading.style.display = state ? 'flex' : 'none';
        employeeGrid.style.opacity = state ? '0.35' : '1';
    }

    function buildUrl(pageUrl = null) {
        const url = new URL(pageUrl || '{{ route("dashboard.admin.showemp") }}', window.location.origin);
        const search = searchInput.value.trim();

        if (search) {
            url.searchParams.set('search', search);
        } else {
            url.searchParams.delete('search');
        }

        url.searchParams.set('_', Date.now());

        return url.toString();
    }

    function performSearch(pageUrl = null) {
        setLoading(true);

        fetch(buildUrl(pageUrl), {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html'
            },
            cache: 'no-store'
        })
        .then(response => response.text())
        .then(html => {
            const doc = new DOMParser().parseFromString(html, 'text/html');

            const newGrid = doc.getElementById('employee-table-body');
            const newPagination = doc.getElementById('pagination-wrapper');

            if (newGrid) {
                employeeGrid.innerHTML = newGrid.innerHTML;
            }

            if (newPagination) {
                paginationWrapper.innerHTML = newPagination.innerHTML;
            }

            const cleanUrl = buildUrl().replace(/([&?])_=\d+/, '');
            window.history.replaceState({}, '', cleanUrl);
        })
        .catch(error => {
            console.error('Employee search failed:', error);
        })
        .finally(() => {
            setLoading(false);
        });
    }

    searchBtn.addEventListener('click', function () {
        performSearch();
    });

    searchInput.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            performSearch();
        }
    });

    searchInput.addEventListener('input', function () {
        clearTimeout(timer);
        timer = setTimeout(() => {
            performSearch();
        }, 500);
    });

    resetBtn.addEventListener('click', function () {
        searchInput.value = '';
        performSearch();
    });

    document.addEventListener('click', function (e) {
        const link = e.target.closest('#pagination-wrapper a');

        if (link) {
            e.preventDefault();
            performSearch(link.href);
        }
    });
});
</script>

<style>
.employee-page {
    animation: fadeIn .35s ease;
}

.employee-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
    margin-bottom: 24px;
}

.employee-top h1 {
    font-size: 34px;
    font-weight: 900;
}

.employee-top p {
    color: var(--text-muted);
    margin-top: 6px;
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
    font-size: 12px;
}

.success-message {
    background: rgba(34,197,94,.12);
    border: 1px solid rgba(34,197,94,.35);
    color: #4ade80;
    padding: 14px 18px;
    border-radius: 12px;
    margin-bottom: 20px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 10px;
}

.employee-stats {
    grid-template-columns: repeat(3, minmax(180px, 1fr));
}

.employee-panel {
    background: var(--bg-card);
    border: 2px solid var(--border);
    border-radius: 22px;
    padding: 24px;
}

.search-box {
    display: flex;
    gap: 14px;
    margin-bottom: 24px;
    flex-wrap: wrap;
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

.loading-indicator {
    align-items: center;
    justify-content: center;
    gap: 12px;
    padding: 16px;
    color: var(--text-muted);
    font-weight: 800;
}

.spinner {
    width: 20px;
    height: 20px;
    border: 3px solid var(--border);
    border-top: 3px solid var(--primary);
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

.employee-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 24px;
}

.employee-card {
    background: linear-gradient(180deg, #171717, #101010);
    border: 2px solid var(--border);
    border-radius: 22px;
    overflow: hidden;
    box-shadow: 0 25px 60px rgba(0,0,0,.35);
    transition: all .25s ease;
}

.employee-card:hover {
    transform: translateY(-4px);
    border-color: var(--primary);
    box-shadow: 0 25px 70px rgba(232,45,45,.18);
}

.employee-cover {
    height: 205px;
    position: relative;
    background:
        radial-gradient(circle at 50% 20%, rgba(232,45,45,.32), transparent 35%),
        linear-gradient(135deg, #3a0909, #121212 65%);
    border-bottom: 1px solid var(--border);
}

.status-pill {
    position: absolute;
    right: 18px;
    top: 18px;
    padding: 8px 14px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 900;
}

.status-pill.active {
    color: #4ade80;
    background: rgba(34,197,94,.15);
}

.status-pill.inactive {
    color: #f87171;
    background: rgba(239,68,68,.15);
}

.employee-photo-wrap {
    position: absolute;
    left: 50%;
    bottom: -58px;
    transform: translateX(-50%);
    width: 150px;
    height: 150px;
    border-radius: 50%;
    padding: 5px;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    box-shadow: 0 0 45px rgba(232,45,45,.35);
}

.employee-photo {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    background: #161616;
    object-fit: cover;
    border: 4px solid #101010;
}

.employee-photo.fallback {
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 42px;
    font-weight: 900;
    color: white;
}

.employee-body {
    padding: 76px 24px 24px;
    text-align: center;
}

.employee-body h2 {
    font-size: 26px;
    font-weight: 900;
    margin-bottom: 8px;
}

.role-badge {
    display: inline-flex;
    padding: 7px 14px;
    border-radius: 999px;
    background: rgba(232,45,45,.12);
    color: #ff8a8a;
    border: 1px solid rgba(232,45,45,.35);
    font-size: 12px;
    font-weight: 900;
    margin-bottom: 20px;
}

.employee-mini-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    border: 1px solid var(--border);
    border-radius: 14px;
    overflow: hidden;
    margin-bottom: 20px;
}

.employee-mini-grid div {
    padding: 15px;
    text-align: left;
    background: rgba(0,0,0,.22);
}

.employee-mini-grid div:first-child {
    border-right: 1px solid var(--border);
}

.employee-mini-grid i {
    color: var(--primary);
    margin-right: 8px;
}

.employee-mini-grid strong {
    display: block;
    font-size: 14px;
    margin-top: 6px;
}

.employee-mini-grid span {
    display: block;
    color: var(--text-muted);
    font-size: 12px;
    margin-top: 4px;
}

.employee-details {
    display: grid;
    gap: 13px;
    text-align: left;
}

.employee-details div {
    display: flex;
    justify-content: space-between;
    gap: 14px;
}

.employee-details span {
    color: var(--text-muted);
    font-weight: 700;
}

.employee-details span i {
    color: var(--primary);
    width: 20px;
}

.employee-details strong {
    color: var(--text-main);
    text-align: right;
    word-break: break-word;
}

.employee-actions {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    margin-top: 24px;
}

.employee-btn {
    width: 100%;
    padding: 13px 12px;
    border-radius: 12px;
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: .7px;
}

.employee-btn.warning {
    background: rgba(251,191,36,.14);
    color: #fbbf24;
    border: 1px solid rgba(251,191,36,.35);
}

.employee-btn.success {
    background: rgba(34,197,94,.14);
    color: #4ade80;
    border: 1px solid rgba(34,197,94,.35);
}

.employee-btn.danger {
    background: rgba(239,68,68,.14);
    color: #f87171;
    border: 1px solid rgba(239,68,68,.35);
}

.empty-employees {
    grid-column: 1 / -1;
    text-align: center;
    padding: 60px;
    border: 2px dashed var(--border);
    border-radius: 22px;
    color: var(--text-muted);
}

.empty-employees i {
    font-size: 48px;
    color: var(--primary);
    margin-bottom: 14px;
}

.pagination-wrapper {
    margin-top: 24px;
}

.pagination-wrapper nav svg {
    width: 16px;
    height: 16px;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

@media (max-width: 1100px) {
    .employee-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 640px) {
    .employee-panel {
        padding: 16px;
    }

    .employee-grid {
        grid-template-columns: 1fr;
    }

    .employee-card {
        border-radius: 18px;
    }

    .employee-cover {
        height: 180px;
    }

    .employee-photo-wrap {
        width: 125px;
        height: 125px;
        bottom: -48px;
    }

    .employee-body {
        padding-top: 64px;
    }

    .employee-mini-grid,
    .employee-actions {
        grid-template-columns: 1fr;
    }

    .employee-mini-grid div:first-child {
        border-right: none;
        border-bottom: 1px solid var(--border);
    }

    .employee-details div {
        flex-direction: column;
        gap: 4px;
    }

    .employee-details strong {
        text-align: left;
    }

    .search-input,
    .search-btn,
    .reset-btn {
        width: 100%;
    }
}
</style>
@endsection