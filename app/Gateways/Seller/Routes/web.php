<?php

use Illuminate\Support\Facades\Route;

Route::prefix('seller')->middleware('web')->group(function () {
    Route::get('/', [\App\Gateways\Seller\Controllers\IndexController::class, 'index']);
    // Route

    // end
});
