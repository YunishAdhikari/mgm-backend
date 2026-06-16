@extends('dashboard.admin.layout')

@section('title', 'Employee List')
@section('page-title', 'Employees')

@section('content')

<section class="card employee-card">

    <div class="card-header employee-header">
        <div>
            <h2>
                <i class="fas fa-users"></i>
                Employees
                <span class="emp-count" id="total-count">{{ $users->total() }}</span>
            </h2>
            <p>Manage employee details, status and access.</p>
        </div>

        <a href="{{ route('addemp') }}" class="add-employee-btn">
            <i class="fas fa-user-plus"></i>
            Add Employee
        </a>
    </div>

    @if(session('success'))
        <div class="success-message" id="success-message">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="search-box">
        <div class="search-input">
            <i class="fas fa-search"></i>
            <input type="text" id="search-input" value="{{ request('search') }}" placeholder="Search name, email, role, department...">
        </div>

        <button type="button" id="search-btn" class="search-btn">
            <i class="fas fa-search"></i> Search
        </button>

        <button type="button" id="reset-btn" class="reset-btn">
            <i class="fas fa-rotate-right"></i> Reset
        </button>
    </div>

    <div class="loading-indicator" id="loading" style="display:none;">
        <div class="spinner"></div>
        <span>Searching...</span>
    </div>

    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Email</th>
                    <th>Position</th>
                    <th>Department</th>
                    <th>Start Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody id="employee-table-body">
                @forelse($users as $user)
                    <tr>
                        <td>
                            <div class="emp-info">
                                @if($user->image)
                                    <img src="{{ asset('storage/' . $user->image) }}" alt="{{ $user->name }}">
                                @else
                                    <div class="emp-avatar">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                @endif

                                <div>
                                    <strong>{{ $user->name }}</strong>
                                </div>
                            </div>
                        </td>

                        <td><span class="email-text">{{ $user->email }}</span></td>

                        <td>
                            <span class="role-badge">
                                {{ ucfirst($user->role->name ?? 'N/A') }}
                            </span>
                        </td>

                        <td><span class="dept-text">{{ $user->department->name ?? 'N/A' }}</span></td>

                        <td>
                            <span class="date-text">
                                {{ $user->created_at ? $user->created_at->format('d M Y') : 'N/A' }}
                            </span>
                        </td>

                        <td>
                            <span class="status-badge {{ ($user->status ?? 'active') === 'active' ? 'active' : 'inactive' }}">
                                {{ ucfirst($user->status ?? 'active') }}
                            </span>
                        </td>

                        <td>
                            <div class="action-buttons">
                                <form method="POST" action="{{ route('admin.users.status', $user->id) }}">
                                    @csrf
                                    @method('PATCH')

                                    <button type="submit" class="action-btn {{ ($user->status ?? 'active') === 'active' ? 'deactivate' : 'activate' }}">
                                        {{ ($user->status ?? 'active') === 'active' ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" onsubmit="return confirm('Delete this employee?')">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="action-btn delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="empty-cell">
                            <i class="fas fa-users"></i>
                            <p>No employees found.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="employee-pagination-wrapper" id="pagination-wrapper">
        {{ $users->links() }}
    </div>

</section>

<style>
:root {
    --bg-card: #27272a;
    --bg-input: #1c1c1f;
    --text-main: #fafafa;
    --text-muted: #a1a1aa;
    --text-dim: #71717a;
    --border: #3f3f46;
    --primary: #dc2626;
    --primary-hover: #ef4444;
    --dark-red: #991b1b;
}

.employee-card {
    background: #18181b;
    border: 1px solid var(--border);
    border-radius: 22px;
    padding: 24px;
    box-shadow: 0 12px 35px rgba(0,0,0,.35);
}

.employee-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 22px;
    margin-bottom: 24px;
    padding-bottom: 20px;
    border-bottom: 1px solid var(--border);
}

.employee-header h2 {
    font-size: 28px;
    font-weight: 900;
    color: var(--text-main);
    display: flex;
    align-items: center;
    gap: 12px;
    margin: 0;
}

.employee-header h2 i {
    color: var(--primary);
}

.employee-header p {
    color: var(--text-muted);
    margin: 6px 0 0;
}

.emp-count {
    font-size: 13px;
    background: #27272a;
    color: var(--text-muted);
    padding: 5px 12px;
    border-radius: 999px;
}

.add-employee-btn {
    text-decoration: none;
    background: linear-gradient(135deg, var(--primary), var(--dark-red));
    color: white;
    padding: 13px 20px;
    border-radius: 14px;
    font-weight: 800;
    display: inline-flex;
    align-items: center;
    gap: 10px;
}

.add-employee-btn:hover {
    color: white;
    background: linear-gradient(135deg, var(--primary-hover), var(--primary));
}

.success-message {
    background: rgba(34,197,94,.15);
    border: 1px solid rgba(34,197,94,.35);
    color: #22c55e;
    padding: 14px 18px;
    border-radius: 14px;
    margin-bottom: 20px;
    font-weight: 700;
}

.search-box {
    display: flex;
    gap: 12px;
    margin-bottom: 24px;
    flex-wrap: wrap;
}

.search-input {
    flex: 1;
    min-width: 240px;
    display: flex;
    align-items: center;
    gap: 12px;
    background: var(--bg-input);
    border: 1px solid var(--border);
    border-radius: 14px;
    padding: 0 16px;
}

.search-input i {
    color: var(--text-dim);
}

.search-input input {
    flex: 1;
    height: 50px;
    border: none;
    outline: none;
    background: transparent;
    color: var(--text-main);
}

.search-input input::placeholder {
    color: var(--text-dim);
}

.search-btn,
.reset-btn {
    height: 50px;
    padding: 0 22px;
    border-radius: 14px;
    font-weight: 800;
    cursor: pointer;
}

.search-btn {
    background: linear-gradient(135deg, var(--primary), var(--dark-red));
    color: white;
    border: none;
}

.reset-btn {
    background: #27272a;
    color: var(--text-muted);
    border: 1px solid var(--border);
}

.loading-indicator {
    display: none;
    align-items: center;
    justify-content: center;
    gap: 12px;
    padding: 18px;
    color: var(--text-muted);
}

.spinner {
    width: 20px;
    height: 20px;
    border: 3px solid var(--border);
    border-top: 3px solid var(--primary);
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

.table-wrapper {
    overflow-x: auto;
    border-radius: 18px;
    border: 1px solid var(--border);
}

.data-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 1000px;
}

.data-table thead {
    background: #1c1c1f;
}

.data-table th {
    padding: 16px 14px;
    color: var(--text-muted);
    font-size: 12px;
    font-weight: 800;
    text-transform: uppercase;
    text-align: left;
    border-bottom: 1px solid var(--border);
}

.data-table td {
    padding: 16px 14px;
    border-bottom: 1px solid var(--border);
    color: var(--text-main);
    vertical-align: middle;
}

.emp-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.emp-info img,
.emp-avatar {
    width: 48px;
    height: 48px;
    min-width: 48px;
    border-radius: 50%;
    object-fit: cover;
}

.emp-avatar {
    background: linear-gradient(135deg, #dc2626, #991b1b);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 900;
}

.email-text,
.dept-text,
.date-text {
    color: var(--text-muted);
    max-width: 170px;
    overflow: hidden;
    text-overflow: ellipsis;
    display: block;
}

.role-badge {
    display: inline-block;
    padding: 8px 14px;
    background: rgba(220,38,38,.15);
    color: #ef4444;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 800;
}

.status-badge {
    display: inline-block;
    padding: 8px 14px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 900;
}

.status-badge.active {
    background: rgba(34,197,94,.15);
    color: #22c55e;
}

.status-badge.inactive {
    background: rgba(239,68,68,.15);
    color: #fca5a5;
}

.action-buttons {
    display: flex;
    gap: 8px;
    align-items: center;
}

.action-btn {
    border: none;
    border-radius: 12px;
    padding: 10px 14px;
    font-size: 12px;
    font-weight: 900;
    cursor: pointer;
    white-space: nowrap;
}

.action-btn.deactivate {
    background: rgba(245,158,11,.18);
    color: #fbbf24;
}

.action-btn.activate {
    background: rgba(34,197,94,.15);
    color: #22c55e;
}

.action-btn.delete {
    background: rgba(239,68,68,.18);
    color: #fca5a5;
}

.empty-cell {
    text-align: center;
    padding: 45px;
    color: var(--text-dim);
}

/* FIXED PAGINATION */
.employee-pagination-wrapper {
    margin-top: 24px;
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
}

.employee-pagination-wrapper nav {
    width: 100%;
}

.employee-pagination-wrapper nav > div {
    display: flex !important;
    justify-content: space-between;
    align-items: center;
    gap: 14px;
    flex-wrap: wrap;
}

.employee-pagination-wrapper .pagination {
    display: flex !important;
    flex-direction: row !important;
    align-items: center;
    justify-content: center;
    gap: 8px;
    list-style: none;
    padding: 0;
    margin: 0;
}

.employee-pagination-wrapper .page-item {
    display: inline-flex !important;
}

.employee-pagination-wrapper .page-link,
.employee-pagination-wrapper .pagination span,
.employee-pagination-wrapper .pagination a {
    background: #27272a !important;
    border: 1px solid var(--border) !important;
    color: var(--text-main) !important;
    border-radius: 10px !important;
    padding: 9px 13px !important;
    min-width: 38px;
    min-height: 38px;
    display: inline-flex !important;
    align-items: center;
    justify-content: center;
    text-decoration: none !important;
    font-size: 14px !important;
    line-height: 1 !important;
}

.employee-pagination-wrapper .active .page-link,
.employee-pagination-wrapper .pagination .active span {
    background: var(--primary) !important;
    border-color: var(--primary) !important;
    color: white !important;
}

.employee-pagination-wrapper svg {
    width: 16px !important;
    height: 16px !important;
    max-width: 16px !important;
    max-height: 16px !important;
}

.employee-pagination-wrapper p {
    color: var(--text-muted);
    margin: 0;
    font-size: 14px;
}

@media (max-width: 768px) {
    .employee-header {
        flex-direction: column;
        align-items: stretch;
    }

    .add-employee-btn {
        justify-content: center;
    }

    .search-box {
        flex-direction: column;
    }

    .search-input,
    .search-btn,
    .reset-btn {
        width: 100%;
    }

    .employee-pagination-wrapper nav > div {
        flex-direction: column;
        justify-content: center;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const searchBtn = document.getElementById('search-btn');
    const resetBtn = document.getElementById('reset-btn');
    const loading = document.getElementById('loading');
    const tableBody = document.getElementById('employee-table-body');
    const paginationWrapper = document.getElementById('pagination-wrapper');
    const totalCount = document.getElementById('total-count');
    const successMessage = document.getElementById('success-message');

    if (successMessage) {
        setTimeout(() => successMessage.style.display = 'none', 3000);
    }

    function debounce(func, wait) {
        let timeout;
        return function(...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func(...args), wait);
        };
    }

    function performSearch() {
        const searchTerm = searchInput.value;

        loading.style.display = 'flex';
        tableBody.style.opacity = '0.5';

        fetch('{{ route("dashboard.admin.showemp") }}?search=' + encodeURIComponent(searchTerm), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');

            const newTableBody = doc.getElementById('employee-table-body');
            if (newTableBody) tableBody.innerHTML = newTableBody.innerHTML;

            const newCount = doc.getElementById('total-count');
            if (newCount) totalCount.textContent = newCount.textContent;

            const newPagination = doc.getElementById('pagination-wrapper');
            if (newPagination) paginationWrapper.innerHTML = newPagination.innerHTML;
        })
        .finally(() => {
            loading.style.display = 'none';
            tableBody.style.opacity = '1';
        });
    }

    searchBtn.addEventListener('click', performSearch);

    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') performSearch();
    });

    searchInput.addEventListener('input', debounce(performSearch, 500));

    resetBtn.addEventListener('click', function() {
        searchInput.value = '';
        performSearch();
    });

    document.addEventListener('click', function(e) {
        const paginationLink = e.target.closest('.employee-pagination-wrapper a');

        if (paginationLink) {
            e.preventDefault();

            loading.style.display = 'flex';
            tableBody.style.opacity = '0.5';

            fetch(paginationLink.href + '&search=' + encodeURIComponent(searchInput.value), {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');

                const newTableBody = doc.getElementById('employee-table-body');
                if (newTableBody) tableBody.innerHTML = newTableBody.innerHTML;

                const newPagination = doc.getElementById('pagination-wrapper');
                if (newPagination) paginationWrapper.innerHTML = newPagination.innerHTML;
            })
            .finally(() => {
                loading.style.display = 'none';
                tableBody.style.opacity = '1';
            });
        }
    });
});
</script>

@endsection