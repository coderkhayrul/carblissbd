(() => {
    'use strict';
    const modal = document.getElementById('sendEmailModal');
    if (!modal) return;
    const form = document.getElementById('sendCustomerEmailForm');
    const subject = document.getElementById('customerEmailSubject');
    const body = document.getElementById('customerEmailMessage');
    const result = document.getElementById('customerEmailResult');
    const send = document.getElementById('sendCustomerEmailButton');
    const cancel = document.getElementById('cancelCustomerEmail');
    let busy = false;
    const token = () => window.crypto?.randomUUID?.() || 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, character => { const number = Math.floor(Math.random() * 16); return (character === 'x' ? number : (number & 3) | 8).toString(16); });
    modal.addEventListener('show.bs.modal', event => {
        const trigger = event.relatedTarget;
        if (busy || !trigger) { event.preventDefault(); return; }
        form.reset(); form.action = trigger.dataset.sendUrl;
        document.getElementById('customerEmailToken').value = token();
        document.getElementById('emailCustomerName').value = trigger.dataset.customerName || '';
        document.getElementById('emailCustomerAddress').value = trigger.dataset.customerEmail || '';
        document.getElementById('customerEmailCount').textContent = '0 / 15000 characters';
        subject.readOnly = false; body.readOnly = false; subject.setCustomValidity(''); body.setCustomValidity('');
        send.disabled = !trigger.dataset.customerEmail; send.textContent = 'Send Email';
        result.classList.add('d-none', 'alert-danger'); result.classList.remove('alert-success');
        if (send.disabled) { result.textContent = 'This customer does not have an email address.'; result.classList.remove('d-none'); }
    });
    modal.addEventListener('shown.bs.modal', () => subject.focus());
    modal.addEventListener('hide.bs.modal', event => { if (busy) event.preventDefault(); });
    subject.addEventListener('input', () => subject.setCustomValidity(''));
    body.addEventListener('input', () => { body.setCustomValidity(''); document.getElementById('customerEmailCount').textContent = body.value.length + ' / 15000 characters'; });
    form.addEventListener('submit', async event => {
        event.preventDefault();
        if (busy || send.disabled) return;
        subject.setCustomValidity(subject.value.trim() ? '' : 'Enter a subject.');
        body.setCustomValidity(body.value.trim() ? '' : 'Enter your email message.');
        if (!form.reportValidity()) return;
        busy = true; send.disabled = true; cancel.disabled = true; subject.readOnly = true; body.readOnly = true;
        send.textContent = 'Sending...'; result.classList.add('d-none');
        let allowCorrection = false;
        const controller = new AbortController(); const timeout = setTimeout(() => controller.abort(), 40000);
        try {
            const response = await fetch(form.action, {method:'POST', headers:{Accept:'application/json'}, body:new FormData(form), signal:controller.signal});
            const data = await response.json();
            allowCorrection = [422, 429, 419].includes(response.status);
            const success = response.ok && data.status === 'sent';
            result.textContent = data.errors ? Object.values(data.errors).flat().join(' ') : data.message || 'Unable to send email.';
            result.classList.toggle('alert-success', success); result.classList.toggle('alert-danger', !success);
            send.textContent = success ? 'Email Submitted' : data.status === 'processing' ? 'Already Processing' : 'Send Email';
        } catch (_) {
            result.textContent = 'Unable to confirm email submission. Check provider records before sending again; the message may already have been accepted.';
            result.classList.remove('alert-success'); result.classList.add('alert-danger'); send.textContent = 'Submission Unconfirmed';
        } finally {
            clearTimeout(timeout); busy = false; cancel.disabled = false; send.disabled = !allowCorrection;
            subject.readOnly = !allowCorrection; body.readOnly = !allowCorrection; result.classList.remove('d-none');
        }
    });
})();
