<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Services\CouponService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::orderBy('id')->get();
        return view('admin.coupons.index', compact('coupons'));
    }

    public function create()
    {
        return view('admin.coupons.form', ['coupon' => new Coupon([
            'status' => true, 'use_type' => 'Once', 'discount_type' => 'Percentage', 'is_highlighted' => false,
        ])]);
    }

    public function edit(Coupon $coupon)
    {
        return view('admin.coupons.form', compact('coupon'));
    }

    private function validatedData(Request $request, ?Coupon $coupon = null): array
    {
        if (is_string($request->input('code'))) {
            $request->merge(['code' => strtoupper(trim($request->input('code')))]);
        }
        return $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('coupons', 'name')->ignore($coupon?->id)],
            'code' => ['required', 'string', 'max:255', 'regex:/^[A-Z0-9_-]+$/', Rule::unique('coupons', 'code')->ignore($coupon?->id)],
            'amount' => ['required', 'numeric', 'decimal:0,2', 'gt:0', $request->input('discount_type') === 'Percentage' ? 'max:100' : 'max:99999999.99'],
            'use_type' => ['required', Rule::in(['Once', 'Multiple'])],
            'discount_type' => ['required', Rule::in(['Percentage', 'Fixed'])],
            'start_date' => ['nullable', 'date_format:Y-m-d'],
            'end_date' => array_merge(['nullable', 'date_format:Y-m-d'], $request->filled('start_date') ? ['after_or_equal:start_date'] : []),
            'is_highlighted' => ['required', 'boolean'],
            'status' => ['required', 'boolean'],
        ]);
    }

    public function store(Request $request, CouponService $service)
    {
        $data = $this->validatedData($request);
        try {
            $service->save(new Coupon, $data);
        } catch (\Throwable $exception) {
            report($exception);
            return back()->withInput()->with('error', 'Unable to create the coupon. Please try again.');
        }
        return redirect()->route('admin.coupons.index')->with('success', 'Coupon created successfully.');
    }

    public function update(Request $request, Coupon $coupon, CouponService $service)
    {
        $data = $this->validatedData($request, $coupon);
        try {
            $service->save($coupon, $data);
        } catch (\Throwable $exception) {
            report($exception);
            return back()->withInput()->with('error', 'Unable to update the coupon. Please try again.');
        }
        return redirect()->route('admin.coupons.index')->with('success', 'Coupon updated successfully.');
    }

    public function updateStatus(Request $request, Coupon $coupon, CouponService $service)
    {
        $data = $request->validate(['status' => ['required', 'boolean']]);
        try {
            $service->updateStatus($coupon, (bool) $data['status']);
        } catch (\Throwable $exception) {
            report($exception);
            return back()->with('error', 'Unable to activate or deactivate the coupon.');
        }
        return back()->with('success', 'Coupon updated successfully.');
    }

    public function destroy(Coupon $coupon, CouponService $service)
    {
        try {
            $service->delete($coupon);
        } catch (\Illuminate\Validation\ValidationException $exception) {
            return back()->with('error', $exception->validator->errors()->first());
        } catch (\Throwable $exception) {
            report($exception);
            return back()->with('error', 'Unable to delete the coupon. Please try again.');
        }
        return redirect()->route('admin.coupons.index')->with('success', 'Coupon deleted successfully.');
    }
}
