<?php

declare(strict_types=1);

namespace App\Api\User\Controllers;

use App\Constants\Constant;
use App\Http\Controllers\Controller;
use App\Modules\User\Entities\UserEntity;
use OpenApi\Attributes as OA;
use OpenApi\Attributes\Contact;

#[OA\Info(version: Constant::Version, description: Constant::Release, title: '用户API文档', contact: new Contact('API Develop Team'))]
#[OA\Server(url: '/api/user/', description: '开发环境')]
#[OA\SecurityScheme(securityScheme: 'bearerAuth', type: 'http', description: 'JWT 认证信息', name: 'Authorization', in: 'header', bearerFormat: 'JWT', scheme: 'bearer')]
class BaseController extends Controller
{
    public function __construct()
    {
        $this->middleware(['jwt.auth']);
    }

    protected function queryWrapper(): array
    {
        return [];
    }

    protected function getUser(): UserEntity
    {
        return new UserEntity;
    }
}
