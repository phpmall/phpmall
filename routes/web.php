<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

// 自动加载各 UI 模块 Web 路由 (app/Modules/*/Routes/route.php)
$moduleRoutes = glob(app_path('Modules/*/Routes/route.php'));
if (! empty($moduleRoutes)) {
    foreach ($moduleRoutes as $routeFile) {
        require $routeFile;
    }
}

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';
