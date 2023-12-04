<?php

use Illuminate\Support\Facades\Route;

// Route
// supplier
Route::get('supplier', [\App\Api\Supplier\Controllers\IndexController::class, 'index'])->name('supplier');
// end
