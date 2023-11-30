<?php

declare(strict_types=1);

use App\Bundles\Merchant\Controllers\Admin\IndexController;
use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::get('/common/region', [IndexController::class, 'index']);
});
