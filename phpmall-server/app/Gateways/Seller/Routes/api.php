<?php

use Illuminate\Support\Facades\Route;

Route::prefix('api/seller')->group(function () {
    Route::get('/', [\App\Gateways\Seller\Controllers\IndexController::class, 'index']);
    // Route

    // end
});
