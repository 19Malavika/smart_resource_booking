@extends('layouts.app')

@section('title', 'Booking Calendar')

@section('content')
<div class="card" style="max-width: 1200px; margin: 0 auto; padding: 24px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <h1 style="font-size: 24px; font-weight: 700; color: #0f1111;">Resource Calendar</h1>
        <a href="{{ route('bookings.create') }}" class="btn btn-primary">Book a Resource</a>
    </div>
    
    <div id="calendar" style="min-height: 600px;"></div>
</div>
@endsection

@section('scripts')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'timeGridWeek',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            slotMinTime: '06:00:00',
            slotMaxTime: '22:00:00',
            allDaySlot: false,
            events: @json($events),
            eventClick: function(info) {
                if (info.event.url) {
                    window.location.href = info.event.url;
                    info.jsEvent.preventDefault(); // Don't let the browser navigate directly
                }
            },
            height: 'auto',
            nowIndicator: true,
            themeSystem: 'standard'
        });
        calendar.render();
    });
</script>
<style>
    /* FullCalendar Amazon styling overrides */
    .fc-theme-standard .fc-scrollgrid {
        border-color: var(--border-light);
    }
    .fc-theme-standard th {
        background-color: #f8fafc;
        padding: 8px 0 !important;
        font-weight: 700;
        color: #565959;
    }
    .fc-event {
        border: none;
        border-radius: 4px;
        padding: 2px 4px;
        cursor: pointer;
        box-shadow: 0 1px 2px rgba(0,0,0,0.1);
    }
    .fc-event-main {
        font-weight: 600;
        font-size: 11px;
    }
    .fc-button-primary {
        background-color: #fff !important;
        color: #0f1111 !important;
        border-color: #d5d9d9 !important;
        box-shadow: 0 2px 5px rgba(213,217,217,0.5) !important;
    }
    .fc-button-primary:hover {
        background-color: #f7fafa !important;
    }
    .fc-button-primary:disabled {
        background-color: #f0f2f2 !important;
        color: #a6a6a6 !important;
    }
    .fc-button-active {
        background-color: #f0f2f2 !important;
        box-shadow: inset 0 2px 5px rgba(15,17,17,0.15) !important;
    }
    .fc-toolbar-title {
        font-size: 18px !important;
        font-weight: 700 !important;
    }
    .fc-timegrid-slot-label-cushion {
        font-size: 12px;
        color: #565959;
    }
</style>
@endsection
