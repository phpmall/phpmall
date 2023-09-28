<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::prefix('portal')->group(function () {
    Route::prefix('admin')->group(function () {
        Route::get('/', [\App\Bundles\Portal\Controllers\Admin\LinkController::class, 'index']);
    });
});
