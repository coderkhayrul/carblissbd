<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Services\CustomerService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class CustomerController extends Controller
{
    public function sendEmail(Request $request, Customer $customer, CustomerService $customerService)
    {
        $data = $request->validate([
            'subject' => ['required', 'string', 'max:255', 'not_regex:/[\r\n]/'],
            'message' => ['required', 'string', 'max:15000'], 'request_token' => ['required', 'uuid'],
        ]);
        $email = $customerService->sendEmail($customer, $data, $request->user()->id);
        return response()->json(['status' => $email->status, 'message' => match ($email->status) {
            'sent' => 'Email submitted successfully. Final inbox delivery depends on the recipient mail provider.',
            'processing' => 'This email is already being processed. Do not resend it.',
            default => $email->error ?: 'Unable to send email.',
        }], $email->status === 'failed' ? 502 : ($email->status === 'processing' ? 202 : 200));
    }
    public function sendSms(Request $request, Customer $customer, CustomerService $customerService)
    {
        $data = $request->validate(['message' => ['required', 'string', 'max:1000'], 'request_token' => ['required', 'uuid']]);
        $sms = $customerService->sendSms($customer, $data, $request->user()->id);
        return response()->json([
            'status' => $sms->status,
            'message' => match ($sms->status) {
                'submitted' => 'SMS submitted to your gateway successfully. Final delivery depends on the provider.',
                'processing' => 'This SMS is already being processed. Do not resend it.',
                default => $sms->error ?: 'Unable to send SMS.',
            },
        ], $sms->status === 'failed' ? 502 : ($sms->status === 'processing' ? 202 : 200));
    }
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
    public function show(Customer $customer, CustomerService $customerService)
    {
        $customer->load('user');
        $summary = $customerService->profileSummary($customer);
        $orders = $customer->orders()->with(['currentStatus', 'customerInfo'])->latest('id')->paginate(15);
        $smsMessages = \Illuminate\Support\Facades\DB::table('customer_sms_messages')->where('customer_id', $customer->id)->latest('id')->limit(20)->get();
        $emailMessages = \Illuminate\Support\Facades\DB::table('customer_email_messages')->where('customer_id', $customer->id)->latest('id')->limit(20)->get();
        return view('admin.customers.show', compact('customer', 'summary', 'orders', 'smsMessages', 'emailMessages'));
    }

    public function editSecurity(Customer $customer, CustomerService $customerService)
    {
        $customer->load('user');
        $summary = $customerService->profileSummary($customer);
        return view('admin.customers.security', compact('customer', 'summary'));
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
