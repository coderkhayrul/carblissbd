# Order confirmation SMS

Website checkout, admin New Sale, and admin duplicate-order creation record one SMS notification per new order when SMS is enabled. Saving edits or resubmitting the same checkout token does not create another notification. The recipient is the order's contact phone, with the customer mobile as fallback.

## Provider setup

Open **Settings → SMS API Settings**. Replace any seeded demo values with the actual provider endpoint, sender ID and credentials. Keep SMS disabled until the configuration is complete.

Set the HTTP method, form/JSON format, recipient number format, request parameters and optional headers according to the provider documentation. Parameter keys are provider-specific. Values can contain `{phone}`, `{message}`, `{api_key}`, `{api_secret}` and `{sender_id}`. Configure the Unicode/type parameter required by your provider for Bangla messages. Blank credential inputs preserve saved credentials.

For example, a provider using bearer authentication can use `{"Authorization":"Bearer {api_key}"}` in request headers. Put credential placeholders in headers/parameters rather than copying credentials into these JSON fields.

Configure the provider's JSON success field and expected value if available. Boolean values use `true` or `false`; nested fields support dot notation. With no success field, HTTP 2xx is treated as submission success. Providers requiring a custom signature, XML, or non-JSON success parsing need a provider-specific adapter after their documentation is available.

The message supports `{company}`, `{customer}`, `{order_no}`, `{total}`, and `{hotline}`. The total includes shipping and applicable order adjustments. Default wording acknowledges receipt because new website orders are initially pending.

## Background processes

Keep a Laravel queue worker running under your hosting process manager:

```sh
php artisan queue:work database --sleep=3 --tries=1 --timeout=30
```

Run the Laravel scheduler every minute (`php artisan schedule:run`) using your hosting cron/task scheduler. For local development, `php artisan schedule:work` can run it continuously. The recovery task requeues pending notifications every five minutes if queue insertion was interrupted. Duplicate jobs do not resend: each notification must atomically transition from pending to processing before contacting the provider.

After deploying code changes, restart long-running queue workers with `php artisan queue:restart`.

## Statuses and failures

Recent notifications appear below SMS settings. `pending` is waiting for a worker, `processing` is claimed, `submitted` means the provider API accepted the configured response, `failed` requires investigation, and `skipped` means SMS was disabled before processing. Submission is not a handset delivery receipt.

Requests run after the order transaction commits; provider/network failures do not roll back the order. There is deliberately no automatic network retry because a timeout can occur after the provider has already accepted the message. Check provider records before any manual resend. A hard-killed worker can leave a notification processing; investigate before resetting it. Disabled SMS does not backfill historical orders when enabled later.

No live SMS is sent by saving settings. Credentials and provider response bodies are not written into notification error details. Existing API credential columns remain stored as before; restrict access to the database and admin settings.
