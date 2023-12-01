<?php

use Illuminate\Support\Facades\Route;

Route::prefix('user')->group(function () {
    Route::prefix('admin')->group(function () {
        Route::get('/', [\App\Bundles\User\Controllers\Manager\UserController::class, 'index']);
    });

    Route::get('address', [\App\Bundles\User\Controllers\User\AddressController::class, 'index']);
    Route::get('address/store', [\App\Bundles\User\Controllers\User\AddressController::class, 'store']);
    Route::get('address/show', [\App\Bundles\User\Controllers\User\AddressController::class, 'show']);
    Route::get('address/update', [\App\Bundles\User\Controllers\User\AddressController::class, 'update']);
    Route::get('address/destroy', [\App\Bundles\User\Controllers\User\AddressController::class, 'destroy']);
    Route::get('profile', [\App\Bundles\User\Controllers\User\ProfileController::class, 'index']);
});
