<?php

use Illuminate\Support\Facades\Route;

Route::prefix('manager')->group(function () {
    Route::get('/', [\App\Api\Manager\Controllers\IndexController::class, 'index']);
    // Route
    Route::get('dashboard', [\App\Api\Manager\Controllers\DashboardController::class, 'index']);

    // end
});
