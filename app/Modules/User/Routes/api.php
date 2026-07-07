<?php

use App\Modules\User\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('api')->middleware(['api', 'jwt.auth'])->group(function () {
    Route::get('user/profile', [UserController::class, 'profile']);
    Route::put('user/profile', [UserController::class, 'updateProfile']);
});
