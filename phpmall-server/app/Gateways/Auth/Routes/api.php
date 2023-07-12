<?php

use Illuminate\Support\Facades\Route;

Route::prefix(config('app.context_path').'auth')->group(function () {
    Route::post('login', [\App\Gateways\Auth\Controllers\LoginController::class, 'login']);
    Route::post('login/mobile', [\App\Gateways\Auth\Controllers\LoginController::class, 'mobile']);
    Route::post('signup/mobile', [\App\Gateways\Auth\Controllers\SignupController::class, 'mobile']);
    Route::post('password/forget/mobile', [\App\Gateways\Auth\Controllers\ForgetController::class, 'mobile']);
    Route::post('password/reset', [\App\Gateways\Auth\Controllers\ResetController::class, 'reset']);
    Route::get('oauth2/redirect', [\App\Gateways\Auth\Controllers\OAuthController::class, 'redirect']);
    Route::post('oauth2/callback', [\App\Gateways\Auth\Controllers\OAuthController::class, 'callback']);
    Route::post('oauth2/bind', [\App\Gateways\Auth\Controllers\OAuthController::class, 'bind']);

    Route::get('captcha', [\App\Gateways\Auth\Controllers\CaptchaController::class, 'index']);
});
