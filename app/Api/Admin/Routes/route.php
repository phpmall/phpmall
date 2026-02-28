<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function () {
    $routes = array_merge(
        [__DIR__.'/route.gen.php'],
        glob(app_path('Bundles/*/Routes/route.gen.php'))
    );
    foreach ($routes as $route) {
        require $route;
    }
});
