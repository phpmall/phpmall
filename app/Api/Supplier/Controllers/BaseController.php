<?php

declare(strict_types=1);

namespace App\Api\Supplier\Controllers;

use App\Api\Supplier\Middleware\CheckAuth;
use App\Constants\Constant;
use App\Http\Controllers\Controller;
use OpenApi\Attributes as OA;
use OpenApi\Attributes\Contact;

#[OA\Info(version: Constant::Version, description: Constant::Release, title: '供应商API文档', contact: new Contact('API Develop Team'))]
#[OA\Server(url: '/api/supplier/', description: '开发环境')]
#[OA\SecurityScheme(securityScheme: 'bearerAuth', type: 'http', description: 'JWT 认证信息', name: 'Authorization', in: 'header', bearerFormat: 'JWT', scheme: 'bearer')]
class BaseController extends Controller
{
    const string MerchantId = 'merchant_id';

    public function __construct()
    {
        $this->middleware(['auth:sanctum', CheckAuth::class]);
    }

    protected function queryWrapper(): array
    {
        return [
            self::MerchantId => 1,
        ];
    }
}
