<?php

use Illuminate\Support\Facades\Route;

Route::prefix('user')->group(function () {
    Route::post('login', [\App\Gateways\User\Controllers\AuthController::class, 'login']);

    Route::get('/', [\App\Gateways\User\Controllers\IndexController::class, 'index']);
    // Route
    Route::get('address', [\App\Gateways\User\Controllers\AddressController::class, 'index']);
    Route::get('address/store', [\App\Gateways\User\Controllers\AddressController::class, 'store']);
    Route::get('address/show', [\App\Gateways\User\Controllers\AddressController::class, 'show']);
    Route::get('address/update', [\App\Gateways\User\Controllers\AddressController::class, 'update']);
    Route::get('address/destroy', [\App\Gateways\User\Controllers\AddressController::class, 'destroy']);
    Route::get('profile', [\App\Gateways\User\Controllers\ProfileController::class, 'index']);
    // end
});
