<?php

declare(strict_types=1);

namespace App\Api\Portal\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class MarketingController extends BaseController
{
    #[OA\Get(path: '/marketing', summary: '营销活动列表', security: [[]], tags: ['商城平台'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function index(Request $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/marketing/current', summary: '当前营销活动', security: [[]], tags: ['商城平台'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function current(Request $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/marketing/upcoming', summary: '即将开始营销活动', security: [[]], tags: ['商城平台'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function upcoming(Request $request): JsonResponse
    {
        return $this->success();
    }
}
