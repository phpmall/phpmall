<?php

use Illuminate\Support\Facades\Route;

Route::prefix(config('app.context_path').'portal')->group(function () {
    Route::get('/', [\App\Gateways\Portal\Controllers\IndexController::class, 'index']);
    // Route
    Route::get('catalog', [\App\Gateways\Portal\Controllers\CatalogController::class, 'index']);
    Route::get('category', [\App\Gateways\Portal\Controllers\CategoryController::class, 'index']);
    // end
});
