<?php

declare(strict_types=1);

namespace App\API\Admin\Controllers;

use App\API\Admin\Middleware\Authorization;
use App\Http\Controllers\Controller;
use OpenApi\Attributes as OA;
use OpenApi\Attributes\Contact;

#[OA\Info(version: '1.0', description: '运营接口', title: 'API文档', contact: new Contact('API Develop Team'))]
#[OA\Server(url: '/apidoc/admin/', description: '开发环境')]
#[OA\SecurityScheme(securityScheme: 'bearerAuth', type: 'http', description: 'JWT 认证信息', name: 'Authorization', in: 'header', bearerFormat: 'JWT', scheme: 'bearer')]
abstract class BaseController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', Authorization::class]);
    }

    protected function getRoles(): array
    {
        return [];
    }
}
