<?php

declare(strict_types=1);

namespace App\Api\Seller\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class DataController extends BaseController
{
    #[OA\Get(path: '/data/overview', summary: '数据概览', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function overview(): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/data/orders', summary: '订单数据', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function orders(): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/data/products', summary: '商品数据', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function products(): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/data/export', summary: '数据导出', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent)]
    #[OA\Response(response: 200, description: 'OK')]
    public function export(Request $request): JsonResponse
    {
        return $this->success();
    }
}
