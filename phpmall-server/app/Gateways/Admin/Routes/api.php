<?php

use Illuminate\Support\Facades\Route;

Route::prefix('api/admin')->group(function () {
    Route::get('/', [\App\Gateways\Admin\Controllers\IndexController::class, 'index']);
    // Route
    Route::get('/dashboard', [\App\Gateways\Admin\Controllers\DashboardController::class, 'index']);

    // end
});
