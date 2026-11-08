(() => {
    'use strict';
    const container = document.getElementById('catalog-load-more');
    if (!container) return;
    const grid = document.getElementById('catalog-products-grid');
    const button = document.getElementById('catalog-load-button');
    const icon = document.getElementById('catalog-loading-icon');
    const status = document.getElementById('catalog-load-status');
    const sentinel = document.getElementById('catalog-scroll-sentinel');
    let nextUrl = button.href, busy = false, observer;
    async function load() {
        if (busy || !nextUrl) return;
        busy = true; observer?.unobserve(sentinel);
        grid.setAttribute('aria-busy', 'true'); button.setAttribute('aria-disabled', 'true');
        button.textContent = 'Loading...'; icon.hidden = false; status.textContent = '';
        const controller = new AbortController();
        const timeout = setTimeout(() => controller.abort(), 20000);
        let succeeded = false;
        try {
            const response = await fetch(nextUrl, {headers: {Accept: 'application/json'}, signal: controller.signal});
            if (!response.ok) throw new Error('Unable to load products');
            const data = await response.json();
            grid.insertAdjacentHTML('beforeend', data.html);
            nextUrl = data.has_more ? data.next_url : null;
            const count = document.querySelector('.catalog-result-count');
            if (count) count.textContent = `${container.dataset.first}–${data.last || grid.children.length} of ${data.total} products`;
            if (nextUrl) button.href = nextUrl;
            else { button.hidden = true; sentinel.hidden = true; status.textContent = 'All products have been loaded.'; observer?.disconnect(); }
            succeeded = true;
        } catch (_) {
            status.textContent = 'Could not load products. Click View More Products to try again.';
        } finally {
            clearTimeout(timeout); busy = false; icon.hidden = true;
            grid.setAttribute('aria-busy', 'false'); button.removeAttribute('aria-disabled'); button.textContent = 'View More Products';
            if (succeeded && nextUrl) observer?.observe(sentinel);
        }
    }
    button.addEventListener('click', event => {
        if (event.ctrlKey || event.metaKey || event.shiftKey || event.altKey) return;
        event.preventDefault(); load();
    });
    if ('IntersectionObserver' in window) {
        observer = new IntersectionObserver(entries => { if (entries.some(entry => entry.isIntersecting)) load(); }, {rootMargin: '0px 0px 250px 0px'});
        observer.observe(sentinel);
    }
})();
