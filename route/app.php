<?php

use app\support\Request;
use think\facade\Route;

Route::get('swagger', function () {
    $openapi = \OpenApi\scan(app_path('controller'));
    return response($openapi->toJson());
});

Request::router();
