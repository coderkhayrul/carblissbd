(() => {
    'use strict';
    const root = document.documentElement;
    let timer = window.storeLoaderSafety;
    function hide() { clearTimeout(timer); root.classList.remove('store-page-loading'); }
    function show() {
        clearTimeout(timer); root.classList.add('store-page-loading');
        timer = setTimeout(hide, 10000);
    }
    // Do not wait for offscreen lazy images, analytics, or third-party requests.
    function ready() { requestAnimationFrame(() => requestAnimationFrame(hide)); }
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', ready, {once: true});
    else ready();
    window.addEventListener('pageshow', hide);
    window.addEventListener('pagehide', hide);
    document.addEventListener('click', event => {
        const link = event.target.closest('a[href]');
        if (!link || event.button !== 0 || event.ctrlKey || event.metaKey || event.shiftKey || event.altKey || link.hasAttribute('download') || link.hasAttribute('onclick') || (link.target && link.target !== '_self') || link.dataset.bsToggle) return;
        const url = new URL(link.href, location.href);
        if (url.origin !== location.origin || !/^https?:$/.test(url.protocol) || (url.pathname === location.pathname && url.search === location.search)) return;
        queueMicrotask(() => { if (!event.defaultPrevented) show(); });
    });
    document.addEventListener('submit', event => {
        const form = event.target;
        if (form.target && form.target !== '_self') return;
        queueMicrotask(() => { if (!event.defaultPrevented) show(); });
    });
    const tracked = new WeakSet();
    function track(image) {
        if (tracked.has(image) || image.closest('.store-loader-icon')) return;
        tracked.add(image);
        if (!image.hasAttribute('decoding')) image.decoding = 'async';
        if (image.complete) return;
        image.classList.add('store-image-loading');
        const done = () => image.classList.remove('store-image-loading');
        image.addEventListener('load', done, {once: true}); image.addEventListener('error', done, {once: true});
        if (image.complete) done();
    }
    document.querySelectorAll('img').forEach(track);
    new MutationObserver(records => records.forEach(record => record.addedNodes.forEach(node => {
        if (node.nodeType !== 1) return;
        if (node.matches('img')) track(node);
        node.querySelectorAll('img').forEach(track);
    }))).observe(document.body, {childList: true, subtree: true});
})();
