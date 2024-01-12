<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::get('/', [\App\Portal\Http\Controllers\IndexController::class, 'index'])->name('portal.index');
    // Route start
    // 全部类目
    Route::get('catalog', [\App\Portal\Http\Controllers\CatalogController::class, 'index'])->name('portal.catalog');
    // 商品分类
    Route::get('category', [\App\Portal\Http\Controllers\CategoryController::class, 'index'])->name('portal.category');
    // end

    Route::get('/login', [\App\Portal\Http\Controllers\AuthController::class, 'login'])->name('login');
    Route::get('/signup', [\App\Portal\Http\Controllers\AuthController::class, 'signup'])->name('signup');
    Route::get('/forget', [\App\Portal\Http\Controllers\AuthController::class, 'forget'])->name('forget');
    Route::get('/reset', [\App\Portal\Http\Controllers\AuthController::class, 'reset'])->name('reset');
    Route::get('/logout', [\App\Portal\Http\Controllers\AuthController::class, 'index'])->name('logout');

    Route::prefix('user')->middleware('auth')->group(function () {
        Route::get('/', [\App\Portal\Http\Controllers\AuthController::class, 'index'])->name('user');

    });
});
