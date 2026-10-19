<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Services\CustomerService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class CustomerController extends Controller
{
    public function updateStatus(Request $request, Customer $customer, CustomerService $customerService)
    {
        $validated = $request->validate([
            'status' => ['required', 'boolean'],
        ]);

        try {
            $customerService->updateStatus($customer, (bool) $validated['status']);
        } catch (\Throwable $exception) {
            report($exception);

            return back()->with('error', 'Unable to update the customer status. Please try again.');
        }

        return back()->with('success', $customer->status
            ? 'Customer activated successfully.'
            : 'Customer suspended successfully.');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customers = Customer::all();
        return view('admin.customers.index', compact('customers'));
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        $customer->with(['orders', 'user']);
        // return $customer;
        return view('admin.customers.show', compact('customer'));
    }

    public function editSecurity(Customer $customer)
    {
        $customer->with(['orders', 'user']);
        // return $customer;
        return view('admin.customers.security', compact('customer'));
    }

    public function changePassword(Request $request, Customer $customer, CustomerService $customerService)
    {
        $validated = $request->validate([
            'password' => ['required', 'string', 'max:72', 'confirmed', Password::min(8)],
            'password_confirmation' => ['required', 'string'],
        ]);

        try {
            $customerService->changePassword($customer, $validated['password']);
        } catch (\Throwable $exception) {
            report($exception);

            return redirect()->route('admin.customers.security.edit', $customer)
                ->with('error', 'Unable to change the customer password. Please try again.');
        }

        return redirect()->route('admin.customers.security.edit', $customer)
            ->with('success', 'Customer password changed successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
