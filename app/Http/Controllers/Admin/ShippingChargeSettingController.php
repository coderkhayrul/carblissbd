<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingChargeSetting;
use Exception;
use Illuminate\Http\Request;

class ShippingChargeSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $shippingChargeSettings = ShippingChargeSetting::get();
        return view('admin.shipping_charge.index', compact('shippingChargeSettings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'area_name' => ['required', 'string', 'max:255'],
            'delivery_charge' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'delivery_time' => ['required', 'string', 'max:255'],
        ]);

        try {
            ShippingChargeSetting::create($validated, [
                'area_name' => $request->area_name,
                'delivery_charge' => $request->delivery_charge,
                'delivery_time' => $request->delivery_time,
                'status' => $request->status == '1' ? true : false,
            ]);

            return redirect()->back()->with('success', 'Shipping Charge Create successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while updating: ' . $e->getMessage());
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $shippingChargeSetting = ShippingChargeSetting::findOrFail($id);
        $validated = $request->validate([
            'area_name' => ['required', 'string', 'max:255'],
            'delivery_charge' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'delivery_time' => ['required', 'string', 'max:255'],
            'status' => ['required', 'boolean'],
        ]);

        $shippingChargeSetting->update($validated);

        return redirect()->route('admin.shipping-charge-settings.index')
            ->with('success', 'Shipping Charge updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $shippingChargeSetting = ShippingChargeSetting::findOrFail($id);

        try {
            $shippingChargeSetting->delete();

            return redirect()->route('admin.shipping-charge-settings.index')
                ->with('success', 'Shipping Charge deleted successfully.');
        } catch (Exception $e) {
            report($e);

            return redirect()->route('admin.shipping-charge-settings.index')
                ->with('error', 'Unable to delete the shipping charge. Please try again.');
        }
    }
}
