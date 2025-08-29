<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <x-primary-button id="save-layout-btn">Save Layout</x-primary-button>
                <form action="{{ route('dashboard.resetLayout') }}" method="POST" class="inline-block">
                    @csrf
                    <x-secondary-button type="submit">Reset to Default</x-secondary-button>
                </form>
                <x-secondary-button id="customize-widgets-btn" type="button">Customize Widgets</x-secondary-button>
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

            // Modal logic
            const customizeBtn = document.getElementById('customize-widgets-btn');
            const modal = document.getElementById('widgets-modal');
            const modalClose = document.getElementById('widgets-modal-close');
            const widgetsForm = document.getElementById('widgets-form');

            customizeBtn.addEventListener('click', () => { modal.classList.remove('hidden'); });
            modalClose.addEventListener('click', () => { modal.classList.add('hidden'); });
            modal.addEventListener('click', (e) => { if (e.target === modal) modal.classList.add('hidden'); });

            widgetsForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                const formData = new FormData(widgetsForm);
                const widgets = [];
                for (const [key, value] of formData.entries()) {
                    if (key.startsWith('widget_')) {
                        widgets.push({ id: key.replace('widget_', ''), is_visible: value === 'on' });
                    }
                }
                await fetch('{{ route('dashboard.widgets.visibility') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ widgets })
                });
                // Simple reload to apply changes
                window.location.reload();
            });
        });
    </script>
    @endpush

    <!-- Widgets Modal -->
    <div id="widgets-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="bg-white rounded-lg shadow-xl max-w-lg w-full mx-auto mt-24">
            <div class="flex justify-between items-center px-6 py-4 border-b">
                <h3 class="text-lg font-semibold">Customize Widgets</h3>
                <button id="widgets-modal-close" class="text-gray-500 hover:text-gray-700">✕</button>
            </div>
            <form id="widgets-form" class="p-6 space-y-3">
                @foreach ($allWidgets as $w)
                    <label class="flex items-center space-x-3">
                        <input type="checkbox" name="widget_{{ $w['id'] }}" @checked($w['is_visible']) class="rounded border-gray-300">
                        <span class="text-sm text-gray-800">{{ $w['label'] }}</span>
                    </label>
                @endforeach
                <div class="pt-4 flex justify-end space-x-2">
                    <button type="button" id="widgets-modal-close-2" class="px-4 py-2 bg-gray-100 rounded">Close</button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Save</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
