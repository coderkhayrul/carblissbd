# Bulk and mini invoice printing

On the admin order list, select orders using the new first-column checkboxes. The header checkbox selects the current DataTable page; selections persist when changing table pages, sorting or searching locally. A server-side filter reload starts a new selection. Select up to 200 orders, choose Mini Invoice / A4 Labels or Full Invoice / A4, then click Print Selected. The preview opens in a new tab and Print / Save PDF opens the browser print dialog.

Mini invoices support two or three columns. With short order content, approximately eight or twelve invoices fit on A4 respectively. Longer names, addresses, product lists or notes make rows taller and reduce invoices per page. No product lines are deliberately hidden or clipped. Table rows and label cards avoid page breaks inside them where the browser can fit the row on one page. Select A4 portrait, 100% scale and disable browser headers / footers. Actual pagination depends on browser/printer fonts and margins; extremely long individual invoices can still span pages. Use the larger two-column format or full invoice for unusually long orders.

Each mini label includes company branding/contact from Company Setting, assigned courier, consignment (when available), recipient, phone, address, invoice number, outstanding COD and every ordered product/variation/quantity. It does not fabricate courier booking IDs or use the demo's customer data. Company logo is used rather than an unprovided courier logo. The barcode encodes the courier consignment ID or, for unbooked orders, the existing order number. Unsupported non-ASCII or over-40-character identifiers display as text without a substitute barcode. Barcode scanning was not tested.

`App\Support\InvoiceBarcode` generates local inline SVG Code 128 Set B bars with checksum/quiet zones. Standard symbol-pattern reference: https://github.com/lindell/JsBarcode/blob/master/src/barcodes/CODE128/constants.js . No CDN, external barcode request or composer dependency is used.

Full invoices retain existing copy variants (customer/accounts/store/delivery/challan). Each invoice begins a separate print page, with additional pages available for long product tables. They share the same invoice content partial as individual printing. Accounts copies retain purchase costs; mini invoices are compact customer/shipping labels regardless of the selected full-copy variant.

The invoice action dropdown also provides Mini Invoice (A4) for a single order. All print endpoints require admin authentication. Batch printing uses CSRF-protected POST, validates distinct existing order IDs and preserves selection order. Responses are private/no-store. No booking, order status, payment or stock is modified by printing. Existing customer details remain on the private print page rather than being sent to a barcode service.

No test code, browser/print tests, example orders or physical printouts were run during implementation, as requested.
