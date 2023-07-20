<?php

use Illuminate\Support\Facades\Route;

Route::prefix(config('app.context_path').'supplier')->group(function () {
    Route::get('/', [\App\Gateways\Supplier\Controllers\IndexController::class, 'index']);
    // Route

    // end
});
