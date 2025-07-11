<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ShopController::class, 'index'])->name('shop.index');
Route::get('/product/{product}', [ShopController::class, 'show'])->name('shop.show');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/remove/{product}', [CartController::class, 'remove'])->name('cart.remove');

// PERBAIKAN: Ubah route update untuk menerima parameter product ID
Route::post('/cart/update/{product}', [CartController::class, 'update'])->name('cart.update');

Route::get('/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
Route::post('/checkout', [CartController::class, 'store'])->name('cart.store');
Route::get('/payment-proof/{order}', [CartController::class, 'paymentProof'])->name('cart.payment-proof');
Route::post('/payment-proof/{order}', [CartController::class, 'uploadPaymentProof'])->name('cart.upload-payment-proof');
Route::get('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

// Route untuk mendapatkan jumlah item di keranjang
Route::get('/cart/count', [CartController::class, 'getCartCount'])->name('cart.count');