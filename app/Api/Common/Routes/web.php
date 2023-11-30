<?php

declare(strict_types=1);

use App\Api\Common\Controllers\CaptchaController;
use App\Api\Common\Controllers\RegionController;
use App\Api\Common\Controllers\UploadController;
use Illuminate\Support\Facades\Route;

Route::prefix('common')->group(function () {
    Route::get('captcha', [CaptchaController::class, 'index']);
    Route::get('region', [RegionController::class, 'index']);
    Route::post('upload', [UploadController::class, 'index']);
});
