<?php

namespace App\Http\Controllers;

use App\Models\Slider;
use App\Models\PromoAds;
use App\Models\Category;

class FrontendController extends Controller
{
    public function home()
    {
        $slides = Slider::where('status', true)->orderBy('orderBy')->orderBy('id')->get();
        $promoAds = PromoAds::where('status', true)->orderBy('orderBy')->orderBy('id')->get();
        $popularCategories = Category::where('status', true)->where('is_top_ten', true)
            ->orderBy('menu_serial')->orderBy('id')->get();

        return view('frontend.pages.home', compact('slides', 'promoAds', 'popularCategories'));
    }

    public function category()
    {
        return view('frontend.pages.category');
    }
}
