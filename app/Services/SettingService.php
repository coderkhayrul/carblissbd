<?php

namespace App\Services;

use App\Models\CompanySetting;
use App\Models\EmailSetting;
use App\Models\PaymentSetting;
use App\Models\SMSApiSetting;
use Illuminate\Support\Facades\Log;

class SettingService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /*
    |--------------------------------------------------------------------------
    | COMPANY SETTING UPDATE
    |--------------------------------------------------------------------------
    */
    public function updateCompanySetting(CompanySetting $companySetting, array $request)
    {
        $companySetting->update([
            'name' => $request['name'],
            'title' => $request['title'],
            'phone' => $request['phone'],
            'hotline' => $request['hotline'],
            'email' => $request['email'],
            'website' => $request['website'],
            'bin' => $request['bin'],
            'app_link' => $request['app_link'],
            'address' => $request['address'],
            'bill_footer' => $request['bill_footer'],
            'meta_title' => $request['meta_title'],
            'meta_keyword' => $request['meta_keyword'],
            'meta_description' => $request['meta_description'],
            'footer_description' => $request['footer_description'],
            'bangla_footer_description' => $request['bangla_footer_description'],
        ]);

        // Logo Update
        if (isset($request['logo'])) {
            $logo = saveImage($request['logo'], '/uploads/settings/');
            if ($companySetting->logo) {
                deleteImage($companySetting->logo);
            }
            $companySetting->update(['logo' => $logo]);
        }

        // Favicon Update
        if (isset($request['favicon'])) {
            $favicon = saveImage($request['favicon'], '/uploads/settings/');
            if ($companySetting->favicon) {
                deleteImage($companySetting->favicon);
            }
            $companySetting->update(['favicon' => $favicon]);
        }

        // Admin Favicon Update
        if (isset($request['admin_favicon'])) {
            $adminFavicon = saveImage($request['admin_favicon'], '/uploads/settings/');
            if ($companySetting->admin_favicon) {
                deleteImage($companySetting->admin_favicon);
            }
            $companySetting->update(['admin_favicon' => $adminFavicon]);
        }

        // Auth BG Update
        if (isset($request['auth_bg'])) {
            $authBg = saveImage($request['auth_bg'], '/uploads/settings/');
            if ($companySetting->auth_bg) {
                deleteImage($companySetting->auth_bg);
            }
            $companySetting->update(['auth_bg' => $authBg]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | PAYMENT SETTING UPDATE
    |--------------------------------------------------------------------------
    */

    public function updatePaymentSetting(PaymentSetting $paymentSetting,  array $request)
    {

        if ($request['type'] == 'bkash') {
            $paymentSetting->update([
                'bkash_active' => $request['bkash_active'] == '1' ? true : false,
                'bkash_url' => $request['bkash_url'],
                'bkash_app_key' => $request['bkash_app_key'],
                'bkash_app_secret' => $request['bkash_app_secret'],
                'bkash_username' => $request['bkash_username'],
                'bkash_password' => $request['bkash_password'],
            ]);
            $message = 'Bkash Payment settings updated successfully.';
        } else {
            $paymentSetting->update([
                // SSL
                'ssl_active' => $request['ssl_active'] == '1' ? true : false,
                'ssl_store_id' => $request['ssl_store_id'],
                'ssl_store_password' => $request['ssl_store_password'],
                'ssl_store_url' => $request['ssl_store_url'],
                'ssl_store_callback_url' => $request['ssl_store_callback_url'],
                'ssl_store_failure_url' => $request['ssl_store_failure_url'],
                'ssl_is_localhost' => $request['ssl_is_localhost'] == '1' ? true : false,
            ]);
            $message = 'SSL Payment settings updated successfully.';
        }

        return $message;
    }


    /*
    |--------------------------------------------------------------------------
    | EMAIL SETTING UPDATE
    |--------------------------------------------------------------------------
    */

    public function emailSettingUpdate(EmailSetting $emailSetting,  array $request)
    {

        $emailSetting->update([
            'sender_name' => $request['sender_name'],
            'sender_email' => $request['sender_email'],
            'mail_mailer' => $request['mail_mailer'],
            'mail_host' => $request['mail_host'],
            'mail_port' => $request['mail_port'],
            'mail_username' => $request['mail_username'],
            'mail_password' => $request['mail_password'],
            'mail_encryption' => $request['mail_encryption'],
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | SMS API SETTING UPDATE
    |--------------------------------------------------------------------------
    */

    public function smsApiSettingUpdate(SMSApiSetting $SMSApiSetting,  array $request)
    {
        $SMSApiSetting->update([
            'base_url' => $request['base_url'],
            'sender_id' => $request['sender_id'],
            'api_key' => $request['api_key'],
            'api_secret' => $request['api_secret'],
        ]);
    }
}
