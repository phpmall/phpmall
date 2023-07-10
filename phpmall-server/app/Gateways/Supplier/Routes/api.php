<?php

use Illuminate\Support\Facades\Route;

Route::prefix('api/supplier')->group(function () {
    Route::get('/', [\App\Gateways\Supplier\Controllers\IndexController::class, 'index']);
    // Route

    // end
});
