<?php

use App\Http\Controllers\StorefrontController;
use App\Http\Controllers\LuxoraAdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', [StorefrontController::class, 'home'])->name('home');
Route::get('/shop', [StorefrontController::class, 'shop'])->name('shop');
Route::get('/products/{slug}', [StorefrontController::class, 'product'])->name('products.show');
Route::get('/about', [StorefrontController::class, 'about'])->name('about');
Route::get('/cart', [StorefrontController::class, 'cart'])->name('cart.show');
Route::post('/cart/items', [StorefrontController::class, 'addToCart'])->name('cart.add');
Route::patch('/cart', [StorefrontController::class, 'updateCart'])->name('cart.update');
Route::get('/checkout', [StorefrontController::class, 'checkout'])->name('checkout');
Route::post('/checkout', [StorefrontController::class, 'placeOrder'])->name('checkout.place');
Route::get('/orders/{order}/success', [StorefrontController::class, 'success'])->name('orders.success');
Route::post('/orders/{order}/cancel', [StorefrontController::class, 'cancel'])->name('orders.cancel');
Route::get('/login', fn () => response('Authentication is required.', 401))->name('login');
Route::middleware(['auth', 'luxora.admin'])->prefix('admin/luxora')->name('admin.luxora.')->group(function () {
    Route::get('/', [LuxoraAdminController::class, 'index'])->name('index');
    Route::patch('/variants/{variant}/stock', [LuxoraAdminController::class, 'updateStock'])->name('stock.update');
    Route::post('/orders/{order}/mark-paid', [LuxoraAdminController::class, 'markPaid'])->name('orders.mark-paid');
});
