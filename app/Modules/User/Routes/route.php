<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::prefix('user')->name('user.')->group(function () {
    require __DIR__.'/route.gen.php';
});
