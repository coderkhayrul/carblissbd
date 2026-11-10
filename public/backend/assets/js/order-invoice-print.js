(() => {
    'use strict';
    const form = document.getElementById('order-bulk-print-form');
    const table = window.orderInvoicePrintTable;
    if (!form || !table) return;
    const selected = new Set();
    const submit = document.getElementById('order-print-selected');
    const count = document.getElementById('order-print-selection-count');
    const inputs = document.getElementById('order-print-selected-inputs');
    const mode = document.getElementById('order-print-mode');
    const columns = document.getElementById('order-print-columns');
    const copy = document.getElementById('order-print-copy');
    function visibleCheckboxes() {
        return table.rows({ page: 'current', search: 'applied' }).nodes().toArray().flatMap(row => Array.from(row.querySelectorAll('.order-print-checkbox')));
    }
    function sync() {
        table.rows().nodes().toArray().forEach(row => row.querySelectorAll('.order-print-checkbox').forEach(box => { box.checked = selected.has(box.value); }));
        const visible = visibleCheckboxes();
        const checked = visible.filter(box => selected.has(box.value)).length;
        document.querySelectorAll('.order-print-all').forEach(box => {
            box.checked = visible.length > 0 && checked === visible.length;
            box.indeterminate = checked > 0 && checked < visible.length;
            box.disabled = visible.length === 0;
        });
        submit.disabled = selected.size === 0 || selected.size > 200;
        count.textContent = selected.size + ' orders selected' + (selected.size > 200 ? ' — maximum 200 per batch' : '');
        // Keep both controls submitted; only their applicability changes, not their valid default values.
        columns.parentElement.hidden = mode.value !== 'mini';
        copy.parentElement.hidden = mode.value !== 'full';
    }
    document.addEventListener('change', event => {
        const box = event.target;
        if (box.matches('.order-print-checkbox')) {
            if (box.checked && selected.size >= 200 && !selected.has(box.value)) { box.checked = false; count.textContent = 'Maximum 200 orders per print batch.'; return; }
            if (box.checked) selected.add(box.value); else selected.delete(box.value);
            sync();
        }
        if (box.matches('.order-print-all')) {
            visibleCheckboxes().forEach(item => {
                if (box.checked && selected.size < 200) selected.add(item.value);
                else if (!box.checked) selected.delete(item.value);
            });
            sync();
        }
    });
    table.on('draw', sync);
    mode.addEventListener('change', sync);
    document.getElementById('order-print-clear').addEventListener('click', () => { selected.clear(); sync(); });
    form.addEventListener('submit', event => {
        if (!selected.size || selected.size > 200) { event.preventDefault(); return; }
        inputs.replaceChildren();
        // Preserve the current filtered/sorted table order, including selections from other pages.
        table.rows({ order: 'applied' }).nodes().toArray().forEach(row => {
            const box = row.querySelector('.order-print-checkbox');
            if (!box || !selected.has(box.value)) return;
            const input = document.createElement('input');
            input.type = 'hidden'; input.name = 'order_ids[]'; input.value = box.value;
            inputs.appendChild(input);
        });
    });
    sync();
})();
