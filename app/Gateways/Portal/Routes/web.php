<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::prefix('api/portal')->group(function () {
    Route::get('/', [\App\Gateways\Portal\Controllers\IndexController::class, 'index'])->name('index');
    // Route
    Route::get('catalog', [\App\Gateways\Portal\Controllers\CatalogController::class, 'index']);
    Route::get('category', [\App\Gateways\Portal\Controllers\CategoryController::class, 'index']);
    // end
});
