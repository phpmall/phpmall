<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::prefix('supplier')->group(function () {
    require __DIR__.'/route.gen.php';
});
