<?php

declare(strict_types=1);

namespace App\Api\Seller\Controllers;

use App\Api\Seller\Requests\ProductAttribute\ProductAttributeStoreRequest;
use App\Api\Seller\Requests\ProductAttribute\ProductAttributeUpdateRequest;
use App\Api\Seller\Responses\ProductAttribute\ProductAttributeListResponse;
use App\Api\Seller\Responses\ProductAttribute\ProductAttributeResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class ProductAttributeController extends BaseController
{
    #[OA\Get(path: '/product-attributes', summary: '获取商品属性列表', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ProductAttributeListResponse::class))]
    public function index(Request $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/product-attributes', summary: '创建商品属性', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ProductAttributeStoreRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ProductAttributeResponse::class))]
    public function store(ProductAttributeStoreRequest $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Put(path: '/product-attributes/{id}', summary: '更新商品属性', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'id', description: '属性ID', in: 'path', required: true)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ProductAttributeUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ProductAttributeResponse::class))]
    public function update(ProductAttributeUpdateRequest $request, int $id): JsonResponse
    {
        return $this->success();
    }

    #[OA\Delete(path: '/product-attributes/{id}', summary: '删除商品属性', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'id', description: '属性ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK')]
    public function destroy(int $id): JsonResponse
    {
        return $this->success();
    }
}
