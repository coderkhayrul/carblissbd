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
        $emailSetting = EmailSetting::first();
        return view('admin.settings.email_setting', compact('emailSetting'));
    }

    /*
    |--------------------------------------------------------------------------
    | EMAIL SETTING UPDATE FUNCTION
    |--------------------------------------------------------------------------
    */
    public function emailSettingsUpdate(Request $request)
    {
        try {
            $emailSetting = EmailSetting::first();
            $this->settingService->emailSettingUpdate($emailSetting, $request->all());

            return redirect()->back()->with('success', 'Email settings updated successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while updating: ' . $e->getMessage());
        }
    }

    /*
    |--------------------------------------------------------------------------
    | SMS API SETTINGS FUNCTION
    |--------------------------------------------------------------------------
    */
    public function smsApiSettings()
    {
        $smsApiSetting = SMSApiSetting::first();
        return view('admin.settings.sms_api_setting', compact('smsApiSetting'));
    }


    /*
    |--------------------------------------------------------------------------
    | SMS API UPDATE SETTINGS FUNCTION
    |--------------------------------------------------------------------------
    */
    public function smsApiSettingsUpdate(Request $request)
    {
        try {
            $smsApiSetting = SMSApiSetting::first();
            $this->settingService->smsApiSettingUpdate($smsApiSetting, $request->all());

            return redirect()->back()->with('success', 'Sms Api settings updated successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while updating: ' . $e->getMessage());
        }
    }
}
