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

// ── Guest routes ──────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',   [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register',[AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// ── Storefront (any logged-in user) ───────────────────────────────────────────
Route::get('/home', fn() => view('home'))->middleware('auth')->name('home');

// ── Admin panel ───────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:admin,editor,moderator'])->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Catalog
    Route::resource('products', ProductController::class);
    Route::post('products/{product}/restore', [ProductController::class, 'restore'])->name('products.restore');
    Route::resource('categories', CategoryController::class);

    // Orders
    Route::resource('orders', OrderController::class);
    Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');

    // Reports
    Route::get('/reports', [ReportsController::class, 'index'])->name('admin.reports');

    // Activity log (admin + moderator)
    Route::get('/activity', [ActivityLogController::class, 'index'])->name('admin.activity');

    // Profile (any admin-panel user)
    Route::get('/profile',          [ProfileController::class, 'edit'])->name('admin.profile');
    Route::put('/profile',          [ProfileController::class, 'update'])->name('admin.profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('admin.profile.password');

    // Admin-only
    Route::middleware('role:admin')->group(function () {
        Route::resource('users', UserController::class);
        Route::get('/settings',  [SettingsController::class, 'index'])->name('admin.settings');
        Route::put('/settings',  [SettingsController::class, 'update'])->name('admin.settings.update');
    });
});
