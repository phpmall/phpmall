<?php

use Illuminate\Support\Facades\Route;

Route::prefix('api')->middleware('api')->group(function () {
    Route::post('login', [\App\Gateways\Passport\Controllers\LoginController::class, 'login']);
    Route::post('login/mobile', [\App\Gateways\Passport\Controllers\LoginController::class, 'mobile']);
    Route::post('signup/mobile', [\App\Gateways\Passport\Controllers\SignupController::class, 'mobile']);
    Route::post('password/forget/mobile', [\App\Gateways\Passport\Controllers\ForgetController::class, 'mobile']);
    Route::post('password/reset', [\App\Gateways\Passport\Controllers\ResetController::class, 'reset']);
    Route::get('oauth2/redirect', [\App\Gateways\Passport\Controllers\OAuthController::class, 'redirect']);
    Route::post('oauth2/callback', [\App\Gateways\Passport\Controllers\OAuthController::class, 'callback']);
    Route::post('oauth2/bind', [\App\Gateways\Passport\Controllers\OAuthController::class, 'bind']);

    Route::get('captcha', [\App\Gateways\Passport\Controllers\CaptchaController::class, 'index']);
});
