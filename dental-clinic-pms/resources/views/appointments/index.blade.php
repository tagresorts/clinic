<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Appointments') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:p-8 bg-white border-b border-gray-200">
                    <div class="flex flex-col sm:flex-row justify-between items-center mb-6 space-y-4 sm:space-y-0">
                        <h3 class="text-2xl font-bold text-gray-900 hidden sm:block">Appointment Schedule</h3>
                        <div class="flex flex-col sm:flex-row items-center space-y-4 sm:space-y-0 sm:space-x-6 w-full sm:w-auto">
                            <form action="{{ route('appointments.index') }}" method="GET" class="flex flex-col sm:flex-row items-center space-y-2 sm:space-y-0 sm:space-x-2 w-full sm:w-auto">
                                <x-text-input id="search" name="search" type="text" class="block w-full sm:w-48" placeholder="Search by patient..." value="{{ request('search') }}" />
                                <input type="text" name="date_range" id="date_range" class="block w-full sm:w-64 border-gray-300 rounded-md" placeholder="Select date range" value="{{ request('date_range') }}">
                                <x-primary-button class="w-full sm:w-auto">
                                    {{ __('Filter') }}
                                </x-primary-button>
                                @if(request('search') || request('date_range'))
                                    <a href="{{ route('appointments.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-white border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50">Clear</a>
                                @endif
                            </form>
                             @if (auth()->user()->hasRole(['administrator', 'receptionist']))
                            <a href="{{ route('appointments.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 w-full sm:w-auto sm:ms-4">
                                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" /></svg>
                                Add Appointment
                            </a>
                            <!-- Columns visibility control -->
                            <div class="relative">
                                <button id="columns-toggle" type="button" class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                    Columns
                                    <svg class="ml-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.114l3.71-3.883a.75.75 0 111.08 1.04l-4.24 4.44a.75.75 0 01-1.08 0l-4.24-4.44a.75.75 0 01.02-1.06z" clip-rule="evenodd"/></svg>
                                </button>
                                <div id="columns-menu" class="hidden absolute right-0 mt-2 w-56 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none p-3 z-20">
                                    <p class="text-xs text-gray-500 mb-2">Show/Hide columns</p>
                                    <div class="space-y-2" id="columns-checkboxes"></div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="hidden sm:block overflow-x-auto shadow-md rounded-lg border border-gray-200">
                        <table id="appointments-table" class="min-w-full divide-y divide-gray-200" data-prefs-url="{{ route('preferences.table.store') }}">
                            <thead class="bg-gray-50">
                                <tr id="appointments-table-header">
                                    <th data-col="patient" scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-move">Patient</th>
                                    <th data-col="dentist" scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-move">Dentist</th>
                                    <th data-col="date_time" scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-move">Date & Time</th>
                                    <th data-col="type" scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-move">Type</th>
                                    <th data-col="status" scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-move">Status</th>
                                    <th data-col="actions" scope="col" class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($appointments as $appointment)
                                <tr class="hover:bg-gray-50">
                                    <td data-col="patient" class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $appointment->patient->full_name }}</div>
                                        <div class="text-xs text-gray-500">{{ $appointment->patient->patient_id }}</div>
                                    </td>
                                    <td data-col="dentist" class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-700">{{ $appointment->dentist->name }}</div>
                                    </td>
                                    <td data-col="date_time" class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-700">{{ $appointment->appointment_datetime->format('M d, Y') }}</div>
                                        <div class="text-xs text-gray-500">{{ $appointment->appointment_datetime->format('g:i A') }}</div>
                                    </td>
                                    <td data-col="type" class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $appointment->appointment_type }}</td>
                                    <td data-col="status" class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            @switch($appointment->status)
                                                @case('scheduled') bg-blue-100 text-blue-800 @break
                                                @case('confirmed') bg-green-100 text-green-800 @break
                                                @case('completed') bg-gray-200 text-gray-800 @break
                                                @case('cancelled') bg-red-100 text-red-800 @break
                                                @case('no_show') bg-yellow-100 text-yellow-800 @break
                                                @default bg-gray-100 text-gray-800
                                            @endswitch
                                        ">
                                            {{ ucfirst(str_replace('_', ' ', $appointment->status)) }}
                                        </span>
                                    </td>
                                    <td data-col="actions" class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end space-x-2">
                                            <a href="{{ route('appointments.show', $appointment) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-indigo-600 bg-indigo-50 hover:bg-indigo-100">View</a>
                                            @if(auth()->user()->hasRole(['administrator', 'receptionist']))
                                                <a href="{{ route('appointments.edit', $appointment) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-gray-700 bg-gray-100 hover:bg-gray-200">Edit</a>
                                                @if($appointment->status !== 'cancelled')
                                                <form class="inline-block" action="{{ route('appointments.destroy', $appointment) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this appointment?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200">Cancel</button>
                                                </form>
                                                @endif
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                        No appointments found.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Card View -->
                    <div class="sm:hidden">
                        <div class="grid grid-cols-1 gap-4">
                            @forelse ($appointments as $appointment)
                                <div class="bg-white shadow-lg rounded-lg p-4 border border-gray-200">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="text-lg font-bold text-gray-900">{{ $appointment->patient->full_name }}</p>
                                            <p class="text-sm text-gray-600">{{ $appointment->appointment_datetime->format('M d, Y, g:i A') }}</p>
                                        </div>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            @switch($appointment->status)
                                                @case('scheduled') bg-blue-100 text-blue-800 @break
                                                @case('confirmed') bg-green-100 text-green-800 @break
                                                @case('completed') bg-gray-200 text-gray-800 @break
                                                @case('cancelled') bg-red-100 text-red-800 @break
                                                @case('no_show') bg-yellow-100 text-yellow-800 @break
                                                @default bg-gray-100 text-gray-800
                                            @endswitch
                                        ">
                                            {{ ucfirst(str_replace('_', ' ', $appointment->status)) }}
                                        </span>
                                    </div>
                                    <div class="mt-4">
                                        <p class="text-sm text-gray-600"><span class="font-medium">Dentist:</span> Dr. {{ $appointment->dentist->name }}</p>
                                        <p class="text-sm text-gray-600"><span class="font-medium">Type:</span> {{ $appointment->appointment_type }}</p>
                                    </div>
                                    <div class="mt-4 flex justify-end space-x-2">
                                        <a href="{{ route('appointments.show', $appointment) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-indigo-600 bg-indigo-50 hover:bg-indigo-100">View</a>
                                        @if(auth()->user()->hasRole(['administrator', 'receptionist']))
                                            <a href="{{ route('appointments.edit', $appointment) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-gray-700 bg-gray-100 hover:bg-gray-200">Edit</a>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="text-center text-gray-500 py-4">
                                    No appointments found.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div class="mt-6">
                        {{ $appointments->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
