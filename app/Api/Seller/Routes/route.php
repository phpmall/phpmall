<?php

declare(strict_types=1);

use App\Api\Seller\Controllers\ProductController;
use App\Api\Seller\Controllers\ProductSkuController;
use Illuminate\Support\Facades\Route;

Route::prefix('seller')->group(function (): void {
    // 批量路由需优先注册，避免被 {id} 通配路由覆盖
    Route::post('products/batch/on-shelf', [ProductController::class, 'batchOnShelf']);
    Route::post('products/batch/off-shelf', [ProductController::class, 'batchOffShelf']);
    Route::post('products/batch/delete', [ProductController::class, 'batchDelete']);
    Route::post('product-skus/batch', [ProductSkuController::class, 'batchUpdate']);

    require __DIR__.'/route.gen.php';
});
