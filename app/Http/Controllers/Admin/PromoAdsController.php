<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PromoAds;
use App\Services\PromoAdsService;
use Illuminate\Http\Request;

class PromoAdsController extends Controller
{
    public function index()
    {
        $promoAds = PromoAds::orderBy('orderBy')->orderBy('id')->get();
        return view('admin.promo-ads.index', compact('promoAds'));
    }

    public function create()
    {
        return view('admin.promo-ads.form', ['promoAd' => new PromoAds(['status' => true, 'orderBy' => 0])]);
    }

    public function edit(PromoAds $promoAd)
    {
        return view('admin.promo-ads.form', compact('promoAd'));
    }

    private function validatedData(Request $request, ?PromoAds $promoAd = null): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'sub_title' => ['required', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:5120'],
            'button_text' => ['nullable', 'required_with:button_url', 'string', 'max:255'],
            'button_url' => ['nullable', 'required_with:button_text', 'url:http,https', 'max:255'],
            'overlay_color' => ['nullable', 'string', 'regex:/^#(?:[0-9a-fA-F]{3}|[0-9a-fA-F]{6}|[0-9a-fA-F]{8})$/'],
            'orderBy' => ['required', 'integer', 'min:0', 'max:2147483647'],
            'status' => ['required', 'boolean'],
        ]);
    }

    public function store(Request $request, PromoAdsService $service)
    {
        $data = $this->validatedData($request);
        try {
            $service->save(new PromoAds, $data);
        } catch (\Throwable $exception) {
            report($exception);
            return back()->withInput()->with('error', 'Unable to create the promo ad. Please try again.');
        }
        return redirect()->route('admin.promo-ads.index')->with('success', 'Promo ad created successfully.');
    }

    public function update(Request $request, PromoAds $promoAd, PromoAdsService $service)
    {
        $data = $this->validatedData($request, $promoAd);
        try {
            $service->save($promoAd, $data);
        } catch (\Throwable $exception) {
            report($exception);
            return back()->withInput()->with('error', 'Unable to update the promo ad. Please try again.');
        }
        return redirect()->route('admin.promo-ads.index')->with('success', 'Promo ad updated successfully.');
    }

    public function updateStatus(Request $request, PromoAds $promoAd, PromoAdsService $service)
    {
        $data = $request->validate(['status' => ['required', 'boolean']]);
        try {
            $service->updateStatus($promoAd, (bool) $data['status']);
        } catch (\Throwable $exception) {
            report($exception);
            return back()->with('error', 'Unable to update the promo ad status.');
        }
        return back()->with('success', 'Promo ad status updated successfully.');
    }

    public function destroy(PromoAds $promoAd, PromoAdsService $service)
    {
        try {
            $service->delete($promoAd);
        } catch (\Illuminate\Validation\ValidationException $exception) {
            return back()->with('error', $exception->validator->errors()->first());
        } catch (\Throwable $exception) {
            report($exception);
            return back()->with('error', 'Unable to delete the promo ad. Please try again.');
        }
        return redirect()->route('admin.promo-ads.index')->with('success', 'Promo ad deleted successfully.');
    }
}
