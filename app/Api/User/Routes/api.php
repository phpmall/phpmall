<?php

use Illuminate\Support\Facades\Route;

Route::prefix('api/user')->group(function () {
    Route::get('/', [\App\Api\User\Controllers\IndexController::class, 'index']);
    // Route

    // end
});
