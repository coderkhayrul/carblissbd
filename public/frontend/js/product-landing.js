(() => {
    'use strict';
    const image = document.getElementById('landing-main-image');
    const quantity = document.getElementById('landing-quantity');
    const price = document.getElementById('landing-price');
    const mrp = document.getElementById('landing-mrp');
    let currentPrice = Number((price?.textContent || '').replace(/[^0-9.]/g, ''));
    const money = value => '৳' + Number(value).toLocaleString('en-BD', {minimumFractionDigits:2,maximumFractionDigits:2});
    const total = () => document.getElementById('landing-total').textContent = money(currentPrice * Math.min(99, Math.max(1, Number(quantity.value) || 1)));
    function selectVariation(input) {
        currentPrice = Number(input.dataset.price);
        price.textContent = money(currentPrice); mrp.textContent = money(input.dataset.mrp); mrp.hidden = Number(input.dataset.mrp) <= currentPrice;
        image.src = input.dataset.image;
        document.getElementById('landing-selected-label').textContent = input.dataset.label;
        document.querySelectorAll('.landing-thumb').forEach(button => button.setAttribute('aria-pressed', String(button.dataset.image === input.dataset.image)));
        total();
    }
    document.querySelectorAll('[name="variation_id"]').forEach(input => { input.addEventListener('change', () => selectVariation(input)); if (input.checked) selectVariation(input); });
    document.querySelectorAll('.landing-thumb').forEach(button => button.addEventListener('click', () => { image.src = button.dataset.image; document.querySelectorAll('.landing-thumb').forEach(other => other.setAttribute('aria-pressed', String(other === button))); }));
    quantity.addEventListener('input', total); total();
    document.getElementById('landing-play-video')?.addEventListener('click', event => {
        const button = event.currentTarget;
        const frame = document.createElement('iframe');
        frame.src = 'https://www.youtube-nocookie.com/embed/' + encodeURIComponent(button.dataset.video) + '?autoplay=1';
        frame.title = 'Product demonstration video'; frame.allow = 'accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture'; frame.allowFullscreen = true; frame.referrerPolicy = 'strict-origin-when-cross-origin';
        document.getElementById('landing-video-frame').replaceChildren(frame);
    });
})();
