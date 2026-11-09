(() => {
    'use strict';
    const root = document.getElementById('admin-order-notifications');
    if (!root) return;
    const initial = JSON.parse(document.getElementById('admin-order-notice-initial').textContent);
    const list = root.querySelector('[data-order-notice-list]');
    const badge = root.querySelector('[data-order-notice-badge]');
    const count = root.querySelector('[data-order-notice-count]');
    const soundButton = root.querySelector('[data-order-notice-sound]');
    const soundStatus = root.querySelector('[data-order-notice-sound-status]');
    const readAllButton = root.querySelector('[data-order-notice-read-all]');
    const csrf = root.querySelector('input[name="_token"]').value;
    const key = 'admin-order-notices:' + root.dataset.userId + ':';
    const pending = new Map();
    let feed = initial;
    let timer;
    let busy = false;
    let stopped = false;
    let audio;
    let soundBusy = false;
    let failures = 0;

    function load(storage, name, fallback) {
        try { return JSON.parse(storage.getItem(key + name)) ?? fallback; } catch (_) { return fallback; }
    }
    function save(storage, name, value) {
        try { storage.setItem(key + name, JSON.stringify(value)); } catch (_) { /* Storage can be blocked by browser settings. */ }
    }
    const previous = load(sessionStorage, 'seen', null);
    const seen = new Set(previous || initial.items.map(item => item.id));
    let soundEnabled = load(localStorage, 'sound', true);
    function rememberSeen() { save(sessionStorage, 'seen', Array.from(seen).slice(-500)); }
    function sharedIds(name) { return new Set(load(localStorage, name, [])); }
    function rememberShared(name, ids) {
        const shared = sharedIds(name);
        ids.forEach(id => shared.add(id));
        save(localStorage, name, Array.from(shared).slice(-500));
    }
    async function withBrowserLock(name, action) {
        if (navigator.locks?.request) return navigator.locks.request(key + name, { ifAvailable: true }, lock => lock ? action() : undefined);
        return action();
    }
    function updateSoundLabel() {
        const running = audio?.state === 'running';
        soundButton.textContent = !soundEnabled ? 'Sound Off' : running ? 'Sound On' : 'Enable Sound';
        soundButton.setAttribute('aria-pressed', String(soundEnabled && running));
        soundStatus.textContent = !soundEnabled ? 'Order sounds are muted.' : running ? 'New order sound is enabled.' : 'Click Enable Sound once to allow order sounds in this browser.';
    }
    function audioContext() {
        const AudioContext = window.AudioContext || window.webkitAudioContext;
        if (!AudioContext) return null;
        if (!audio || audio.state === 'closed') audio = new AudioContext();
        return audio;
    }
    function chime() {
        const context = audioContext();
        if (!context || context.state !== 'running') return false;
        const start = context.currentTime;
        [659.25, 880, 1174.66].forEach((frequency, index) => {
            const oscillator = context.createOscillator();
            const gain = context.createGain();
            oscillator.type = 'sine';
            oscillator.frequency.value = frequency;
            gain.gain.setValueAtTime(0, start + index * 0.15);
            gain.gain.linearRampToValueAtTime(0.12, start + index * 0.15 + 0.015);
            gain.gain.exponentialRampToValueAtTime(0.001, start + index * 0.15 + 0.35);
            oscillator.connect(gain); gain.connect(context.destination);
            oscillator.start(start + index * 0.15);
            oscillator.stop(start + index * 0.15 + 0.36);
            oscillator.onended = () => { oscillator.disconnect(); gain.disconnect(); };
        });
        return true;
    }
    async function playPending() {
        if (soundBusy || !pending.size) return;
        if (!soundEnabled) { pending.clear(); return; }
        soundBusy = true;
        try {
            await withBrowserLock('sound-lock', () => {
                const played = sharedIds('played');
                played.forEach(id => pending.delete(id));
                if (!pending.size) return;
                if (chime()) { rememberShared('played', Array.from(pending.keys())); pending.clear(); }
            });
        } catch (_) { /* A locked audio context keeps the alerts pending until interaction. */ }
        finally { soundBusy = false; updateSoundLabel(); }
    }
    function unlockSound() {
        if (!soundEnabled) return;
        try {
            const context = audioContext();
            if (!context) { soundStatus.textContent = 'Sound is not supported in this browser.'; return; }
            const resume = context.resume();
            resume.then(() => { updateSoundLabel(); playPending(); }).catch(updateSoundLabel);
        } catch (_) { updateSoundLabel(); }
    }
    soundButton.addEventListener('click', () => {
        if (soundEnabled && audio?.state === 'running') { soundEnabled = false; pending.clear(); }
        else soundEnabled = true;
        save(localStorage, 'sound', soundEnabled);
        if (soundEnabled) unlockSound();
        updateSoundLabel();
    });
    document.addEventListener('pointerdown', event => { if (!soundButton.contains(event.target)) unlockSound(); }, { passive: true });
    document.addEventListener('keydown', unlockSound, { passive: true });

    function element(tag, className, text) {
        const node = document.createElement(tag);
        if (className) node.className = className;
        if (text !== undefined) node.textContent = text;
        return node;
    }
    function render(data) {
        badge.hidden = data.unread_count === 0;
        badge.textContent = data.unread_count > 99 ? '99+' : String(data.unread_count);
        count.textContent = data.unread_count + ' unread';
        list.replaceChildren();
        if (!data.items.length) list.appendChild(element('div', 'p-4 text-center text-muted', 'No new orders yet.'));
        data.items.forEach(item => {
            const row = element('div', 'd-flex align-items-start gap-2 p-3 border-bottom' + (!item.read ? ' bg-label-primary' : ''));
            row.dataset.orderNoticeId = item.id;
            const icon = element('i', 'bx bx-cart fs-4'); icon.setAttribute('aria-hidden', 'true');
            const link = element('a', 'flex-grow-1 text-body');
            link.href = item.url; link.dataset.orderNoticeOpen = item.id;
            link.style.cssText = 'min-width:0;overflow-wrap:anywhere';
            link.append(element('strong', 'd-block', 'New Order: ' + item.order_no), element('small', 'd-block', item.customer + ' · BDT ' + item.amount), element('small', 'd-block text-muted', item.source + ' · ' + item.time));
            row.append(icon, link);
            if (!item.read) {
                const button = element('button', 'btn btn-sm btn-link p-1');
                button.type = 'button'; button.dataset.orderNoticeRead = item.id;
                button.setAttribute('aria-label', 'Mark ' + item.order_no + ' as read');
                const check = element('i', 'bx bx-check'); check.setAttribute('aria-hidden', 'true');
                button.appendChild(check); row.appendChild(button);
            }
            list.appendChild(row);
        });
    }
    async function toast(items) {
        await withBrowserLock('toast-lock', () => {
            const announced = sharedIds('announced');
            const fresh = items.filter(item => !announced.has(item.id));
            if (!fresh.length) return;
            rememberShared('announced', fresh.map(item => item.id));
            const notice = element('div', 'alert alert-success shadow-lg border');
            notice.style.cssText = 'position:fixed;top:90px;right:16px;z-index:2200;width:360px;max-width:calc(100vw - 32px);overflow-wrap:anywhere';
            notice.setAttribute('role', 'status');
            const close = element('button', 'btn-close float-end'); close.type = 'button'; close.setAttribute('aria-label', 'Dismiss notification'); close.onclick = () => notice.remove();
            const first = fresh[0];
            const link = element('a', 'd-block mt-2 fw-bold text-success', fresh.length === 1 ? 'View Order ' + first.order_no : 'View New Orders');
            link.href = fresh.length === 1 ? first.url : root.querySelector('a.btn-primary').href;
            notice.append(close, element('strong', 'd-block', fresh.length === 1 ? 'New order received!' : fresh.length + ' new orders received!'), element('small', 'd-block', first.source + ' · ' + first.customer + ' · BDT ' + first.amount), link);
            document.body.appendChild(notice);
            setTimeout(() => notice.remove(), 8000);
        });
    }
    function receive(data, first = false) {
        feed = data;
        const createdOrder = Number(root.dataset.createdOrder);
        const fresh = data.items.filter(item => (!seen.has(item.id) || (first && item.order_id === createdOrder)) && !item.read);
        data.items.forEach(item => seen.add(item.id));
        rememberSeen(); render(data);
        if (fresh.length) {
            fresh.forEach(item => pending.set(item.id, item));
            toast(fresh).catch(() => {});
            playPending();
        }
    }
    async function request(url, body) {
        const controller = new AbortController();
        const timeout = setTimeout(() => controller.abort(), 10000);
        try {
            const response = await fetch(url, { method: body ? 'POST' : 'GET', cache: 'no-store', credentials: 'same-origin', signal: controller.signal, keepalive: !!body,
                headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest', ...(body ? { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf } : {}) },
                ...(body ? { body: JSON.stringify(body) } : {}) });
            if (response.redirected || [401, 403, 419].includes(response.status)) { stopped = true; throw new Error('Session expired. Reload the page.'); }
            if (!response.ok) throw new Error('Unable to update order notifications.');
            return await response.json();
        } finally { clearTimeout(timeout); }
    }
    function schedule() {
        clearTimeout(timer);
        if (!stopped) timer = setTimeout(poll, failures ? Math.min(60000, 10000 * failures) : document.hidden ? 15000 : 5000);
    }
    async function poll() {
        if (busy || stopped) return;
        busy = true;
        try { receive(await request(root.dataset.feedUrl)); failures = 0; }
        catch (_) { failures++; }
        finally { busy = false; schedule(); }
    }
    async function markRead(id, button) {
        if (button) button.disabled = true;
        try { await request(root.dataset.readUrl + '/' + id + '/read', {}); await poll(); }
        catch (error) { soundStatus.textContent = error.message; if (button) button.disabled = false; }
    }
    list.addEventListener('click', event => {
        const readButton = event.target.closest('[data-order-notice-read]');
        if (readButton) { event.preventDefault(); markRead(readButton.dataset.orderNoticeRead, readButton); return; }
        const link = event.target.closest('[data-order-notice-open]');
        if (link) request(root.dataset.readUrl + '/' + link.dataset.orderNoticeOpen + '/read', {}).catch(() => {});
    });
    readAllButton.addEventListener('click', async () => {
        readAllButton.disabled = true;
        try { await request(root.dataset.readAllUrl, { through_id: Math.max(0, ...feed.items.map(item => item.id)) }); await poll(); }
        catch (error) { soundStatus.textContent = error.message; }
        finally { readAllButton.disabled = false; }
    });
    document.addEventListener('visibilitychange', () => { if (!document.hidden) { clearTimeout(timer); poll(); } });
    window.addEventListener('pageshow', event => { if (event.persisted) { stopped = false; poll(); } });
    window.addEventListener('pagehide', () => { clearTimeout(timer); });
    window.addEventListener('storage', event => {
        if (event.key === key + 'sound') { soundEnabled = load(localStorage, 'sound', true); if (!soundEnabled) pending.clear(); updateSoundLabel(); }
        if (event.key === key + 'played') sharedIds('played').forEach(id => pending.delete(id));
    });
    receive(initial, true);
    updateSoundLabel();
    if (load(localStorage, 'sound', null) === true) unlockSound();
    schedule();
})();
