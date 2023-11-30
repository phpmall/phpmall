<?php

declare(strict_types=1);

use App\Gateways\Common\Controllers\CaptchaController;
use App\Gateways\Common\Controllers\RegionController;
use App\Gateways\Common\Controllers\UploadController;
use Illuminate\Support\Facades\Route;

Route::prefix('common')->group(function () {
    Route::get('captcha', [CaptchaController::class, 'index']);
    Route::get('region', [RegionController::class, 'index']);
    Route::post('upload', [UploadController::class, 'index']);
});
