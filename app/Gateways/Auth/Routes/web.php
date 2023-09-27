<?php

use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('login/mobile', [\App\Gateways\Auth\Controllers\LoginController::class, 'mobile']);
    Route::post('signup/mobile', [\App\Gateways\Auth\Controllers\SignupController::class, 'mobile']);
    Route::post('forget/mobile', [\App\Gateways\Auth\Controllers\ForgetController::class, 'mobile']);
    Route::post('reset', [\App\Gateways\Auth\Controllers\ResetController::class, 'index']);

    Route::get('oauth/redirect', [\App\Gateways\Auth\Controllers\OAuthController::class, 'redirect']);
    Route::get('oauth/callback', [\App\Gateways\Auth\Controllers\OAuthController::class, 'callback']);
    Route::post('oauth/bind', [\App\Gateways\Auth\Controllers\OAuthController::class, 'bind']);

    Route::get('captcha', [\App\Gateways\Auth\Controllers\CaptchaController::class, 'index']);
});
