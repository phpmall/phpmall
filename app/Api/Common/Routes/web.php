<?php

use Illuminate\Support\Facades\Route;

Route::prefix('common')->group(function () {
    Route::get('captcha', [\App\Api\Common\Controllers\CaptchaController::class, 'index']);
});
