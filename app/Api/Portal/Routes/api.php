<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

// Route
// 全部类目
Route::get('portal/catalog', [\App\Api\Portal\Controllers\CatalogController::class, 'index'])->name('portal.catalog');
// 商品分类
Route::get('portal/category', [\App\Api\Portal\Controllers\CategoryController::class, 'index'])->name('portal.category');
// end
