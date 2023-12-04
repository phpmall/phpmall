<?php

use Illuminate\Support\Facades\Route;

// Route
// 仪表台
Route::get('user', [\App\Api\User\Controllers\IndexController::class, 'index'])->name('user');
// end
