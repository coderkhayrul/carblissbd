<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\ShippingChargeSettingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\FrontendController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;




// আনপ্রোটেক্টেড রাউটস (লগইন)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
});

Route::get('/', function () {
    return redirect()->route('admin.login');
})->name('index');


Route::middleware(['auth', 'is_admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    Route::get('/settings/company-setting', [AdminController::class, 'companySettings'])->name('company.settings');
    Route::post('/settings/company-setting', [AdminController::class, 'companySettingsUpdate'])->name('company.settings.update');

    Route::get('/settings/payment-setting', [AdminController::class, 'paymentSettings'])->name('payment.settings');
    Route::get('/settings/email-setting', [AdminController::class, 'emailSettings'])->name('email.settings');
    Route::get('/settings/sms-api-setting', [AdminController::class, 'smsApiSettings'])->name('sms.api.settings');

    Route::resource('shipping-charge-settings', ShippingChargeSettingController::class)->except(['show']);
});
