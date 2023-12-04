<?php

use Illuminate\Support\Facades\Route;

// Route
// 卖家
Route::get('seller', [\App\Api\Seller\Controllers\IndexController::class, 'index'])->name('seller');
// end

