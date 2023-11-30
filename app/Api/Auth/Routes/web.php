<?php

use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('login/mobile', [\App\Api\Auth\Controllers\LoginController::class, 'mobile']);
    Route::post('signup/mobile', [\App\Api\Auth\Controllers\SignupController::class, 'mobile']);
    Route::post('forget/mobile', [\App\Api\Auth\Controllers\ForgetController::class, 'mobile']);
    Route::post('reset', [\App\Api\Auth\Controllers\ResetController::class, 'index']);

    Route::get('oauth/redirect', [\App\Api\Auth\Controllers\OAuthController::class, 'redirect']);
    Route::get('oauth/callback', [\App\Api\Auth\Controllers\OAuthController::class, 'callback']);
    Route::post('oauth/bind', [\App\Api\Auth\Controllers\OAuthController::class, 'bind']);
});
