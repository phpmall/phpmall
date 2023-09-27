<?php

declare(strict_types=1);

namespace App\Gateways\User\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class ProfileController extends BaseController
{
    #[OA\Get(path: '/user/profile', summary: '获取用户详细信息', tags: ['用户资料'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function index(): JsonResponse
    {
        return $this->success(['profile index']);
    }

    public function update(Request $request): JsonResponse
    {
        return $this->success(['profile updateHandle']);
    }
}
