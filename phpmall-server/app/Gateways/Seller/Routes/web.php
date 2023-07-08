<?php

use Illuminate\Support\Facades\Route;

Route::prefix('seller')->middleware('web')->name('seller.')->group(function () {
    Route::get('/', [\App\Gateways\Seller\Controllers\IndexController::class, 'index'])->name('index');
    // Route

    // end
});
