<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::prefix('seller')->name('seller.')->group(function () {
    require __DIR__.'/route.gen.php';
});
