# Admin order notifications

Every new Eloquent Order creates one durable `admin_order_notifications` record. The creation hook runs in the same transaction as checkout / admin sales / duplicate orders, so a rolled-back order cannot appear in the feed. The notification stores the order reference, not a premature snapshot of customer/totals. Existing orders are not backfilled.

The navbar displays the latest 20 orders, source (Website / Landing Page / Admin-GSale), recipient, invoice, amount, time and unread count. Every admin has independent read receipts. Clicking an order marks its notification read; the check button and Mark all read are also available. Notifications link to existing authorized order views. These are in-app admin alerts, not customer email/SMS and not OS push notifications.

The authenticated admin feed polls every five seconds while visible and every fifteen seconds in background, with timeout/backoff for failures. Browser background throttling/sleep may delay updates. No queue worker, websocket server or third-party service is required. The panel must be open for audible alerts. After being closed, saved notifications remain in the navbar but do not replay historical sounds on first opening.

Sound uses a short generated Web Audio chime without external audio files. Click Enable Sound once or interact with the page to allow audio; browser autoplay policy may otherwise prevent sound. The navbar supports mute, remembered per admin/browser. New alerts blocked by autoplay remain pending until interaction. Browsers with Web Locks deduplicate toast/sound playback across tabs; shared browser storage also records announced/played IDs. Storage restrictions may limit cross-tab persistence.

Admin-created orders and duplicate orders carry a one-request sound hint to the redirected order page so creating an order on the current tab also announces it. Existing order saves, status updates, courier updates, and idempotent checkout reuse do not create a second notification record. Browser text is rendered with textContent/escaped Blade to avoid interpreting recipient names as HTML.

Tables: admin_order_notifications (unique order_id) and admin_order_notification_reads (unique notification/user pair). Deleting orders/users cascades their respective records. The existing unrelated notifications table is preserved.

No tests, simulated orders or actual notification sounds were run during implementation. The migration was applied locally.
