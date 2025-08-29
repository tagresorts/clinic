<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg h-full">
    <div class="p-6">
        <h3 class="text-lg font-semibold mb-4">Clinic Calendar</h3>
        <div id="calendar"></div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'timeGridWeek',
            headerToolbar: { left: 'prev,next today', center: 'title', right: 'dayGridMonth,timeGridWeek,timeGridDay' },
            slotMinTime: '08:00:00',
            slotMaxTime: '20:00:00',
            events: '/appointments/feed'
        });
        calendar.render();
    });
</script>
@endpush
