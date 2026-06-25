<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    if (file_exists(__DIR__.'/route.php')) {
        require __DIR__.'/route.php';
    }
});
