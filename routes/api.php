<?php

use App\Http\Controllers\Api\Auth\ApiAuthController;
use App\Http\Controllers\Api\CartApiController;
use App\Http\Controllers\Api\CatalogApiController;
use App\Http\Controllers\Api\CheckoutApiController;
use App\Http\Controllers\Api\OrderApiController;
use App\Http\Controllers\Api\WishlistApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes — Mobile App
|--------------------------------------------------------------------------
|
| All routes are prefixed with /api/v1 by convention.
| Public routes: product catalog, auth (login/register/OTP)
| Protected routes: cart, checkout, orders, wishlist
|
*/

// ── Public routes (no auth required) ──
Route::prefix('v1')->group(function () {

    // ── Authentication (mobile OTP flow) ──
    Route::prefix('auth')->group(function () {
        Route::post('/login', [ApiAuthController::class, 'login']);
        Route::post('/register', [ApiAuthController::class, 'register'])->middleware('throttle:5,1');
        Route::post('/otp/request', [ApiAuthController::class, 'requestOtp'])->middleware('throttle:3,1');
        Route::post('/otp/verify', [ApiAuthController::class, 'verifyOtp'])->middleware('throttle:5,1');
        Route::post('/otp/resend', [ApiAuthController::class, 'resendOtp'])->middleware('throttle:3,1');
        Route::post('/password/reset/request', [ApiAuthController::class, 'passwordResetRequest'])->middleware('throttle:3,1');
        Route::post('/password/reset/verify', [ApiAuthController::class, 'passwordResetVerify'])->middleware('throttle:5,1');
        Route::post('/password/reset/confirm', [ApiAuthController::class, 'passwordResetConfirm'])->middleware('throttle:5,1');
    });

    // ── Catalog (public) ──
    Route::get('/products', [CatalogApiController::class, 'index'])->name('api.products.index');
    Route::get('/products/{product}', [CatalogApiController::class, 'show'])->name('api.products.show');
    Route::get('/categories', [CatalogApiController::class, 'categories'])->name('api.categories.index');
});

// ── Protected routes (require Sanctum token) ──
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {

    // ── Auth ──
    Route::post('/auth/logout', [ApiAuthController::class, 'logout'])->middleware('throttle:10,1');
    Route::get('/auth/me', [ApiAuthController::class, 'me']);

    // ── Cart ──
    Route::get('/cart', [CartApiController::class, 'index'])->name('api.cart.index');
    Route::post('/cart/add/{product}', [CartApiController::class, 'add'])->name('api.cart.add')->middleware('throttle:30,1');
    Route::patch('/cart/{item}', [CartApiController::class, 'update'])->name('api.cart.update')->middleware('throttle:30,1');
    Route::delete('/cart/{item}', [CartApiController::class, 'remove'])->name('api.cart.remove')->middleware('throttle:30,1');
    Route::delete('/cart', [CartApiController::class, 'clear'])->name('api.cart.clear')->middleware('throttle:10,1');

    // ── Checkout ──
    Route::get('/checkout', [CheckoutApiController::class, 'summary'])->name('api.checkout.summary');
    Route::post('/checkout', [CheckoutApiController::class, 'store'])->name('api.checkout.store')->middleware('throttle:3,1');

    // ── Orders ──
    Route::get('/orders', [OrderApiController::class, 'index'])->name('api.orders.index');
    Route::get('/orders/{order}', [OrderApiController::class, 'show'])->name('api.orders.show');

    // ── Wishlist ──
    Route::get('/wishlist', [WishlistApiController::class, 'index'])->name('api.wishlist.index');
    Route::post('/wishlist/{product}', [WishlistApiController::class, 'store'])->name('api.wishlist.store')->middleware('throttle:30,1');
    Route::delete('/wishlist/{product}', [WishlistApiController::class, 'destroy'])->name('api.wishlist.destroy')->middleware('throttle:30,1');
});
