<?php

declare(strict_types=1);

namespace App\Api\Portal\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class BannerController extends BaseController
{
    #[OA\Get(path: '/banners', summary: '轮播图列表', security: [[]], tags: ['商城平台'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function index(Request $request): JsonResponse
    {
        return $this->success();
    }
}
