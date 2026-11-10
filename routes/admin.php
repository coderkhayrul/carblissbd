<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\AttributeTypeController;
use App\Http\Controllers\Admin\StatusController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\SeasonController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\PopupAdsController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\PromoAdsController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ReturnReasonController;
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
    Route::get('orders/{order}/due-collection', [\App\Http\Controllers\Admin\OrderDueCollectionController::class,'create'])->name('orders.due-collection');
    Route::post('orders/{order}/due-collection', [\App\Http\Controllers\Admin\OrderDueCollectionController::class,'store'])->middleware('throttle:15,1')->name('orders.due-collection.store');
    Route::get('orders/{order}/payments/{payment}/receipt', [\App\Http\Controllers\Admin\OrderDueCollectionController::class,'receipt'])->name('orders.collection-receipt');
    Route::get('reports/customers', [\App\Http\Controllers\Admin\CustomerReportController::class, 'index'])->name('reports.customers');
    Route::get('reports/customers/export', [\App\Http\Controllers\Admin\CustomerReportController::class, 'export'])->name('reports.customers.export');
    Route::get('reports/products', [\App\Http\Controllers\Admin\ProductReportController::class, 'index'])->name('reports.products');
    Route::get('reports/products/export', [\App\Http\Controllers\Admin\ProductReportController::class, 'export'])->name('reports.products.export');
    Route::get('reports/sales', [\App\Http\Controllers\Admin\SalesReportController::class, 'index'])->name('reports.sales');
    Route::get('reports/sales/customers', [\App\Http\Controllers\Admin\SalesReportController::class, 'customers'])->middleware('throttle:60,1')->name('reports.sales.customers');
    Route::get('reports/sales/export', [\App\Http\Controllers\Admin\SalesReportController::class, 'export'])->name('reports.sales.export');
    Route::get('order-notifications', [\App\Http\Controllers\Admin\OrderNotificationController::class, 'index'])->middleware('throttle:300,1')->name('order-notifications.index');
    Route::post('order-notifications/read-all', [\App\Http\Controllers\Admin\OrderNotificationController::class, 'readAll'])->name('order-notifications.read-all');
    Route::post('order-notifications/{notification}/read', [\App\Http\Controllers\Admin\OrderNotificationController::class, 'read'])->whereNumber('notification')->name('order-notifications.read');
    Route::get('configuration/courier/pathao', [\App\Http\Controllers\Admin\PathaoController::class, 'settings'])->name('pathao.settings');
    Route::post('configuration/courier/pathao', [\App\Http\Controllers\Admin\PathaoController::class, 'save'])->name('pathao.save');
    Route::post('configuration/courier/pathao/connect', [\App\Http\Controllers\Admin\PathaoController::class, 'connect'])->middleware('throttle:5,1')->name('pathao.connect');
    Route::get('configuration/courier/pathao/locations/{type}', [\App\Http\Controllers\Admin\PathaoController::class, 'locations'])->middleware('throttle:60,1')->name('pathao.locations');
    Route::post('configuration/courier/pathao/stores', [\App\Http\Controllers\Admin\PathaoController::class, 'createStore'])->middleware('throttle:5,1')->name('pathao.stores.create');
    Route::get('orders/{order}/pathao', [\App\Http\Controllers\Admin\PathaoController::class, 'booking'])->name('pathao.booking');
    Route::post('orders/{order}/pathao', [\App\Http\Controllers\Admin\PathaoController::class, 'book'])->middleware('throttle:10,1')->name('pathao.book');
    Route::post('orders/{order}/pathao/price', [\App\Http\Controllers\Admin\PathaoController::class, 'price'])->middleware('throttle:20,1')->name('pathao.price');
    Route::post('orders/{order}/pathao/sync', [\App\Http\Controllers\Admin\PathaoController::class, 'sync'])->middleware('throttle:20,1')->name('pathao.sync');
    Route::post('orders/{order}/pathao/recover', [\App\Http\Controllers\Admin\PathaoController::class, 'recover'])->middleware('throttle:5,1')->name('pathao.recover');
    Route::post('orders/{order}/pathao/release', [\App\Http\Controllers\Admin\PathaoController::class, 'release'])->middleware('throttle:5,1')->name('pathao.release');
    Route::get('configuration/courier/steadfast', [\App\Http\Controllers\Admin\SteadfastController::class, 'settings'])->name('steadfast.settings');
    Route::post('configuration/courier/steadfast', [\App\Http\Controllers\Admin\SteadfastController::class, 'save'])->name('steadfast.save');
    Route::post('configuration/courier/steadfast/connection', [\App\Http\Controllers\Admin\SteadfastController::class, 'connection'])->middleware('throttle:5,1')->name('steadfast.connection');
    Route::post('orders/{order}/steadfast/sync', [\App\Http\Controllers\Admin\SteadfastController::class, 'sync'])->middleware('throttle:20,1')->name('steadfast.sync');
    Route::post('orders/{order}/steadfast/cancel', [\App\Http\Controllers\Admin\SteadfastController::class, 'cancel'])->name('steadfast.cancel');
    Route::post('orders/{order}/steadfast/release', [\App\Http\Controllers\Admin\SteadfastController::class, 'release'])->middleware('throttle:5,1')->name('steadfast.release');
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
    Route::post('customers/{customer}/sms', [CustomerController::class, 'sendSms'])->middleware('throttle:10,1')->name('customers.sms.send');
    Route::post('customers/{customer}/email', [CustomerController::class, 'sendEmail'])->middleware('throttle:10,1')->name('customers.email.send');
    Route::patch('customers/{customer}/status', [CustomerController::class, 'updateStatus'])->name('customers.status.update');
    Route::patch('customers/{customer}/password', [CustomerController::class, 'changePassword'])->name('customers.password.update');
    Route::resource('customers', CustomerController::class);


    Route::patch('brands/{brand}/status', [BrandController::class, 'updateStatus'])->name('brands.status.update');
    Route::resource('brands', BrandController::class)->except('show');
    Route::patch('categories/{category}/status', [CategoryController::class, 'updateStatus'])->name('categories.status.update');
    Route::patch('categories/{category}/serial', [CategoryController::class, 'updateSerial'])->name('categories.serial.update');
    Route::resource('categories', CategoryController::class)->except('show');
    Route::patch('attribute-types/{attributeType}/status', [AttributeTypeController::class, 'updateStatus'])->name('attribute-types.status.update');
    Route::resource('attribute-types', AttributeTypeController::class)
        ->parameters(['attribute-types' => 'attributeType'])->except('show');
    Route::patch('attributes/{attribute}/status', [AttributeController::class, 'updateStatus'])->name('attributes.status.update');
    Route::resource('attributes', AttributeController::class)->except('show');
    Route::patch('statuses/{status}/status', [StatusController::class, 'updateStatus'])->name('statuses.status.update');
    Route::resource('statuses', StatusController::class)->except('show');
    Route::patch('tags/{tag}/status', [TagController::class, 'updateStatus'])->name('tags.status.update');
    Route::resource('tags', TagController::class)->except('show');
    Route::patch('seasons/{season}/status', [SeasonController::class, 'updateStatus'])->name('seasons.status.update');
    Route::resource('seasons', SeasonController::class)->except('show');
    Route::patch('menus/{menu}/status', [MenuController::class, 'updateStatus'])->name('menus.status.update');
    Route::resource('menus', MenuController::class)->except('show');
    Route::patch('coupons/{coupon}/status', [CouponController::class, 'updateStatus'])->name('coupons.status.update');
    Route::resource('coupons', CouponController::class)->except('show');
    Route::patch('products/{product}/status', [ProductController::class, 'updateStatus'])->name('products.status.update');
    Route::resource('products', ProductController::class)->except('show');
    Route::get('orders/export', [OrderController::class, 'export'])->name('orders.export');
    Route::post('orders/print-invoices', [OrderController::class, 'printInvoices'])->name('orders.print-invoices');
    Route::get('orders/{order}/mini-invoice', [OrderController::class, 'miniInvoice'])->name('orders.mini-invoice');
    Route::get('orders/products/search', [OrderController::class, 'searchProducts'])->name('orders.products.search');
    Route::get('orders/customers/search', [OrderController::class, 'searchCustomers'])->name('orders.customers.search');
    Route::post('orders/customers', [OrderController::class, 'createCustomer'])->name('orders.customers.store');
    Route::get('orders/{order}/invoice/{copy}', [OrderController::class, 'invoice'])->name('orders.invoice');
    Route::get('orders/{order}/actions/{action}', [OrderController::class, 'action'])->whereIn('action', ['status', 'return', 'complaint', 'duplicate'])->name('orders.action');
    Route::post('orders/{order}/actions/{action}', [OrderController::class, 'performAction'])->whereIn('action', ['status', 'return', 'complaint', 'duplicate'])->name('orders.action.store');
    Route::patch('orders/{order}/courier', [OrderController::class, 'assignCourier'])->name('orders.courier.assign');
    Route::resource('orders', OrderController::class)->only(['index', 'show', 'edit', 'update', 'create', 'store']);
    Route::patch('promo-ads/{promoAd}/status', [PromoAdsController::class, 'updateStatus'])->name('promo-ads.status.update');
    Route::resource('promo-ads', PromoAdsController::class)->parameters(['promo-ads' => 'promoAd'])->except('show');
    Route::get('popup-ads', [PopupAdsController::class, 'edit'])->name('popup-ads.edit');
    Route::put('popup-ads', [PopupAdsController::class, 'update'])->name('popup-ads.update');
    Route::patch('popup-ads/status', [PopupAdsController::class, 'updateStatus'])->name('popup-ads.status.update');
    Route::patch('return-reasons/{returnReason}/status', [ReturnReasonController::class, 'updateStatus'])->name('return-reasons.status.update');
    Route::resource('return-reasons', ReturnReasonController::class)
        ->parameters(['return-reasons' => 'returnReason'])->except('show');
});
