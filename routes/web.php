<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\DashboardController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Products CRUD
Route::resource('products', ProductController::class);
Route::post('products/{product}/restore', [ProductController::class, 'restore'])->name('products.restore');

// Categories CRUD
Route::resource('categories', CategoryController::class);

// Orders CRUD
Route::resource('orders', OrderController::class);
Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
