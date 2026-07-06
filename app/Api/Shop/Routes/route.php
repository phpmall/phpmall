<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::prefix('shop')->group(function () {
    Route::get('products/{id}/skus', [ProductController::class, 'skus'])->name('products.{id}.skus');
    Route::get('products/{id}/reviews', [ProductController::class, 'reviews'])->name('products.{id}.reviews');

    require __DIR__.'/route.gen.php';
});
