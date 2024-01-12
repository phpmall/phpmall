<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    // 会员登录
    Route::get('login', [\App\Portal\Http\Controllers\AuthController::class, 'login'])->name('login');
    Route::post('login', [\App\Api\Auth\Controllers\LoginController::class, 'index']);
    // 会员注册
    Route::get('signup', [\App\Portal\Http\Controllers\AuthController::class, 'signup'])->name('signup');
    // 忘记密码
    Route::get('forget', [\App\Portal\Http\Controllers\AuthController::class, 'forget'])->name('forget');
    // 重设密码
    Route::get('reset', [\App\Portal\Http\Controllers\AuthController::class, 'reset'])->name('reset');

    // 会员中心
    Route::prefix('user')->middleware('auth')->group(function () {
        // 会员首页
        Route::get('/', [\App\Portal\Http\Controllers\AuthController::class, 'index'])->name('user');
        // 注销登录
        Route::get('logout', [\App\Portal\Http\Controllers\AuthController::class, 'index'])->name('logout');
    });

    // 商城首页
    Route::get('/', [\App\Portal\Http\Controllers\IndexController::class, 'index'])->name('index');
    // 全部类目
    Route::get('catalog', [\App\Portal\Http\Controllers\CatalogController::class, 'index'])->name('catalog');
    // 商品分类
    Route::get('category', [\App\Portal\Http\Controllers\CategoryController::class, 'index'])->name('category');
});
