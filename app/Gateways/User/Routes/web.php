<?php

declare(strict_types=1);

use App\Gateways\User\Controllers\IndexController;
use Illuminate\Support\Facades\Route;

Route::prefix('user')->middleware('web')->group(function () {
    Route::get('/', [IndexController::class, 'index']);
});
