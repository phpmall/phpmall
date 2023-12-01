<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::prefix('api/auth')->group(function () {
    Route::post('login/mobile', [\App\Api\Auth\Controllers\LoginController::class, 'mobile']);
    Route::post('signup/mobile', [\App\Api\Auth\Controllers\SignupController::class, 'mobile']);
    Route::post('forget/mobile', [\App\Api\Auth\Controllers\ForgetController::class, 'mobile']);
    Route::post('reset', [\App\Api\Auth\Controllers\ResetController::class, 'index']);
});
