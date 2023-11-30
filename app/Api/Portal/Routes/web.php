<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::prefix('portal')->group(function () {
    Route::get('/', [\App\Api\Portal\Controllers\IndexController::class, 'index']);
    // Route
    Route::get('catalog', [\App\Api\Portal\Controllers\CatalogController::class, 'index']);
    Route::get('category', [\App\Api\Portal\Controllers\CategoryController::class, 'index']);
    // end
});
