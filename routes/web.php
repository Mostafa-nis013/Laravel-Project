<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\ReportsController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\GlobalSearchController;
use App\Http\Controllers\Shop\ShopController;
use App\Http\Controllers\Shop\CartController;
use App\Http\Controllers\Shop\CheckoutController;

// ── Guest-only (login / register) ────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',   [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register',[AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// ── Storefront – public (no auth needed to browse) ────────────────────────────
Route::get('/',         [ShopController::class, 'index'])->name('shop.index');
Route::get('/p/{slug}', [ShopController::class, 'show'])->name('shop.show');

// ── Storefront – auth required (cart / checkout / orders) ─────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/cart',            [CartController::class,    'index'])->name('shop.cart');
    Route::post('/cart/add',       [CartController::class,    'add'])->name('cart.add');
    Route::patch('/cart/{id}',     [CartController::class,    'update'])->name('cart.update');
    Route::delete('/cart/{id}',    [CartController::class,    'remove'])->name('cart.remove');
    Route::delete('/cart',         [CartController::class,    'clear'])->name('cart.clear');

    Route::get('/checkout',        [CheckoutController::class,'index'])->name('shop.checkout');
    Route::post('/checkout',       [CheckoutController::class,'store'])->name('shop.checkout.store');
    Route::get('/order/{number}',  [CheckoutController::class,'confirm'])->name('shop.order.confirm');

    Route::get('/my-orders',       [ShopController::class,    'orders'])->name('shop.orders');
});

// Redirect legacy /home to shop
Route::get('/home', fn() => redirect()->route('shop.index'))->name('home');

// ── Admin panel – all panel users ─────────────────────────────────────────────
Route::middleware(['auth', 'role:admin,editor,moderator'])->group(function () {

    Route::get('/dashboard',  [DashboardController::class, 'index'])->name('dashboard');

    // Products (keep original resource names: products.index etc.)
    Route::resource('products', ProductController::class);
    Route::post('products/{product}/restore', [ProductController::class, 'restore'])->name('products.restore');

    // Categories
    Route::resource('categories', CategoryController::class);

    // Orders
    Route::resource('orders', OrderController::class);
    Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');

    // Global search
    Route::get('/admin/search', GlobalSearchController::class)->name('admin.search');

    // Reports & Activity log
    Route::get('/admin/reports',  [ReportsController::class,    'index'])->name('admin.reports');
    Route::get('/admin/activity', [ActivityLogController::class,'index'])->name('admin.activity');

    // Profile (any panel user)
    Route::get('/admin/profile',          [ProfileController::class, 'edit'])->name('admin.profile');
    Route::put('/admin/profile',          [ProfileController::class, 'update'])->name('admin.profile.update');
    Route::put('/admin/profile/password', [ProfileController::class, 'updatePassword'])->name('admin.profile.password');

    // Admin-only
    Route::middleware('role:admin')->group(function () {
        Route::resource('users', UserController::class);
        Route::get('/admin/settings', [SettingsController::class, 'index'])->name('admin.settings');
        Route::put('/admin/settings', [SettingsController::class, 'update'])->name('admin.settings.update');
    });
});
