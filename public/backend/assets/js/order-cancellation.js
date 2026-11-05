(() => {
    'use strict';
    document.querySelectorAll('.order-cancellation-field').forEach(field => {
        const select = field.closest('form').querySelector('[name="status_id"]');
        const textarea = field.querySelector('textarea');
        const cancelled = JSON.parse(field.dataset.cancelStatuses).map(String);
        const validate = () => textarea.setCustomValidity(textarea.required && !textarea.value.trim() ? 'Please explain why the customer cancelled the order.' : '');
        const update = () => {
            const required = cancelled.includes(select.value);
            field.hidden = !required; textarea.disabled = !required; textarea.required = required; validate();
        };
        select.addEventListener('change', update); textarea.addEventListener('input', validate); update();
    });
})();
