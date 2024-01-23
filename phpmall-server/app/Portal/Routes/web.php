<?php

declare(strict_types=1);

use App\Portal\Http\Controllers\CatalogController;
use App\Portal\Http\Controllers\CategoryController;
use App\Portal\Http\Controllers\IndexController;
use App\Portal\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    // 商城首页
    Route::get('/', [IndexController::class, 'index'])->name('index');
    // 全部类目
    Route::get('catalog', [CatalogController::class, 'index'])->name('catalog');
    // 商品分类
    Route::get('category', [CategoryController::class, 'index'])->name('category');


    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware(['auth', 'verified'])->name('dashboard');

    Route::prefix('user')->middleware('auth')->name('user.')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });
});
