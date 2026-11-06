<title>{{ $seo['title'] }}</title>
<meta name="description" content="{{ $seo['description'] }}">
@if ($seo['keywords'])<meta name="keywords" content="{{ $seo['keywords'] }}">@endif
<meta name="robots" content="{{ $seo['robots'] }}">
@if ($seo['public'])<link rel="canonical" href="{{ $seo['canonical'] }}">@endif
<meta property="og:type" content="{{ $seo['type'] }}">
<meta property="og:site_name" content="{{ $seo['siteName'] }}">
<meta property="og:title" content="{{ $seo['title'] }}">
<meta property="og:description" content="{{ $seo['description'] }}">
<meta property="og:url" content="{{ $seo['canonical'] }}">
<meta property="og:image" content="{{ $seo['image'] }}">
<meta property="og:image:alt" content="{{ $seo['title'] }}">
@if (str_starts_with($seo['image'], 'https://'))<meta property="og:image:secure_url" content="{{ $seo['image'] }}">@endif
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $seo['title'] }}">
<meta name="twitter:description" content="{{ $seo['description'] }}">
<meta name="twitter:image" content="{{ $seo['image'] }}">
<meta name="twitter:image:alt" content="{{ $seo['title'] }}">
@if ($seo['price'] !== null)<meta property="product:price:amount" content="{{ number_format($seo['price'], 2, '.', '') }}"><meta property="product:price:currency" content="BDT">@endif
@if ($footerCompany?->google_site_verification)<meta name="google-site-verification" content="{{ $footerCompany->google_site_verification }}">@endif
@foreach ($seo['schemas'] as $schema)
<script type="application/ld+json">{!! json_encode($schema, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
@endforeach
@if ($seo['analyticsId'])
<script async src="https://www.googletagmanager.com/gtag/js?id={{ $seo['analyticsId'] }}"></script>
<script>
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('js', new Date());
gtag('config', @json($seo['analyticsId']), {page_location: @json($seo['canonical']), page_title: @json($seo['title']), page_referrer: document.referrer ? new URL(document.referrer).origin : '', allow_google_signals: false, allow_ad_personalization_signals: false});
</script>
@endif
