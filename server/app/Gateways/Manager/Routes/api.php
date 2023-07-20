<?php

use Illuminate\Support\Facades\Route;

Route::prefix(config('app.context_path').'manager')->group(function () {
    Route::get('/', [\App\Gateways\Manager\Controllers\IndexController::class, 'index']);
    // Route
    Route::get('/dashboard', [\App\Gateways\Manager\Controllers\DashboardController::class, 'index']);

    // end
});
