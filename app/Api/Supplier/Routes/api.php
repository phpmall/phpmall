<?php

use Illuminate\Support\Facades\Route;

Route::prefix('supplier')->group(function () {
    Route::get('/', [\App\Api\Supplier\Controllers\IndexController::class, 'index']);
    // Route

    // end
});
