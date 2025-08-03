<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Appointment Calendar') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="mb-4 flex flex-wrap space-x-4">
                <div>
                    <label for="dentist_filter" class="block text-sm font-medium text-gray-700">Filter by Dentist:</label>
                    <select id="dentist_filter" name="dentist_filter" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option value="">All Dentists</option>
                        @foreach($dentists as $dentist)
                            <option value="{{ $dentist->id }}">{{ $dentist->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="status_filter" class="block text-sm font-medium text-gray-700">Filter by Status:</label>
                    <select id="status_filter" name="status_filter" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option value="">All Statuses</option>
                        @foreach($appointmentStatuses as $status)
                            <option value="{{ $status }}">{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex flex-col md:flex-row gap-6">
                <div class="w-full md:w-3/4 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div id='calendar'></div>
                    </div>
                </div>
                <div class="w-full md:w-1/4">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200 space-y-4">
                            <h3 class="text-lg font-medium text-gray-900">Schedule Summary</h3>

                            <!-- Date Range Selector -->
                            <div>
                                <div class="flex items-center space-x-2">
                                    <x-text-input type="date" id="summary_start_date" name="summary_start_date" class="block w-full" />
                                    <span class="text-gray-500">to</span>
                                    <x-text-input type="date" id="summary_end_date" name="summary_end_date" class="block w-full" />
                                </div>
                                <x-primary-button id="update_summary_btn" class="mt-2 w-full justify-center">Update Summary</x-primary-button>
                            </div>

                            <hr/>

                            <div id="schedule-summary-content">
                                <p class="text-gray-500">Select a day or a date range to see the summary.</p>
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
            var summaryContentEl = document.getElementById('schedule-summary-content');
            var startDateInput = document.getElementById('summary_start_date');
            var endDateInput = document.getElementById('summary_end_date');
            var updateSummaryBtn = document.getElementById('update_summary_btn');
            var dentistFilter = document.getElementById('dentist_filter');
            var statusFilter = document.getElementById('status_filter');

            // --- Calendar Initialization ---
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek'
                },
                views: {
                    dayGridMonth: {
                        eventDisplay: 'list-item' // Render events as a list item
                    }
                },
                events: {
                    url: '{{ route("appointments.feed") }}',
                    extraParams: function() {
                        return {
                            dentist_id: dentistFilter.value,
                            appointment_status: statusFilter.value
                        };
                    },
                    failure: function(error) {
                        let errorMsg = 'An unknown error occurred while fetching events.';
                        // Note: error object structure might differ with non-fetch sources
                        // This part might need adjustment if the server error format is different.
                        if (error.xhr && error.xhr.responseJSON && error.xhr.responseJSON.message) {
                             errorMsg = 'Error: ' + error.xhr.responseJSON.message + '\\nFile: ' + error.xhr.responseJSON.file + '\\nLine: ' + error.xhr.responseJSON.line;
                        }
                        alert(errorMsg);
                    }
                },
                eventClick: function(info) {
                    info.jsEvent.preventDefault();
                    if (info.event.url) {
                        window.open(info.event.url, '_blank');
                    }
                },
                dateClick: function(info) {
                    // When a date is clicked, update the summary for that single day
                    startDateInput.value = info.dateStr;
                    endDateInput.value = info.dateStr;
                    updateSummary(info.dateStr, info.dateStr);
                }
            });
            calendar.render();

            // --- Event Listeners ---
            dentistFilter.addEventListener('change', function() {
                calendar.refetchEvents();
            });
            statusFilter.addEventListener('change', function() {
                calendar.refetchEvents();
            });

            // --- Summary Panel Logic ---
            function updateSummary(startDate, endDate) {
                summaryContentEl.innerHTML = '<p class="text-gray-500">Loading summary...</p>';

                const url = `{{ route('api.appointments.summary') }}?start_date=${startDate}&end_date=${endDate}`;

                fetch(url, {
                    headers: {
                        'Authorization': 'Bearer {{ auth()->user()->createToken("api-token")->plainTextToken }}', // This is not ideal for production
                        'Accept': 'application/json',
                    },
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    renderSummary(data, startDate, endDate);
                })
                .catch(error => {
                    summaryContentEl.innerHTML = `<p class="text-red-500">Error loading summary: ${error.message}</p>`;
                    console.error('Error fetching summary:', error);
                });
            }

            function renderSummary(appointments, startDate, endDate) {
                summaryContentEl.innerHTML = ''; // Clear current summary

                let titleText = `Appointments for ${new Date(startDate).toLocaleDateString()}`;
                if (startDate !== endDate) {
                    titleText = `Appointments from ${new Date(startDate).toLocaleDateString()} to ${new Date(endDate).toLocaleDateString()}`;
                }

                let title = document.createElement('h4');
                title.className = 'text-md font-bold mb-2';
                title.textContent = titleText;
                summaryContentEl.appendChild(title);

                if (appointments.length > 0) {
                    let list = document.createElement('ul');
                    list.className = 'space-y-3';
                    appointments.forEach(appt => {
                        let item = document.createElement('li');
                        item.className = 'p-2 rounded-lg border border-gray-200';

                        item.innerHTML = `
                            <a href="${appt.url}" target="_blank" class="block hover:bg-gray-50">
                                <p class="font-semibold">${appt.patient_name}</p>
                                <p class="text-sm text-gray-600">${appt.time} - ${appt.type}</p>
                                <p class="text-sm text-gray-600">${appt.dentist_name}</p>
                            </a>
                        `;
                        list.appendChild(item);
                    });
                    summaryContentEl.appendChild(list);
                } else {
                    summaryContentEl.innerHTML += '<p class="text-gray-500">No appointments in this period.</p>';
                }
            }

            // --- Event Listeners ---
            updateSummaryBtn.addEventListener('click', function() {
                if (startDateInput.value && endDateInput.value) {
                    updateSummary(startDateInput.value, endDateInput.value);
                } else {
                    alert('Please select a start and end date.');
                }
            });

            // --- Initial Load ---
            function formatDate(date) {
                const d = new Date(date);
                let month = '' + (d.getMonth() + 1);
                let day = '' + d.getDate();
                const year = d.getFullYear();

                if (month.length < 2) month = '0' + month;
                if (day.length < 2) day = '0' + day;

                return [year, month, day].join('-');
            }

            const todayStr = formatDate(new Date());
            startDateInput.value = todayStr;
            endDateInput.value = todayStr;
            updateSummary(todayStr, todayStr);
        });
    </script>
    @endpush
</x-app-layout>
