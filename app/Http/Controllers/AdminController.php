<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompanySettingRequest;
use App\Models\CompanySetting;
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
    public function dashboard()
    {
        return view('admin.dashboard');
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
    | PAYMENT SETTING
    |--------------------------------------------------------------------------
    */
    public function paymentSettings()
    {
        return view('admin.settings.payment_setting');
    }

    /*
    |--------------------------------------------------------------------------
    | EMAIL SETTING
    |--------------------------------------------------------------------------
    */
    public function emailSettings()
    {
        return view('admin.settings.email_setting');
    }

    /*
    |--------------------------------------------------------------------------
    | SMS API SETTINGS
    |--------------------------------------------------------------------------
    */
    public function smsApiSettings()
    {
        return view('admin.settings.sms_api_setting');
    }
}
