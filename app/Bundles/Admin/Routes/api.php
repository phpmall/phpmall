<?php

use Illuminate\Support\Facades\Route;

Route::prefix('api/admin')->group(function () {
    Route::get('/', [\App\Api\Seller\Controllers\IndexController::class, 'index']);
    // Route

    // end
});
