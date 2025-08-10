<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Patients') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:p-8 bg-white border-b border-gray-200">
                    <div class="flex flex-col sm:flex-row justify-between items-center mb-6 space-y-4 sm:space-y-0">
                        <h3 class="text-2xl font-bold text-gray-900 hidden sm:block">Patient List</h3>
                        <div class="flex flex-col sm:flex-row items-center space-y-4 sm:space-y-0 sm:space-x-6 w-full sm:w-auto">
                            <form action="{{ route('patients.index') }}" method="GET" class="flex flex-col sm:flex-row items-center space-y-2 sm:space-y-0 sm:space-x-2 w-full sm:w-auto">
                                <x-text-input id="search" name="search" type="text" class="block w-full sm:w-48" placeholder="Search..." value="{{ request('search') }}" />
                                <x-primary-button class="w-full sm:w-auto">
                                    {{ __('Search') }}
                                </x-primary-button>
                                @if(request('search'))
                                    <a href="{{ route('patients.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150 w-full sm:w-auto">Clear</a>
                                @endif
                                <select name="status" class="block w-full sm:w-40 border-gray-300 rounded-md">
                                    <option value="">Active</option>
                                    <option value="deactivated" @selected(request('status')==='deactivated')>Deactivated</option>
                                    <option value="all" @selected(request('status')==='all')>All</option>
                                </select>
                            </form>
                            <a href="{{ route('patients.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 w-full sm:w-auto sm:ms-4">
                                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                </svg>
                                Add Patient
                            </a>
                        </div>
                    </div>

                    <!-- Desktop Table View -->
                    <div class="hidden sm:block overflow-x-auto shadow-md rounded-lg border border-gray-200">
                        <table id="patients-table" class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr id="patients-table-header">
                                    <th data-col="name" scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-move">Name</th>
                                    <th data-col="dob" scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-move">Date of Birth</th>
                                    <th data-col="gender" scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-move">Gender</th>
                                    <th data-col="address" scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-move">Address</th>
                                    <th data-col="phone" scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-move">Phone</th>
                                    <th data-col="email" scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-move">Email</th>
                                    <th data-col="actions" scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">Actions</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($patients as $patient)
                                <tr class="hover:bg-gray-50 transition-colors duration-150 ease-in-out">
                                    <td data-col="name" class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $patient->fullName }}
                                        </div>
                                    </td>
                                    <td data-col="dob" class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-700">{{ $patient->date_of_birth->format('M d, Y') }}</div>
                                        <div class="text-xs text-gray-500">({{ $patient->age }} yrs old)</div>
                                    </td>
                                    <td data-col="gender" class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-700">{{ ucfirst($patient->gender) }}</div>
                                    </td>
                                    <td data-col="address" class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-700">{{ $patient->address }}</div>
                                    </td>
                                    <td data-col="phone" class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-700">{{ $patient->phone }}</div>
                                    </td>
                                    <td data-col="email" class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-700">{{ $patient->email ?? 'N/A' }}</div>
                                    </td>
                                    <td data-col="actions" class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end space-x-2">
                                            <a href="{{ route('patients.show', $patient) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-indigo-600 bg-indigo-50 hover:bg-indigo-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">View</a>
                                            <a href="{{ route('patients.edit', $patient) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-gray-700 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">Edit</a>
                                            @can('patient-delete')
                                                <form class="inline-block" action="{{ route('patients.destroy', $patient) }}" method="POST" onsubmit="return confirm('Are you sure you want to deactivate this patient?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">Deactivate</button>
                                                </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                                        No patients found matching your criteria.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Card View -->
                    <div class="sm:hidden">
                        <div class="grid grid-cols-1 gap-4">
                            @forelse ($patients as $patient)
                                <div class="bg-white shadow-lg rounded-lg p-4 border border-gray-200">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="text-lg font-bold text-gray-900">{{ $patient->fullName }}</p>
                                            <p class="text-sm text-gray-600">{{ $patient->age }} yrs old, {{ ucfirst($patient->gender) }}</p>
                                        </div>
                                    </div>
                                    <div class="mt-4">
                                        <p class="text-sm text-gray-600"><span class="font-medium">Address:</span> {{ $patient->address }}</p>
                                        <p class="text-sm text-gray-600"><span class="font-medium">Phone:</span> {{ $patient->phone }}</p>
                                        <p class="text-sm text-gray-600"><span class="font-medium">Email:</span> {{ $patient->email ?? 'N/A' }}</p>
                                    </div>
                                    <div class="mt-4 flex justify-end space-x-2">
                                        <a href="{{ route('patients.show', $patient) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-indigo-600 bg-indigo-50 hover:bg-indigo-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">View</a>
                                        <a href="{{ route('patients.edit', $patient) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-gray-700 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">Edit</a>
                                        @can('patient-delete')
                                            <form class="inline-block" action="{{ route('patients.destroy', $patient) }}" method="POST" onsubmit="return confirm('Are you sure you want to deactivate this patient?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">Deactivate</button>
                                            </form>
                                        @endcan
                                    </div>
                                </div>
                            @empty
                                <div class="text-center text-gray-500 py-4">
                                    No patients found matching your criteria.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div class="mt-6">
                        {{ $patients->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const tableKey = 'patients.index.table';
    const table = document.getElementById('patients-table');
    if (!table) return;

    const headerRow = document.getElementById('patients-table-header');
    const initialColumns = ['name','dob','gender','address','phone','email','actions'];

    function loadPreferences() {
        try {
            const raw = localStorage.getItem(tableKey);
            return raw ? JSON.parse(raw) : { order: initialColumns, hidden: [] };
        } catch { return { order: initialColumns, hidden: [] }; }
    }

    function savePreferences(prefs) {
        localStorage.setItem(tableKey, JSON.stringify(prefs));
        // Optional: also persist to server when logged in
        fetch('{{ route('preferences.table.store') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ table_key: tableKey, preferences: prefs })
        }).catch(() => {});
    }

    function applyPreferences(prefs) {
        // Reorder columns in header and rows
        const map = new Map();
        Array.from(headerRow.children).forEach(th => map.set(th.dataset.col, th));
        prefs.order.forEach((colKey, idx) => {
            const th = map.get(colKey);
            if (th) headerRow.appendChild(th);
        });

        const rows = table.tBodies[0].rows;
        for (const row of rows) {
            const cellMap = new Map();
            Array.from(row.children).forEach(td => cellMap.set(td.dataset.col, td));
            prefs.order.forEach(colKey => {
                const td = cellMap.get(colKey);
                if (td) row.appendChild(td);
            });
        }

        // Hide/unhide
        const allCols = initialColumns;
        allCols.forEach(colKey => {
            const display = prefs.hidden.includes(colKey) ? 'none' : '';
            const th = headerRow.querySelector(`[data-col="${colKey}"]`);
            if (th) th.style.display = display;
            for (const row of rows) {
                const td = row.querySelector(`[data-col="${colKey}"]`);
                if (td) td.style.display = display;
            }
        });
    }

    // Enable drag-to-reorder on header (basic HTML5 drag and drop)
    Array.from(headerRow.children).forEach(th => {
        th.draggable = true;
        th.addEventListener('dragstart', e => {
            e.dataTransfer.setData('text/plain', th.dataset.col);
        });
        th.addEventListener('dragover', e => e.preventDefault());
        th.addEventListener('drop', e => {
            e.preventDefault();
            const fromKey = e.dataTransfer.getData('text/plain');
            const toKey = th.dataset.col;
            const prefs = loadPreferences();
            const order = prefs.order.filter(k => k !== fromKey);
            const toIndex = order.indexOf(toKey);
            order.splice(toIndex, 0, fromKey);
            prefs.order = order;
            savePreferences(prefs);
            applyPreferences(prefs);
        });
    });

    // Simple menu to toggle columns (press "c" to open)
    document.addEventListener('keydown', e => {
        if (e.key.toLowerCase() === 'c') {
            const prefs = loadPreferences();
            const toToggle = prompt('Hide/unhide columns (comma-separated keys):\n' + initialColumns.join(', ') + '\nCurrently hidden: ' + prefs.hidden.join(', '));
            if (toToggle !== null) {
                const keys = toToggle.split(',').map(s => s.trim()).filter(Boolean);
                const hidden = initialColumns.filter(k => keys.includes(k));
                prefs.hidden = hidden;
                savePreferences(prefs);
                applyPreferences(prefs);
            }
        }
    });

    // Column resizing (basic): drag right edge to resize
    Array.from(headerRow.children).forEach(th => {
        const handle = document.createElement('span');
        handle.className = 'resizer';
        handle.style.cssText = 'position:absolute;right:0;top:0;width:6px;cursor:col-resize;height:100%;';
        th.style.position = 'relative';
        th.appendChild(handle);
        let startX = 0; let startWidth = 0;
        handle.addEventListener('mousedown', e => {
            startX = e.pageX; startWidth = th.offsetWidth;
            document.addEventListener('mousemove', onMove);
            document.addEventListener('mouseup', onUp, { once: true });
        });
        function onMove(e) {
            const newW = Math.max(80, startWidth + (e.pageX - startX));
            th.style.width = newW + 'px';
        }
        function onUp() {
            document.removeEventListener('mousemove', onMove);
        }
    });

    // Initial apply
    applyPreferences(loadPreferences());
});
</script>
@endpush
