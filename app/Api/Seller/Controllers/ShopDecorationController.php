<?php

declare(strict_types=1);

namespace App\Api\Seller\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class ShopDecorationController extends BaseController
{
    #[OA\Get(path: '/shop-decoration', summary: '获取店铺装修信息', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function index(Request $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Put(path: '/shop-decoration', summary: '更新店铺装修', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent)]
    #[OA\Response(response: 200, description: 'OK')]
    public function update(Request $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/shop-decoration/preview', summary: '预览店铺装修', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function preview(): JsonResponse
    {
        return $this->success();
    }
}
