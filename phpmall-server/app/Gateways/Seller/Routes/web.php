<?php

use Illuminate\Support\Facades\Route;

Route::prefix('api/seller')->middleware('web')->name('seller.')->group(function () {
    Route::get('/', [\App\Gateways\Seller\Controllers\IndexController::class, 'index'])->name('index');
    // Route

    // end
});
