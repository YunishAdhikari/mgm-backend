@extends('dashboard.manager.layout')

@section('content')

<style>
    :root {
        --bg-page: #09090b;
        --bg-card: #18181b;
        --bg-input: #27272a;
        --text-main: #fafafa;
        --text-muted: #a1a1aa;
        --text-dim: #71717a;
        --border: #3f3f46;
        --primary: #8b5cf6;
        --primary-hover: #a78bfa;
    }

    .breadcrumb {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 20px;
        font-size: 14px;
        color: var(--text-muted);
    }

    .breadcrumb .separator { color: var(--text-dim); }
    .breadcrumb .current { color: var(--text-main); }

    .page-header { margin-bottom: 20px; }

    .page-header h1 {
        font-size: 24px;
        font-weight: 800;
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 6px;
        color: var(--text-main);
    }

    .page-header h1 i { color: var(--primary); }
    .page-header p { color: var(--text-muted); font-size: 14px; }

    .alert-success {
        background: rgba(16, 185, 129, 0.15);
        border: 1px solid rgba(16, 185, 129, 0.3);
        border-radius: 12px;
        padding: 14px 18px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
        color: #6ee7b7;
        font-weight: 700;
    }

    .complaints-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
        gap: 16px;
    }

    .complaint-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 18px;
        transition: all 0.3s ease;
    }

    .complaint-card:hover {
        border-color: var(--primary);
        box-shadow: 0 0 20px rgba(139, 92, 246, 0.15);
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 14px;
    }

    .card-guest strong {
        display: block;
        font-size: 16px;
        font-weight: 700;
        color: var(--text-main);
    }

    .card-guest small {
        font-size: 13px;
        color: var(--text-muted);
    }

    .type-badge {
        padding: 5px 12px;
        font-size: 12px;
        font-weight: 600;
        background: rgba(139, 92, 246, 0.15);
        color: #a78bfa;
        border-radius: 20px;
    }

    .card-issue strong {
        display: block;
        color: var(--text-main);
        margin-bottom: 6px;
    }

    .card-issue small {
        font-size: 13px;
        color: var(--text-muted);
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .card-meta {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
        margin: 14px 0;
        padding: 12px 0;
        border-top: 1px solid var(--border);
        border-bottom: 1px solid var(--border);
    }

    .meta-item { font-size: 13px; }
    .meta-label { color: var(--text-dim); font-size: 11px; text-transform: uppercase; font-weight: 700; }
    .meta-value { color: var(--text-main); font-weight: 600; }

    .card-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
    }

    .status-select {
        padding: 8px 12px;
        background: var(--bg-input);
        border: 1px solid var(--border);
        border-radius: 8px;
        color: var(--text-main);
        font-size: 13px;
        cursor: pointer;
        flex: 1;
    }

    .view-btn {
        background: rgba(139, 92, 246, 0.15);
        color: #a78bfa;
        border: none;
        padding: 10px 16px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .view-btn:hover { background: var(--primary); color: white; }

    .badge { padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 700; }
    .badge-low { background: rgba(16, 185, 129, 0.15); color: #6ee7b7; }
    .badge-medium { background: rgba(59, 130, 246, 0.15); color: #60a5fa; }
    .badge-high { background: rgba(234, 179, 8, 0.15); color: #facc15; }
    .badge-urgent { background: rgba(239, 68, 68, 0.15); color: #f87171; }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: var(--text-dim);
    }

    .empty-state i { font-size: 50px; margin-bottom: 16px; opacity: 0.5; display: block; }

    /* Modal */
    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.75);
        z-index: 9999;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .modal-box {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 18px;
        width: 100%;
        max-width: 600px;
        max-height: 90vh;
        overflow-y: auto;
    }

    .modal-header {
        padding: 18px 22px;
        border-bottom: 1px solid var(--border);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-header h3 { margin: 0; font-size: 18px; color: var(--text-main); }

    .modal-close {
        background: rgba(239, 68, 68, 0.15);
        color: #f87171;
        border: none;
        width: 34px;
        height: 34px;
        border-radius: 50%;
        font-size: 22px;
        cursor: pointer;
    }

    .modal-body { padding: 22px; }

    .modal-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 14px;
    }

    .modal-info {
        background: var(--bg-input);
        border-radius: 12px;
        padding: 14px;
    }

    .modal-info label {
        display: block;
        color: var(--text-muted);
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        margin-bottom: 6px;
    }

    .modal-info span { color: var(--text-main); font-weight: 600; font-size: 14px; }

    .modal-desc { margin-top: 16px; }
    .modal-desc label {
        display: block;
        color: var(--text-muted);
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        margin-bottom: 8px;
    }

    .modal-desc-content {
        background: var(--bg-input);
        border-radius: 12px;
        padding: 14px;
        color: var(--text-main);
        white-space: pre-wrap;
        line-height: 1.6;
    }

    @media (max-width: 700px) {
        .complaints-grid { grid-template-columns: 1fr; }
        .modal-grid { grid-template-columns: 1fr; }
    }
</style>

<div class="breadcrumb">
    <span><i class="fas fa-home"></i> Home</span>
    <span class="separator">/</span>
    <span class="current">Guest Complaints</span>
</div>

<div class="page-header">
    <h1><i class="fas fa-exclamation-circle"></i> Guest Complaints</h1>
    <p>Track complaints submitted by guests and staff.</p>
</div>

@if(session('success'))
    <div class="alert-success">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

<div class="complaints-grid">
    @forelse($complaints as $complaint)
        <div class="complaint-card">
            <div class="card-header">
                <div class="card-guest">
                    <strong>{{ $complaint->guest_name ?? 'Guest' }}</strong>
                    <small>{{ $complaint->room_number ?? 'N/A' }}</small>
                </div>
                <span class="type-badge">{{ ucfirst($complaint->type) }}</span>
            </div>

            <div class="card-issue">
                <strong>{{ $complaint->title }}</strong>
                <small>{{ Str::limit($complaint->description, 80) }}</small>
            </div>

            <div class="card-meta">
                <div class="meta-item">
                    <div class="meta-label">Priority</div>
                    <span class="badge badge-{{ $complaint->priority }}">{{ ucfirst($complaint->priority) }}</span>
                </div>
                <div class="meta-item">
                    <div class="meta-label">Submitted By</div>
                    <div class="meta-value">{{ $complaint->creator->name ?? 'Guest' }}</div>
                </div>
                <div class="meta-item">
                    <div class="meta-label">Status</div>
                    <form action="{{ route('manager.complaints.status', $complaint->id) }}" method="POST">
                        @csrf @method('PATCH')
                        <select name="status" onchange="this.form.submit()" class="status-select">
                            <option value="pending" {{ $complaint->status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="in_progress" {{ $complaint->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="resolved" {{ $complaint->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                            <option value="closed" {{ $complaint->status === 'closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                    </form>
                </div>
                <div class="meta-item">
                    <div class="meta-label">Date</div>
                    <div class="meta-value">{{ $complaint->created_at->format('d M Y') }}</div>
                </div>
            </div>

            <div class="card-actions">
                <button type="button" class="view-btn" 
                    data-guest="{{ $complaint->guest_name ?? 'Guest' }}"
                    data-room="{{ $complaint->room_number ?? 'N/A' }}"
                    data-title="{{ $complaint->title }}"
                    data-description="{{ $complaint->description }}"
                    data-priority="{{ ucfirst($complaint->priority) }}"
                    data-status="{{ ucwords(str_replace('_', ' ', $complaint->status)) }}"
                    data-submitted-by="{{ $complaint->creator->name ?? 'Guest' }}"
                    data-contact="{{ $complaint->email ?? $complaint->phone ?? 'No contact' }}"
                    data-category="{{ $complaint->category ?? 'N/A' }}"
                    onclick="openModal(this)">
                    <i class="fas fa-eye"></i> View Details
                </button>
            </div>
        </div>
    @empty
        <div class="empty-state">
            <i class="fas fa-check-circle"></i>
            <p>No complaints found.</p>
        </div>
    @endforelse
</div>

<div id="complaintModal" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header">
            <h3 id="modalTitle">Complaint Details</h3>
            <button type="button" onclick="closeModal()" class="modal-close">×</button>
        </div>
        <div class="modal-body">
            <div class="modal-grid">
                <div class="modal-info">
                    <label>Guest</label>
                    <span id="modalGuest"></span>
                </div>
                <div class="modal-info">
                    <label>Room</label>
                    <span id="modalRoom"></span>
                </div>
                <div class="modal-info">
                    <label>Contact</label>
                    <span id="modalContact"></span>
                </div>
                <div class="modal-info">
                    <label>Priority</label>
                    <span id="modalPriority"></span>
                </div>
                <div class="modal-info">
                    <label>Status</label>
                    <span id="modalStatus"></span>
                </div>
                <div class="modal-info">
                    <label>Submitted By</label>
                    <span id="modalSubmittedBy"></span>
                </div>
            </div>
            <div class="modal-desc">
                <label>Description</label>
                <div id="modalDescription" class="modal-desc-content"></div>
            </div>
        </div>
    </div>
</div>

<script>
    function openModal(btn) {
        document.getElementById('modalTitle').innerText = btn.dataset.title;
        document.getElementById('modalGuest').innerText = btn.dataset.guest;
        document.getElementById('modalRoom').innerText = btn.dataset.room;
        document.getElementById('modalContact').innerText = btn.dataset.contact;
        document.getElementById('modalPriority').innerText = btn.dataset.priority;
        document.getElementById('modalStatus').innerText = btn.dataset.status;
        document.getElementById('modalSubmittedBy').innerText = btn.dataset.submittedBy;
        document.getElementById('modalDescription').innerText = btn.dataset.description;
        document.getElementById('complaintModal').style.display = 'flex';
    }

    function closeModal() {
        document.getElementById('complaintModal').style.display = 'none';
    }

    document.getElementById('complaintModal')?.addEventListener('click', function(e) {
        if (e.target.id === 'complaintModal') closeModal();
    });
</script>

@endsection