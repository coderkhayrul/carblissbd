# Customer Report

Reports > Customer Report defaults to this month by original order date. It starts from customer records and left joins a single aggregate per customer, so customers with no orders remain visible and product/return/payment joins do not multiply figures. Source, entry and current order-status filters constrain period orders, not the customer directory; choose Buyers in Date Range to hide customers without eligible matching sales.

Cancelled/Canceled/Cancel and fully Returned/Return statuses do not contribute to sales order count, sales, paid or due. Pending orders remain operational booked sales. Total orders, cancellation/return/completed counts and recorded return values are separate. Return records are pre-aggregated per order. Partial refunds are not invented or subtracted from unchanged order balances; current paid/due snapshots are not cash transactions in the date range.

Lifetime purchases use all sources/dates and current eligible statuses. Repeat buyers have eligible purchases in the current selection and at least two lifetime eligible orders; single-order buyers have exactly one lifetime eligible order. Repeat share = repeat buyers / selected-period buyers, not cohort retention or churn. First and last purchase are lifetime eligible order dates. Dormant/Never uses today's Bangladesh date minus 90 days and includes customers who have never purchased, including newly registered non-buyers. Labels state this explicitly.

Registered-in-date-range counts registrations among the filtered customer population using Bangladesh day boundaries converted to the database/app timezone. It is not the same as first-time buyers. Customer search matches account name/mobile/email; recipient order contact details may differ and are not used to merge accounts. Inactive accounts remain reportable.

Includes top ten customers by period booked sales, average sales order, lifetime booked sales (not predictive CLV), profile/order links, 25/50/100-row pagination, CSV export of all filtered records, and current-page Print/PDF. Summary totals cover the whole filtered result, not just the displayed page. No geographic/demographic or stock data is fabricated.

Admin-only routes return private/no-store data; filters are validated and bound to queries. UTF-8 CSV exports stream with spreadsheet formula-injection protection. No database migration, fake orders/customers or dependencies are required. No tests, browser checks, report execution or exports were run, as instructed.
