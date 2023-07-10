<?php

use Illuminate\Support\Facades\Route;

Route::prefix('api/user')->middleware('web')->name('user.')->group(function () {
    Route::get('/', [\App\Gateways\User\Controllers\IndexController::class, 'index'])->name('index');
    // Route
    Route::get('address', [\App\Gateways\User\Controllers\AddressController::class, 'index'])->name('address');
    Route::get('address/store', [\App\Gateways\User\Controllers\AddressController::class, 'store'])->name('address.store');
    Route::get('address/show', [\App\Gateways\User\Controllers\AddressController::class, 'show'])->name('address.show');
    Route::get('address/update', [\App\Gateways\User\Controllers\AddressController::class, 'update'])->name('address.update');
    Route::get('address/destroy', [\App\Gateways\User\Controllers\AddressController::class, 'destroy'])->name('address.destroy');
    Route::get('profile', [\App\Gateways\User\Controllers\ProfileController::class, 'index'])->name('profile');
    // end
});
