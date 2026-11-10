# Order due collection

Order List > action menu > Due Collection / Receipts opens the collection page. Delivered / Completed orders with outstanding amounts also have a Collect Due button in the Payment column. The order view links to collection history and printable receipts.

Admin records funds actually received, in full or partial amounts, with payment method, Bangladesh received time, transaction/deposit reference (mandatory except Cash) and optional note. A confirmation checkbox is required. Courier-reported collected_amount is not merchant cash/bank receipt and is never automatically converted to payment.

Eligible orders have current Delivered/Completed/Complete status, positive outstanding balance, no recorded product returns, and consistent saved balances / completed receipts. Returned products need refund/balance reconciliation before collection; this feature does not invent a refund workflow or outstanding adjustment. Cancelled, fully returned or undelivered orders cannot be collected here.

An order row lock and transaction protect payment insertion and paid/due updates. All values are compared in integer paisa. Zero/negative/overpayments and future receipt dates are rejected. The sum of Completed receipts must equal the existing paid balance. The new amount increments paid and decrements due; zero due makes existing order list / invoices / reports display paid without changing delivery status.

UUID collection tokens and content fingerprints make identical retries idempotent; reusing a token for different payment details is rejected. References are unique per order/payment method, with legacy completed references checked too. A shared bank/courier batch reference can be allocated to different orders, but duplicate allocation on the same order/method is blocked; admins must verify each order's actual allocated amount. Collections are not automatic bank reconciliation.

Completed receipts preserve collector, received time, method/reference, note and paid-before/paid-after/due-after snapshots. Receipt numbers are PAY- plus the immutable payment record ID, zero padded. Print/PDF shows receipt-time balances rather than implying they are the latest balances after further receipts. Existing Completed receipts are not editable/deletable through this feature.

Pending payment rows are outstanding intents, not received funds. Collection preserves their original amounts/status and marks them superseded. Partial collection creates a fresh pending remaining-balance snapshot. Full payment leaves no live pending balance. The original generic Pending records must not be summed as received money. Failed records are unchanged.

Uses order_customer_payments with added audit/idempotency fields and per-order reference indexes. The migration was applied. Admin auth, CSRF and POST throttling protect collection writes; receipt routes verify the payment belongs to the requested order and has due_collection type. Collection/receipt pages are private/no-store. Customer/recipient text and notes are escaped.

No tests, demo payments, real collections, courier bookings or notification sends were executed during implementation, per user instructions.
