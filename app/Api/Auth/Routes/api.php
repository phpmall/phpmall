<?php

declare(strict_types=1);

use App\Api\Auth\Controllers\ForgetController;
use App\Api\Auth\Controllers\LoginController;
use App\Api\Auth\Controllers\ResetController;
use App\Api\Auth\Controllers\SignupController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('login/mobile', [LoginController::class, 'mobile']);
    Route::post('signup/mobile', [SignupController::class, 'mobile']);
    Route::post('forget/mobile', [ForgetController::class, 'mobile']);
    Route::post('reset', [ResetController::class, 'index']);
    // Route

    // end
});
