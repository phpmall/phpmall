<?php

use Illuminate\Support\Facades\Route;

Route::prefix('api/portal')->group(function () {
    Route::get('/', [\App\Api\Seller\Controllers\IndexController::class, 'index']);
    // Route

    // end
});

Route::prefix('portal')->group(function () {
    Route::prefix('admin')->group(function () {
        Route::get('/', [\App\Bundles\Portal\Controllers\Manager\LinkController::class, 'index']);
    });
});
