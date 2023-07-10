<?php

use Illuminate\Support\Facades\Route;

Route::prefix('api/seller')->middleware('api')->group(function () {
    Route::get('/', [\App\Gateways\Seller\Controllers\IndexController::class, 'index']);
    // Route

    // end
});
