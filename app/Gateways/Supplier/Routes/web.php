<?php

use Illuminate\Support\Facades\Route;

Route::prefix('supplier')->middleware('web')->group(function () {
    Route::get('/', [\App\Gateways\Supplier\Controllers\IndexController::class, 'index']);
    // Route

    // end
});
