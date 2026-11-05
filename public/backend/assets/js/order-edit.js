(() => {
    'use strict';
    const data = window.orderEditData;
    const form = document.getElementById('editOrderForm');
    if (!data || !form) return;
    const catalog = new Map(data.catalog.map(product => [String(product.id), product]));
    const container = document.getElementById('order-edit-items');
    const search = document.getElementById('order-product-search');
    const results = document.getElementById('order-product-results');
    const message = document.getElementById('order-search-status');
    const cancel = document.getElementById('cancel-product-replacement');
    const shipping = document.getElementById('shipping_cost');
    const discount = document.getElementById('extra_discount_amount');
    let replacement = null, timer, request, generation = 0;
    const cents = value => Math.round((Number(value) || 0) * 100);
    const money = value => 'BDT ' + (value / 100).toLocaleString('en-BD', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    function image(element, url) {
        element.src = url || data.fallbackImage;
        element.onerror = () => { element.onerror = null; element.src = data.fallbackImage; };
    }
    function resetReplacement() {
        replacement = null; cancel.hidden = true;
        message.textContent = 'Search for an active product to add it to this order.';
    }
    cancel.addEventListener('click', resetReplacement);
    search.addEventListener('keydown', event => { if (event.key === 'Enter') event.preventDefault(); });
    function recalculate() {
        let subtotal = 0, quantity = 0;
        const rows = [...container.children];
        rows.forEach((row, index) => {
            row.querySelectorAll('[data-field]').forEach(input => input.name = `items[${index}][${input.dataset.field}]`);
            const qty = Number(row.querySelector('[data-field="quantity"]').value) || 0;
            const total = cents(row.querySelector('[data-field="sale_price"]').value) * qty;
            row.querySelector('.line-total').textContent = money(total);
            quantity += qty; subtotal += total;
        });
        const delivery = cents(shipping.value) + cents(data.extraShipping);
        const discounts = cents(discount.value) + cents(data.fixedDiscount);
        const total = subtotal + delivery + cents(data.vat) - discounts;
        document.getElementById('edit-quantity').textContent = quantity;
        Object.entries({subtotal, shipping: delivery, discount: discounts, total, due: total - cents(data.paid)}).forEach(([key, value]) => document.getElementById('edit-' + key).textContent = money(value));
        const error = !rows.length ? 'Add at least one product.' : total < cents(data.paid) ? 'The total cannot be less than the amount already paid.' : total < 0 ? 'Discount exceeds the total.' : total > 9999999999 || subtotal > 9999999999 ? 'The order total exceeds the supported range.' : '';
        const notice = document.getElementById('edit-total-error');
        notice.textContent = error; notice.hidden = !error;
        document.getElementById('order-edit-empty').hidden = rows.length > 0;
        form.querySelectorAll('.order-save').forEach(button => button.disabled = !!error);
    }
    function addRow(item, existingRow = null) {
        if (!existingRow && container.children.length >= 100) { message.textContent = 'A maximum of 100 products is supported.'; return; }
        const row = existingRow || document.createElement('div');
        const original = data.savedLines[item.id] || null;
        const locked = Number(original?.returned_qty) > 0;
        row.className = 'border rounded p-3 mb-3';
        row.innerHTML = `<input type="hidden" data-field="id"><input type="hidden" data-field="product_id"><input type="hidden" data-field="product_variation_id">
            <div class="d-flex gap-3 align-items-start"><img class="product-image rounded border" width="72" height="72" style="object-fit:contain" alt="Product image"><div class="flex-grow-1" style="min-width:0"><strong class="product-name d-block text-break"></strong><small class="product-sku text-muted d-block text-break"></small><small class="original-variation d-block"></small></div></div>
            <div class="row g-3 mt-1"><div class="col-12 col-md-5"><label class="form-label d-block">Color / Size / Variation<select class="form-select variation-choice mt-1"></select></label></div><div class="col-6 col-md-3"><label class="form-label d-block">Quantity<input type="number" class="form-control mt-1" data-field="quantity" min="1" max="99999" step="1" required></label></div><div class="col-6 col-md-4"><label class="form-label d-block">Sale Price (BDT)<input type="number" class="form-control mt-1" data-field="sale_price" min="0" max="99999999.99" step="0.01" required></label></div></div>
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mt-3"><strong class="line-total"></strong><div class="d-flex gap-2"><button type="button" class="btn btn-sm btn-label-primary replace-product">Replace Product</button><button type="button" class="btn btn-sm btn-label-danger remove-product">Remove</button></div></div><small class="returned-note text-warning d-block mt-2"></small>`;
        const field = key => row.querySelector(`[data-field="${key}"]`);
        ['id', 'product_id', 'product_variation_id', 'quantity', 'sale_price'].forEach(key => field(key).value = item[key] ?? '');
        const product = catalog.get(String(item.product_id));
        const select = row.querySelector('.variation-choice');
        if (product?.is_variation) {
            select.add(new Option('Select a variation', ''));
            product.variations.forEach(variation => {
                const option = new Option(variation.label + (variation.active ? '' : ' (Inactive)'), variation.id);
                option.disabled = !variation.active && String(variation.id) !== String(original?.product_variation_id);
                select.add(option);
            });
            select.value = item.product_variation_id || '';
            select.required = true;
        } else { select.add(new Option('No variation', '')); select.disabled = true; }
        const display = (updatePrice = false) => {
            const variation = product?.variations.find(value => String(value.id) === String(field('product_variation_id').value));
            const same = original && String(original.product_id) === String(item.product_id) && String(original.product_variation_id || '') === String(field('product_variation_id').value || '');
            row.querySelector('.product-name').textContent = (same ? original.product_name : product?.name) || 'Unavailable product';
            row.querySelector('.product-sku').textContent = 'SKU: ' + ((same ? original.sku : variation?.sku || product?.sku) || '-');
            row.querySelector('.original-variation').textContent = (same ? original.variation_name : variation?.label) || '';
            image(row.querySelector('.product-image'), same ? original.image : variation?.image || product?.image);
            if (updatePrice) field('sale_price').value = Number(same ? original.sale_price : variation?.sale_price ?? product?.sale_price ?? 0).toFixed(2);
        };
        select.addEventListener('change', () => { field('product_variation_id').value = select.value; display(true); recalculate(); });
        row.querySelector('.replace-product').disabled = locked;
        row.querySelector('.remove-product').disabled = locked;
        if (locked) {
            select.disabled = true; field('quantity').readOnly = true; field('sale_price').readOnly = true;
            row.querySelector('.returned-note').textContent = `Returned: ${original.returned_qty}. This line is preserved for return records.`;
        }
        row.querySelector('.replace-product').addEventListener('click', () => {
            replacement = row; cancel.hidden = false; search.focus();
            message.textContent = 'Select a search result to replace this product.';
        });
        row.querySelector('.remove-product').addEventListener('click', () => { if (replacement === row) resetReplacement(); row.remove(); recalculate(); });
        row.addEventListener('input', recalculate);
        display();
        if (!existingRow) container.append(row);
        recalculate();
    }
    Object.values(data.items).filter(item => item && typeof item === 'object').forEach(item => addRow(item));
    search.addEventListener('input', () => {
        clearTimeout(timer); request?.abort(); const current = ++generation;
        results.replaceChildren();
        const term = search.value.trim();
        if (term.length < 2) { message.textContent = 'Type at least 2 characters to search.'; return; }
        timer = setTimeout(async () => {
            request = new AbortController(); message.textContent = 'Searching...';
            try {
                const url = new URL(data.searchUrl, window.location.origin); url.searchParams.set('q', term);
                const response = await fetch(url, {headers: {Accept: 'application/json'}, signal: request.signal});
                if (!response.ok) throw new Error('Search failed');
                const payload = await response.json(); if (current !== generation) return;
                message.textContent = payload.products.length ? 'Select a product below.' : 'No matching products found.';
                payload.products.forEach(product => {
                    // Keep the original catalog, including historical inactive variations, for existing rows.
                    const previous = catalog.get(String(product.id));
                    catalog.set(String(product.id), previous ? {...product, variations: [...product.variations, ...previous.variations.filter(old => !product.variations.some(v => v.id === old.id))]} : product);
                    const result = document.createElement('div'); result.className = 'd-flex align-items-center gap-3 border rounded p-2 mb-2';
                    const thumb = document.createElement('img'); thumb.width = 48; thumb.height = 48; thumb.style.objectFit = 'contain'; thumb.alt = 'Product image'; image(thumb, product.image);
                    const text = document.createElement('div'); text.className = 'flex-grow-1 text-break'; text.textContent = `${product.name} (${product.sku || '-'})`;
                    const button = document.createElement('button'); button.type = 'button'; button.className = 'btn btn-sm btn-primary'; button.textContent = 'Select';
                    const variation = product.variations.find(value => value.active);
                    button.disabled = product.is_variation && !variation;
                    button.addEventListener('click', () => {
                        const target = replacement;
                        addRow({id: target?.querySelector('[data-field="id"]').value || '', product_id: product.id, product_variation_id: product.is_variation ? variation.id : '', quantity: target?.querySelector('[data-field="quantity"]').value || 1, sale_price: product.is_variation ? variation.sale_price : product.sale_price}, target);
                        resetReplacement(); results.replaceChildren(); search.value = '';
                    });
                    result.append(thumb, text, button); results.append(result);
                });
            } catch (error) { if (error.name !== 'AbortError' && current === generation) message.textContent = 'Unable to search products. Please try again.'; }
        }, 300);
    });
    shipping.addEventListener('input', recalculate); discount.addEventListener('input', recalculate);
    document.getElementById('shipping_id').addEventListener('change', event => {
        const charge = event.target.selectedOptions[0]?.dataset.charge;
        if (charge !== undefined) shipping.value = Number(charge).toFixed(2);
        recalculate();
    });
    form.querySelector('[name="phone"]').addEventListener('blur', event => {
        const value = event.target.value.trim().replace(/[\s()-]/g, '');
        if (/^01\d{9}$/.test(value)) event.target.value = '+88' + value;
        else if (/^8801\d{9}$/.test(value)) event.target.value = '+' + value;
    });
    form.addEventListener('submit', event => {
        recalculate();
        if (form.querySelector('.order-save').disabled) { event.preventDefault(); return; }
        form.querySelectorAll('.order-save').forEach(button => { button.disabled = true; button.textContent = 'Saving...'; });
    });
    recalculate();
})();
