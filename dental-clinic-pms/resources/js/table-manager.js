// Generic Table Manager for column visibility and preferences
(function() {
    function initTable(tableId, initialColumns) {
        console.log(`Table Manager: Initializing table ${tableId}`);
        const table = document.getElementById(tableId);
        if (!table) {
            console.log(`Table Manager: Table ${tableId} not found`);
            return false;
        }

        const headerRow = document.getElementById(`${tableId}-header`);
        if (!headerRow) {
            console.log(`Table Manager: Header row for ${tableId} not found`);
            return false;
        }

        const tableKey = `${tableId}.preferences`;

        function loadPreferences() {
            try {
                const raw = localStorage.getItem(tableKey);
                return raw ? JSON.parse(raw) : { order: initialColumns, hidden: [] };
            } catch { 
                return { order: initialColumns, hidden: [] }; 
            }
        }

        function savePreferences(prefs) {
            localStorage.setItem(tableKey, JSON.stringify(prefs));
            const prefsUrl = table.dataset.prefsUrl;
            if (!prefsUrl) return;
            
            fetch(prefsUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ table_key: tableKey, preferences: prefs })
            }).catch(() => {});
        }

        function applyPreferences(prefs) {
            // Reorder header
            const map = new Map();
            Array.from(headerRow.children).forEach(th => map.set(th.dataset.col, th));
            prefs.order.forEach(colKey => {
                const th = map.get(colKey);
                if (th) headerRow.appendChild(th);
            });

            // Reorder rows
            const rows = table.tBodies[0]?.rows ?? [];
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

        // Initialize columns dropdown
        const menuBtn = document.getElementById(`${tableId}-columns-toggle`);
        const menu = document.getElementById(`${tableId}-columns-menu`);
        const checkboxesWrap = document.getElementById(`${tableId}-columns-checkboxes`);
        
        console.log(`Table Manager: Looking for ${tableId} column controls:`, {
            menuBtn: !!menuBtn,
            menu: !!menu,
            checkboxesWrap: !!checkboxesWrap
        });
        
        if (menuBtn && menu && checkboxesWrap) {
            const prefs = loadPreferences();
            checkboxesWrap.innerHTML = '';
            
            initialColumns.forEach(colKey => {
                const id = `col-cb-${colKey}`;
                const checked = !prefs.hidden.includes(colKey);
                const disabled = colKey === 'actions';
                const label = colKey.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                
                const row = document.createElement('label');
                row.className = 'flex items-center space-x-2 text-sm text-gray-700';
                row.innerHTML = `
                    <input type="checkbox" ${checked ? 'checked' : ''} ${disabled ? 'disabled' : ''} 
                           data-col="${colKey}" class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500" 
                           id="${id}">
                    <span>${label}</span>
                `;
                checkboxesWrap.appendChild(row);
            });

            // Toggle menu
            menuBtn.addEventListener('click', (e) => { 
                e.stopPropagation(); 
                menu.classList.toggle('hidden'); 
            });

            // Close menu when clicking outside
            document.addEventListener('click', () => menu.classList.add('hidden'));

            // Handle checkbox changes
            checkboxesWrap.addEventListener('change', (e) => {
                const target = e.target;
                if (target && target.matches('input[type="checkbox"][data-col]')) {
                    const prefs = loadPreferences();
                    const col = target.getAttribute('data-col');
                    
                    if (!target.checked) {
                        if (!prefs.hidden.includes(col)) prefs.hidden.push(col);
                    } else {
                        prefs.hidden = prefs.hidden.filter(k => k !== col);
                    }
                    
                    savePreferences(prefs);
                    applyPreferences(prefs);
                }
            });
        }

        // Drag and drop reordering
        Array.from(headerRow.children).forEach(th => {
            th.draggable = true;
            th.addEventListener('dragstart', e => {
                e.dataTransfer.setData('text/plain', th.dataset.col);
                e.dataTransfer.effectAllowed = 'move';
            });
            
            th.addEventListener('dragover', e => { 
                e.preventDefault(); 
                e.dataTransfer.dropEffect = 'move'; 
            });
            
            th.addEventListener('drop', e => {
                e.preventDefault();
                const fromKey = e.dataTransfer.getData('text/plain');
                const toKey = th.dataset.col;
                
                if (!fromKey || !toKey || fromKey === toKey) return;
                
                const prefs = loadPreferences();
                const order = prefs.order.filter(k => k !== fromKey);
                const toIndex = order.indexOf(toKey);
                const insertAt = toIndex >= 0 ? toIndex : order.length;
                order.splice(insertAt, 0, fromKey);
                prefs.order = order;
                
                savePreferences(prefs);
                applyPreferences(prefs);
            });
        });

        // Apply initial preferences
        applyPreferences(loadPreferences());
        
        console.log(`Table Manager: Successfully initialized table ${tableId}`);
        return true;
    }

    // Initialize tables when DOM is ready
    function init() {
        console.log('Table Manager: Initializing...');
        
        let tablesInitialized = 0;
        
        // Initialize patients table
        if (initTable('patients-table', [
            'name', 'dob', 'gender', 'address', 'phone', 'email', 'actions'
        ])) tablesInitialized++;

        // Initialize payments table
        if (initTable('payments-table', [
            'payment_ref', 'patient', 'invoice', 'date', 'amount', 
            'method', 'reference', 'received_by', 'actions'
        ])) tablesInitialized++;

        // Initialize invoices table
        if (initTable('invoices-table', [
            'invoice_number', 'patient', 'date', 'due_date', 'total_amount', 
            'outstanding', 'status', 'payment_status', 'actions'
        ])) tablesInitialized++;
        
        console.log(`Table Manager: Initialization complete. ${tablesInitialized} tables initialized.`);
        
        // Test alert to confirm script is loaded
        if (window.location.pathname.includes('/payments') || window.location.pathname.includes('/invoices')) {
            console.log('Table Manager: Script loaded successfully for payments/invoices pages');
        }
        
        // If no tables were initialized, retry after a delay
        if (tablesInitialized === 0) {
            console.log('Table Manager: No tables found, retrying in 500ms...');
            setTimeout(init, 500);
        }
    }

    // Wait for DOM to be fully loaded
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init, { once: true });
    } else {
        // If DOM is already loaded, wait a bit to ensure all elements are rendered
        setTimeout(init, 100);
    }
})();