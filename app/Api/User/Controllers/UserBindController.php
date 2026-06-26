<?php

declare(strict_types=1);

namespace App\Api\User\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class UserBindController extends BaseController
{
    #[OA\Get(path: '/binds', summary: 'User Bind Controller index', tags: ['会员中心'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function index(Request $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/binds/bind', security: [['bearerAuth' => []]], summary: 'User Bind Controller bind', tags: ['会员中心'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function bind(Request $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/binds/unbind', security: [['bearerAuth' => []]], summary: 'User Bind Controller unbind', tags: ['会员中心'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function unbind(Request $request): JsonResponse
    {
        return $this->success();
    }
}
