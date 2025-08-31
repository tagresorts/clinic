<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <svg class="h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l9.75-9.75L21.75 12M4.5 9.75V21h15V9.75"/>
                </svg>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Dashboard') }}
                </h2>
            </div>
            <div class="flex items-center space-x-2">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    Interactive Dashboard
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-6 bg-gradient-to-br from-gray-50 to-blue-50 min-h-screen">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            <!-- Enhanced Toolbar -->
            <div class="w-full mb-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-3 sm:space-y-0">
                        <div class="flex items-center space-x-2">
                            <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"></path>
                            </svg>
                            <span class="text-sm font-medium text-gray-700">Dashboard Controls</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <button id="add-panel-btn" class="inline-flex items-center px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition-colors duration-200" style="display: none;">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Add Panel
                            </button>
                            <x-primary-button id="save-layout-btn" class="flex items-center space-x-2 bg-blue-600 hover:bg-blue-700 focus:ring-blue-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                                </svg>
                                <span>Save Layout</span>
                            </x-primary-button>
                            <form action="{{ route('dashboard.resetLayout') }}" method="POST" class="inline-block">
                                @csrf
                                <x-secondary-button type="submit" class="flex items-center space-x-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                    <span>Reset to Default</span>
                                </x-secondary-button>
                            </form>
                            <a href="{{ route('admin.dashboard-widgets.edit') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-900 transition-colors duration-200 shadow-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 018.25 20.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/>
                                </svg>
                                Widget Settings
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dashboard Wrappers -->
            <div id="dashboard-wrappers" class="space-y-6">
                @php
                    $wrapperWidgets = [];
                    foreach ($widgets as $widget) {
                        $wrapperId = $widget['wrapper_id'] ?? 1;
                        if (!isset($wrapperWidgets[$wrapperId])) {
                            $wrapperWidgets[$wrapperId] = [];
                        }
                        $wrapperWidgets[$wrapperId][] = $widget;
                    }
                    
                    // Get all wrapper IDs (both from widgets and saved wrappers)
                    $allWrapperIds = array_keys($wrapperWidgets);
                    foreach ($userWrappers as $wrapper) {
                        $allWrapperIds[] = $wrapper->wrapper_id;
                    }
                    $allWrapperIds = array_unique($allWrapperIds);
                    sort($allWrapperIds);
                @endphp
                
                @foreach ($allWrapperIds as $wrapperId)
                    <div class="dashboard-wrapper bg-white rounded-xl shadow-sm border border-gray-200 p-6" data-wrapper-id="{{ $wrapperId }}">
                        <div class="grid-stack">
                            @if (isset($wrapperWidgets[$wrapperId]))
                                @foreach ($wrapperWidgets[$wrapperId] as $widget)
                                    <div class="grid-stack-item" gs-x="{{ $widget['layout']['x'] }}" gs-y="{{ $widget['layout']['y'] }}" gs-w="{{ $widget['layout']['w'] }}" gs-h="{{ $widget['layout']['h'] }}" gs-id="{{ $widget['key'] }}">
                                        <div class="grid-stack-item-content">
                                            <x-dynamic-component :component="$widget['component']" :data="$data" />
                                        </div>
                                    </div>
                                @endforeach
                            @endif
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
            let grids = [];
            let wrapperCounter = 1;
            let draggedWidget = null;
            let sourceGrid = null;

            // Initialize all existing grids
            document.querySelectorAll('.grid-stack').forEach(gridElement => {
                initializeGrid(gridElement);
            });

            // Set wrapper counter to the highest existing wrapper ID
            const existingWrappers = document.querySelectorAll('.dashboard-wrapper');
            if (existingWrappers.length > 0) {
                const wrapperIds = Array.from(existingWrappers).map(wrapper => 
                    parseInt(wrapper.getAttribute('data-wrapper-id'))
                );
                wrapperCounter = Math.max(...wrapperIds);
            }

            function initializeGrid(gridElement) {
                const grid = GridStack.init({
                    float: false,
                    cellHeight: '8rem',
                    minRow: 1,
                    margin: '12px',
                    disableOneColumnMode: false,
                    resizable: {
                        handles: 'all'
                    },
                    draggable: {
                        handle: '.grid-stack-item-content'
                    },
                    acceptWidgets: true
                }, gridElement);
                
                // Add event listeners for cross-wrapper dragging
                grid.on('dragstart', function(event, ui) {
                    draggedWidget = event.target;
                    sourceGrid = grid;
                    event.target.style.opacity = '0.5';
                });
                
                grid.on('dragstop', function(event, ui) {
                    if (draggedWidget) {
                        draggedWidget.style.opacity = '1';
                        
                        // Check if widget was moved to a different wrapper
                        const currentWrapper = draggedWidget.closest('.dashboard-wrapper');
                        const targetWrapper = event.target.closest('.dashboard-wrapper');
                        
                        if (currentWrapper && targetWrapper && currentWrapper !== targetWrapper) {
                            handleWidgetMove(event, grid, draggedWidget, targetWrapper);
                        }
                    }
                });
                
                grids.push(grid);
                return grid;
            }

            function handleWidgetMove(event, sourceGrid, widget, targetWrapper) {
                // Widget was moved to a different wrapper
                console.log('Widget moved between wrappers');
                
                // Get the widget ID and target wrapper ID
                const widgetId = widget.getAttribute('gs-id');
                const targetWrapperId = targetWrapper.getAttribute('data-wrapper-id');
                
                // Find the target grid
                const targetGridElement = targetWrapper.querySelector('.grid-stack');
                const targetGrid = grids.find(g => g.el === targetGridElement);
                
                if (targetGrid && targetGrid !== sourceGrid) {
                    // Remove from source grid
                    sourceGrid.removeWidget(widget);
                    
                    // Add to target grid at the drop position
                    const rect = targetGridElement.getBoundingClientRect();
                    const x = Math.floor((event.clientX - rect.left) / (rect.width / 12));
                    const y = Math.floor((event.clientY - rect.top) / (rect.height / 8));
                    
                    // Create new widget in target grid
                    const newWidgetElement = targetGrid.addWidget({
                        x: x,
                        y: y,
                        w: 4,
                        h: 2,
                        id: widgetId,
                        content: widget.querySelector('.grid-stack-item-content').innerHTML
                    });
                    
                    // Set the wrapper_id attribute on the new widget
                    newWidgetElement.setAttribute('data-wrapper-id', targetWrapperId);
                    
                    // Clean up
                    draggedWidget = null;
                    sourceGrid = null;
                    
                    // Auto-save the layout to persist the change
                    setTimeout(() => {
                        saveLayout();
                    }, 100);
                }
            }

            

            const saveLayout = () => {
                const layoutData = {
                    wrappers: []
                };

                // Save each wrapper with its widgets
                document.querySelectorAll('.dashboard-wrapper').forEach((wrapper) => {
                    const wrapperId = wrapper.getAttribute('data-wrapper-id');
                    const gridElement = wrapper.querySelector('.grid-stack');
                    
                    // Find the grid instance for this specific wrapper
                    const grid = grids.find(g => g.el === gridElement);
                    
                    if (grid) {
                        const gridItems = grid.getGridItems();
                        const wrapperData = {
                            id: wrapperId,
                            widgets: []
                        };
                        
                        gridItems.forEach(item => {
                            const widgetId = item.getAttribute('gs-id');
                            const x = item.getAttribute('gs-x');
                            const y = item.getAttribute('gs-y');
                            const w = item.getAttribute('gs-w');
                            const h = item.getAttribute('gs-h');
                            
                            wrapperData.widgets.push({
                                id: widgetId,
                                x: parseInt(x) || 0,
                                y: parseInt(y) || 0,
                                w: parseInt(w) || 4,
                                h: parseInt(h) || 2
                            });
                        });
                        
                        layoutData.wrappers.push(wrapperData);
                    }
                });

                console.log('Saving layout:', layoutData);

                fetch('{{ route("dashboard.saveLayout") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ layout: layoutData })
                }).then(res => {
                    if(res.ok) {
                        console.log('Layout saved successfully');
                        // Show success notification
                        const button = document.getElementById('save-layout-btn');
                        const originalText = button.innerHTML;
                        button.innerHTML = `
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Layout Saved!</span>
                        `;
                        button.classList.remove('bg-blue-600', 'hover:bg-blue-700');
                        button.classList.add('bg-green-600', 'hover:bg-green-700');
                        
                        setTimeout(() => {
                            button.innerHTML = originalText;
                            button.classList.remove('bg-green-600', 'hover:bg-green-700');
                            button.classList.add('bg-blue-600', 'hover:bg-blue-700');
                        }, 2000);
                    } else {
                        console.error('Error saving layout');
                        alert('Error saving layout');
                    }
                }).catch(error => {
                    console.error('Error saving layout:', error);
                    alert('Error saving layout');
                });
            };

            document.getElementById('save-layout-btn').addEventListener('click', saveLayout);
        });
    </script>
    @endpush
</x-app-layout>
