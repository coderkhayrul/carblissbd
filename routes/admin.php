<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\ShippingChargeSettingController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\UserController;
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
    Route::post('/settings/payment-setting', [AdminController::class, 'paymentSettingsUpdate'])->name('payment.settings.update');


    Route::get('/settings/email-setting', [AdminController::class, 'emailSettings'])->name('email.settings');
    Route::post('/settings/email-setting', [AdminController::class, 'emailSettingsUpdate'])->name('email.settings.update');

    Route::get('/settings/sms-api-setting', [AdminController::class, 'smsApiSettings'])->name('sms.api.settings');
    Route::post('/settings/sms-api-setting', [AdminController::class, 'smsApiSettingsUpdate'])->name('sms.api.settings.update');

    Route::resource('shipping-charge-settings', ShippingChargeSettingController::class)->except(['show', 'create', 'edit']);
    Route::resource('users', UserController::class)->except(['create', 'edit']);
    Route::patch('users/{id}/suspend', [UserController::class, 'suspend'])->name('users.suspend');
    Route::patch('users/{id}/unsuspend', [UserController::class, 'unsuspend'])->name('users.unsuspend');


    Route::patch('sliders/{slider}/status', [SliderController::class, 'updateStatus'])->name('sliders.status.update');
    Route::resource('sliders', SliderController::class);
});
