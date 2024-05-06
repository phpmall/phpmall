<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

$homeDomain = str_replace('www.', 'home.', parse_url(config('app.url'), PHP_URL_HOST));

Route::middleware('web')->group(function () {
    Route::get('/', [\Juling\Portal\Controllers\IndexController::class, 'index'])->name('index');
    // 全部类目
    Route::get('catalog', [\Juling\Portal\Controllers\CatalogController::class, 'index'])->name('portal.catalog');
    // 商品分类
    Route::get('category', [\Juling\Portal\Controllers\CategoryController::class, 'index'])->name('portal.category');
    
    Route::domain($homeDomain)->group(function () {
        Route::get('profile/{id}', function (string $account, string $id) {
            // ...
        });
    });
});
