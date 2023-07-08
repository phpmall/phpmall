<?php

use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware('web')->name('admin.')->group(function () {
    Route::get('/', [\App\Gateways\Admin\Controllers\IndexController::class, 'index'])->name('index');
    // Route
    Route::get('/dashboard', [\App\Gateways\Admin\Controllers\DashboardController::class, 'index'])->name('dashboard');

    // end
});
