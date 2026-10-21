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

    public function category()
    {
        return view('frontend.pages.category');
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
                'variations' => fn($query) => $query->where('status', true)->orderBy('sale_price'),
                'variations.variationAttributes.attribute',
                'variations.variationAttributes.attributeType'
            ])
            ->firstOrFail();

        return view('frontend.pages.product-detail', compact('product'));
    }
}
