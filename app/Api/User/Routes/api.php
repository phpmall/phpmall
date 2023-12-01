<?php

use Illuminate\Support\Facades\Route;

Route::prefix('user')->group(function () {
    Route::get('/', [\App\Api\User\Controllers\IndexController::class, 'index']);
    // Route

    // end
});
