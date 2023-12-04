<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

// Route
// 附件上传接口
Route::post('common/upload', [\App\Api\Common\Controllers\UploadController::class, 'index']);
// end
