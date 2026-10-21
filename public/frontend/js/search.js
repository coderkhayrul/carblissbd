(() => {
    const mobileToggle = document.getElementById('mobile-search-toggle');
    const mobileContainer = document.getElementById('mobile-search-container');
    mobileToggle?.addEventListener('click', () => {
        const opening = mobileContainer.classList.contains('d-none');
        mobileContainer.classList.toggle('d-none', !opening);
        mobileToggle.setAttribute('aria-expanded', String(opening));
        if (opening) document.getElementById('mobile-product-search').focus({preventScroll: true});
    });

    document.querySelectorAll('.product-search').forEach(search => {
        const input = search.querySelector('input');
        const panel = search.querySelector('.product-search-results');
        const items = search.querySelector('.product-search-items');
        const status = search.querySelector('.product-search-status');
        const all = search.querySelector('.product-search-all');
        let timer;
        let controller;
        let version = 0;
        const setOpen = open => {
            panel.hidden = !open;
            input.setAttribute('aria-expanded', String(open));
        };
        const cancel = () => {
            clearTimeout(timer);
            controller?.abort();
            version++;
        };
        const close = () => { cancel(); setOpen(false); };
        const load = async (query, requestVersion) => {
            controller = new AbortController();
            try {
                const url = new URL(search.dataset.searchUrl, window.location.href);
                url.searchParams.set('q', query);
                const response = await fetch(url, {signal: controller.signal, headers: {'Accept': 'application/json'}});
                if (!response.ok) throw new Error('Search unavailable');
                const data = await response.json();
                if (version !== requestVersion) return;
                items.replaceChildren();
                status.textContent = data.products.length ? 'Products matching your search' : 'No products found. Try another name, brand or SKU.';
                data.products.forEach(product => {
                    const link = document.createElement('a');
                    link.className = 'product-search-item';
                    link.href = product.url;
                    const image = document.createElement('img');
                    image.src = product.image;
                    image.alt = product.name;
                    const content = document.createElement('span');
                    const name = document.createElement('span');
                    name.className = 'product-search-name';
                    name.textContent = product.name;
                    const price = document.createElement('span');
                    price.className = 'product-search-price';
                    price.textContent = product.price;
                    content.append(name, price);
                    link.append(image, content);
                    items.appendChild(link);
                });
                all.href = data.results_url;
                all.hidden = !data.products.length;
            } catch (error) {
                if (error.name === 'AbortError' || version !== requestVersion) return;
                status.textContent = 'Could not search right now. Press Search to try again.';
            }
        };
        const schedule = () => {
            cancel();
            const query = input.value.trim();
            if (Array.from(query).length < 2) { setOpen(false); return; }
            setOpen(true);
            items.replaceChildren();
            all.hidden = true;
            status.textContent = 'Searching...';
            const requestVersion = version;
            timer = setTimeout(() => load(query, requestVersion), 300);
        };
        input.addEventListener('input', event => { if (!event.isComposing) schedule(); });
        input.addEventListener('compositionend', schedule);
        input.addEventListener('focus', schedule);
        search.addEventListener('keydown', event => {
            if (event.key === 'Escape') { input.focus({preventScroll: true}); close(); }
            if (event.key === 'ArrowDown' || event.key === 'ArrowUp') {
                const links = [...panel.querySelectorAll('a:not([hidden])')];
                if (panel.hidden || !links.length) return;
                event.preventDefault();
                const index = links.indexOf(document.activeElement);
                const next = event.key === 'ArrowDown' ? (index + 1) % links.length : (index <= 0 ? links.length - 1 : index - 1);
                links[next].focus({preventScroll: true});
                links[next].scrollIntoView({block: 'nearest'});
            }
        });
        document.addEventListener('pointerdown', event => { if (!search.contains(event.target)) close(); });
        search.addEventListener('focusout', event => { if (!search.contains(event.relatedTarget)) close(); });
    });
})();
