<?php

declare(strict_types=1);

namespace App\Api\Seller\Controllers;

use App\Api\Seller\Requests\ProductSku\ProductSkuBatchUpdateRequest;
use App\Api\Seller\Requests\ProductSku\ProductSkuStoreRequest;
use App\Api\Seller\Requests\ProductSku\ProductSkuUpdateRequest;
use App\Api\Seller\Responses\ProductSku\ProductSkuListResponse;
use App\Api\Seller\Responses\ProductSku\ProductSkuResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class ProductSkuController extends BaseController
{
    #[OA\Get(path: '/product-skus', summary: '获取商品SKU列表', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ProductSkuListResponse::class))]
    public function index(Request $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/product-skus', summary: '创建商品SKU', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ProductSkuStoreRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ProductSkuResponse::class))]
    public function store(ProductSkuStoreRequest $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Put(path: '/product-skus/{id}', summary: '更新商品SKU', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'id', description: 'SKU ID', in: 'path', required: true)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ProductSkuUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ProductSkuResponse::class))]
    public function update(ProductSkuUpdateRequest $request, int $id): JsonResponse
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
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ProductSkuBatchUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function batchUpdate(ProductSkuBatchUpdateRequest $request): JsonResponse
    {
        return $this->success();
    }
}
