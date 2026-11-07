# Send customer emails

1. Configure and enable **Admin > Settings > Email Settings** using your actual SMTP provider and verified sender email.
2. Open **Customers > Actions > Send Email**. The customer's saved email is shown read-only.
3. Enter the subject and plain-text message, then submit. The result stays in the modal. Cancel closes the modal; clicking outside does not dismiss it.
4. Open **Customer Profile > Recent Customer Emails** to see recipient, subject, full message and processing/sent/failed status.

Manual customer emails send immediately through the same SMTP configuration used by order confirmation jobs; they do not need a queue worker. Order confirmations continue using the existing database queue. SMTP credentials are read at send time. Messages are escaped in the email template and history view. No customer email address or recipient can be overridden through the form.

Requests require admin authorization, CSRF validation and a unique submission token, with a 10-request-per-minute limit. A repeated token never sends again. If a network/SMTP timeout makes the result uncertain, inspect your provider's records before opening a new message: it might already have accepted the previous one. Sent means SMTP submission, not guaranteed inbox delivery.

No provider settings were changed, no emails were sent, and no tests were run during implementation.
