# Product landing pages

Each active, published product has `/landing/{product-slug}`. Open its link from Admin > Products > Landing Page, or Product Edit > Landing Page > Open Landing Page. The standard product details page also links to it.

In Product Edit, complete **Landing Page Title**, **Landing Page Feature** (one benefit per line), **Landing Short Description**, **Landing Long Description**, and **Landing YouTube URL**. The existing product gallery supplies multiple images; upload each variation's image in the variations tab. Missing landing copy falls back to the ordinary product content. Missing variation images fall back to the main product image. Unpublished or inactive products return 404.

Visitors select a variation using image cards, choose quantity, and click Order. The existing checkout receives only that selected product/variation and quantity. It then collects delivery details, shows the dynamic shipping charge and creates the order using the existing customer, invoice, payment, status and notification logic. Server-side validation checks the chosen variation belongs to the active product; prices and totals come from the database. The customer must finish checkout to actually place the order.

Landing orders retain `order_source=Website`, with `entry_channel=Landing Page`, `landing_product_id` and `landing_page_url` recorded separately. Admin order lists show a Landing Page badge and support filtering by entry channel. Order View links to the originating landing page. Starting a regular cart checkout clears landing attribution; updating the same landing checkout retains it. Admin-created and duplicated orders use `entry_channel=Admin`.

Landing pages render their own title, description, canonical URL, social-sharing metadata, Product/Offer and Breadcrumb structured data. Active landing URLs appear in sitemap sections. Keep the existing live-site SEO/indexing configuration enabled for public indexing. Video iframes load only after clicking Play; the initial page uses no YouTube player requests. YouTube URLs can use watch, youtu.be, shorts or embed formats; invalid/non-YouTube URLs do not render an iframe.

Descriptions are displayed as escaped plain text with line breaks. The landing feature/description database fields were expanded to accommodate longer content. No test code, tests, sample orders or external notifications were run during implementation.

Video reference: [YouTube embedded players](https://developers.google.com/youtube/player_parameters).
