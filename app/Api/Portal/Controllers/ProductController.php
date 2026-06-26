<?php

declare(strict_types=1);

namespace App\Api\Portal\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class ProductController extends BaseController
{
    #[OA\Get(path: '/products', summary: '商品列表', security: [[]], tags: ['商城平台'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function index(Request $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/products/{id}', summary: '商品详情', security: [[]], tags: ['商城平台'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK')]
    public function show(int $id): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/products/recommend', summary: '推荐商品', security: [[]], tags: ['商城平台'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function recommend(): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/products/hot', summary: '热销商品', security: [[]], tags: ['商城平台'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function hot(): JsonResponse
    {
        return $this->success();
    }
}
