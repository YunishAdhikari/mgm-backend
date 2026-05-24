@extends('dashboard.admin.layout')
{{-- @extends('layouts.admin') --}}

@section('title', 'Employee List')
@section('page-title', 'Employees')

@section('content')

<!-- Employee Card -->
<section class="card employee-card">

    <!-- Card Header -->
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

    <!-- Success Message -->
    @if(session('success'))
        <div class="success-message" id="success-message">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- Search Form -->
    <div class="search-box">
        <div class="search-input">
            <i class="fas fa-search"></i>
            <input type="text" 
                   id="search-input" 
                   value="{{ request('search') }}" 
                   placeholder="Search name, email, role, department...">
        </div>

        <button type="button" id="search-btn" class="search-btn">
            <i class="fas fa-search"></i> Search
        </button>

        <button type="button" id="reset-btn" class="reset-btn">
            <i class="fas fa-rotate-right"></i> Reset
        </button>
    </div>

    <!-- Loading Indicator -->
    <div class="loading-indicator" id="loading" style="display: none;">
        <div class="spinner"></div>
        <span>Searching...</span>
    </div>

    <!-- Table Wrapper -->
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
                        <td><span class="role-badge">{{ ucfirst($user->role->name ?? 'N/A') }}</span></td>
                        <td><span class="dept-text">{{ $user->department->name ?? 'N/A' }}</span></td>
                        <td><span class="date-text">{{ $user->created_at ? $user->created_at->format('d M Y') : 'N/A' }}</span></td>
                        <td>
                            <span class="status-badge {{ ($user->status ?? 'active') === 'active' ? 'active' : 'inactive' }}">
                                {{ ucfirst($user->status ?? 'active') }}
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <form method="POST" action="{{ route('admin.users.status', $user->id) }}" class="ajax-form">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="action-btn {{ ($user->status ?? 'active') === 'active' ? 'deactivate' : 'activate' }}">
                                        {{ ($user->status ?? 'active') === 'active' ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" onsubmit="return confirm('Delete this employee?')" class="ajax-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn delete" title="Delete">
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

    <!-- Pagination -->
    <div class="pagination-wrapper" id="pagination-wrapper">
        {{ $users->links() }}
    </div>

</section>

<!-- AJAX Script -->
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

    // Hide success message after 3 seconds
    if (successMessage) {
        setTimeout(() => successMessage.style.display = 'none', 3000);
    }

    // Debounce function
    function debounce(func, wait) {
        let timeout;
        return function(...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func(...args), wait);
        };
    }

    // Perform AJAX search
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
        .catch(error => {
            console.error('Error:', error);
            alert('Search failed. Please try again.');
        })
        .finally(() => {
            loading.style.display = 'none';
            tableBody.style.opacity = '1';
        });
    }

    // Event listeners
    searchBtn.addEventListener('click', performSearch);
    searchInput.addEventListener('keypress', e => { if (e.key === 'Enter') performSearch(); });
    searchInput.addEventListener('input', debounce(performSearch, 500));
    resetBtn.addEventListener('click', () => { searchInput.value = ''; performSearch(); });

    // Pagination AJAX
    document.addEventListener('click', function(e) {
        const paginationLink = e.target.closest('.pagination a');
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

<style>
/* All the CSS styles from the previous response */
.employee-card { border-radius: 16px; border: none; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06); }
.employee-header { display: flex; justify-content: space-between; align-items: center; gap: 20px; margin-bottom: 24px; padding-bottom: 20px; border-bottom: 2px solid #f3f4f6; }
.employee-header h2 { font-size: 24px; font-weight: 700; display: flex; align-items: center; gap: 12px; color: #1e293b; }
.employee-header h2 i { color: #ff15c4; }
.emp-count { font-size: 14px; background: #f1f5f9; color: #64748b; padding: 4px 12px; border-radius: 20px; font-weight: 600; }
.employee-header p { color: #64748b; font-size: 14px; margin-top: 4px; }
.add-employee-btn { text-decoration: none; background: linear-gradient(135deg, #ff1548, #ff15c4); color: white; padding: 12px 20px; border-radius: 12px; font-weight: 700; display: flex; align-items: center; gap: 10px; white-space: nowrap; }
.add-employee-btn:hover { transform: translateY(-2px); }
.success-message { background: #dcfce7; color: #166534; padding: 14px 18px; border-radius: 12px; margin-bottom: 20px; font-weight: 600; display: flex; align-items: center; gap: 10px; }
.search-box { display: flex; align-items: center; gap: 12px; margin-bottom: 24px; flex-wrap: wrap; }
.search-input { flex: 1; min-width: 200px; display: flex; align-items: center; gap: 12px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 0 16px; }
.search-input i { color: #94a3b8; }
.search-input input { flex: 1; padding: 14px 0; border: none; outline: none; background: transparent; font-size: 14px; }
.search-btn, .reset-btn { padding: 14px 24px; border: none; border-radius: 12px; font-weight: 700; cursor: pointer; transition: all 0.3s; }
.search-btn { background: #1583ff; color: white; }
.reset-btn { background: #f1f5f9; color: #64748b; }
.loading-indicator { display: none; align-items: center; justify-content: center; gap: 12px; padding: 20px; color: #64748b; font-weight: 600; }
.spinner { width: 20px; height: 20px; border: 3px solid #f3f3f3; border-top: 3px solid #1583ff; border-radius: 50%; animation: spin 1s linear infinite; }
@keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
.table-wrapper { overflow-x: hidden; border-radius: 16px; border: 1px solid #e2e8f0; }
.data-table { width: 100%; border-collapse: collapse; table-layout: fixed; }
.data-table thead { background: #1a1a2e; }
.data-table th { padding: 16px 12px; color: white; font-size: 11px; font-weight: 700; text-transform: uppercase; text-align: left; white-space: nowrap; }
.data-table td { padding: 16px 12px; border-bottom: 1px solid #f1f5f9; font-size: 14px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.emp-info { display: flex; align-items: center; gap: 12px; }
.emp-info img, .emp-avatar { width: 45px; height: 45px; min-width: 45px; border-radius: 50%; object-fit: cover; }
.emp-avatar { background: linear-gradient(135deg, #ff1548, #ff15c4); color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 16px; }
.emp-info strong { font-size: 14px; color: #1e293b; }
.email-text, .dept-text, .date-text { color: #475569; font-weight: 500; max-width: 150px; overflow: hidden; text-overflow: ellipsis; display: block; }
.role-badge { display: inline-block; padding: 6px 12px; background: #e0f2fe; color: #0284c7; border-radius: 20px; font-size: 12px; font-weight: 600; white-space: nowrap; }
.status-badge { display: inline-block; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 700; white-space: nowrap; }
.status-badge.active { background: #dcfce7; color: #16a34a; }
.status-badge.inactive { background: #fee2e2; color: #dc2626; }
.action-buttons { display: flex; gap: 8px; }
.action-btn { padding: 8px 12px; border: none; border-radius: 10px; font-size: 11px; font-weight: 700; cursor: pointer; white-space: nowrap; }
.action-btn.deactivate { background: #fef3c7; color: #b45309; }
.action-btn.activate { background: #dcfce7; color: #16a34a; }
.action-btn.delete { background: #fee2e2; color: #dc2626; }
.action-btn:hover { transform: translateY(-2px); }
.empty-cell { text-align: center; padding: 40px; color: #94a3b8; }
.empty-cell i { font-size: 40px; margin-bottom: 12px; display: block; }
.pagination-wrapper { margin-top: 24px; display: flex; justify-content: flex-end; }
@media (max-width: 768px) {
    .employee-header { flex-direction: column; align-items: stretch; }
    .add-employee-btn { justify-content: center; }
    .search-box { flex-direction: column; }
    .search-input, .search-btn, .reset-btn { width: 100%; }
}
</style>

@endsection