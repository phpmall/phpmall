<?php

declare(strict_types=1);

use App\Gateways\Portal\Controllers\IndexController;
use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::get('/', [IndexController::class, 'index']);
});
