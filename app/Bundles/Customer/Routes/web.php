<?php

use App\Bundles\Customer\Controllers;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function () {
    Route::get('customer', [Controllers\Admin\CustomerController::class, 'index']);
});
