<?php

use Illuminate\Support\Facades\Route;

Route::prefix('seller')->group(function () {
    Route::get('/', [\App\Gateways\Seller\Controllers\IndexController::class, 'index']);
    // Route

    // end
});
