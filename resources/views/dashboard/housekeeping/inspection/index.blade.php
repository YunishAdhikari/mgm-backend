@extends('dashboard.housekeeping.layout')

@section('content')
<div class="hk-page">

    <div class="page-header">
        <div>
            <h1>Inspection Queue</h1>
        </div>

        <div class="header-badge">
            {{ $rooms->count() }} Waiting
        </div>
    </div>

    @if(session('success'))
        <div class="alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    @if($rooms->isEmpty())
        <div class="empty-card">
            <div class="empty-icon">
                <i class="fas fa-clipboard-check"></i>
            </div>
            <h3>No rooms waiting for inspection</h3>
            <p>Cleaned rooms will appear here once staff mark them as cleaned from the app.</p>
        </div>
    @else
        <div class="inspection-grid">
            @foreach($rooms as $allocation)
                <div class="inspection-card">
                    <div class="room-main">
                        <div class="room-number">
                            {{ $allocation->room->room_number ?? '-' }}
                        </div>

                        <div>
                            <h3>
                                {{ ucfirst(str_replace('_', ' ', $allocation->roomStatusUpdate->status ?? 'Room')) }}
                            </h3>
                            <p>
                                Cleaned by:
                                <strong>{{ $allocation->assignedTo->name ?? 'Unknown Staff' }}</strong>
                            </p>
                            <p>
                                Cleaned at:
                                <strong>
                                    {{ $allocation->cleaned_at ? \Carbon\Carbon::parse($allocation->cleaned_at)->format('H:i') : '-' }}
                                </strong>
                            </p>
                        </div>
                    </div>

                    @if($allocation->notes)
                        <div class="notes-box">
                            <i class="fas fa-sticky-note"></i>
                            {{ $allocation->notes }}
                        </div>
                    @endif

                    <div class="actions">
                        <form method="POST" action="{{ route('housekeeping.inspection.approve', $allocation->id) }}">
                            @csrf
                            <button type="submit" class="btn-approve">
                                <i class="fas fa-check"></i> Approve
                            </button>
                        </form>

                        <button type="button"
                                class="btn-reject"
                                onclick="openRejectModal('{{ $allocation->id }}')">
                            <i class="fas fa-times"></i> Reject
                        </button>
                    </div>
                </div>

                <div id="reject-modal-{{ $allocation->id }}" class="modal-overlay" style="display:none;">
                    <div class="modal-box">
                        <div class="modal-header">
                            <h3><i class="fas fa-exclamation-triangle"></i> Reject Room {{ $allocation->room->room_number ?? '-' }}</h3>
                            <button class="modal-close" onclick="closeRejectModal('{{ $allocation->id }}')">&times;</button>
                        </div>

                        <form method="POST" action="{{ route('housekeeping.inspection.reject', $allocation->id) }}">
                            @csrf

                            <label>Reason for rejection</label>
                            <textarea name="reason" rows="4" required placeholder="Example: Dust under bed, bathroom not cleaned, bins not emptied..."></textarea>

                            <div class="modal-actions">
                                <button type="button" class="btn-cancel" onclick="closeRejectModal('{{ $allocation->id }}')">
                                    Cancel
                                </button>
                                <button type="submit" class="btn-reject-submit">
                                    <i class="fas fa-times"></i> Reject Room
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<style>
/* ============ RED THEME VARIABLES ============ */
:root {
    --red-primary: #ef4444;
    --red-dark: #dc2626;
    --red-light: #f87171;
    --red-subtle: rgba(239, 68, 68, 0.15);
    --red-glow: rgba(239, 68, 68, 0.3);
    
    --bg-dark: #18181b;
    --bg-card: #27272a;
    --bg-input: #27272a;
    
    --border-default: #3f3f46;
    --border-hover: #52525b;
    
    --text-primary: #fafafa;
    --text-secondary: #a1a1aa;
    --text-muted: #71717a;
}

/* ============ PAGE ============ */
.hk-page {
    padding: 24px;
    color: var(--text-primary);
}

/* ============ PAGE HEADER ============ */
.page-header {
    background: var(--bg-dark);
    border: 1px solid var(--border-default);
    border-radius: 20px;
    padding: 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 22px;
}

.page-header h1 {
    font-size: 30px;
    font-weight: 800;
    margin: 0;
}

.page-header p {
    color: var(--text-secondary);
    margin-top: 6px;
}

.header-badge {
    background: rgba(239, 68, 68, 0.14);
    color: var(--red-light);
    padding: 10px 16px;
    border-radius: 999px;
    font-weight: 800;
    font-size: 14px;
}

/* ============ ALERT SUCCESS ============ */
.alert-success {
    background: rgba(239, 68, 68, 0.12);
    border: 1px solid rgba(239, 68, 68, 0.4);
    color: var(--red-light);
    padding: 14px 16px;
    border-radius: 14px;
    margin-bottom: 18px;
    display: flex;
    align-items: center;
    gap: 10px;
}

/* ============ EMPTY CARD ============ */
.empty-card {
    background: var(--bg-dark);
    border: 1px solid var(--border-default);
    border-radius: 20px;
    padding: 48px 34px;
    text-align: center;
}

.empty-icon {
    width: 80px;
    height: 80px;
    background: rgba(239, 68, 68, 0.1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    font-size: 32px;
    color: var(--red-primary);
}

.empty-card h3 {
    font-size: 22px;
    margin-bottom: 8px;
}

.empty-card p {
    color: var(--text-secondary);
}

/* ============ INSPECTION GRID ============ */
.inspection-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 18px;
}

.inspection-card {
    background: var(--bg-dark);
    border: 1px solid var(--border-default);
    border-radius: 20px;
    padding: 22px;
    transition: all 0.2s;
}

.inspection-card:hover {
    border-color: var(--red-primary);
    box-shadow: 0 0 20px rgba(239, 68, 68, 0.15);
}

/* ============ ROOM MAIN ============ */
.room-main {
    display: flex;
    align-items: center;
    gap: 16px;
}

.room-number {
    width: 72px;
    height: 72px;
    border-radius: 18px;
    background: rgba(239, 68, 68, 0.14);
    color: var(--red-light);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    font-weight: 900;
    flex-shrink: 0;
}

.inspection-card h3 {
    margin: 0;
    font-size: 22px;
}

.inspection-card p {
    margin: 6px 0 0;
    color: var(--text-secondary);
}

.inspection-card strong {
    color: var(--text-primary);
}

/* ============ NOTES BOX ============ */
.notes-box {
    margin-top: 16px;
    background: var(--bg-card);
    border: 1px solid var(--border-default);
    border-radius: 14px;
    padding: 14px;
    color: var(--text-secondary);
    display: flex;
    align-items: flex-start;
    gap: 10px;
    font-size: 14px;
}

.notes-box i {
    color: var(--red-primary);
    margin-top: 2px;
}

/* ============ ACTIONS ============ */
.actions {
    display: flex;
    gap: 12px;
    margin-top: 18px;
}

.btn-approve,
.btn-reject,
.btn-cancel,
.btn-reject-submit {
    border: none;
    border-radius: 12px;
    padding: 12px 18px;
    font-weight: 800;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: all 0.2s;
}

.btn-approve {
    background: #22c55e;
    color: white;
    flex: 1;
}

.btn-approve:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
}

.btn-reject {
    background: var(--bg-card);
    border: 1px solid var(--red-primary);
    color: var(--red-light);
}

.btn-reject:hover {
    background: var(--red-primary);
    color: white;
}

/* ============ MODAL ============ */
.modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.72);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

.modal-box {
    background: var(--bg-dark);
    border: 1px solid var(--border-default);
    border-radius: 20px;
    padding: 24px;
    width: 460px;
    max-width: 92%;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.modal-header h3 {
    margin: 0;
    font-size: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.modal-header h3 i {
    color: var(--red-primary);
}

.modal-close {
    background: none;
    border: none;
    color: var(--text-muted);
    font-size: 28px;
    cursor: pointer;
    transition: color 0.2s;
}

.modal-close:hover {
    color: var(--red-primary);
}

.modal-box label {
    display: block;
    color: var(--text-secondary);
    margin-bottom: 8px;
    font-weight: 600;
}

.modal-box textarea {
    width: 100%;
    background: var(--bg-card);
    color: var(--text-primary);
    border: 1px solid var(--border-default);
    border-radius: 12px;
    padding: 14px;
    resize: vertical;
    font-family: inherit;
    font-size: 14px;
}

.modal-box textarea:focus {
    outline: none;
    border-color: var(--red-primary);
}

.modal-actions {
    margin-top: 20px;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}

.btn-cancel {
    background: var(--bg-card);
    color: var(--text-secondary);
}

.btn-cancel:hover {
    border-color: var(--border-hover);
}

.btn-reject-submit {
    background: var(--red-primary);
    color: white;
}

.btn-reject-submit:hover {
    background: var(--red-dark);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
}

/* ============ RESPONSIVE ============ */
@media (max-width: 900px) {
    .inspection-grid {
        grid-template-columns: 1fr;
    }

    .page-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 14px;
    }
}
</style>

<script>
function openRejectModal(id) {
    document.getElementById('reject-modal-' + id).style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeRejectModal(id) {
    document.getElementById('reject-modal-' + id).style.display = 'none';
    document.body.style.overflow = 'auto';
}

// Close modal on outside click
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('modal-overlay')) {
        const modalId = e.target.id.replace('reject-modal-', '');
        closeRejectModal(modalId);
    }
});

// Close modal on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const openModals = document.querySelectorAll('.modal-overlay[style*="flex"]');
        openModals.forEach(modal => {
            const id = modal.id.replace('reject-modal-', '');
            closeRejectModal(id);
        });
    }
});
</script>
@endsection