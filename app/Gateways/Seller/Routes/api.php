<?php

use Illuminate\Support\Facades\Route;

Route::prefix(config('app.context_path').'seller')->group(function () {
    Route::get('/', [\App\Gateways\Seller\Controllers\IndexController::class, 'index']);
    // Route

    // end
});
