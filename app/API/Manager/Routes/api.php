<?php

// ==========================================================================
// Code generated by gen:route CLI tool. DO NOT EDIT.
// ==========================================================================

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

// Route start
Route::prefix('api/manager')->middleware('api')->group(function () {
    // 运营首页
    Route::get('dashboard', [\App\API\Manager\Controllers\IndexController::class, 'dashboard']);
    // 获取管理菜单
    Route::get('menu', [\App\API\Manager\Controllers\IndexController::class, 'menu']);
    // 获取系统消息
    Route::get('message', [\App\API\Manager\Controllers\IndexController::class, 'message']);
    // 获取个人资料
    Route::get('profile', [\App\API\Manager\Controllers\IndexController::class, 'profile']);
    // 修改密码
    Route::post('password', [\App\API\Manager\Controllers\IndexController::class, 'password']);
    // 注销登录
    Route::post('logout', [\App\API\Manager\Controllers\IndexController::class, 'logout']);
    // 管理员接口
    Route::get('manager', [\App\Bundles\Manager\Controllers\Manager\ManagerController::class, 'index']);
    // 全部卖家
    Route::get('seller', [\App\Bundles\Seller\Controllers\Manager\SellerController::class, 'index']);
    // 卖家店铺
    Route::get('shop', [\App\Bundles\Shop\Controllers\Manager\ShopController::class, 'index']);
    // 卖家门店
    Route::get('store', [\App\Bundles\Store\Controllers\Manager\StoreController::class, 'index']);
    // 权限列表
    Route::get('permission', [\App\Bundles\System\Controllers\Manager\PermissionController::class, 'index']);
    // 角色列表
    Route::get('role', [\App\Bundles\System\Controllers\Manager\RoleController::class, 'index']);
    // 买家收货地址
    Route::get('userAddress', [\App\Bundles\User\Controllers\Manager\UserAddressController::class, 'index']);
    // 用户列表
    Route::get('user', [\App\Bundles\User\Controllers\Manager\UserController::class, 'index']);
    // 添加新用户
    Route::post('user/store', [\App\Bundles\User\Controllers\Manager\UserController::class, 'store']);
    // 获取详情
    Route::get('user/show', [\App\Bundles\User\Controllers\Manager\UserController::class, 'show']);
    // 更新用户详情
    Route::put('user/update', [\App\Bundles\User\Controllers\Manager\UserController::class, 'update']);
    // 删除用户
    Route::delete('user/destroy', [\App\Bundles\User\Controllers\Manager\UserController::class, 'destroy']);
});
// end
