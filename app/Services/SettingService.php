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
        $companySetting->fill([
            'name' => $request['name'],
            'title' => $request['title'],
            'phone' => $request['phone'],
            'hotline' => $request['hotline'] ?? null,
            'email' => $request['email'],
            'website' => $request['website'] ?? null,
            'bin' => $request['bin'] ?? null,
            'app_link' => $request['app_link'] ?? null,
            'address' => $request['address'],
            'bill_footer' => $request['bill_footer'] ?? null,
            'meta_title' => $request['meta_title'] ?? null,
            'meta_keyword' => $request['meta_keyword'] ?? null,
            'meta_description' => $request['meta_description'] ?? null,
            'google_site_verification' => $request['google_site_verification'] ?? null,
            'gtag' => $request['gtag'] ?? null,
            'analytics_enabled' => (bool) ($request['analytics_enabled'] ?? false),
            'search_indexing_enabled' => (bool) ($request['search_indexing_enabled'] ?? true),
            'page_loader_enabled' => (bool) ($request['page_loader_enabled'] ?? true),
            'footer_description' => $request['footer_description'] ?? null,
            'bangla_footer_description' => $request['bangla_footer_description'] ?? null,
        ]);

        $companySetting->saveOrFail();
        if (isset($request['loader_image'])) {
            $loaderImage = saveImage($request['loader_image'], '/uploads/settings/');
            $oldLoaderImage = $companySetting->loader_image;
            $companySetting->update(['loader_image' => $loaderImage]);
            if ($oldLoaderImage) deleteImage($oldLoaderImage);
        } elseif (!empty($request['remove_loader_image'])) {
            $oldLoaderImage = $companySetting->loader_image;
            $companySetting->update(['loader_image' => null]);
            if ($oldLoaderImage) deleteImage($oldLoaderImage);
        }
        if (isset($request['og_image'])) {
            $image = saveImage($request['og_image'], '/uploads/settings/');
            $oldImage = $companySetting->og_image;
            $companySetting->update(['og_image' => $image]);
            if ($oldImage) deleteImage($oldImage);
        }

        // Logo Update
        if (isset($request['logo'])) {
            $logo = saveImage($request['logo'], '/uploads/settings/');
            $oldLogo = $companySetting->logo;
            $companySetting->update(['logo' => $logo]);
            if ($oldLogo) deleteImage($oldLogo);
        }

        // Favicon Update
        if (isset($request['favicon'])) {
            $favicon = saveImage($request['favicon'], '/uploads/settings/');
            $oldFavicon = $companySetting->favicon;
            $companySetting->update(['favicon' => $favicon]);
            if ($oldFavicon) deleteImage($oldFavicon);
        }

        // Admin Favicon Update
        if (isset($request['admin_favicon'])) {
            $adminFavicon = saveImage($request['admin_favicon'], '/uploads/settings/');
            $oldAdminFavicon = $companySetting->admin_favicon;
            $companySetting->update(['admin_favicon' => $adminFavicon]);
            if ($oldAdminFavicon) deleteImage($oldAdminFavicon);
        }

        // Auth BG Update
        if (isset($request['auth_bg'])) {
            $authBg = saveImage($request['auth_bg'], '/uploads/settings/');
            $oldAuthBg = $companySetting->auth_bg;
            $companySetting->update(['auth_bg' => $authBg]);
            if ($oldAuthBg) deleteImage($oldAuthBg);
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

        $emailSetting->fill([
            'enabled' => $request['enabled'],
            'sender_name' => $request['sender_name'] ?? '',
            'sender_email' => $request['sender_email'] ?? '',
            'mail_mailer' => $request['mail_mailer'],
            'mail_host' => $request['mail_host'] ?? '',
            'mail_port' => $request['mail_port'] ?? '',
            'mail_username' => $request['mail_username'] ?? '',
            'mail_encryption' => $request['mail_encryption'],
        ]);
        if (!empty($request['mail_password'])) $emailSetting->mail_password = \Illuminate\Support\Facades\Crypt::encryptString($request['mail_password']);
        elseif (!$emailSetting->exists) $emailSetting->mail_password = '';
        $emailSetting->saveOrFail();
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
