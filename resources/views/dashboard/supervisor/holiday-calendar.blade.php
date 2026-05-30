@extends('dashboard.supervisor.layout')

@section('content')

<div class="manager-page-card">
    <div class="page-header">
        <div class="header-content">
            <h1>Holiday Calendar</h1>
            <p>View approved and pending staff holidays in calendar format.</p>
        </div>
        
        <a href="{{ route('manager.reports.holiday.form') }}" class="pdf-btn">
            <i class="fa-solid fa-file-pdf"></i>
            Generate PDF
        </a>
    </div>

    <div class="legend">
        <span><i class="dot approved"></i> Approved</span>
        <span><i class="dot pending"></i> Pending</span>
    </div>

    <div id="calendar"></div>
</div>

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css" rel="stylesheet">

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar');

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        height: 'auto',
        events: @json($events),

        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,listWeek'
        },

        eventClick: function(info) {
            const props = info.event.extendedProps;

            alert(
                'Employee: ' + props.employee + '\n' +
                'Department: ' + props.department + '\n' +
                'Status: ' + props.status + '\n' +
                'Total Days: ' + props.total_days + '\n' +
                'Reason: ' + props.reason
            );
        }
    });

    calendar.render();
});
</script>

<style>
    :root {
        --primary: #8b5cf6;
        --primary-hover: #a78bfa;
        --secondary: #ec4899;
        
        --bg-card: #27272a;
        --bg-input: #1c1c1f;
        
        --text-main: #fafafa;
        --text-muted: #a1a1aa;
        --text-dim: #71717a;
        
        --border: #3f3f46;
        --border-light: #52525b;
        
        --success: #10b981;
        --warning: #f59e0b;
        --danger: #ef4444;
        
        --glow: 0 0 20px rgba(139, 92, 246, 0.3);
        
        --radius-lg: 1.5rem;
        --radius-md: 1rem;
    }

    .manager-page-card {
        background: var(--bg-card);
        border-radius: var(--radius-lg);
        padding: 28px;
        border: 1px solid var(--border);
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        animation: fadeIn 0.4s ease-out;
    }

    .page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }

    .header-content h1 {
        margin: 0 0 6px;
        font-size: 26px;
        font-weight: 800;
        background: linear-gradient(135deg, #fff 0%, #a1a1aa 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .header-content p {
        margin: 0;
        color: var(--text-muted);
        font-size: 14px;
        font-weight: 500;
    }

    .pdf-btn {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 14px 24px;
        background: linear-gradient(135deg, var(--danger), #b91c1c);
        color: white;
        text-decoration: none;
        border-radius: var(--radius-md);
        font-weight: 700;
        font-size: 14px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }

    .pdf-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4);
    }

    .legend {
        display: flex;
        gap: 24px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }

    .legend span {
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 600;
        color: var(--text-muted);
        font-size: 14px;
    }

    .legend .dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
    }

    .legend .approved {
        background: var(--success);
        box-shadow: 0 0 8px rgba(16, 185, 129, 0.4);
    }

    .legend .pending {
        background: var(--warning);
        box-shadow: 0 0 8px rgba(245, 158, 11, 0.4);
    }

    /* --- FullCalendar Dark Theme Overrides --- */
    #calendar {
        overflow-x: auto;
    }

    .fc {
        --fc-border-color: var(--border);
        --fc-button-bg-color: var(--bg-input);
        --fc-button-border-color: var(--border);
        --fc-button-text-color: var(--text-main);
        --fc-button-hover-bg-color: var(--border);
        --fc-button-hover-border-color: var(--border-light);
        --fc-button-active-bg-color: var(--primary);
        --fc-button-active-border-color: var(--primary);
        --fc-today-bg-color: rgba(139, 92, 246, 0.1);
        --fc-event-bg-color: var(--primary);
        --fc-event-border-color: var(--primary);
        --fc-neutral-bg-color: var(--bg-card);
        --fc-page-bg-color: transparent;
        --fc-list-event-hover-bg-color: var(--bg-input);
    }

    .fc .fc-toolbar-title {
        font-size: 22px !important;
        font-weight: 800;
        color: var(--text-main);
    }

    .fc .fc-col-header-cell-cushion,
    .fc .fc-daygrid-day-number,
    .fc .fc-daygrid-week-number {
        color: var(--text-muted);
        text-decoration: none;
        font-weight: 600;
    }

    .fc .fc-daygrid-day.fc-day-today .fc-daygrid-day-number {
        background: var(--primary);
        color: white;
        border-radius: 8px;
        width: 28px;
        height: 28px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .fc .fc-button {
        background: var(--bg-input) !important;
        border: 1px solid var(--border) !important;
        border-radius: 10px !important;
        font-weight: 700 !important;
        font-size: 13px !important;
        padding: 10px 16px !important;
        transition: all 0.2s ease !important;
    }

    .fc .fc-button:hover {
        border-color: var(--primary) !important;
        box-shadow: var(--glow) !important;
    }

    .fc .fc-button:focus {
        box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.3) !important;
    }

    .fc .fc-button-primary:not(:disabled).fc-button-active,
    .fc .fc-button-primary:not(:disabled):active {
        background: var(--primary) !important;
        border-color: var(--primary) !important;
    }

    .fc-event {
        border: none !important;
        padding: 6px 10px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    }

    .fc-event:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
    }

    .fc-daygrid-event-dot {
        border-color: var(--primary) !important;
    }

    .fc-timegrid-slot {
        height: 48px;
    }

    .fc-timegrid-slot-label-cushion {
        color: var(--text-dim);
    }

    /* Scrollbar */
    .fc .fc-scroller::-webkit-scrollbar {
        height: 8px;
        width: 8px;
    }

    .fc .fc-scroller::-webkit-scrollbar-track {
        background: var(--bg-input);
        border-radius: 4px;
    }

    .fc .fc-scroller::-webkit-scrollbar-thumb {
        background: var(--border);
        border-radius: 4px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .manager-page-card {
            padding: 20px;
            border-radius: var(--radius-md);
        }

        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .pdf-btn {
            width: 100%;
            justify-content: center;
        }

        .header-content h1 {
            font-size: 22px;
        }

        .fc-toolbar {
            flex-direction: column;
            gap: 12px;
        }

        .fc-header-toolbar {
            flex-direction: column;
            gap: 8px !important;
        }

        .fc .fc-toolbar {
            flex-direction: column;
            gap: 12px;
        }

        .fc .fc-toolbar-title {
            font-size: 18px !important;
        }

        .legend {
            gap: 16px;
        }
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

@endsection