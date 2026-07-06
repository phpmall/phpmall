<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::prefix('portal')->group(function () {
    Route::get('products/{id}/reviews', [ProductController::class, 'reviews'])->name('products.{id}.reviews');

    require __DIR__.'/route.gen.php';
});
