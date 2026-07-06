<?php

declare(strict_types=1);

use App\Api\Portal\Controllers\RegionController;
use Illuminate\Support\Facades\Route;

Route::prefix('portal')->group(function () {
    Route::get('products/{id}/reviews', [ProductController::class, 'reviews'])->name('products.{id}.reviews');
    Route::get('regions', [RegionController::class, 'index'])->name('regions');

    require __DIR__.'/route.gen.php';
});
