<?php

use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::get('login', [\App\Gateways\Auth\Controllers\LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [\App\Gateways\Auth\Controllers\LoginController::class, 'index']);
    Route::post('login/mobile', [\App\Gateways\Auth\Controllers\LoginController::class, 'mobile']);

    Route::post('signup/mobile', [\App\Gateways\Auth\Controllers\SignupController::class, 'mobile']);
    Route::post('forget/mobile', [\App\Gateways\Auth\Controllers\ForgetController::class, 'mobile']);
    Route::post('reset', [\App\Gateways\Auth\Controllers\ResetController::class, 'index']);
    Route::get('oauth/redirect', [\App\Gateways\Auth\Controllers\OAuthController::class, 'redirect']);
    Route::post('oauth/callback', [\App\Gateways\Auth\Controllers\OAuthController::class, 'callback']);
    Route::post('oauth/bind', [\App\Gateways\Auth\Controllers\OAuthController::class, 'bind']);
    Route::get('captcha', [\App\Gateways\Auth\Controllers\CaptchaController::class, 'index']);
});
