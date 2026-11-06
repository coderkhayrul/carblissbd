<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompanySettingRequest;
use App\Models\CompanySetting;
use App\Models\EmailSetting;
use App\Models\PaymentSetting;
use App\Models\SMSApiSetting;
use App\Services\SettingService;
use Exception;
use Illuminate\Http\Request;

class AdminController extends Controller
{

    public SettingService $settingService;


    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }


    /*
    |--------------------------------------------------------------------------
    | ADMIN DASHBOARD FUNCTION
    |--------------------------------------------------------------------------
    */
    public function dashboard(Request $request, \App\Services\DashboardService $dashboardService)
    {
        $filters = $request->validate([
            'period' => ['nullable', \Illuminate\Validation\Rule::in(array_keys(\App\Services\DashboardService::PERIODS))],
            'from' => ['nullable', 'required_with:to', 'date_format:Y-m-d', 'after_or_equal:1900-01-01', 'before_or_equal:2100-12-31'],
            'to' => ['nullable', 'required_with:from', 'date_format:Y-m-d', 'after_or_equal:from', 'before_or_equal:2100-12-31'],
            'source' => ['nullable', \Illuminate\Validation\Rule::in(['Website', 'GSale', 'App'])],
            'staff' => ['nullable', 'integer', \Illuminate\Validation\Rule::exists('users', 'id')->where('role', 'admin')],
        ]);
        $report = $dashboardService->report($filters);
        $periods = \App\Services\DashboardService::PERIODS;
        $staff = \App\Models\User::where('role', 'admin')->orderBy('name')->get(['id', 'name']);
        return view('admin.dashboard', compact('report', 'periods', 'staff', 'filters'));
    }

    /*
    |--------------------------------------------------------------------------
    | ADMIN SETTINGS FUNCTION
    |--------------------------------------------------------------------------
    */
    public function companySettings()
    {
        $companySetting = CompanySetting::first();
        return view('admin.settings.company_setting', compact('companySetting'));
    }

    /*
    |--------------------------------------------------------------------------
    | ADMIN SETTINGS UPDATE FUNCTION
    |--------------------------------------------------------------------------
    */
    public function companySettingsUpdate(CompanySettingRequest $request)
    {
        try {
            $companySetting = CompanySetting::first();
            $this->settingService->updateCompanySetting($companySetting, $request->all());

            return redirect()->back()->with('success', 'Company settings updated successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while updating company settings: ' . $e->getMessage());
        }
    }

    /*
    |--------------------------------------------------------------------------
    | PAYMENT SETTING FUNCTION
    |--------------------------------------------------------------------------
    */
    public function paymentSettings()
    {
        $paymentSetting = PaymentSetting::first();
        return view('admin.settings.payment_setting', compact('paymentSetting'));
    }

    /*
    |--------------------------------------------------------------------------
    | PAYMENT SETTING UPDATE FUNCTION
    |--------------------------------------------------------------------------
    */
    public function paymentSettingsUpdate(Request $request)
    {
        try {
            $paymentSetting = PaymentSetting::first();
            $update = $this->settingService->updatePaymentSetting($paymentSetting, $request->all());

            return redirect()->back()->with('success', $update);
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while updating: ' . $e->getMessage());
        }
    }




    /*
    |--------------------------------------------------------------------------
    | EMAIL SETTING FUNCTION
    |--------------------------------------------------------------------------
    */
    public function emailSettings()
    {
        $emailSetting = EmailSetting::orderBy('id')->first() ?? new EmailSetting;
        $emailNotifications = \Illuminate\Support\Facades\DB::table('order_email_notifications')->join('orders', 'orders.id', '=', 'order_email_notifications.order_id')
            ->select('order_email_notifications.*', 'orders.order_no')->latest('order_email_notifications.id')->limit(20)->get();
        return view('admin.settings.email_setting', compact('emailSetting', 'emailNotifications'));
    }

    /*
    |--------------------------------------------------------------------------
    | EMAIL SETTING UPDATE FUNCTION
    |--------------------------------------------------------------------------
    */
    public function emailSettingsUpdate(Request $request)
    {
        $request->merge(['enabled' => $request->boolean('enabled'), 'mail_encryption' => strtolower((string) $request->input('mail_encryption', 'tls'))]);
        $emailSetting = EmailSetting::orderBy('id')->first() ?? new EmailSetting;
        $required = $request->boolean('enabled') ? 'required' : 'nullable';
        $data = $request->validate([
            'enabled' => ['required', 'boolean'], 'sender_name' => [$required, 'string', 'max:255'],
            'sender_email' => [$required, 'email', 'max:255'], 'mail_mailer' => ['required', \Illuminate\Validation\Rule::in(['smtp'])],
            'mail_host' => [$required, 'string', 'max:255', 'regex:/^[a-zA-Z0-9.\-:]+$/'],
            'mail_port' => [$required, 'integer', 'min:1', 'max:65535'],
            'mail_username' => ['nullable', 'string', 'max:255'], 'mail_password' => ['nullable', 'string', 'max:2000'],
            'mail_encryption' => ['required', \Illuminate\Validation\Rule::in(['tls', 'ssl', 'none'])],
        ]);
        try {
            $this->settingService->emailSettingUpdate($emailSetting, $data);

            return redirect()->back()->with('success', 'Email settings updated successfully.');
        } catch (Exception $e) {
            report($e);
            return redirect()->back()->with('error', 'Unable to save email settings. Please try again.');
        }
    }

    /*
    |--------------------------------------------------------------------------
    | SMS API SETTINGS FUNCTION
    |--------------------------------------------------------------------------
    */
    public function smsApiSettings()
    {
        $smsApiSetting = SMSApiSetting::orderBy('id')->first() ?? new SMSApiSetting;
        $smsNotifications = \App\Models\OrderSmsNotification::with('order:id,order_no')->latest('id')->limit(20)->get();
        return view('admin.settings.sms_api_setting', compact('smsApiSetting', 'smsNotifications'));
    }


    /*
    |--------------------------------------------------------------------------
    | SMS API UPDATE SETTINGS FUNCTION
    |--------------------------------------------------------------------------
    */
    public function smsApiSettingsUpdate(Request $request)
    {
        $smsApiSetting = SMSApiSetting::orderBy('id')->first() ?? new SMSApiSetting;
        $jsonObject = function ($attribute, $value, $fail) {
            $decoded = json_decode($value);
            if (!is_object($decoded)) $fail('The ' . str_replace('_', ' ', $attribute) . ' must be a JSON object.');
            if ($attribute === 'request_headers' && is_object($decoded)) {
                foreach ($decoded as $header) {
                    if (!is_string($header) || str_contains($header, "\r") || str_contains($header, "\n")) {
                        $fail('Header values must be single-line strings.');
                        break;
                    }
                }
            }
        };
        $data = $request->validate([
            'enabled' => ['required', 'boolean'],
            'base_url' => ['nullable', 'required_if:enabled,1', 'url:http,https', 'max:255'],
            'sender_id' => ['nullable', 'required_if:enabled,1', 'string', 'max:255'],
            'api_key' => ['nullable', 'string', 'max:255'],
            'api_secret' => ['nullable', 'string', 'max:255'],
            'request_method' => ['required', 'in:GET,POST'],
            'request_format' => ['required', 'in:form,json'],
            'phone_format' => ['required', 'in:international,digits,local'],
            'request_parameters' => ['nullable', 'json', 'max:10000', $jsonObject],
            'request_headers' => ['nullable', 'json', 'max:10000', $jsonObject],
            'success_path' => ['nullable', 'string', 'max:255'],
            'success_value' => ['nullable', 'required_with:success_path', 'string', 'max:255'],
            'message_template' => ['nullable', 'string', 'max:1000'],
        ]);
        foreach (['api_key', 'api_secret'] as $credential) {
            if (empty($data[$credential])) unset($data[$credential]);
        }
        if ($request->boolean('enabled') && empty($data['api_key']) && !$smsApiSetting->api_key) {
            throw \Illuminate\Validation\ValidationException::withMessages(['api_key' => 'An API key is required to enable SMS.']);
        }
        try {
            $smsApiSetting->fill($data)->saveOrFail();

            return redirect()->back()->with('success', 'Sms Api settings updated successfully.');
        } catch (\Throwable) {
            return redirect()->back()->with('error', 'Unable to save SMS settings. Please try again.');
        }
    }
}
