@php $calId = 'calendar-'.\Illuminate\Support\Str::uuid(); @endphp
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg h-full">
    <div class="p-4 h-full flex flex-col min-h-0">
        <h3 class="text-lg font-semibold mb-2">Clinic Calendar</h3>
        <div id="{{ $calId }}" class="flex-1 min-h-0"></div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('{{ $calId }}');
        if (!calendarEl) return;
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'timeGridWeek',
            headerToolbar: { left: 'prev,next today', center: 'title', right: 'dayGridMonth,timeGridWeek,timeGridDay' },
            slotMinTime: '08:00:00',
            slotMaxTime: '20:00:00',
            expandRows: true,
            height: '100%',
            events: '/appointments/feed'
        });
        calendar.render();

        // Resize observer to adapt to widget/card size changes (e.g., GridStack resize)
        try {
            var ro = new ResizeObserver(function() { calendar.updateSize(); });
            ro.observe(calendarEl);
            // Also observe parent flex container for padding/height changes
            if (calendarEl.parentElement) ro.observe(calendarEl.parentElement);
        } catch (e) {
            // Fallback: update on window resize
            window.addEventListener('resize', function(){ calendar.updateSize(); });
        }
    });
</script>
@endpush
