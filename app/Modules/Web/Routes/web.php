<?php

declare(strict_types=1);

use App\Modules\Web\Middleware\Locale;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', Locale::class])->group(function () {
    Route::get('/', [App\Modules\Web\Controllers\IndexController::class, 'index']);
    require __DIR__.'/route.gen.php';
});
