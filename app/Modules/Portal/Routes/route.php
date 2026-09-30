<?php

declare(strict_types=1);

use App\Modules\Portal\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::name('portal.')->group(function () {
    require __DIR__.'/route.gen.php';
});

Route::get('/', [HomeController::class, 'index'])->name('home');
