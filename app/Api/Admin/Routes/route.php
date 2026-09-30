<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function () {
    require __DIR__.'/route.gen.php';
    $routes = glob(app_path('Domains/*/Routes/route.gen.php'));
    foreach ($routes as $route) {
        require $route;
    }
});
