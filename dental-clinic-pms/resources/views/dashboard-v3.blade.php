<x-app-layout>
    <div class="bg-gray-100 min-h-screen">
        <div class="p-4">
            <h1 class="text-2xl font-bold text-gray-800 mb-4">Dashboard</h1>
            
            <div id="dashboard-container" class="min-h-screen bg-gray-50 border-2 border-dashed border-gray-300 rounded-lg p-4 relative">
                <div class="card bg-white p-4 rounded shadow cursor-move border-2 border-blue-200" style="position: absolute; left: 20px; top: 20px; width: 300px; height: 150px; resize: both; overflow: auto; z-index: 10;">
                    <h3 class="font-semibold mb-2">Card 1</h3>
                    <p>Content here</p>
                </div>
                <div class="card bg-white p-4 rounded shadow cursor-move border-2 border-green-200" style="position: absolute; left: 340px; top: 20px; width: 300px; height: 150px; resize: both; overflow: auto; z-index: 10;">
                    <h3 class="font-semibold mb-2">Card 2</h3>
                    <p>Content here</p>
                </div>
                <div class="card bg-white p-4 rounded shadow cursor-move border-2 border-yellow-200" style="position: absolute; left: 20px; top: 190px; width: 300px; height: 150px; resize: both; overflow: auto; z-index: 10;">
                    <h3 class="font-semibold mb-2">Card 3</h3>
                    <p>Content here</p>
                </div>
                <div class="card bg-white p-4 rounded shadow cursor-move border-2 border-red-200" style="position: absolute; left: 340px; top: 190px; width: 300px; height: 150px; resize: both; overflow: auto; z-index: 10;">
                    <h3 class="font-semibold mb-2">Card 4</h3>
                    <p>Content here</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('dashboard-container');
    const cards = document.querySelectorAll('.card');
    
    cards.forEach(card => {
        let isDragging = false;
        let startX, startY, startLeft, startTop;
        
        card.addEventListener('mousedown', function(e) {
            // Don't start drag if clicking on resize handle
            if (e.target === card || card.contains(e.target)) {
                isDragging = true;
                startX = e.clientX;
                startY = e.clientY;
                startLeft = parseInt(card.style.left) || 0;
                startTop = parseInt(card.style.top) || 0;
                
                card.style.zIndex = '1000';
                e.preventDefault();
            }
        });
        
        document.addEventListener('mousemove', function(e) {
            if (!isDragging) return;
            
            const deltaX = e.clientX - startX;
            const deltaY = e.clientY - startY;
            
            let newLeft = startLeft + deltaX;
            let newTop = startTop + deltaY;
            
            // Get container bounds
            const containerRect = container.getBoundingClientRect();
            const cardRect = card.getBoundingClientRect();
            
            // Constrain to container
            const maxLeft = containerRect.width - cardRect.width - 20; // 20px padding
            const maxTop = containerRect.height - cardRect.height - 20;
            
            newLeft = Math.max(20, Math.min(newLeft, maxLeft));
            newTop = Math.max(20, Math.min(newTop, maxTop));
            
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
});
</script>
@endpush