<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:20,1')->name('login.store');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])
        ->middleware('throttle:5,1')->name('register.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/products', [ProductController::class, 'index'])->name('products.index');

    Route::middleware('admin')->group(function () {
        Route::get('/products/trash', [ProductController::class, 'trash'])->name('products.trash');
        Route::patch('/products/{product}/restore', [ProductController::class, 'restore'])
            ->whereNumber('product')->name('products.restore');
        Route::delete('/products/{product}/force', [ProductController::class, 'forceDestroy'])
            ->whereNumber('product')->name('products.force-destroy');
        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])
            ->whereNumber('product')->name('products.edit');
        Route::match(['put', 'patch'], '/products/{product}', [ProductController::class, 'update'])
            ->whereNumber('product')->name('products.update');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])
            ->whereNumber('product')->name('products.destroy');
    });

    Route::get('/products/{product}', [ProductController::class, 'show'])
        ->whereNumber('product')->name('products.show');
});
