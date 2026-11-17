# Admin new-order email

Every newly created Eloquent Order (website checkout, landing checkout, admin sale, duplicate order) creates one durable `admin_order_email_notifications` record in the order transaction. Rolled-back orders do not send notifications. Existing orders are not backfilled. Customer confirmation emails remain separate, so an order without a customer email still notifies the admin.

Recipient: the enabled Email Setting's **Sender Email**, using its existing SMTP configuration. This is the configured sending address, which can differ from the SMTP login username. The recipient is resolved when the job sends and saved with the successful delivery record.

Run the migration during deployment before serving the updated application:

```sh
php artisan migrate --force
```

Production needs a supervised database queue worker and the Laravel scheduler:

```sh
php artisan queue:work database --queue=default --sleep=3 --tries=3 --timeout=60
php artisan schedule:run
```

Run `schedule:run` every minute via the server's scheduler. Restart existing long-running workers on deployment. No worker is started by this implementation because the existing queue can also contain customer emails and SMS.

The job is dispatched after commit with a short delay. Disabled or unconfigured SMTP leaves the record pending; the scheduler redispatches pending records older than 15 minutes every five minutes. SMTP exceptions retry up to three times with safe error messages. Failed records are retained for diagnosis and can be retried through Laravel's failed-job tooling after correcting the configuration. Delivery locks and `sent_at` prevent ordinary duplicate dispatches from sending twice. SMTP acceptance followed by a process/database failure can still duplicate a message; SMTP does not provide exactly-once delivery.

The responsive table-based HTML email includes the order number, Dhaka creation time, source/channel, status, recipient information, product and variation snapshots, totals, payment balance, customer note, and an authenticated admin order link. All dynamic customer text is escaped. Set the production `APP_URL` to the public HTTPS address so email links work outside the development machine.

No test messages or automated tests were executed.
