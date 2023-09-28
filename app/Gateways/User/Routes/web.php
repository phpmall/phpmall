<?php

use Illuminate\Support\Facades\Route;

Route::prefix('user')->group(function () {
    Route::get('/', [\App\Gateways\User\Controllers\IndexController::class, 'index']);
    // Route
    
    // end
});
