<?php

declare(strict_types=1);

namespace App\Gateways\User\Controllers;

use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class ProfileController extends BaseController
{
    #[OA\Get(path: '/profile', summary: '获取用户详细信息', tags: ['用户资料'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function index(): JsonResponse
    {
        return $this->success();
    }
}
