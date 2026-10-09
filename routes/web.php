<?php

use App\Http\Controllers\Auth\CustomerAuthController;
use App\Http\Controllers\FrontendController;
use Illuminate\Support\Facades\Route;


Route::middleware('guest')->group(function () {
    // CUSTOMER LOGIN
    Route::get('/login', [CustomerAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [CustomerAuthController::class, 'login'])->name('login.submit');

    Route::get('/register', [CustomerAuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [CustomerAuthController::class, 'register'])->name('register.submit');
});

Route::get('/', [FrontendController::class, 'home'])->name('home');
Route::get('/category', [FrontendController::class, 'category'])->name('category');
Route::get('/offers', [FrontendController::class, 'offers'])->name('offers');
Route::get('/product-detail', [FrontendController::class, 'productDetail'])->name('product.detail');
Route::get('/cart', [FrontendController::class, 'cart'])->name('cart');
Route::get('/checkout', [FrontendController::class, 'checkout'])->name('checkout');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [CustomerAuthController::class, 'profile'])->name('profile');
    Route::post('logout', [CustomerAuthController::class, 'destroy'])->name('logout');
});
