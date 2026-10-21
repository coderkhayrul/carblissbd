<?php

namespace App\Http\Controllers;

use App\Models\Slider;
use App\Models\PromoAds;
use App\Models\Category;
use App\Models\Product;
use App\Models\Brand;

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
            ->with(['category', 'brand', 'variations' => fn ($query) => $query->where('status', true)->orderBy('sale_price')])
            ->latest('id')->get();

        return view('frontend.pages.home', compact('slides', 'promoAds', 'popularCategories', 'newArrivals', 'topBrands'));
    }

    public function category()
    {
        return view('frontend.pages.category');
    }

    public function newArrivals()
    {
        $newArrivals = Product::where('status', true)->where('publish', true)->where('is_new_arrival', true)
            ->with(['category', 'brand', 'variations' => fn ($query) => $query->where('status', true)->orderBy('sale_price')])
            ->latest('id')->get();

        return view('frontend.pages.new-arrivals', compact('newArrivals'));
    }

    public function productDetail(\Illuminate\Http\Request $request)
    {
        $slug = $request->validate(['product' => ['required', 'string', 'max:255']])['product'];
        $product = Product::where('slug', $slug)->where('status', true)->where('publish', true)
            ->with(['category', 'brand', 'variations' => fn ($query) => $query->where('status', true)->orderBy('sale_price'),
                'variations.variationAttributes.attribute', 'variations.variationAttributes.attributeType'])
            ->firstOrFail();

        return view('frontend.pages.product-detail', compact('product'));
    }
}
