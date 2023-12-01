<?php

declare(strict_types=1);

use App\Bundles\Captcha\Controllers\Common\CaptchaController;
use Illuminate\Support\Facades\Route;

Route::prefix('common')->group(function () {
    // Route
    Route::get('captcha', [CaptchaController::class, 'index']);
    // end
});
