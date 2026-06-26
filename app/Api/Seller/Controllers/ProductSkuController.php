<?php

declare(strict_types=1);

namespace App\Api\Seller\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class ProductSkuController extends BaseController
{
    #[OA\Get(path: '/product-skus', summary: '获取商品SKU列表', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function index(Request $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/product-skus', summary: '创建商品SKU', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent)]
    #[OA\Response(response: 200, description: 'OK')]
    public function store(Request $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Put(path: '/product-skus/{id}', summary: '更新商品SKU', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'id', description: 'SKU ID', in: 'path', required: true)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent)]
    #[OA\Response(response: 200, description: 'OK')]
    public function update(Request $request, int $id): JsonResponse
    {
        return $this->success();
    }

    #[OA\Delete(path: '/product-skus/{id}', summary: '删除商品SKU', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'id', description: 'SKU ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK')]
    public function destroy(int $id): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/product-skus/batch', summary: '批量更新商品SKU', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent)]
    #[OA\Response(response: 200, description: 'OK')]
    public function batchUpdate(Request $request): JsonResponse
    {
        return $this->success();
    }
}
