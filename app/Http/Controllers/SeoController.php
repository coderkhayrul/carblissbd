<?php

namespace App\Http\Controllers;

use App\Models\{Product, Category, Brand, Page};
use App\Services\SeoService;

class SeoController extends Controller
{
    private const SIZE = 5000;
    private function query(string $type)
    {
        return match ($type) {
            'products', 'landings' => Product::where('status', true)->where('publish', true),
            'categories' => Category::where('status', true),
            'brands' => Brand::where('status', true),
            'pages' => Page::where('status', true),
            default => abort(404),
        };
    }
    private function xml(string $value): string
    {
        return htmlspecialchars($value, ENT_XML1 | ENT_QUOTES, 'UTF-8');
    }

    public function index()
    {
        $urls = [route('seo.sitemap.section', ['type' => 'static', 'page' => 1])];
        foreach (['products', 'landings', 'categories', 'brands', 'pages'] as $type) {
            $count = (int) ceil($this->query($type)->count() / self::SIZE);
            for ($page = 1; $page <= $count; $page++) $urls[] = route('seo.sitemap.section', compact('type', 'page'));
        }
        $xml = '<?xml version="1.0" encoding="UTF-8"?><sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        foreach ($urls as $url) $xml .= '<sitemap><loc>' . $this->xml($url) . '</loc></sitemap>';
        return response($xml . '</sitemapindex>', 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }
    public function section(string $type, int $page)
    {
        abort_if($page < 1 || $page > 100000, 404);
        $entries = [];
        if ($type === 'static') {
            abort_unless($page === 1, 404);
            foreach (['home', 'category', 'brand', 'offers', 'new-arrivals'] as $route) $entries[] = ['url' => route($route)];
        } else {
            $columns = $type === 'pages' ? ['id', 'updated_at'] : ['id', 'slug', 'updated_at'];
            $rows = $this->query($type)->orderBy('id')->forPage($page, self::SIZE)->get($columns);
            abort_if($rows->isEmpty(), 404);
            foreach ($rows as $row) {
                $url = match ($type) {
                    'products' => route('product.detail', ['product' => $row->slug]),
                    'landings' => route('product.landing', $row->slug),
                    'categories' => route('category', ['category' => $row->slug]),
                    'brands' => route('brand', ['brand' => $row->slug]),
                    'pages' => route('pages.show', $row->id),
                };
                $entries[] = ['url' => $url, 'modified' => $row->updated_at?->toAtomString()];
            }
        }
        return response()->stream(function () use ($entries) {
            echo '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
            foreach ($entries as $entry) {
                echo '<url><loc>' . $this->xml($entry['url']) . '</loc>';
                if (!empty($entry['modified'])) echo '<lastmod>' . $this->xml($entry['modified']) . '</lastmod>';
                echo '</url>';
            }
            echo '</urlset>';
        }, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }
    public function robots(SeoService $seo)
    {
        $content = "User-agent: *\n";
        if (!$seo->indexable(app('company.branding'))) $content .= "Disallow: /\n";
        else $content .= "Allow: /\nDisallow: /admin\nDisallow: /profile\nDisallow: /checkout\nDisallow: /order-success/\nDisallow: /products/more\nSitemap: " . route('seo.sitemap') . "\n";
        return response($content, 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }
}
