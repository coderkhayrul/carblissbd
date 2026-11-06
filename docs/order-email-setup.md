# Order confirmation email setup

1. Open **Admin > Email Settings**. Enter your provider's verified sender address, SMTP host, port, username and password. Choose TLS/STARTTLS (usually port 587), SSL (usually 465), or None only if your provider requires it. SMTP providers using API keys as SMTP passwords are supported through these same fields.
2. Enable **Order Confirmation Emails** and save. Passwords are encrypted with Laravel's APP_KEY; preserve that key. Blank password input keeps the saved password. Credentials never appear in the form or queued job payload.
3. Run a persistent worker under your process manager:

   ```sh
   php artisan queue:work database --queue=default --tries=3 --timeout=60
   ```

4. Run Laravel's scheduler every minute using cron or the server task scheduler:

   ```sh
   php artisan schedule:run
   ```

Website checkout, admin New Sale and admin Duplicate Order schedule confirmation only after the complete database transaction commits. Order updates do not send a new confirmation. The order recipient email is used first, with the linked customer email as fallback. Orders without a valid email address are skipped. Orders created while email is disabled are not sent retroactively.

SMTP settings are read for each delivery, so changing them does not require rebuilding config or restarting the queue worker. Emails contain the invoice number, products and variations, delivery information, totals, paid amount and due amount. Sending an email does not change the order's business status.

See **Recent Order Emails** in Email Settings for pending, sent, skipped or failed deliveries. Failed sends retry three times. After fixing configuration, inspect `php artisan queue:failed` and run `php artisan queue:retry JOB_UUID` for the specific email job. Pending notification records whose queue insertion was interrupted are recovered by the scheduler. Database row locks and a sent timestamp prevent repeat processing of already recorded deliveries; SMTP acceptance followed by a process/database failure can still cause a duplicate on retry.

No provider credentials, worker or external email delivery are configured by this implementation. No test emails were sent.
