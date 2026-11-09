(() => {
    'use strict';
    async function jsonRequest(url, options = {}) {
        const controller = new AbortController();
        const timer = setTimeout(() => controller.abort(), 65000);
        try {
            const response = await fetch(url, { ...options, signal: controller.signal, headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest', ...(options.headers || {}) } });
            const body = await response.json();
            if (!response.ok) throw new Error(body.message || 'Pathao request failed. Check configuration and try again.');
            return body.data;
        } finally { clearTimeout(timer); }
    }
    document.querySelectorAll('[data-pathao-locations]').forEach(root => {
        const city = root.querySelector('[data-pathao-city]');
        const zone = root.querySelector('[data-pathao-zone]');
        const area = root.querySelector('[data-pathao-area]');
        const status = root.querySelector('[data-location-status]');
        const loadButton = root.querySelector('[data-load-pathao-cities]');
        let version = 0;
        function reset(select, label) { select.replaceChildren(new Option(label, '')); select.disabled = true; }
        async function load(type, parent, select, idKey, nameKey) {
            const requestVersion = ++version;
            status.textContent = 'Loading…';
            try {
                const rows = await jsonRequest(root.dataset.locationUrl + '/' + type + (parent ? '?parent=' + encodeURIComponent(parent) : ''));
                if (version !== requestVersion) return;
                if (!Array.isArray(rows)) throw new Error('Invalid location response.');
                rows.forEach(row => select.add(new Option(row[nameKey], row[idKey])));
                select.disabled = false;
                status.textContent = rows.length ? '' : 'No locations available.';
            } catch (error) {
                if (version === requestVersion) status.textContent = error.name === 'AbortError' ? 'Request timed out. Please retry.' : error.message;
            }
        }
        loadButton.addEventListener('click', async () => {
            reset(city, 'Select city'); reset(zone, 'Select zone'); reset(area, 'Select area');
            loadButton.disabled = true;
            await load('cities', null, city, 'city_id', 'city_name');
            loadButton.disabled = false;
        });
        city.addEventListener('change', () => {
            version++; reset(zone, 'Select zone'); reset(area, 'Select area');
            if (city.value) load('zones', city.value, zone, 'zone_id', 'zone_name');
        });
        zone.addEventListener('change', () => {
            version++; reset(area, 'Select area');
            if (zone.value) load('areas', zone.value, area, 'area_id', 'area_name');
        });
    });
    document.querySelectorAll('[data-pathao-booking]').forEach(form => {
        const priceButton = form.querySelector('[data-pathao-price]');
        const output = form.querySelector('[data-pathao-price-result]');
        priceButton.addEventListener('click', async () => {
            priceButton.disabled = true;
            output.textContent = 'Calculating courier charge…';
            try {
                const data = await jsonRequest(form.dataset.priceUrl, { method: 'POST', body: new FormData(form) });
                output.textContent = 'Courier quote: BDT ' + (data.final_price ?? data.price ?? 'unavailable') + '. COD rate: ' + (data.cod_percentage ?? 'N/A') + '. This does not change the customer delivery charge.';
            } catch (error) { output.textContent = error.name === 'AbortError' ? 'Request timed out. Please retry.' : error.message; }
            finally { priceButton.disabled = false; }
        });
        form.addEventListener('submit', event => {
            if (form.dataset.submitting === 'true') { event.preventDefault(); return; }
            if (!window.confirm('Book this parcel with Pathao? Recipient details and the outstanding COD amount will be sent.')) { event.preventDefault(); return; }
            form.dataset.submitting = 'true';
            form.querySelector('button[type="submit"]').disabled = true;
        });
    });
})();
