<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CommentController as AdminCommentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ClientOrderController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

// ── Landing Page ──
Route::get('/', LandingController::class)->name('welcome');

// ── Sitemap XML ──
Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');

// ── Dashboard redirect (Breeze default route) ──
Route::get('/dashboard', function () {
    return redirect('/');
})->middleware(['auth', 'verified'])->name('dashboard');

// ── Catálogo público ──
Route::get('/productos', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/productos/{product}', [CatalogController::class, 'show'])->name('catalog.show');

// ── Carrito (requiere login) ──
Route::middleware('auth')->group(function () {
    Route::get('/carrito', [CartController::class, 'index'])->name('cart.index');
    Route::post('/carrito/agregar/{product}', [CartController::class, 'add'])->name('cart.add')->middleware('throttle:30,1');
    Route::patch('/carrito/{item}', [CartController::class, 'update'])->name('cart.update')->middleware('throttle:30,1');
    Route::delete('/carrito/{item}', [CartController::class, 'remove'])->name('cart.remove')->middleware('throttle:30,1');
    Route::delete('/carrito', [CartController::class, 'clear'])->name('cart.clear')->middleware('throttle:10,1');

    // ── Checkout ──
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store')->middleware('throttle:3,1');

    // ── Pedidos del cliente ──
    Route::get('/mis-pedidos', [ClientOrderController::class, 'index'])->name('orders.index');
    Route::get('/mis-pedidos/{order}', [ClientOrderController::class, 'show'])->name('orders.show');

    // ── Perfil ──
    Route::get('/perfil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/perfil', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/perfil', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ── Comentarios ──
    Route::post('/comentarios', [CommentController::class, 'store'])->name('comments.store')->middleware('throttle:5,1');
});

// ── Admin Panel ──
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
    });

    Route::middleware('admin.auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('products', ProductController::class);
        Route::resource('categories', CategoryController::class)->except(['show']);
        Route::resource('orders', AdminOrderController::class)->only(['index', 'show']);
        Route::patch('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');

        // ── Comentarios (moderación) ──
        Route::get('comments', [AdminCommentController::class, 'index'])->name('comments.index');
        Route::patch('comments/{comment}/approve', [AdminCommentController::class, 'approve'])->name('comments.approve');
        Route::patch('comments/{comment}/reject', [AdminCommentController::class, 'reject'])->name('comments.reject');
        Route::delete('comments/{comment}', [AdminCommentController::class, 'destroy'])->name('comments.destroy');
    });
});

require __DIR__.'/auth.php';
