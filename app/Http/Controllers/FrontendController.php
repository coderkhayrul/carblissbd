<?php

namespace App\Http\Controllers;

use App\Models\Slider;
use App\Models\PromoAds;

class FrontendController extends Controller
{
    public function home()
    {
        $slides = Slider::where('status', true)->orderBy('orderBy')->orderBy('id')->get();
        $promoAds = PromoAds::where('status', true)->orderBy('orderBy')->orderBy('id')->get();

        return view('frontend.pages.home', compact('slides', 'promoAds'));
    }

    public function category()
    {
        return view('frontend.pages.category');
    }
}
