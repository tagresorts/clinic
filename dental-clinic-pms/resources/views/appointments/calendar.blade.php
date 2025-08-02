<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Appointment Calendar') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-2 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div id='calendar'></div>
                    </div>
                </div>
                <div class="md:col-span-1">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Schedule Summary</h3>
                            <div id="schedule-summary">
                                <p class="text-gray-500">Select a day or week to see the summary.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var summaryEl = document.getElementById('schedule-summary');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek'
                },
                events: {
                    url: '{{ route("appointments.feed") }}',
                    failure: function() {
                        alert('there was an error while fetching events!');
                    }
                },
                eventClick: function(info) {
                    info.jsEvent.preventDefault();
                    if (info.event.url) {
                        window.open(info.event.url, '_blank');
                    }
                },
                dateClick: function(info) {
                    updateSummary(info.date);
                },
                datesSet: function(dateInfo) {
                    // When view changes, update summary for the first visible day
                    updateSummary(dateInfo.start);
                }
            });

            function updateSummary(date) {
                let events = calendar.getEvents().filter(e =>
                    e.start.toISOString().split('T')[0] === date.toISOString().split('T')[0]
                );

                summaryEl.innerHTML = ''; // Clear current summary

                let title = document.createElement('h4');
                title.className = 'text-lg font-bold mb-2';
                title.textContent = 'Appointments for ' + date.toLocaleDateString(undefined, { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
                summaryEl.appendChild(title);

                if (events.length > 0) {
                    let list = document.createElement('ul');
                    list.className = 'space-y-3';
                    events.sort((a, b) => a.start - b.start).forEach(event => {
                        let item = document.createElement('li');
                        item.className = 'p-3 rounded-lg';
                        item.style.backgroundColor = event.backgroundColor + '20'; // Lighter shade
                        item.style.borderColor = event.borderColor;
                        item.style.borderLeftWidth = '4px';

                        let time = event.start.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

                        item.innerHTML = `
                            <p class="font-semibold">${event.title}</p>
                            <p class="text-sm text-gray-600">${time}</p>
                            <p class="text-sm text-gray-600">${event.extendedProps.dentist}</p>
                        `;
                        list.appendChild(item);
                    });
                    summaryEl.appendChild(list);
                } else {
                    summaryEl.innerHTML += '<p class="text-gray-500">No appointments for this day.</p>';
                }
            }

            calendar.render();
            // Initial summary for today
            updateSummary(new Date());
        });
    </script>
    @endpush
</x-app-layout>
