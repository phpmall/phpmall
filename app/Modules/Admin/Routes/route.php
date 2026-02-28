<?php

declare(strict_types=1);

use App\Modules\Admin\Middleware\Locale;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware(['web', Locale::class])->name('admin.')->group(function () {
    Route::redirect('/', '/admin/index.php');
    require __DIR__.'/admin.gen.php';
    require __DIR__.'/route.gen.php';
});
