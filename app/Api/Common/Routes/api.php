<?php

declare(strict_types=1);

use App\Api\Common\Controllers\UploadController;
use App\Bundles\Captcha\Controllers\Common\CaptchaController;
use App\Bundles\Region\Controllers\Common\RegionController;
use Illuminate\Support\Facades\Route;

Route::prefix('common')->group(function () {
    Route::get('captcha', [CaptchaController::class, 'index']);
    Route::get('region', [RegionController::class, 'index']);
    Route::post('upload', [UploadController::class, 'index']);
    // Route

    // end
});
