<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PopupAds;
use App\Services\PopupAdsService;
use Illuminate\Http\Request;

class PopupAdsController extends Controller
{
    private function popup(): PopupAds
    {
        return PopupAds::orderBy('id')->first() ?? new PopupAds(['status' => false, 'is_video' => false]);
    }

    public function edit()
    {
        return view('admin.popup-ads.edit', ['popup' => $this->popup()]);
    }

    public function update(Request $request, PopupAdsService $service)
    {
        $data = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'url' => ['nullable', 'required_if:is_video,1', 'url:http,https', 'max:255'],
            'description' => ['nullable', 'string', 'max:10000'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:5120'],
            'is_video' => ['required', 'boolean'],
            'status' => ['required', 'boolean'],
        ]);

        try {
            $service->save($this->popup(), $data);
        } catch (\Throwable $exception) {
            report($exception);
            return back()->withInput()->with('error', 'Unable to update the popup ad. Please try again.');
        }

        return redirect()->route('admin.popup-ads.edit')->with('success', 'Popup ad updated successfully.');
    }

    public function updateStatus(Request $request, PopupAdsService $service)
    {
        $data = $request->validate(['status' => ['required', 'boolean']]);

        try {
            $service->updateStatus($this->popup(), (bool) $data['status']);
        } catch (\Throwable $exception) {
            report($exception);
            return back()->with('error', 'Unable to update the popup ad status.');
        }

        return redirect()->route('admin.popup-ads.edit')->with('success', 'Popup ad status updated successfully.');
    }
}
