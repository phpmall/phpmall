<?php

use Illuminate\Support\Facades\Route;

Route::prefix('user/admin')->group(function () {
    Route::get('/', [\App\Bundles\User\Controllers\Admin\UserController::class, 'index']);
});
