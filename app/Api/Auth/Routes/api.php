<?php

declare(strict_types=1);

use App\Api\Auth\Controllers\AuthenticatedSessionController;
use App\Api\Auth\Controllers\EmailVerificationNotificationController;
use App\Api\Auth\Controllers\NewPasswordController;
use App\Api\Auth\Controllers\PasswordResetLinkController;
use App\Api\Auth\Controllers\RegisteredUserController;
use App\Api\Auth\Controllers\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::prefix('api/auth')->group(function () {
    Route::post('login/mobile', [\App\Api\Auth\Controllers\LoginController::class, 'mobile']);
    Route::post('signup/mobile', [\App\Api\Auth\Controllers\SignupController::class, 'mobile']);
    Route::post('forget/mobile', [\App\Api\Auth\Controllers\ForgetController::class, 'mobile']);
    Route::post('reset', [\App\Api\Auth\Controllers\ResetController::class, 'index']);

    Route::get('oauth/redirect', [\App\Api\Auth\Controllers\OAuthController::class, 'redirect']);
    Route::get('oauth/callback', [\App\Api\Auth\Controllers\OAuthController::class, 'callback']);
    Route::post('oauth/bind', [\App\Api\Auth\Controllers\OAuthController::class, 'bind']);
});


Route::post('/register', [RegisteredUserController::class, 'store'])
                ->middleware('guest')
                ->name('register');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
                ->middleware('guest')
                ->name('login');

Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
                ->middleware('guest')
                ->name('password.email');

Route::post('/reset-password', [NewPasswordController::class, 'store'])
                ->middleware('guest')
                ->name('password.store');

Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)
                ->middleware(['auth', 'signed', 'throttle:6,1'])
                ->name('verification.verify');

Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
                ->middleware(['auth', 'throttle:6,1'])
                ->name('verification.send');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
                ->middleware('auth')
                ->name('logout');
