<?php

declare(strict_types=1);

namespace App\Gateways\Admin\Controllers;

use App\Foundation\Controllers\Controller;
use App\Gateways\Admin\Middleware\Authorization;
use OpenApi\Attributes as OA;
use OpenApi\Attributes\Contact;

#[OA\Info(version: '1.0', description: '提供运营API接口', title: 'API文档', contact: new Contact('API Develop Team'))]
#[OA\Server(url: 'http://127.0.0.1:8000', description: '开发环境')]
#[OA\SecurityScheme(securityScheme: 'bearerAuth', type: 'http', description: 'JWT 认证信息', name: 'Authorization', in: 'header', bearerFormat: 'JWT', scheme: 'bearer')]
abstract class BaseController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', Authorization::class]);
    }

    protected function getUserRoles(): array
    {
        return [];
    }
}
