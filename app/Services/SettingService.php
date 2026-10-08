<?php

namespace App\Services;

use App\Models\CompanySetting;

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
}
