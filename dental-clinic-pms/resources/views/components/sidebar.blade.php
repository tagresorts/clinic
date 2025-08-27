@php
use App\Menus\AdminMenu;
$adminMenu = AdminMenu::build();
@endphp
<!-- resources/views/components/sidebar.blade.php -->
<aside
    class="bg-gray-800 text-gray-300 h-screen flex flex-col w-64 transition-transform duration-300 transform lg:fixed lg:translate-x-0 lg:transition-all z-40"
    :class="{
        'translate-x-0': sidebarOpen,
        '-translate-x-full lg:translate-x-0': !sidebarOpen,
        'lg:w-64': sidebarOpen,
        'lg:w-20': !sidebarOpen
    }">

    <!-- Logo -->
    <div class="flex items-center justify-center h-16 bg-white">
        <a href="{{ route('dashboard') }}">
            <div :class="sidebarOpen ? 'block' : 'hidden'">
                <x-application-logo class="block h-16 w-auto fill-current text-gray-800" />
            </div>
            <div :class="sidebarOpen ? 'hidden' : 'block'">
                <svg class="h-8 w-8 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.59 14.37a6 6 0 01-5.84 7.38v-4.82m5.84-2.56a16.95 16.95 0 00-1.82-6.95M15.59 14.37a16.95 16.95 0 01-2.22 6.09M15.59 14.37a6 6 0 00-7.38-5.84m7.38 5.84v4.82m-7.38-5.84a16.95 16.95 0 00-6.95-1.82m6.95 1.82a16.95 16.95 0 01-6.09-2.22m0 0a16.95 16.95 0 01-2.22-6.09m14.37 8.31a6 6 0 01-7.38 5.84m-5.84-7.38a6 6 0 017.38-5.84" />
                </svg>
            </div>
        </a>
    </div>

    @auth
        <!-- Navigation Links -->
        <nav class="flex-1 overflow-y-auto overflow-x-hidden">
            <div class="px-2 py-4 space-y-2">
                @foreach ($adminMenu as $item)
                    @if (!empty($item['children']))
                        @if (isset($item['can']) && auth()->user()->hasRole($item['can']))
                            <div x-data="{ open: {{ $item['active'] ? 'true' : 'false' }} }">
                                <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-2.5 text-sm font-medium rounded-lg hover:bg-gray-700 focus:outline-none">
                                    <div class="flex items-center">
                                        {!! $item['icon'] !!}
                                        <span class="ml-4" x-show="sidebarOpen">{{ $item['title'] }}</span>
                                    </div>
                                    <svg x-show="sidebarOpen" :class="{'rotate-180': open}" class="h-5 w-5 transform transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
                                </button>
                                <div x-show="open && sidebarOpen" class="pl-12 mt-2 space-y-2">
                                    @foreach ($item['children'] as $child)
                                        @if (isset($child['can']) && auth()->user()->hasRole($child['can']))
                                            <a href="{{ $child['url'] }}" class="flex items-center px-4 py-2 text-sm rounded-lg hover:bg-gray-700 @if($child['active']) text-white @endif">
                                                <svg class="h-2 w-2 mr-2" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3" /></svg>
                                                {{ $child['title'] }}
                                            </a>
                                        @elseif (!isset($child['can']))
                                            <a href="{{ $child['url'] }}" class="flex items-center px-4 py-2 text-sm rounded-lg hover:bg-gray-700 @if($child['active']) text-white @endif">
                                                <svg class="h-2 w-2 mr-2" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3" /></svg>
                                                {{ $child['title'] }}
                                            </a>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @elseif (!isset($item['can']))
                            <div x-data="{ open: {{ $item['active'] ? 'true' : 'false' }} }">
                                <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-2.5 text-sm font-medium rounded-lg hover:bg-gray-700 focus:outline-none">
                                    <div class="flex items-center">
                                        {!! $item['icon'] !!}
                                        <span class="ml-4" x-show="sidebarOpen">{{ $item['title'] }}</span>
                                    </div>
                                    <svg x-show="sidebarOpen" :class="{'rotate-180': open}" class="h-5 w-5 transform transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
                                </button>
                                <div x-show="open && sidebarOpen" class="pl-12 mt-2 space-y-2">
                                    @foreach ($item['children'] as $child)
                                        <a href="{{ $child['url'] }}" class="flex items-center px-4 py-2 text-sm rounded-lg hover:bg-gray-700 @if($child['active']) text-white @endif">
                                            <svg class="h-2 w-2 mr-2" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3" /></svg>
                                            {{ $child['title'] }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @else
                        <a href="{{ $item['url'] }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg hover:bg-gray-700 @if($item['active']) bg-gray-900 text-white @endif">
                            {!! $item['icon'] !!}
                            <span class="ml-4" x-show="sidebarOpen">{{ $item['title'] }}</span>
                        </a>
                    @endif
                @endforeach
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
    @endauth
</aside>
