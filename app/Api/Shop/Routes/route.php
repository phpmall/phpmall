<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::prefix('shop')->group(function () {
    require __DIR__.'/route.gen.php';
});
