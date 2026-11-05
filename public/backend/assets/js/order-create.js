(() => {
    'use strict';
    const search = document.getElementById('sale-customer-search');
    if (!search) return;
    const results = document.getElementById('sale-customer-results');
    const message = document.getElementById('sale-customer-message');
    const id = document.getElementById('sale-customer-id');
    const selected = document.getElementById('sale-selected-customer');
    const modalForm = document.getElementById('saleCustomerForm');
    const modal = document.getElementById('saleCustomerModal');
    let timer, request, generation = 0;
    const normalize = value => {
        value = value.trim().replace(/[\s()-]/g, '');
        return /^01\d{9}$/.test(value) ? '+88' + value : /^8801\d{9}$/.test(value) ? '+' + value : value;
    };
    function selectCustomer(customer) {
        id.value = customer.id;
        selected.textContent = `${customer.name} - ${customer.mobile || 'No phone'}`;
        selected.hidden = false;
        const values = {name: customer.name, phone: normalize(customer.mobile || ''), email: customer.email, address: customer.shipping_address || customer.address};
        Object.entries(values).forEach(([key, value]) => document.getElementById(key).value = value || '');
        results.replaceChildren(); search.value = '';
        message.textContent = 'Customer selected. Check the recipient and delivery information below.';
    }
    search.addEventListener('keydown', event => { if (event.key === 'Enter') event.preventDefault(); });
    search.addEventListener('input', () => {
        clearTimeout(timer); request?.abort(); const current = ++generation;
        results.replaceChildren(); const term = search.value.trim();
        if (term.length < 2) { message.textContent = 'Type at least 2 characters to search.'; return; }
        timer = setTimeout(async () => {
            request = new AbortController(); message.textContent = 'Searching...';
            try {
                const url = new URL(search.dataset.searchUrl, window.location.origin); url.searchParams.set('q', term);
                const response = await fetch(url, {headers: {Accept: 'application/json'}, signal: request.signal});
                if (!response.ok) throw new Error();
                const payload = await response.json(); if (current !== generation) return;
                message.textContent = payload.customers.length ? 'Select a customer.' : 'No customer found. Use New Customer to create one.';
                payload.customers.forEach(customer => {
                    const button = document.createElement('button'); button.type = 'button'; button.className = 'btn btn-label-secondary w-100 text-start text-wrap mb-2';
                    button.textContent = `${customer.name} - ${customer.mobile || 'No phone'} (#${customer.id})`;
                    button.addEventListener('click', () => selectCustomer(customer)); results.append(button);
                });
            } catch (error) { if (error.name !== 'AbortError' && current === generation) message.textContent = 'Unable to search customers. Please try again.'; }
        }, 300);
    });
    modalForm.elements.phone.addEventListener('blur', event => event.target.value = normalize(event.target.value));
    modalForm.addEventListener('submit', async event => {
        event.preventDefault();
        const save = document.getElementById('save-sale-customer');
        const errors = document.getElementById('sale-customer-errors');
        save.disabled = true; errors.hidden = true;
        modalForm.elements.phone.value = normalize(modalForm.elements.phone.value);
        try {
            const response = await fetch(modalForm.action, {method: 'POST', headers: {Accept: 'application/json'}, body: new FormData(modalForm)});
            const payload = await response.json();
            if (!response.ok) {
                errors.textContent = payload.errors ? Object.values(payload.errors).flat().join(' ') : payload.message || 'Unable to save customer.';
                errors.hidden = false; return;
            }
            ++generation; clearTimeout(timer); request?.abort();
            selectCustomer(payload.customer);
            bootstrap.Modal.getInstance(modal).hide(); modalForm.reset();
        } catch (_) { errors.textContent = 'Unable to save customer. Please try again.'; errors.hidden = false;
        } finally { save.disabled = false; }
    });
    document.getElementById('editOrderForm').addEventListener('submit', event => {
        if (!id.value) {
            event.preventDefault(); message.textContent = 'Select or create a customer before saving.'; search.focus();
            document.querySelectorAll('.order-save').forEach(button => { button.disabled = false; button.textContent = 'Create Order'; });
        }
    });
    const paymentType = document.getElementById('payment_type');
    const reference = document.getElementById('transaction_id');
    const updateReference = () => reference.required = paymentType.value === 'Online';
    paymentType.addEventListener('change', updateReference); updateReference();
})();
