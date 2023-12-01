<?php

declare(strict_types=1);

use App\Api\Manager\Controllers\IndexController;
use App\Api\Manager\Controllers\AdminController;
use App\Api\Manager\Controllers\PermissionController;
use App\Api\Manager\Controllers\RoleController;
use Illuminate\Support\Facades\Route;

Route::prefix('manager')->middleware('web')->group(function () {
    Route::get('/', [IndexController::class, 'index']);
    Route::get('manager', [AdminController::class, 'index']);
    Route::get('role', [RoleController::class, 'index']);
    Route::get('permission', [PermissionController::class, 'index']);
});
