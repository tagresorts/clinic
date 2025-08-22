<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- FullCalendar CSS -->
        <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>

        <!-- Gridstack CSS (served via CDN; node_modules is not public) -->
        <link href="https://cdn.jsdelivr.net/npm/gridstack@12.2.2/dist/gridstack.min.css" rel="stylesheet"/>
    </head>
    <body class="font-sans antialiased">
        <div x-data="{ sidebarOpen: window.innerWidth >= 1024 }" @resize.window="sidebarOpen = window.innerWidth >= 1024" x-on:keydown.escape.window="sidebarOpen = false" class="relative min-h-screen lg:flex">
            <!-- Sidebar -->
            <x-sidebar />

            <!-- Main content -->
            <div class="flex-1" :class="{'lg:ml-64': sidebarOpen, 'lg:ml-20': !sidebarOpen}">
                <!-- Header -->
                <header class="flex justify-between items-center p-4 bg-white border-b">
                    <div class="flex items-center">
                        <button @click.stop="sidebarOpen = !sidebarOpen" class="text-gray-500 focus:outline-none">
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                            </svg>
                        </button>
                        @isset($header)
                            <div class="ml-4">
                                {{ $header }}
                            </div>
                        @endisset
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-4">
                    <!-- Session Messages -->
                    @if (session('success') || session('error'))
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        @if (session('success'))
                            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                                <span class="block sm:inline">{{ session('success') }}</span>
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                                <span class="block sm:inline">{{ session('error') }}</span>
                            </div>
                        @endif
                    </div>
                    @endif

                    {{ $slot }}
                </main>
            </div>
             <!-- Backdrop -->
            <div x-show="sidebarOpen && window.innerWidth < 1024" @click="sidebarOpen = false" class="fixed inset-0 bg-black opacity-50 z-30 lg:hidden"></div>
        </div>
        @stack('scripts')
    </body>
</html>
