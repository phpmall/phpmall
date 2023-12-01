<?php

use Illuminate\Support\Facades\Route;

Route::prefix('api/organization')->group(function () {
    Route::get('/', [\App\Api\Seller\Controllers\IndexController::class, 'index']);
    // Route

    // end
});
