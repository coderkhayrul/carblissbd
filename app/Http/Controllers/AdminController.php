<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    //ADMIN DASHBOARD FUNCTION
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    //ADMIN SETTINGS FUNCTION
    public function companySettings()
    {
        return view('admin.settings.company_setting');
    }

    public function paymentSettings()
    {
        return view('admin.settings.payment_setting');
    }

    public function emailSettings()
    {
        return view('admin.settings.email_setting');
    }

    public function smsApiSettings()
    {
        return view('admin.settings.sms_api_setting');
    }
}
