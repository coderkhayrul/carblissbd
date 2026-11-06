<?php
namespace App\Services;

use App\Models\CompanySetting;
use Illuminate\Support\Str;

class SeoService
{
    public function indexable(?CompanySetting $company): bool
    {
        return ($company?->search_indexing_enabled ?? true)
            && !app()->environment(['local', 'testing'])
            && !preg_match('/(^localhost$|\.test$|\.localhost$|^127\.|^::1$)/i', request()->getHost());
    }

    private function text(?string $value, int $limit = 160): string
    {
        return Str::limit(trim(preg_replace('/\s+/u', ' ', html_entity_decode(strip_tags($value ?? ''), ENT_QUOTES | ENT_HTML5, 'UTF-8'))), $limit, '');
    }

    public function build(array $data, ?CompanySetting $company): array
    {
        $route = request()->route()?->getName();
        $name = $company?->name ?: config('app.name');
        $title = $company?->meta_title ?: ($company?->title ?: $name);
        $description = $this->text($company?->meta_description ?: $company?->footer_description);
        $keywords = $company?->meta_keyword;
        $image = asset($company?->og_image ?: ($company?->logo ?: 'backend/assets/extra/preview-image.png'));
        $canonical = url()->current();
        $landing = $route === 'product.landing';
        $product = in_array($route, ['product.detail', 'product.landing'], true) ? ($data['product'] ?? null) : null;
        $entity = match ($route) {
            'category' => $data['selectedCategory'] ?? null,
            'brand' => $data['selectedBrand'] ?? null,
            'pages.show' => $data['page'] ?? null,
            default => null,
        };
        if ($entity) {
            $title = $entity->meta_title ?: $entity->name . ' | ' . $name;
            $description = $this->text($entity->meta_description ?: ($entity->description ?: ($entity->content ?: $description)));
            $keywords = $entity->meta_tags ?: $keywords;
            $image = asset($entity->banner_image ?: ($entity->image ?: ($entity->logo ?: ($company?->og_image ?: ($company?->logo ?: 'backend/assets/extra/preview-image.png')))));
            $canonical = match ($route) {
                'category' => route('category', ['category' => $entity->slug]),
                'brand' => route('brand', ['brand' => $entity->slug]),
                default => route('pages.show', $entity->id),
            };
        } elseif ($route !== 'home' && !$product) {
            $label = ['category' => 'Categories', 'brand' => 'Brands', 'offers' => 'Offers', 'new-arrivals' => 'New Arrivals', 'products.search' => 'Product Search', 'cart' => 'Shopping Cart', 'checkout' => 'Checkout', 'profile' => 'My Account', 'order.success' => 'Order Received', 'login' => 'Login', 'register' => 'Register'][$route] ?? 'Account';
            $title = $label . ' | ' . $name;
        }
        $public = in_array($route, ['home', 'product.detail', 'product.landing', 'category', 'brand', 'pages.show', 'offers', 'new-arrivals'], true);
        if ($public && in_array($route, ['category', 'brand', 'offers'], true) && (int) request('page', 1) > 1) {
            $canonical .= (str_contains($canonical, '?') ? '&' : '?') . 'page=' . (int) request('page');
            $title .= ' - Page ' . (int) request('page');
        }
        $schemas = [];
        if ($public) {
            $organization = ['@context' => 'https://schema.org', '@type' => 'Organization', '@id' => route('home') . '#organization', 'name' => $name, 'url' => route('home')];
            if ($company?->logo) $organization['logo'] = asset($company->logo);
            $schemas[] = $organization;
            if ($route === 'home') $schemas[] = ['@context' => 'https://schema.org', '@type' => 'WebSite', 'name' => $name, 'url' => route('home')];
        }
        $price = null;
        if ($product) {
            $canonical = $landing ? route('product.landing', $product->slug) : route('product.detail', ['product' => $product->slug]);
            $title = $landing ? (($product->landing_page_title ?: ($product->meta_title ?: $product->name)) . ' | ' . $name) : ($product->meta_title ?: $product->name . ' | ' . $name);
            $description = $this->text(($landing ? $product->landing_short_description : null) ?: ($product->meta_description ?: ($product->short_description ?: ($product->description ?: ($description ?: $product->name)))));
            $keywords = $product->meta_keywords ?: $keywords;
            $images = collect([$product->thumbnail_image])->merge($product->gallery_images ?? [])->filter()->unique()->map(fn ($path) => asset($path))->values()->all();
            if (!$images && $product->is_variation) $images = $product->variations->pluck('image')->filter()->map(fn ($path) => asset($path))->values()->all();
            $image = $images[0] ?? $image;
            $price = (float) ($product->is_variation ? ($product->variations->first()?->sale_price ?? $product->sale_price) : $product->sale_price);
            $schema = ['@context' => 'https://schema.org', '@type' => 'Product', 'name' => $product->name, 'description' => $description, 'url' => $canonical, 'sku' => $product->sku];
            if ($images) $schema['image'] = $images;
            if ($product->brand) $schema['brand'] = ['@type' => 'Brand', 'name' => $product->brand->name];
            $schema['offers'] = $product->is_variation && $product->variations->isNotEmpty()
                ? ['@type' => 'AggregateOffer', 'url' => $canonical, 'priceCurrency' => 'BDT', 'lowPrice' => (string) $product->variations->min('sale_price'), 'highPrice' => (string) $product->variations->max('sale_price'), 'offerCount' => $product->variations->count()]
                : ['@type' => 'Offer', 'url' => $canonical, 'priceCurrency' => 'BDT', 'price' => number_format($price, 2, '.', ''), 'availability' => 'https://schema.org/' . ($product->is_variation ? 'OutOfStock' : 'InStock')];
            $schemas[] = $schema;
            $crumbs = [['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => route('home')]];
            if ($product->category?->status) $crumbs[] = ['@type' => 'ListItem', 'position' => 2, 'name' => $product->category->name, 'item' => route('category', ['category' => $product->category->slug])];
            $crumbs[] = ['@type' => 'ListItem', 'position' => count($crumbs) + 1, 'name' => $product->name, 'item' => $canonical];
            $schemas[] = ['@context' => 'https://schema.org', '@type' => 'BreadcrumbList', 'itemListElement' => $crumbs];
        }
        if (!$public) $canonical = route('home');
        return [
            'title' => $this->text($title, 255), 'description' => $description, 'keywords' => $keywords,
            'image' => $image, 'canonical' => $canonical, 'siteName' => $name, 'type' => $product ? 'product' : 'website',
            'public' => $public,
            'robots' => $public && $this->indexable($company) ? 'index, follow, max-image-preview:large' : 'noindex, follow',
            'schemas' => $schemas, 'price' => $price,
            'analyticsId' => $public && $company?->analytics_enabled && preg_match('/^G-[A-Z0-9]+$/', $company->gtag ?? '') ? $company->gtag : null,
        ];
    }
}
