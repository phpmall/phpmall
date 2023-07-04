<?php

use Illuminate\Support\Facades\Route;

Route::prefix('supplier')->middleware('web')->name('supplier.')->group(function () {
    Route::get('/', [\App\Gateways\Supplier\Controllers\IndexController::class, 'index'])->name('index');
    // Route

    // end
});
