@extends('dashboard.manager.layout')

@section('content')

<!-- Breadcrumb -->
<div class="breadcrumb">
    <span><i class="fas fa-home"></i> Home</span>
    <span class="separator">/</span>
    <span class="current">Holiday Calendar</span>
</div>

<!-- Page Header -->
<div class="page-header">
    <div>
        <h1><i class="fas fa-calendar-alt"></i> Holiday Calendar</h1>
    </div>

    <a href="{{ route('manager.reports.holiday.form') }}" class="pdf-btn">
        <i class="fas fa-file-pdf"></i> Generate Holiday PDF
    </a>
</div>

<!-- Legend -->
<div class="legend">
    <span><span class="legend-dot approved"></span> Approved</span>
    <span><span class="legend-dot pending"></span> Pending</span>
    <span><span class="legend-dot rejected"></span> Rejected</span>
</div>

<!-- Calendar Card -->
<div class="calendar-card">
    <div id="calendar"></div>
</div>

<!-- FullCalendar CSS & JS -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        height: 'auto',
        events: @json($events),

        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,listMonth'
        },

        eventClick: function(info) {
            var props = info.event.extendedProps;
            
            var modal = document.createElement('div');
            modal.className = 'event-modal';
            modal.innerHTML = `
                <div class="modal-content">
                    <span class="close-btn" onclick="this.closest('.event-modal').remove()">&times;</span>
                    <h3><i class="fas fa-user"></i> ${props.employee}</h3>
                    <p><strong>Department:</strong> ${props.department}</p>
                    <p><strong>Status:</strong> <span class="badge status-${props.status}">${props.status}</span></p>
                    <p><strong>Total Days:</strong> ${props.total_days}</p>
                    <p><strong>Reason:</strong> ${props.reason}</p>
                </div>
            `;
            document.body.appendChild(modal);
        }
    });

    calendar.render();
});
</script>

<style>
/* Page Header */
.page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
    padding-bottom: 16px;
    border-bottom: 1px solid var(--gray);
}

.page-header h1 {
    font-size: 24px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 12px;
}

.page-header h1 i {
    color: var(--primary);
}

.pdf-btn {
    text-decoration: none;
    background: var(--primary);
    color: white;
    padding: 12px 18px;
    border: none;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    font-weight: 600;
    transition: all 0.3s;
}

.pdf-btn:hover {
    background: var(--primary-dark);
    transform: translateY(-2px);
}

/* Legend */
.legend {
    display: flex;
    gap: 20px;
    margin-bottom: 20px;
}

.legend span {
    display: flex;
    align-items: center;
    gap: 8px;
    color: var(--text-muted);
    font-size: 14px;
}

.legend-dot {
    width: 12px;
    height: 12px;
    border-radius: 3px;
}

.legend-dot.approved {
    background: #22c55e;
}

.legend-dot.pending {
    background: #eab308;
}

.legend-dot.rejected {
    background: #ef4444;
}

/* Calendar Card */
.calendar-card {
    background: var(--dark-secondary);
    border: 1px solid var(--gray);
    border-radius: 12px;
    padding: 20px;
    overflow-x: auto;
}

/* FullCalendar Customization */
#calendar {
    font-family: 'Plus Jakarta Sans', sans-serif;
}

.fc {
    --fc-border-color: var(--gray);
    --fc-page-bg-color: var(--dark-secondary);
    --fc-neutral-bg-color: var(--dark);
    --fc-list-event-hover-bg-color: var(--gray);
    --fc-today-bg-color: rgba(220, 38, 38, 0.1);
    --fc-event-border-color: transparent;
}

.fc .fc-toolbar-title {
    font-size: 20px;
    font-weight: 700;
    color: var(--text);
}

.fc .fc-button {
    background: var(--gray) !important;
    border: 1px solid var(--gray-light) !important;
    color: var(--text) !important;
    padding: 8px 14px !important;
    font-size: 13px !important;
    font-weight: 600 !important;
    text-transform: capitalize;
}

.fc .fc-button:hover {
    background: var(--gray-light) !important;
}

.fc .fc-button-primary:not(:disabled).fc-button-active,
.fc .fc-button-primary:not(:disabled):active {
    background: var(--primary) !important;
    border-color: var(--primary) !important;
}

.fc .fc-daygrid-day-number {
    color: var(--text);
    font-size: 13px;
}

.fc .fc-daygrid-day.fc-day-today {
    background: rgba(220, 38, 38, 0.1);
}

.fc .fc-col-header-cell-cushion {
    color: var(--text-muted);
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
}

.fc-event {
    border: none !important;
    padding: 4px 8px;
    font-size: 12px;
    font-weight: 600;
    border-radius: 4px;
}

.fc-event-approved {
    background: #22c55e;
    color: white;
}

.fc-event-pending {
    background: #eab308;
    color: #1a1a1a;
}

.fc-event-rejected {
    background: #ef4444;
    color: white;
}

/* Event Modal */
.event-modal {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.8);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

.modal-content {
    background: var(--dark-secondary);
    border: 1px solid var(--gray);
    border-radius: 16px;
    padding: 24px;
    max-width: 400px;
    width: 100%;
    position: relative;
}

.close-btn {
    position: absolute;
    top: 16px;
    right: 16px;
    font-size: 28px;
    color: var(--text-muted);
    cursor: pointer;
    line-height: 1;
}

.modal-content h3 {
    font-size: 18px;
    font-weight: 700;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.modal-content h3 i {
    color: var(--primary);
}

.modal-content p {
    margin-bottom: 12px;
    font-size: 14px;
    color: var(--text-muted);
}

.modal-content strong {
    color: var(--text);
}

.modal-content .badge {
    padding: 4px 10px;
    font-size: 12px;
    font-weight: 600;
    border-radius: 100px;
}

.status-approved {
    background: rgba(34, 197, 94, 0.2);
    color: #22c55e;
}

.status-pending {
    background: rgba(234, 179, 8, 0.2);
    color: #eab308;
}

.status-rejected {
    background: rgba(239, 68, 68, 0.2);
    color: #ef4444;
}

/* Responsive */
@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
    }

    .pdf-btn {
        width: 100%;
        justify-content: center;
    }

    .legend {
        flex-wrap: wrap;
    }
}

/* Breadcrumb */
.breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 24px;
    font-size: 14px;
    color: var(--text-muted);
}

.breadcrumb .separator {
    color: var(--text-dim);
}

.breadcrumb .current {
    color: var(--text);
}
</style>

@endsection