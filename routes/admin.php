<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\AttributeTypeController;
use App\Http\Controllers\Admin\StatusController;
use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\ShippingChargeSettingController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\SocialLinkController;
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

    Route::patch('pages/{page}/status', [PageController::class, 'updateStatus'])->name('pages.status.update');
    Route::patch('pages/{page}/quick-link', [PageController::class, 'updateQuickLink'])->name('pages.quick-link.update');
    Route::resource('pages', PageController::class);


    Route::patch('social-links/{id}/status', [SocialLinkController::class, 'updateStatus'])->name('social-links.status.update');
    Route::resource('social-links', SocialLinkController::class);

    Route::get('customers/{customer}/security', [CustomerController::class, 'editSecurity'])->name('customers.security.edit');
    Route::patch('customers/{customer}/status', [CustomerController::class, 'updateStatus'])->name('customers.status.update');
    Route::patch('customers/{customer}/password', [CustomerController::class, 'changePassword'])->name('customers.password.update');
    Route::resource('customers', CustomerController::class);


    Route::patch('brands/{brand}/status', [BrandController::class, 'updateStatus'])->name('brands.status.update');
    Route::resource('brands', BrandController::class)->except('show');
    Route::patch('categories/{category}/status', [CategoryController::class, 'updateStatus'])->name('categories.status.update');
    Route::resource('categories', CategoryController::class)->except('show');
    Route::patch('attribute-types/{attributeType}/status', [AttributeTypeController::class, 'updateStatus'])->name('attribute-types.status.update');
    Route::resource('attribute-types', AttributeTypeController::class)
        ->parameters(['attribute-types' => 'attributeType'])->except('show');
    Route::patch('attributes/{attribute}/status', [AttributeController::class, 'updateStatus'])->name('attributes.status.update');
    Route::resource('attributes', AttributeController::class)->except('show');
    Route::patch('statuses/{status}/status', [StatusController::class, 'updateStatus'])->name('statuses.status.update');
    Route::resource('statuses', StatusController::class)->except('show');
});
