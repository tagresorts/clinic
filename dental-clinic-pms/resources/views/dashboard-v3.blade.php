<x-app-layout>
    <div class="bg-gray-100 min-h-screen">
        <div class="p-4">
            <h1 class="text-2xl font-bold text-gray-800 mb-4">Dashboard</h1>
            
            <div id="dashboard-container" class="min-h-screen bg-gray-50 border-2 border-dashed border-gray-300 rounded-lg p-4 relative">
                <div class="card bg-white p-4 rounded shadow cursor-move border-2 border-blue-200" style="position: absolute; left: 20px; top: 20px; width: 250px; height: 150px; resize: both; overflow: auto; z-index: 10;">
                    <h3 class="font-semibold mb-2">Card 1</h3>
                    <p>Content here</p>
                </div>
                <div class="card bg-white p-4 rounded shadow cursor-move border-2 border-green-200" style="position: absolute; left: 290px; top: 20px; width: 250px; height: 150px; resize: both; overflow: auto; z-index: 10;">
                    <h3 class="font-semibold mb-2">Card 2</h3>
                    <p>Content here</p>
                </div>
                <div class="card bg-white p-4 rounded shadow cursor-move border-2 border-yellow-200" style="position: absolute; left: 560px; top: 20px; width: 250px; height: 150px; resize: both; overflow: auto; z-index: 10;">
                    <h3 class="font-semibold mb-2">Card 3</h3>
                    <p>Content here</p>
                </div>
                <div class="card bg-white p-4 rounded shadow cursor-move border-2 border-red-200" style="position: absolute; left: 830px; top: 20px; width: 250px; height: 150px; resize: both; overflow: auto; z-index: 10;">
                    <h3 class="font-semibold mb-2">Card 4</h3>
                    <p>Content here</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

@push('scripts')
<script>
(function initDragAndResize() {
    function init() {
        const container = document.getElementById('dashboard-container');
        const cards = document.querySelectorAll('.card');

        cards.forEach(card => {
            let isDragging = false;
            let startX, startY, startLeft, startTop;

            card.addEventListener('mousedown', function(e) {
                isDragging = true;
                startX = e.clientX;
                startY = e.clientY;
                startLeft = parseInt(card.style.left) || 0;
                startTop = parseInt(card.style.top) || 0;

                card.style.zIndex = '1000';
                e.preventDefault();
                e.stopPropagation();
            });

            document.addEventListener('mousemove', function(e) {
                if (!isDragging) return;

                const deltaX = e.clientX - startX;
                const deltaY = e.clientY - startY;

                let newLeft = startLeft + deltaX;
                let newTop = startTop + deltaY;

                const containerRect = container.getBoundingClientRect();
                const cardWidth = card.offsetWidth;
                const cardHeight = card.offsetHeight;

                const padding = 20;
                const maxLeft = containerRect.width - cardWidth - padding;
                const maxTop = containerRect.height - cardHeight - padding;

                newLeft = Math.max(padding, Math.min(newLeft, maxLeft));
                newTop = Math.max(padding, Math.min(newTop, maxTop));

                card.style.left = newLeft + 'px';
                card.style.top = newTop + 'px';
            });

            document.addEventListener('mouseup', function() {
                if (isDragging) {
                    isDragging = false;
                    card.style.zIndex = '10';
                }
            });
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
</script>
@endpush