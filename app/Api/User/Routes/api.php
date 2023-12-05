<?php

use Illuminate\Support\Facades\Route;

// Route
// 仪表台
Route::get('user', [\App\Api\User\Controllers\IndexController::class, 'index'])->name('user');
// 获取用户全部收货地址
Route::get('user/address', [\App\Bundles\User\Controllers\User\AddressController::class, 'index'])->name('user.address');
// 新增用户收货地址
Route::post('user/address/store', [\App\Bundles\User\Controllers\User\AddressController::class, 'store']);
// 查询用户收货地址
Route::get('user/address/show', [\App\Bundles\User\Controllers\User\AddressController::class, 'show'])->name('user.address.show');
// 更新用户收货地址
Route::put('user/address/update', [\App\Bundles\User\Controllers\User\AddressController::class, 'update']);
// 删除用户收货地址
Route::delete('user/address/destroy', [\App\Bundles\User\Controllers\User\AddressController::class, 'destroy']);
// 获取用户资料
Route::get('user/profile/show', [\App\Bundles\User\Controllers\User\ProfileController::class, 'show'])->name('user.profile.show');
// 更新用户资料
Route::put('user/profile/update', [\App\Bundles\User\Controllers\User\ProfileController::class, 'update']);
// end
