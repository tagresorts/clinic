// Appointments table init
(function initAppointmentsTableWhenReady() {
  function init() {
    const tableKey = 'appointments.index.table';
    const table = document.getElementById('appointments-table');
    if (!table) return;

    const headerRow = document.getElementById('appointments-table-header');
    const initialColumns = ['patient', 'dentist', 'date_time', 'type', 'status', 'actions'];

    function loadPreferences() {
      try {
        const raw = localStorage.getItem(tableKey);
        return raw ? JSON.parse(raw) : { order: initialColumns, hidden: [] };
      } catch { return { order: initialColumns, hidden: [] }; }
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

    // Drag and drop reordering
    Array.from(headerRow.children).forEach(th => {
      th.draggable = true;
      th.addEventListener('dragstart', e => {
        e.dataTransfer.setData('text/plain', th.dataset.col);
        e.dataTransfer.effectAllowed = 'move';
      });
      th.addEventListener('dragover', e => { e.preventDefault(); e.dataTransfer.dropEffect = 'move'; });
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

    // Columns dropdown
    const menuBtn = document.getElementById('columns-toggle');
    const menu = document.getElementById('columns-menu');
    const checkboxesWrap = document.getElementById('columns-checkboxes');
    if (menuBtn && menu && checkboxesWrap) {
      const prefs = loadPreferences();
      checkboxesWrap.innerHTML = '';
      initialColumns.forEach(colKey => {
        const id = `col-cb-${colKey}`;
        const checked = !prefs.hidden.includes(colKey);
        const disabled = colKey === 'actions';
        const row = document.createElement('label');
        row.className = 'flex items-center space-x-2 text-sm text-gray-700';
        row.innerHTML = `
          <input type="checkbox" ${checked ? 'checked' : ''} ${disabled ? 'disabled' : ''} data-col="${colKey}" class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500" id="${id}">
          <span class="capitalize">${colKey.replace('_',' ')}</span>
        `;
        checkboxesWrap.appendChild(row);
      });
      menuBtn.addEventListener('click', (e) => { e.stopPropagation(); menu.classList.toggle('hidden'); });
      document.addEventListener('click', () => menu.classList.add('hidden'));
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

    // Column resizing
    Array.from(headerRow.children).forEach(th => {
      const handle = document.createElement('span');
      handle.className = 'resizer';
      handle.style.cssText = 'position:absolute;right:0;top:0;width:6px;cursor:col-resize;height:100%;';
      th.style.position = 'relative';
      th.appendChild(handle);
      let startX = 0; let startWidth = 0;
      function onMove(e) {
        const newW = Math.max(80, startWidth + (e.pageX - startX));
        th.style.width = newW + 'px';
      }
      function onUp() {
        document.removeEventListener('mousemove', onMove);
      }
      handle.addEventListener('mousedown', e => {
        startX = e.pageX; startWidth = th.offsetWidth;
        document.addEventListener('mousemove', onMove);
        document.addEventListener('mouseup', onUp, { once: true });
      });
    });

    // Initial apply
    applyPreferences(loadPreferences());
  }
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init, { once: true });
  } else {
    init();
  }
})();