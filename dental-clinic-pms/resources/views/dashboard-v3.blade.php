<x-app-layout>
    <div class="bg-gray-100 min-h-screen">
        <div class="p-4">
            <h1 class="text-2xl font-bold text-gray-800 mb-4">Dashboard</h1>
            
            <div id="dashboard-container" class="min-h-screen bg-gray-50 border-2 border-dashed border-gray-300 rounded-lg p-4">
                <div id="cards-container" class="relative">
                    <div class="card bg-white p-4 rounded shadow cursor-move" style="position: absolute; left: 0; top: 0; width: 300px; height: 150px; resize: both; overflow: auto;">
                        <h3 class="font-semibold mb-2">Card 1</h3>
                        <p>Content here</p>
                    </div>
                    <div class="card bg-white p-4 rounded shadow cursor-move" style="position: absolute; left: 320px; top: 0; width: 300px; height: 150px; resize: both; overflow: auto;">
                        <h3 class="font-semibold mb-2">Card 2</h3>
                        <p>Content here</p>
                    </div>
                    <div class="card bg-white p-4 rounded shadow cursor-move" style="position: absolute; left: 0; top: 170px; width: 300px; height: 150px; resize: both; overflow: auto;">
                        <h3 class="font-semibold mb-2">Card 3</h3>
                        <p>Content here</p>
                    </div>
                    <div class="card bg-white p-4 rounded shadow cursor-move" style="position: absolute; left: 320px; top: 170px; width: 300px; height: 150px; resize: both; overflow: auto;">
                        <h3 class="font-semibold mb-2">Card 4</h3>
                        <p>Content here</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('dashboard-container');
    const cards = document.querySelectorAll('.card');
    
    // Make cards draggable within the container
    cards.forEach(card => {
        card.addEventListener('mousedown', function(e) {
            if (e.target === card || card.contains(e.target)) {
                const startX = e.clientX - card.offsetLeft;
                const startY = e.clientY - card.offsetTop;
                
                function onMouseMove(e) {
                    const newX = e.clientX - startX;
                    const newY = e.clientY - startY;
                    
                    // Keep card within container bounds
                    const maxX = container.offsetWidth - card.offsetWidth;
                    const maxY = container.offsetHeight - card.offsetHeight;
                    
                    card.style.left = Math.max(0, Math.min(newX, maxX)) + 'px';
                    card.style.top = Math.max(0, Math.min(newY, maxY)) + 'px';
                }
                
                function onMouseUp() {
                    document.removeEventListener('mousemove', onMouseMove);
                    document.removeEventListener('mouseup', onMouseUp);
                }
                
                document.addEventListener('mousemove', onMouseMove);
                document.addEventListener('mouseup', onMouseUp);
            }
        });
    });
});
</script>
@endpush