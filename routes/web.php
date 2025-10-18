<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\WilayahController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home.index');

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:user'])
    ->name('user.')
    ->group(function () {
        Route::get('/home', [HomeController::class, 'index'])->name('home');
        Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
        Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
        Route::post('/checkout/calculate', [CheckoutController::class, 'calculateShipping'])->name('checkout.calculate');
        // Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
        Route::post('/checkout/payment', [CheckoutController::class, 'payment'])->name('checkout.payment');
        Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');

        Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
        Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
        Route::put('/cart/item/{id}', [CartController::class, 'update'])->name('cart.update');
        Route::post('/cart/increase/{id}', [CartController::class, 'increase'])->name('cart.increase');
        Route::post('/cart/decrease/{id}', [CartController::class, 'decrease'])->name('cart.decrease');
        Route::delete('/cart/remove/{id}', [CartController::class, 'destroy'])->name('cart.remove');
        Route::post('/cart/apply-coupon', [CheckoutController::class, 'applyCoupon'])->name('cart.applyCoupon');
        Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
        Route::patch('/cart/deactivate/{id}', [CartController::class, 'deactivate'])->name('cart.deactivate');
    });

Route::middleware(['auth', 'role:admin'])
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    });

// API perlu setting khusus agar hanya bisa diakses user
Route::prefix('api/wilayah')->name('api.wilayah.')->group(function () {
    Route::get('/cities/{province_id}', [WilayahController::class, 'getCities']);
    Route::get('/districts/{city_id}', [WilayahController::class, 'getDistricts']);
    Route::get('/villages/{district_id}', [WilayahController::class, 'getVillages']);
});

require __DIR__.'/auth.php';
