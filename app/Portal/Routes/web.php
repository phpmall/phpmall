<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::get('/', [\App\Portal\Http\Controllers\IndexController::class, 'index'])->name('portal.index');
    // Route start
    // 登录页面
    Route::get('login', [\App\Portal\Http\Controllers\AuthController::class, 'login'])->name('login');
    // 全部类目
    Route::get('catalog', [\App\Portal\Http\Controllers\CatalogController::class, 'index'])->name('portal.catalog');
    // 商品分类
    Route::get('category', [\App\Portal\Http\Controllers\CategoryController::class, 'index'])->name('portal.category');
    // end
});
