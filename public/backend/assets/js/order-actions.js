(() => {
    'use strict';
    let active = null;
    function closeMenu() {
        if (!active) return;
        active.menu.hidden = true;
        active.menu.classList.remove('show');
        active.button.setAttribute('aria-expanded', 'false');
        if (active.next?.parentNode === active.parent) active.parent.insertBefore(active.menu, active.next);
        else active.parent.append(active.menu);
        active = null;
    }
    document.addEventListener('click', event => {
        const button = event.target.closest('.order-menu-toggle');
        if (button) {
            const wasOpen = active?.button === button;
            closeMenu();
            if (wasOpen) return;
            const menu = document.getElementById(button.dataset.orderMenu);
            if (!menu) return;
            active = {button, menu, parent: menu.parentNode, next: menu.nextSibling};
            // Render in the viewport so the horizontally scrolling table cannot clip the menu.
            document.body.append(menu);
            menu.hidden = false;
            menu.classList.add('show');
            menu.style.position = 'fixed';
            menu.style.margin = '0';
            const rect = button.getBoundingClientRect();
            const width = menu.offsetWidth;
            const height = Math.min(menu.offsetHeight, window.innerHeight - 16);
            menu.style.maxHeight = height + 'px';
            menu.style.left = Math.max(8, Math.min(window.innerWidth - width - 8, rect.right - width)) + 'px';
            menu.style.top = Math.max(8, Math.min(window.innerHeight - height - 8, rect.bottom + height + 8 <= window.innerHeight ? rect.bottom + 4 : rect.top - height - 4)) + 'px';
            button.setAttribute('aria-expanded', 'true');
            menu.querySelector('a, button')?.focus();
        } else if (active && !active.menu.contains(event.target)) closeMenu();
    });
    document.addEventListener('keydown', event => {
        if (event.key === 'Escape' && active) {
            const button = active.button;
            closeMenu();
            button.focus();
        }
    });
    window.addEventListener('resize', closeMenu);
    document.addEventListener('scroll', event => {
        if (active && !active.menu.contains(event.target)) closeMenu();
    }, true);
    document.addEventListener('submit', event => {
        const form = event.target.closest('.order-courier-form');
        if (!form) return;
        if (!window.confirm(form.dataset.courier === 'Steadfast' ? 'Book this order with Steadfast? Recipient details and the outstanding COD amount will be sent to Steadfast.' : 'Assign ' + form.dataset.courier + ' to this order? This saves the assignment in the backend only.')) {
            event.preventDefault();
            return;
        }
        form.querySelector('button').disabled = true;
    });
})();
