<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <x-primary-button id="save-layout-btn">Save Layout</x-primary-button>
                <form action="{{ route('dashboard.resetLayout') }}" method="POST" class="inline-block">
                    @csrf
                    <x-secondary-button type="submit">Reset to Default</x-secondary-button>
                </form>
            </div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dashboard') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            <div class="grid-stack">
                @foreach ($widgets as $widget)
                    <div class="grid-stack-item" gs-x="{{ $widget['layout']['x'] }}" gs-y="{{ $widget['layout']['y'] }}" gs-w="{{ $widget['layout']['w'] }}" gs-h="{{ $widget['layout']['h'] }}" gs-id="{{ $widget['key'] }}">
                        <div class="grid-stack-item-content">
                            <x-dynamic-component :component="$widget['component']" :data="$data" />
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    @push('scripts')
    <!-- Gridstack JS via CDN; node_modules path is not web-accessible -->
    <script src="https://cdn.jsdelivr.net/npm/gridstack@12.2.2/dist/gridstack-all.js"></script>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function () {
            let grid = GridStack.init({
                float: true,
                cellHeight: '8rem',
                minRow: 1,
            }, '.grid-stack');

            const saveLayout = () => {
                const serializedData = grid.save();
                fetch('{{ route("dashboard.saveLayout") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ layout: serializedData })
                }).then(res => {
                    if(res.ok) {
                        alert('Layout Saved!');
                    } else {
                        alert('Error saving layout');
                    }
                });
            };

            document.getElementById('save-layout-btn').addEventListener('click', saveLayout);

        });
    </script>
    @endpush
</x-app-layout>
