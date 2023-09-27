<?php

use Illuminate\Support\Facades\Route;

Route::prefix('common')->group(function () {
    Route::get('captcha', [\App\Gateways\Common\Controllers\CaptchaController::class, 'index']);
});
