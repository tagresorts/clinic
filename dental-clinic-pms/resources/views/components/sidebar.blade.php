<!-- resources/views/components/sidebar.blade.php -->
<aside
    class="bg-gray-800 text-gray-300 h-screen fixed top-0 left-0 z-40 flex flex-col w-64 transition-transform duration-300 transform lg:relative lg:translate-x-0 lg:transition-all"
    :class="{
        'translate-x-0': sidebarOpen,
        '-translate-x-full lg:translate-x-0': !sidebarOpen,
        'lg:w-64': sidebarOpen,
        'lg:w-20': !sidebarOpen
    }">

    <!-- Logo -->
    <div class="flex items-center justify-center h-16 border-b border-gray-700 flex-shrink-0">
        <a href="{{ route('dashboard') }}">
            <div :class="sidebarOpen ? 'block' : 'hidden'">
                <x-application-logo class="block h-9 w-auto fill-current text-white" />
            </div>
            <div :class="sidebarOpen ? 'hidden' : 'block'">
                <svg class="h-8 w-8 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.59 14.37a6 6 0 01-5.84 7.38v-4.82m5.84-2.56a16.95 16.95 0 00-1.82-6.95M15.59 14.37a16.95 16.95 0 01-2.22 6.09M15.59 14.37a6 6 0 00-7.38-5.84m7.38 5.84v4.82m-7.38-5.84a16.95 16.95 0 00-6.95-1.82m6.95 1.82a16.95 16.95 0 01-6.09-2.22m0 0a16.95 16.95 0 01-2.22-6.09m14.37 8.31a6 6 0 01-7.38 5.84m-5.84-7.38a6 6 0 017.38-5.84" />
                </svg>
            </div>
        </a>
    </div>

    <!-- Navigation Links -->
    <nav class="flex-1 overflow-y-auto overflow-x-hidden">
        <div class="px-2 py-4 space-y-2">
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg hover:bg-gray-700" :class="{ 'bg-gray-900 text-white': request()->routeIs('dashboard') }">
                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955a1.5 1.5 0 012.122 0l8.954 8.955M11.25 3.75v16.5M3.75 21.75h16.5" /></svg>
                <span class="ml-4" x-show="sidebarOpen">Dashboard</span>
            </a>

            <!-- Patients -->
            <div x-data="{ open: request()->routeIs('patients.*') }">
                <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-2.5 text-sm font-medium rounded-lg hover:bg-gray-700 focus:outline-none">
                    <div class="flex items-center">
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-4.663M12 12a4.5 4.5 0 014.5 4.5m-9 0a4.5 4.5 0 014.5-4.5m0-9a4.5 4.5 0 014.5 4.5m-9 0a4.5 4.5 0 014.5-4.5" /></svg>
                        <span class="ml-4" x-show="sidebarOpen">Patients</span>
                    </div>
                    <svg x-show="sidebarOpen" :class="{'rotate-180': open}" class="h-5 w-5 transform transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
                </button>
                <div x-show="open && sidebarOpen" class="pl-12 mt-2 space-y-2">
                    <a href="{{ route('patients.index') }}" class="block px-4 py-2 text-sm rounded-lg hover:bg-gray-700" :class="{'text-white': request()->routeIs('patients.index')}">View All</a>
                    <a href="{{ route('patients.create') }}" class="block px-4 py-2 text-sm rounded-lg hover:bg-gray-700" :class="{'text-white': request()->routeIs('patients.create')}">Add Patient</a>
                </div>
            </div>

            @if(auth()->user()->hasRole(['dentist', 'administrator']))
            <!-- Treatments -->
            <div x-data="{ open: request()->routeIs('treatment-plans.*') || request()->routeIs('procedures.*') }">
                <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-2.5 text-sm font-medium rounded-lg hover:bg-gray-700 focus:outline-none">
                    <div class="flex items-center">
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.08.828.23 1.216M15.9 5.337A48.36 48.36 0 0012 5.25c-2.651 0-5.198.468-7.5 1.372M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.08.828.23 1.216M15.9 5.337A48.36 48.36 0 0012 5.25c-2.651 0-5.198.468-7.5 1.372" /></svg>
                        <span class="ml-4" x-show="sidebarOpen">Treatments</span>
                    </div>
                    <svg x-show="sidebarOpen" :class="{'rotate-180': open}" class="h-5 w-5 transform transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
                </button>
                <div x-show="open && sidebarOpen" class="pl-12 mt-2 space-y-2">
                    <a href="{{ route('treatment-plans.index') }}" class="block px-4 py-2 text-sm rounded-lg hover:bg-gray-700" :class="{'text-white': request()->routeIs('treatment-plans.index')}">Treatment Plans</a>
                    @if(auth()->user()->hasRole('administrator'))
                    <a href="{{ route('procedures.index') }}" class="block px-4 py-2 text-sm rounded-lg hover:bg-gray-700" :class="{'text-white': request()->routeIs('procedures.index')}">Procedures</a>
                    @endif
                </div>
            </div>
            @endif

            <!-- Appointments -->
            <div x-data="{ open: request()->routeIs('appointments.*') }">
                <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-2.5 text-sm font-medium rounded-lg hover:bg-gray-700 focus:outline-none">
                    <div class="flex items-center">
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0h18M12 14.25h.008v.008H12v-.008z" /></svg>
                        <span class="ml-4" x-show="sidebarOpen">Appointments</span>
                    </div>
                    <svg x-show="sidebarOpen" :class="{'rotate-180': open}" class="h-5 w-5 transform transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
                </button>
                <div x-show="open && sidebarOpen" class="pl-12 mt-2 space-y-2">
                    <a href="{{ route('appointments.index') }}" class="block px-4 py-2 text-sm rounded-lg hover:bg-gray-700" :class="{'text-white': request()->routeIs('appointments.index')}">All Appointments</a>
                    <a href="{{ route('appointments.tentative') }}" class="block px-4 py-2 text-sm rounded-lg hover:bg-gray-700" :class="{'text-white': request()->routeIs('appointments.tentative')}">Tentative</a>
                    <a href="{{ route('appointments.calendar') }}" class="block px-4 py-2 text-sm rounded-lg hover:bg-gray-700" :class="{'text-white': request()->routeIs('appointments.calendar')}">Calendar</a>
                    <a href="{{ route('appointments.create') }}" class="block px-4 py-2 text-sm rounded-lg hover:bg-gray-700" :class="{'text-white': request()->routeIs('appointments.create')}">Add Appointment</a>
                </div>
            </div>

            @if(auth()->user()->hasRole('administrator'))
            <!-- Stock Management -->
            <div x-data="{ open: request()->routeIs('inventory.*') || request()->routeIs('suppliers.*') || request()->routeIs('purchase-orders.*') || request()->routeIs('stock-movements.*') }">
                <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-2.5 text-sm font-medium rounded-lg hover:bg-gray-700 focus:outline-none">
                    <div class="flex items-center">
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 15.353 16.556 17.25 12 17.25s-8.25-1.897-8.25-4.125V10.125" /></svg>
                        <span class="ml-4" x-show="sidebarOpen">Stock Management</span>
                    </div>
                    <svg x-show="sidebarOpen" :class="{'rotate-180': open}" class="h-5 w-5 transform transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
                </button>
                <div x-show="open && sidebarOpen" class="pl-12 mt-2 space-y-2">
                    <a href="{{ route('inventory.index') }}" class="block px-4 py-2 text-sm rounded-lg hover:bg-gray-700" :class="{'text-white': request()->routeIs('inventory.index')}">Inventory</a>
                    <a href="{{ route('suppliers.index') }}" class="block px-4 py-2 text-sm rounded-lg hover:bg-gray-700" :class="{'text-white': request()->routeIs('suppliers.index')}">Suppliers</a>
                    @if(Route::has('purchase-orders.index'))
                    <a href="{{ route('purchase-orders.index') }}" class="block px-4 py-2 text-sm rounded-lg hover:bg-gray-700" :class="{'text-white': request()->routeIs('purchase-orders.index')}">Purchase Orders</a>
                    @endif
                    @if(Route::has('stock-movements.index'))
                    <a href="{{ route('stock-movements.index') }}" class="block px-4 py-2 text-sm rounded-lg hover:bg-gray-700" :class="{'text-white': request()->routeIs('stock-movements.index')}">Stock Movements</a>
                    @endif
                </div>
            </div>

            <!-- Admin -->
            <div x-data="{ open: request()->routeIs('users.*') || request()->routeIs('roles.*') || request()->routeIs('permissions.*') || request()->routeIs('smtp.*') || request()->routeIs('email-templates.*') || request()->routeIs('ops-settings.*') }">
                <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-2.5 text-sm font-medium rounded-lg hover:bg-gray-700 focus:outline-none">
                    <div class="flex items-center">
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.343 3.94c.09-.542.56-1.007 1.11-1.226m-2.22 2.452a11.95 11.95 0 00-3.832 4.122m5.051-4.336a11.95 11.95 0 013.832 4.122m-5.051-4.336c.362-1.03.813-1.99 1.364-2.882m-1.364 2.882A11.95 11.95 0 007.5 9.25m3.832 4.122a11.95 11.95 0 01-5.051 4.336m5.051-4.336a11.95 11.95 0 013.832-4.122m3.832 4.122a11.95 11.95 0 00-5.051 4.336m5.051-4.336c-.362 1.03-.813 1.99-1.364 2.882m1.364-2.882a11.95 11.95 0 015.051-4.336m-5.051 4.336a11.95 11.95 0 003.832-4.122" /></svg>
                        <span class="ml-4" x-show="sidebarOpen">Admin</span>
                    </div>
                    <svg x-show="sidebarOpen" :class="{'rotate-180': open}" class="h-5 w-5 transform transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
                </button>
                <div x-show="open && sidebarOpen" class="pl-12 mt-2 space-y-2">
                    <a href="{{ route('users.index') }}" class="block px-4 py-2 text-sm rounded-lg hover:bg-gray-700" :class="{'text-white': request()->routeIs('users.index')}">Users</a>
                    <a href="{{ route('roles.index') }}" class="block px-4 py-2 text-sm rounded-lg hover:bg-gray-700" :class="{'text-white': request()->routeIs('roles.index')}">Roles</a>
                    <a href="{{ route('permissions.index') }}" class="block px-4 py-2 text-sm rounded-lg hover:bg-gray-700" :class="{'text-white': request()->routeIs('permissions.index')}">Permissions</a>
                    <a href="{{ route('smtp.index') }}" class="block px-4 py-2 text-sm rounded-lg hover:bg-gray-700" :class="{'text-white': request()->routeIs('smtp.index')}">SMTP Settings</a>
                    <a href="{{ route('email-templates.index') }}" class="block px-4 py-2 text-sm rounded-lg hover:bg-gray-700" :class="{'text-white': request()->routeIs('email-templates.index')}">Email Templates</a>
                    <a href="{{ route('ops-settings.index') }}" class="block px-4 py-2 text-sm rounded-lg hover:bg-gray-700" :class="{'text-white': request()->routeIs('ops-settings.index')}">Operational Settings</a>
                </div>
            </div>
            @endif
        </div>
    </nav>

    <!-- User profile -->
    <div class="border-t border-gray-700 p-2">
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" class="w-full flex items-center p-2 rounded-lg hover:bg-gray-700">
                <img class="h-10 w-10 rounded-full object-cover" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&color=7F9CF5&background=EBF4FF" alt="User avatar">
                <div x-show="sidebarOpen" class="ml-3 text-left">
                    <p class="text-sm font-medium text-white">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-400">{{ Auth::user()->getRoleNames()->first() }}</p>
                </div>
            </button>
            <div x-show="open" @click.away="open = false" class="absolute bottom-full left-0 mb-2 w-full bg-gray-700 rounded-lg shadow-lg" x-transition>
                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-600">Profile</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="block w-full text-left px-4 py-2 text-sm text-gray-300 hover:bg-gray-600">
                        Log Out
                    </a>
                </form>
            </div>
        </div>
    </div>
</aside>
