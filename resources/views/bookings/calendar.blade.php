@extends('layouts.app')

@section('title', 'Booking Calendar')

@section('content')
<div class="bg-zinc-950 rounded-xl border border-zinc-800 shadow-sm p-6 max-w-6xl mx-auto">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <h1 class="text-2xl font-bold text-zinc-50">Resource Calendar</h1>
        
        <div class="flex items-center gap-4">
            <form method="GET" action="{{ route('bookings.calendar') }}" class="flex items-center gap-2">
                <select name="resource_id" onchange="this.form.submit()" class="reveal-up bg-zinc-900 border border-zinc-800 rounded-lg px-3 py-1.5 text-sm text-zinc-100 focus:outline-none focus:border-zinc-700">
                    <option value="">All Resources</option>
                    @foreach($resources as $resource)
                        <option value="{{ $resource->id }}" {{ $selectedResourceId == $resource->id ? 'selected' : '' }}>
                            {{ $resource->name }}
                        </option>
                    @endforeach
                </select>
            </form>

            @can('create', App\Models\Booking::class)
            <a href="{{ route('bookings.create', ['resource_id' => $selectedResourceId]) }}" class="inline-flex items-center justify-center px-4 py-1.5 rounded-lg text-sm font-medium bg-zinc-50 text-zinc-950 hover:bg-zinc-200 transition-colors">Book Resource</a>
            @endcan
        </div>
    </div>
    
    <div id="calendar" class="min-h-[600px]"></div>
</div>
@endsection

@section('scripts')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var businessHoursData = @json($businessHours ?? []);
        
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'timeGridWeek',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            slotMinTime: '06:00:00',
            slotMaxTime: '22:00:00',
            allDaySlot: true,
            dayMaxEvents: 3,
            eventDisplay: 'block',
            eventTimeFormat: {
                hour: 'numeric',
                minute: '2-digit',
                meridiem: 'short'
            },
            events: @json($events),
            businessHours: businessHoursData.length > 0 ? businessHoursData : false,
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
    /* FullCalendar Premium SaaS styling overrides for Tailwind Dark Theme */
    .fc-theme-standard .fc-scrollgrid {
        border-color: #27272a !important; /* zinc-800 */
    }
    .fc-theme-standard td, .fc-theme-standard th {
        border-color: #27272a !important; /* zinc-800 */
    }
    .fc-theme-standard th {
        background-color: #18181b !important; /* zinc-900 */
        padding: 12px 0 !important;
        font-weight: 600;
        font-size: 13px;
        color: #a1a1aa; /* zinc-400 */
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .fc-event {
        border: none;
        border-radius: 6px;
        padding: 4px;
        cursor: pointer;
        box-shadow: 0 1px 3px rgba(0,0,0,0.3);
    }
    .fc-event-main {
        font-weight: 600;
        font-size: 11px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        padding-left: 2px;
    }
    .fc-button-primary {
        background-color: #09090b !important; /* zinc-950 */
        color: #fafafa !important; /* zinc-50 */
        border-color: #27272a !important; /* zinc-800 */
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.4) !important;
        border-radius: 8px !important;
        font-weight: 600 !important;
        font-size: 13px !important;
        text-transform: capitalize !important;
    }
    .fc-button-primary:hover {
        background-color: #18181b !important; /* zinc-900 */
        border-color: #3f3f46 !important; /* zinc-700 */
    }
    .fc-button-primary:disabled {
        background-color: #18181b !important; /* zinc-900 */
        color: #71717a !important; /* zinc-500 */
        border-color: #27272a !important; /* zinc-800 */
    }
    .fc-button-active {
        background-color: rgba(16, 185, 129, 0.1) !important;
        color: #34d399 !important; /* emerald-400 */
        border-color: #34d399 !important;
    }
    .fc-toolbar-title {
        font-size: 18px !important;
        font-weight: 700 !important;
        color: #fafafa !important; /* zinc-50 */
    }
    .fc-timegrid-slot-label-cushion {
        font-size: 12px;
        font-weight: 500;
        color: #a1a1aa; /* zinc-400 */
    }
    .fc-day-today {
        background-color: rgba(52, 211, 153, 0.05) !important;
    }
</style>
@endsection