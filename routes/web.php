<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\WilayahController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::view('/home2', 'home2')->name('home2');
Route::view('/search', 'searchpage')->name('search.page');


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

        Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
        Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
        Route::put('/cart/item/{id}', [CartController::class, 'update'])->name('cart.update');
        Route::delete('/cart/item/{id}', [CartController::class, 'destroy'])->name('cart.destroy');
        Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
        Route::patch('/cart/deactivate/{id}', [CartController::class, 'deactivate'])->name('cart.deactivate');

        Route::view('/order-history', 'orderHistory')->name('orders.history');
        Route::view('/order-details', 'orderDetails')->name('orders.details');
        Route::view('/cart-page', 'cartPage')->name('cart.page');
        Route::get('/product-detail/{id}', [ProductController::class, 'show'])->name('products.detail');
        Route::get('/checkout-page', [CheckoutController::class, 'index'])->name('checkout.page');
    });

Route::middleware(['auth', 'role:admin'])
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::view('/admin-dashboard', 'admindashboard')->name('dashboard.view');
        Route::view('/manajemen-kategori', 'manajemenkategori')->name('categories.manage');
        Route::view('/orders-management', 'order')->name('orders.manage');
    });

// API perlu setting khusus agar hanya bisa diakses user
Route::prefix('api/wilayah')->name('api.wilayah.')->group(function () {
    Route::get('/cities/{province_id}', [WilayahController::class, 'getCities']);
    Route::get('/districts/{city_id}', [WilayahController::class, 'getDistricts']);
    Route::get('/villages/{district_id}', [WilayahController::class, 'getVillages']);
});

require __DIR__.'/auth.php';
