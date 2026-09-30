<?php

declare(strict_types=1);

$routes = glob(app_path('Api/*/Routes/route.php'));
if (! empty($routes)) {
    foreach ($routes as $routeFile) {
        require $routeFile;
    }
}
