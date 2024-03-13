<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::get('/', [\App\Bundles\Portal\Controllers\IndexController::class, 'index'])->name('index');
    // 全部类目
    Route::get('catalog', [\App\Bundles\Portal\Controllers\CatalogController::class, 'index'])->name('portal.catalog');
    // 商品分类
    Route::get('category', [\App\Bundles\Portal\Controllers\CategoryController::class, 'index'])->name('portal.category');
});
