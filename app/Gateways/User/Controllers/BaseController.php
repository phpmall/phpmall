<?php

declare(strict_types=1);

namespace App\Gateways\User\Controllers;

use App\Bundles\Foundation\Controllers\Controller;
use App\Bundles\Foundation\Enums\GuardTypeEnum;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OA;
use OpenApi\Attributes\Contact;

#[OA\Info(version: '1.0', description: '提供买家API接口', title: 'API文档', contact: new Contact('API Develop Team'))]
#[OA\Server(url: 'http://127.0.0.1:8000', description: '开发环境')]
#[OA\Server(url: 'https://api.demo.phpmall.net', description: '测试环境')]
#[OA\SecurityScheme(securityScheme: 'bearerAuth', type: 'http', description: 'JWT 认证信息', name: 'Authorization', in: 'header', bearerFormat: 'JWT', scheme: 'bearer')]
abstract class BaseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }
}
