<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

// Route
// 发送手机短信验证码
Route::post('forget/mobile', [\App\Api\Auth\Controllers\ForgetController::class, 'mobile']);
// 通过手机号和密码登录
Route::post('auth/login/mobile', [\App\Api\Auth\Controllers\LoginController::class, 'mobile']);
// 通过手机号和密码登录
Route::post('login/mobile2', [\App\Api\Auth\Controllers\LoginController::class, 'mobile2']);
// 通过手机短信验证码登录
Route::post('login/mobile', [\App\Api\Auth\Controllers\LoginController::class, 'mobile3']);
// 通过验证码重新设置新密码
Route::post('reset', [\App\Api\Auth\Controllers\ResetController::class, 'reset']);
// 通过手机号码注册
Route::post('signup/mobile', [\App\Api\Auth\Controllers\SignupController::class, 'mobile']);
// end
