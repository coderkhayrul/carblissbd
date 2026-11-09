# Pathao Merchant API setup

Implemented against the merchant API and webhook screenshots supplied by the user on 10 November 2026.

## Configuration

Open **Configuration > Courier Configuration > Pathao**.

The supplied production Client ID, Client Secret, merchant email and password have been saved encrypted in the local database. Authentication was completed, encrypted tokens stored, one active store loaded and **Car Bliss BD** selected as the default pickup store. Pathao and webhook reception are enabled. Credentials are not embedded in source code. Do not use the documentation's public sandbox account for production credentials.

Production: `https://api-hermes.pathao.com`. Sandbox: `https://courier-api-sandbox.pathao.com`. Only these hosts are accepted and redirects are disabled. Switching environment invalidates stored tokens and clears cached pickup stores/default store. Re-enter the appropriate credentials for the new environment. Preserve Laravel APP_KEY so encrypted data remains readable.

Click **Connect / Load Stores**. This obtains/reuses an access token, follows paginated store results (up to 20 pages), and saves a picker of your stores. Select an active pickup store and save again. Save preferred delivery type (48 normal, 12 on demand), item type (1 document, 2 parcel) and default parcel weight (0.5–10 kg).

OAuth tokens are persisted encrypted with expiry. The same issue-token endpoint supports password and refresh_token grants. Refresh is serialized with a shared cache lock and occurs before expiry. An invalid refresh token gets one password-grant fallback. A rejected API access token gets one refresh/retry; network errors or booking server failures are never blindly retried. Authentication failures block further requests until corrected credentials are saved. HTTP 429 respects numeric Retry-After, otherwise waits five minutes. No access token is sent to the browser or placed in URLs.

## Stores and locations

The configuration page can create a pickup store with name/contact 3–50 characters, local 11-digit phones, address 15–120 characters, and required city/zone/area IDs. City → zone → area selectors load their options through authenticated admin endpoints. The server checks parent-child relationships too. Lookup lists are cached per environment for an hour. Pathao can require one hour for store approval; reload stores afterwards. A timed-out create-store request must be checked in the merchant panel before manual resubmission.

## Order booking

Order List → courier dropdown → **Assign Pathao** opens a dedicated booking page. Review pickup store, delivery/item type, package quantity, weight, optional recipient location and special instructions. The saved order recipient is shown beside the form. Correct the order before booking if necessary.

The merchant order ID is the existing invoice number. Recipient name must be 3–100 characters, address 10–220 characters and mobile 11 digits. Database +880 mobiles are converted to local form only for the outgoing request. Outstanding order due becomes amount_to_collect. Pathao requires an integer, so fractional outstanding taka is rejected rather than silently rounded. Fully paid orders send zero COD. Optional recipient location keys are omitted when unselected, not sent as null; Pathao may infer them from the address according to the supplied documentation.

**Calculate Courier Charge** calls merchant/price-plan with store, item/delivery type, weight, city and zone. A quote is informational and does not overwrite the customer's order shipping charge or totals. The courier's returned delivery_fee is saved separately after booking.

Each order has one reserved Pathao shipment. Reservations are committed before booking HTTP calls, so double-clicks do not create another parcel. Steadfast/other courier assignment and order product/recipient/amount edits are blocked once a Pathao reservation exists. Pathao booking is also blocked if a Steadfast reservation exists. No existing locally assigned Pathao orders are automatically sent.

A confirmed booking saves consignment ID, environment, pickup store, courier status, fee and encrypted payload snapshot. An ambiguous timeout leaves an unconfirmed reservation. Search the invoice in Pathao, then enter its consignment ID in the order view to recover it; the server verifies both identifiers against Order Info. If no parcel exists, an explicit admin confirmation can clear an unconfirmed reservation after two minutes. Clearing does not cancel or create a parcel. Confirmed bookings cannot be cleared this way.

## Status and scope

Order view and courier dropdown offer **Sync Pathao Status**. It reads orders/{consignment_id}/info, verifies invoice/consignment identifiers and applies exact configured status mappings. Unmapped statuses update courier details only. Mapping keys are normalized to lowercase but otherwise match the returned order_status_slug / order_status exactly; the supplied screenshots do not define a complete status vocabulary, so the UI permits additional mapping rows. A default pending → Processing mapping is saved if that active local status exists. Each provider status change is recorded in shipment history.

Webhook integration is included below. There is no automatic API polling, cancellation API, or automatic settlement/inventory changes. The screenshots do not document a cancellation endpoint. The bulk-order screenshot only shows the start of its request without response/per-order failure semantics; bookings use the documented single-order endpoint with individual confirmations, not an assumed bulk-response parser.

Implemented endpoints: issue-token (both grants), GET/POST stores, POST orders, GET orders/{consignment_id}/info, city-list, cities/{city_id}/zone-list, zones/{zone_id}/area-list, merchant/price-plan. All admin actions require admin authentication and writes require CSRF. Credentials/passwords are excluded from validation session flashing; errors never expose raw provider responses or authorization headers.

## Webhook setup

Callback route: POST `/webhooks/pathao`. The generated Callback URL and private Webhook Secret are available in the admin configuration page. Add them to Pathao's Webhook Integration screen and select events. The local `http://carblissbd.test/webhooks/pathao` cannot receive callbacks from Pathao; register a public HTTPS URL with a valid certificate and configure production APP_URL. No external webhook registration was performed from the local application.

Pathao's `webhook_integration` probe receives HTTP 202 and response header `X-Pathao-Merchant-Webhook-Integration-Secret: f3992ecc-59da-4cbe-a049-a13da2018d51`. That fixed public verification value is not the private secret. An unsigned integration probe only receives this static acknowledgement and does not change orders or mark authentication verified. If a signature is supplied on the probe it must match.

For all normal events the incoming `X-PATHAO-Signature` must equal our saved private webhook secret, using a timing-safe comparison. The screenshot documents a plain shared secret, not HMAC. Authentication is independent of OAuth token availability. Secrets can be replaced in the admin form and must be updated in Pathao too; they are excluded from validation session flashing.

Authenticated events are saved encrypted in a durable inbox and acknowledged with HTTP 202 plus the fixed verification header. No outbound courier call is made in the webhook HTTP request, keeping external API latency out of the ten-second response budget. Canonical payload hashes deduplicate retries, including JSON whitespace / top-level field ordering differences.

Run `php artisan queue:work database --queue=default --tries=3 --timeout=60` continuously for realtime processing. Server cron must run `php artisan schedule:run` every minute; the scheduler recovers pending events if queue insertion or the worker was interrupted. Processing is serialized per shipment. Worker/scheduler launch is a deployment step; they were not started during implementation, because the existing default queue also contains outbound email/SMS jobs.

All 24 documented events are recognized. Order events match a managed shipment's consignment, optional merchant invoice and store ID; they never create customer orders from arbitrary external parcels. An authenticated callback can recover a lost consignment identifier for an existing reservation. Unknown/unmanaged/mismatched events are acknowledged and marked ignored, shown in recent webhook deliveries. Store events asynchronously refresh current merchant store data.

Use exact `order.*` event mappings in admin settings. Defaults map created/pickup-requested/assigned-for-pickup to Processing, picked/transit/hub/delivery-assignment to Shipped, delivered to Delivered, and full return / returned-to-merchant to Returned when those local statuses exist. Partial delivery, failed pickup/delivery, hold and exchanges are left for explicit business mappings. Pickup cancellation does not falsely cancel the customer's order. Returned exchange/partial remainders do not automatically mark the full order returned.

Webhook ISO `timestamp` takes priority over local `updated_at`. Older delivery events cannot regress shipment status. Separate metadata/settlement clocks preserve reason, delivery fee, return consignment/type, collected amount and invoice without payment events overwriting delivery status. Same-second return stages can progress in order. Manual sync avoids replacing a newer webhook with an older or undated snapshot. Order Updated, Payment Invoice and Return ID Created are informational events that do not change delivery status. Collected amount/invoice are courier snapshots, not automatic payment receipts or stock returns. These values and webhook history appear on the order view.

The migrations were applied and merchant authentication/store loading were completed as setup actions. No tests, webhook simulations, store creation or parcel booking were run. Live webhook delivery remains pending public callback registration and a running worker/scheduler.
