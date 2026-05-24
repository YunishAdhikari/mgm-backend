@extends('dashboard.supervisor.layout')

@section('content')

<div class="manager-page-card">
    {{-- <div class="page-header">
        <div>
            <h1>Holiday Calendar</h1>
            <p>View approved and pending staff holidays in calendar format.</p>
        </div>
    </div> --}}

    <div class="page-header page-header-flex">
    <div>
        <h1>Holiday Calendar</h1>
        {{-- <p>View approved and pending staff holidays in calendar format.</p> --}}
    </div>

    {{-- <a href="{{ route('manager.reports.holiday.form') }}" class="pdf-btn">
        <i class="fa-solid fa-file-pdf"></i>
        Generate Holiday PDF
    </a> --}}
</div>

    <div class="legend">
        <span><i class="approved"></i> Approved</span>
        <span><i class="pending"></i> Pending</span>
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
            right: 'dayGridMonth,timeGridWeek,listMonth'
        },

        eventClick: function(info) {
            const props = info.event.extendedProps;

            alert(
                'Employee: ' + props.employee +
                '\nDepartment: ' + props.department +
                '\nStatus: ' + props.status +
                '\nTotal Days: ' + props.total_days +
                '\nReason: ' + props.reason
            );
        }
    });

    calendar.render();
});
</script>

<style>
.manager-page-card {
    background: white;
    border-radius: 24px;
    padding: 24px;
    box-shadow: 0 10px 28px rgba(0,0,0,0.06);
}

.page-header {
    margin-bottom: 20px;
}

.page-header h1 {
    margin: 0 0 6px;
    color: #111827;
}

.page-header p {
    margin: 0;
    color: #6b7280;
}

.legend {
    display: flex;
    gap: 18px;
    margin-bottom: 18px;
    flex-wrap: wrap;
}

.legend span {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 800;
    color: #374151;
}

.legend i {
    width: 14px;
    height: 14px;
    border-radius: 50%;
    display: inline-block;
}

.legend .approved {
    background: #22c55e;
}

.legend .pending {
    background: #f59e0b;
}

#calendar {
    overflow-x: auto;
}

.fc {
    font-family: Arial, sans-serif;
}

.fc-toolbar-title {
    font-size: 24px !important;
    font-weight: 900;
    color: #111827;
}

.fc-button {
    background: #1583ff !important;
    border: none !important;
    border-radius: 10px !important;
    font-weight: 800 !important;
}

.fc-event {
    border: none !important;
    padding: 4px 6px;
    border-radius: 8px;
    font-weight: 700;
    cursor: pointer;
}

@media(max-width: 700px) {
    .fc-header-toolbar {
        flex-direction: column;
        gap: 12px;
    }
}


.page-header-flex {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
}

.pdf-btn {
    text-decoration: none;
    background: #dc2626;
    color: white;
    padding: 12px 18px;
    border-radius: 14px;
    font-weight: 800;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

@media(max-width: 700px) {
    .page-header-flex {
        flex-direction: column;
        align-items: flex-start;
    }

    .pdf-btn {
        width: 100%;
        justify-content: center;
    }
}
</style>

@endsection