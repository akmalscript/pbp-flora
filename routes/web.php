<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\WilayahController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/home', function () {
    return view('home');
})->middleware(['auth', 'verified'])->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rute untuk menampilkan halaman checkout
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');

// Rute untuk memproses form alamat dan menghitung ongkir
Route::post('/checkout/calculate', [CheckoutController::class, 'calculateShipping'])->name('checkout.calculate');

// Grup rute untuk API data wilayah Indonesia
Route::prefix('api/wilayah')->name('api.wilayah.')->group(function () {
    Route::get('/cities/{province_id}', [WilayahController::class, 'getCities']);
    Route::get('/districts/{city_id}', [WilayahController::class, 'getDistricts']);
    Route::get('/villages/{district_id}', [WilayahController::class, 'getVillages']);
});

require __DIR__.'/auth.php';
