<?php

declare(strict_types=1);

use App\Gateways\Admin\Controllers\IndexController;
use App\Gateways\Admin\Controllers\ManagerController;
use App\Gateways\Admin\Controllers\PermissionController;
use App\Gateways\Admin\Controllers\RoleController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware('web')->group(function () {
    Route::get('/', [IndexController::class, 'index']);
    Route::get('manager', [ManagerController::class, 'index']);
    Route::get('role', [RoleController::class, 'index']);
    Route::get('permission', [PermissionController::class, 'index']);
});
