# Storefront SEO and social sharing

## Admin configuration

Company Setting > SEO Configuration supports the site's default title, description and keywords; default share image; Search Console verification token; GA4 Measurement ID; Analytics enable switch; and search indexing switch. Upload a public JPG/PNG share image, ideally 1200 × 630. Product metadata uses the product's existing SEO fields, product thumbnail/gallery (variation image fallback), actual prices and brand. Category, brand and content pages use their own existing SEO fields.

All public pages render canonical URLs, descriptions, Open Graph and Twitter large-image cards in server HTML. Product pages also render Product/Offer or AggregateOffer JSON-LD and breadcrumbs. No reviews, ratings, GTINs or refund policies are invented. Multiple variation prices use the active variation price range. Product identity stays in the canonical query parameter (`/product-detail?product=slug`); tracking parameters are removed. Paginated listings retain their page numbers.

## Live deployment and Google

1. Deploy to a publicly reachable HTTPS domain. Set `APP_URL=https://your-domain` and `APP_ENV=production` in the server environment. Rebuild cached configuration as part of your normal deployment if config caching is enabled. Keep debug disabled on the live server. Images must also be publicly reachable over HTTPS; localhost/.test links cannot be fetched by social-media crawlers.
2. Enable search indexing in Company Setting. Local/testing environments and local domains remain noindex regardless of that switch. Private storefront pages and search results are always noindex. robots.txt is served by Laravel; do not put a static robots.txt in public/ or configure the web server to override that route.
3. Add your HTTPS website as a URL-prefix property in Search Console. Choose HTML tag verification, copy only the `content` token into Company Setting, save and click Verify in Google. A Domain property instead requires DNS verification at your DNS provider.
4. Submit `https://your-domain/sitemap.xml` in Search Console. It is a sitemap index containing active, published product URLs and active category, brand and content pages, split into sections of at most 5,000 URLs. Private orders, checkout, cart, accounts and searches are excluded.
5. In Google Analytics create a GA4 web data stream. Save its `G-...` Measurement ID and enable Analytics in Company Setting. The implementation adds the Google tag once on public storefront pages and sends page views with clean canonical URLs. Checkout/account/order-success pages do not load Analytics, and customer names, phone numbers and email addresses are not sent. Ecommerce purchase/conversion events and a consent-management interface are not implemented by this setup.

## Share previews

Social platforms control their final preview layouts and cache previews independently. After changing a product's image/title, use the platform's sharing debugger/inspector to request a refresh if an old preview remains. Crawlers must be able to access the product URL and image without authentication. Product sharing uses the product's canonical image; selecting a variation in the browser does not create a separate variation URL.

No Google accounts have been connected, no sitemap submitted, and no tests or external verification performed. Configuration is ready for the site owner's IDs and live domain.

References: [Open Graph protocol](https://ogp.me/), [Google Product structured data](https://developers.google.com/search/docs/appearance/structured-data/product-snippet), [Google sitemaps](https://developers.google.com/search/docs/crawling-indexing/sitemaps/build-sitemap), [Search Console verification](https://support.google.com/webmasters/answer/9008080), [GA4 Google tag](https://developers.google.com/analytics/devguides/collection/ga4/tag-options).
