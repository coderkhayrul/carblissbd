<?php

namespace App\Http\Controllers;

use App\Models\Slider;
use App\Models\PromoAds;
use App\Models\Category;
use App\Models\Product;
use App\Models\Brand;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    public function page(\App\Models\Page $page)
    {
        abort_unless($page->status, 404);

        return view('frontend.pages.page', compact('page'));
    }

    public function home()
    {
        $slides = Slider::where('status', true)->orderBy('orderBy')->orderBy('id')->get();
        $topBrands = Brand::where('status', true)->where('is_top_ten', true)
            ->orderBy('serial')->orderBy('id')->get();
        $promoAds = PromoAds::where('status', true)->orderBy('orderBy')->orderBy('id')->get();
        $popularCategories = Category::where('status', true)->where('is_top_ten', true)
            ->orderBy('menu_serial')->orderBy('id')->get();

        $newArrivals = Product::where('status', true)->where('publish', true)->where('is_new_arrival', true)
            ->with(['category', 'brand', 'variations' => fn($query) => $query->where('status', true)->orderBy('sale_price')])
            ->latest('id')->get();

        $recommendedProducts = $this->recommendedProductsQuery()->limit(16)->get();
        $hasMoreProducts = $recommendedProducts->count() > 15;
        $recommendedProducts = $recommendedProducts->take(15);

        return view('frontend.pages.home', compact('slides', 'promoAds', 'popularCategories', 'newArrivals', 'topBrands', 'recommendedProducts', 'hasMoreProducts'));
    }

    private function recommendedProductsQuery()
    {
        return Product::where('status', true)->where('publish', true)
            ->with(['category', 'brand', 'variations' => fn($query) => $query->where('status', true)->orderBy('sale_price')])
            ->orderByDesc('id');
    }

    public function search(\Illuminate\Http\Request $request)
    {
        $data = $request->validate(['q' => ['nullable', 'string', 'max:120']]);
        $term = trim($data['q'] ?? '');
        $query = $this->recommendedProductsQuery();
        if (mb_strlen($term) < 2) {
            $query->whereRaw('1 = 0');
        } else {
            $query->where(function ($query) use ($term) {
                $pattern = '%' . $term . '%';
                $query->where('name', 'like', $pattern)->orWhere('bangla_name', 'like', $pattern)
                    ->orWhere('sku', 'like', $pattern)
                    ->orWhereHas('category', fn ($category) => $category->where('name', 'like', $pattern))
                    ->orWhereHas('brand', fn ($brand) => $brand->where('name', 'like', $pattern));
            });
        }

        if ($request->expectsJson()) {
            $products = $query->limit(8)->get()->map(function ($product) {
                $price = $product->is_variation ? ($product->variations->first()?->sale_price ?? $product->sale_price) : $product->sale_price;
                return [
                    'name' => $product->name,
                    'price' => ($product->is_variation ? 'From ' : '') . '৳' . number_format((float) $price, 2),
                    'image' => asset($product->thumbnail_image ?: 'backend/assets/extra/preview-image.png'),
                    'url' => route('product.detail', ['product' => $product->slug]),
                ];
            });
            return response()->json(['products' => $products, 'results_url' => route('products.search', ['q' => $term])]);
        }

        $products = $query->paginate(20)->withQueryString();
        return view('frontend.pages.search', compact('products', 'term'));
    }

    public function moreProducts(Request $request)
    {
        $data = $request->validate(['before' => ['required', 'integer', 'min:1']]);
        $products = $this->recommendedProductsQuery()->where('id', '<', $data['before'])->limit(31)->get();
        $hasMore = $products->count() > 30;
        $products = $products->take(30);

        return response()->json([
            'html' => view('frontend.partials.recommended-product-cards', compact('products'))->render(),
            'next_cursor' => $products->last()?->id,
            'has_more' => $hasMore,
        ]);
    }

    public function category(Request $request)
    {
        $data = $request->validate(['category' => ['nullable', 'string', 'max:255']]);
        $categories = Category::where('status', true)->orderBy('menu_serial')->orderBy('name')->get();
        $selectedCategory = !empty($data['category'])
            ? $categories->firstWhere('slug', $data['category'])
            : null;
        if (!empty($data['category']) && !$selectedCategory) abort(404);

        $categoryIds = $categories->pluck('id')->all();
        if ($selectedCategory) {
            $categoryIds = [$selectedCategory->id];
            $frontier = [$selectedCategory->id];
            // Traverse active descendants without looping on invalid circular parent data.
            while ($frontier) {
                $frontier = $categories->whereIn('parent_id', $frontier)
                    ->whereNotIn('id', $categoryIds)->pluck('id')->all();
                $categoryIds = array_merge($categoryIds, $frontier);
            }
        }

        $products = $this->recommendedProductsQuery()->whereIn('category_id', $categoryIds)
            ->paginate(20)->withQueryString();

        return view('frontend.pages.category', compact('categories', 'selectedCategory', 'products'));
    }

    public function brand(Request $request)
    {
        $data = $request->validate(['brand' => ['nullable', 'string', 'max:255']]);
        $brands = Brand::where('status', true)->orderBy('serial')->orderBy('name')->get();
        $selectedBrand = !empty($data['brand']) ? $brands->firstWhere('slug', $data['brand']) : null;
        if (!empty($data['brand']) && !$selectedBrand) abort(404);

        $query = $this->recommendedProductsQuery();
        if ($selectedBrand) {
            $query->where('brand_id', $selectedBrand->id);
        } else {
            $query->whereIn('brand_id', $brands->pluck('id'));
        }
        $products = $query->paginate(20)->withQueryString();

        return view('frontend.pages.brand', compact('brands', 'selectedBrand', 'products'));
    }

    public function offers()
    {
        $products = $this->recommendedProductsQuery()->where('is_offer', true)
            ->paginate(20)->withQueryString();

        return view('frontend.pages.offers', compact('products'));
    }

    public function newArrivals()
    {
        $newArrivals = Product::where('status', true)->where('publish', true)->where('is_new_arrival', true)
            ->with(['category', 'brand', 'variations' => fn($query) => $query->where('status', true)->orderBy('sale_price')])
            ->latest('id')->get();

        return view('frontend.pages.new-arrivals', compact('newArrivals'));
    }

    public function productDetail(Request $request)
    {
        $slug = $request->validate(['product' => ['required', 'string', 'max:255']])['product'];
        $product = Product::where('slug', $slug)->where('status', true)->where('publish', true)
            ->with([
                'category',
                'brand',
                'tags',
                'variations' => fn($query) => $query->where('status', true)->orderBy('sale_price'),
                'variations.variationAttributes.attribute',
                'variations.variationAttributes.attributeType'
            ])
            ->firstOrFail();

        $relatedProducts = $this->recommendedProductsQuery()->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)->limit(5)->get();

        return view('frontend.pages.product-detail', compact('product', 'relatedProducts'));
    }
}
