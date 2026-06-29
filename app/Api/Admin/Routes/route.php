<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function () {
    require __DIR__.'/route.gen.php';
    // 模块自动路由
    $routes = glob(app_path('Modules/*/Routes/route.gen.php'));
    foreach ($routes as $route) {
        require $route;
    }
});
