(() => {
    'use strict';
    const form = document.getElementById('productForm');
    if (!form) return;
    const data = window.productFormData;
    const container = document.getElementById('variationRows');
    const enabled = document.getElementById('is_variation');
    const editor = document.getElementById('variationEditor');
    const tabs = [...document.querySelectorAll('.product-tabs [data-bs-toggle="tab"]')];
    const showTab = button => bootstrap.Tab.getOrCreateInstance(button).show();
    let rowCounter = 0;

    function setOptions(select, items, selected, placeholder) {
        select.replaceChildren(new Option(placeholder, ''));
        items.forEach(item => select.add(new Option(item.name + (item.status ? '' : ' (Inactive)'), item.id)));
        select.value = selected == null ? '' : String(selected);
    }

    function reindex() {
        [...container.children].forEach((card, index) => {
            card.querySelector('.variation-title').textContent = 'Variation ' + (index + 1);
            card.querySelectorAll('[data-field]').forEach(input => {
                input.name = `variations[${index}][${input.dataset.field}]`;
            });
            card.querySelectorAll('.attribute-pair').forEach((pair, pairIndex) => {
                pair.querySelector('.attribute-type').name = `variations[${index}][attributes][${pairIndex}][attribute_type_id]`;
                pair.querySelector('.attribute-value').name = `variations[${index}][attributes][${pairIndex}][attribute_id]`;
            });
        });
        document.getElementById('addVariation').disabled = container.children.length >= 100;
    }

    function addPair(card, initial = {}) {
        const list = card.querySelector('.attribute-pairs');
        if (list.children.length >= 20) return;
        const pair = document.createElement('div');
        const key = 'pair-' + (++rowCounter);
        pair.className = 'attribute-pair row g-3 mb-3 align-items-end';
        pair.innerHTML = `<div class="col-12 col-sm-5"><label class="form-label" for="${key}-type">Attribute Type</label><select id="${key}-type" class="form-select attribute-type" required></select></div>
            <div class="col-12 col-sm-5"><label class="form-label" for="${key}-value">Attribute</label><select id="${key}-value" class="form-select attribute-value" required></select></div>
            <div class="col-12 col-sm-2"><button class="btn btn-label-danger w-100 remove-pair" type="button" aria-label="Remove attribute pair">Remove</button></div>`;
        list.append(pair);
        const type = pair.querySelector('.attribute-type');
        const value = pair.querySelector('.attribute-value');
        setOptions(type, data.types, initial.attribute_type_id, 'Select Type');
        function refresh(selected) {
            setOptions(value, data.attributes.filter(attribute => String(attribute.attribute_type_id) === type.value), selected, 'Select Attribute');
        }
        refresh(initial.attribute_id);
        type.addEventListener('change', () => refresh(''));
        pair.querySelector('.remove-pair').addEventListener('click', () => {
            if (list.children.length <= 1) return;
            pair.remove();
            reindex();
        });
        reindex();
    }

    function addVariation(initial = {}) {
        if (container.children.length >= 100) return;
        const card = document.createElement('section');
        card.className = 'variation-card border rounded p-3 p-md-4 mb-4';
        const key = 'variation-' + (++rowCounter);
        card.innerHTML = `<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4"><h6 class="variation-title mb-0"></h6><button type="button" class="btn btn-sm btn-label-danger remove-variation">Remove Variation</button></div>
            <input type="hidden" data-field="id">
            <div class="row">
                <div class="col-12 col-md-6 mb-3"><label class="form-label" for="${key}-name">Variation Name</label><input id="${key}-name" class="form-control" data-field="name" maxlength="255" required placeholder="e.g. Black / Large"></div>
                <div class="col-12 col-md-6 mb-3"><label class="form-label" for="${key}-sku">Unique Variation SKU</label><input id="${key}-sku" class="form-control" data-field="sku" maxlength="255" required></div>
            </div><div class="variation-prices row"></div>
            <div class="mb-4"><label class="form-label" for="${key}-status">Variation Status</label><select id="${key}-status" class="form-select" data-field="status"><option value="1">Active</option><option value="0">Inactive</option></select></div>
            <h6>Attribute combination</h6><div class="attribute-pairs"></div><button class="btn btn-sm btn-label-secondary add-pair" type="button">+ Add Attribute Type</button>`;
        container.append(card);
        const prices = {purchase_price: 'Purchase Price', sale_price: 'Sale Price', bangla_sale_price: 'Bangla Sale Price', mrp_price: 'MRP Price'};
        Object.entries(prices).forEach(([field, label]) => {
            const column = document.createElement('div');
            column.className = 'col-12 col-sm-6 col-xl-3 mb-3';
            column.innerHTML = `<label class="form-label" for="${key}-${field}">${label}</label><input id="${key}-${field}" class="form-control" type="number" min="0" max="99999999.99" step="0.01" required data-field="${field}">`;
            card.querySelector('.variation-prices').append(column);
        });
        card.querySelectorAll('[data-field]').forEach(input => {
            const field = input.dataset.field;
            input.value = initial[field] ?? (field === 'status' ? 1 : (prices[field] ? document.getElementById(field).value : ''));
        });
        const pairs = Object.values(initial.attributes || {});
        (pairs.length ? pairs : [{}]).forEach(pair => addPair(card, pair));
        card.querySelector('.add-pair').addEventListener('click', () => addPair(card));
        card.querySelector('.remove-variation').addEventListener('click', () => {
            if (card.querySelector('[data-field="id"]').value && !window.confirm('Remove this saved variation when the product is saved?')) return;
            card.remove();
            reindex();
        });
        reindex();
    }

    function updateEditor() {
        const active = enabled.value === '1';
        editor.hidden = !active;
        if (active && !container.children.length) addVariation();
        editor.querySelectorAll('input, select, button').forEach(input => { input.disabled = !active; });
        if (active) reindex();
    }
    Object.values(data.variations || {}).forEach(row => addVariation(row));
    updateEditor();
    enabled.addEventListener('change', updateEditor);
    document.getElementById('addVariation').addEventListener('click', () => addVariation());

    function updateNavigation() {
        const index = tabs.findIndex(tab => tab.classList.contains('active'));
        document.getElementById('previousTab').disabled = index === 0;
        document.getElementById('nextTab').disabled = index === tabs.length - 1;
    }
    tabs.forEach(tab => tab.addEventListener('shown.bs.tab', updateNavigation));
    for (const [id, direction] of [['previousTab', -1], ['nextTab', 1]]) {
        document.getElementById(id).addEventListener('click', () => {
            const index = tabs.findIndex(tab => tab.classList.contains('active'));
            if (tabs[index + direction]) showTab(tabs[index + direction]);
        });
    }
    updateNavigation();

    let invalidPending = false;
    let reportingInvalid = false;
    form.addEventListener('invalid', event => {
        if (reportingInvalid) return;
        event.preventDefault();
        if (invalidPending) return;
        invalidPending = true;
        const input = event.target;
        const panel = input.closest('.tab-pane');
        const tab = panel && document.querySelector(`[data-bs-target="#${panel.id}"]`);
        if (tab) showTab(tab);
        setTimeout(() => {
            input.focus();
            reportingInvalid = true;
            input.reportValidity();
            reportingInvalid = false;
            invalidPending = false;
        }, 200);
    }, true);
    form.addEventListener('submit', () => {
        reindex();
        form.querySelectorAll('button[type="submit"]').forEach(button => { button.disabled = true; });
    });
    for (const error of data.errors || []) {
        const parts = error.split('.');
        const name = parts[0] + parts.slice(1).map(part => `[${part}]`).join('');
        const input = form.elements.namedItem(name);
        const panel = input?.closest?.('.tab-pane') || (parts[0] === 'variations' ? document.getElementById('panel-variations') : null);
        if (panel) { showTab(document.querySelector(`[data-bs-target="#${panel.id}"]`)); break; }
    }
    document.querySelectorAll('.product-image').forEach(input => {
        const preview = document.getElementById(input.dataset.preview);
        const original = preview.src;
        let url;
        input.addEventListener('change', () => {
            if (url) URL.revokeObjectURL(url);
            preview.src = original;
            input.setCustomValidity('');
            const file = input.files[0];
            if (!file) return;
            if (!['image/jpeg', 'image/png', 'image/webp', 'image/gif'].includes(file.type) || file.size > 5 * 1024 * 1024) {
                input.value = '';
                input.setCustomValidity('Choose a JPG, PNG, WebP or GIF up to 5 MB.');
                input.reportValidity();
                return;
            }
            url = URL.createObjectURL(file);
            preview.src = url;
        });
    });
})();
