# Product Report

Admin Reports > Product Report is an operational catalogue / product-line sales report. It defaults to the current month by original order date. Summary, top ten, category totals and full CSV export use the complete filtered dataset; the detail table is paginated. Print/PDF includes the summaries and current detail page, labelled with page number.

Filters: dates/all time, exact category, brand, current catalogue active/published status, name/SKU/variation search, order source, entry channel, current order status, retained-sales activity, grouping and sorting. In Product Summary, all variations' order lines roll into their parent product once. In Variation/SKU mode, current variation rows are separate, with base/no-variation rows for simple products and historical non-variation lines. Variant active status combines parent and variant status. Current prices in product summary are the parent's catalogue prices, not an average/range of variant prices.

The base query starts from the current catalogue and left joins aggregated order lines, so zero-sale products remain visible. Products without retained sales can include newly added, cancelled-only or fully returned products; the label does not assert they have never been ordered. Historical lines for a deleted variant remain part of the product summary, but no fabricated current SKU is created for a missing variant.

Calculations use saved order_details.sale_price and purchase_price snapshots, not current catalogue prices:

- Cancelled/Canceled/Cancel orders contribute zero to booked units, sales and retained cost.
- Fully Returned/Return order status treats every line quantity as returned, even if a courier update did not create a product return record.
- Other orders use returned_qty, clamped between zero and quantity.
- Booked units and gross line sales exclude cancelled orders, but include full-return quantities before subtraction.
- Retained units = booked units minus returned units.
- Return value = returned units × saved sale price.
- Retained line sales = retained units × saved sale price.
- Retained product cost = retained units × saved purchase price.
- Estimated gross margin = retained line sales minus retained product cost; margin % divides by retained line sales where positive.

These figures are before order-level discounts, tax, shipping, courier fees and refund/settlement adjustments. They are not accounting net profit or recognized revenue. Pending orders remain booked sales. Consequently product-line sales need not equal Sales Report order grand totals. Cost estimates depend on the completeness of historical purchase-price snapshots; zero/missing costs are not replaced with current costs. Per-row distinct order counts must not be summed as an overall unique order count.

The database has no stock-on-hand ledger, so this feature does not infer available inventory or add artificial stock counts. It makes no inventory changes. Product sales figures and returns follow current status/returned quantities for orders in the chosen original date range, not a return-date cohort. A later return changes that original order cohort's report.

Backend aggregation avoids joining variation attribute pairs, which would duplicate sales. Filters use bound query inputs, routes require admin authentication, report/export responses are private/no-store, and CSV cells are protected against formula injection. Exports stream all filtered detail rows as UTF-8 CSV; product edits are linked from the report. No migrations, fake data or new dependencies are needed.

No tests, browser checks, report execution or CSV exports were run during implementation, as requested.
