<?php

declare(strict_types=1);

use App\Modules\User\Middleware\Locale;
use Illuminate\Support\Facades\Route;

Route::prefix('user')->middleware(['web', Locale::class])->name('user.')->group(function () {
    Route::get('/', [App\Modules\User\Controllers\IndexController::class, 'index']);
    require __DIR__.'/route.gen.php';
});
