<?php

use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::get('login', [\App\Gateways\Passport\Controllers\LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [\App\Gateways\Passport\Controllers\LoginController::class, 'login']);
    Route::post('login/mobile', [\App\Gateways\Passport\Controllers\LoginController::class, 'mobile']);

    Route::get('signup', [\App\Gateways\Passport\Controllers\SignupController::class, 'showSignupForm'])->name('signup');
    Route::post('signup/mobile', [\App\Gateways\Passport\Controllers\SignupController::class, 'mobile']);

    Route::get('password/forget', [\App\Gateways\Passport\Controllers\ForgetController::class, 'showForgetForm'])->name('password.forget');
    Route::post('password/forget/mobile', [\App\Gateways\Passport\Controllers\ForgetController::class, 'mobile']);

    Route::get('password/reset', [\App\Gateways\Passport\Controllers\ResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('password/reset', [\App\Gateways\Passport\Controllers\ResetController::class, 'reset']);

    Route::get('oauth2/redirect', [\App\Gateways\Passport\Controllers\OAuthController::class, 'redirect'])->name('oauth.redirect');
    Route::get('oauth2/callback', [\App\Gateways\Passport\Controllers\OAuthController::class, 'callback'])->name('oauth.callback');
    Route::get('oauth2/bind', [\App\Gateways\Passport\Controllers\OAuthController::class, 'bind'])->name('oauth.bind');

    Route::get('captcha', [\App\Gateways\Passport\Controllers\CaptchaController::class, 'index'])->name('captcha');
});
