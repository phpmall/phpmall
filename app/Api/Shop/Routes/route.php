<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::prefix('shop')->group(function () {
    Route::get('products/{id}/skus', [ProductController::class, 'skus'])->name('products.{id}.skus');

    require __DIR__.'/route.gen.php';
});
