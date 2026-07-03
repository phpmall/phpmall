<?php

declare(strict_types=1);

namespace App\Api\Seller\Controllers;

use App\Api\Seller\Requests\Product\ProductBatchDeleteRequest;
use App\Api\Seller\Requests\Product\ProductBatchOffShelfRequest;
use App\Api\Seller\Requests\Product\ProductBatchOnShelfRequest;
use App\Api\Seller\Requests\Product\ProductIndexRequest;
use App\Api\Seller\Requests\Product\ProductStoreRequest;
use App\Api\Seller\Requests\Product\ProductUpdateRequest;
use App\Api\Seller\Responses\Product\ProductListResponse;
use App\Api\Seller\Responses\Product\ProductResponse;
use App\Exceptions\NotImplementedException;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class ProductController extends BaseController
{
    #[OA\Get(path: '/products', summary: '获取商品列表', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'status', description: '商品状态', in: 'query', required: false, schema: new OA\Schema(type: 'integer', nullable: true))]
    #[OA\Parameter(name: 'keyword', description: '搜索关键词', in: 'query', required: false, schema: new OA\Schema(type: 'string', nullable: true))]
    #[OA\Parameter(name: 'category_id', description: '分类ID', in: 'query', required: false, schema: new OA\Schema(type: 'integer', nullable: true))]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: false, schema: new OA\Schema(type: 'integer', example: 1))]
    #[OA\Parameter(name: 'per_page', description: '每页数量', in: 'query', required: false, schema: new OA\Schema(type: 'integer', example: 20))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ProductListResponse::class))]
    public function index(ProductIndexRequest $request): JsonResponse
    {
        throw new NotImplementedException('TODO: implement '.__CLASS__.'::'.__FUNCTION__);
    }

    #[OA\Post(path: '/products', summary: '创建商品', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ProductStoreRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ProductResponse::class))]
    public function store(ProductStoreRequest $request): JsonResponse
    {
        throw new NotImplementedException('TODO: implement '.__CLASS__.'::'.__FUNCTION__);
    }

    #[OA\Get(path: '/products/{id}', summary: '获取商品详情', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'id', description: '商品ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ProductResponse::class))]
    public function show(int $id): JsonResponse
    {
        throw new NotImplementedException('TODO: implement '.__CLASS__.'::'.__FUNCTION__);
    }

    #[OA\Put(path: '/products/{id}', summary: '更新商品', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'id', description: '商品ID', in: 'path', required: true)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ProductUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ProductResponse::class))]
    public function update(ProductUpdateRequest $request, int $id): JsonResponse
    {
        throw new NotImplementedException('TODO: implement '.__CLASS__.'::'.__FUNCTION__);
    }

    #[OA\Delete(path: '/products/{id}', summary: '删除商品', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'id', description: '商品ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK')]
    public function destroy(int $id): JsonResponse
    {
        throw new NotImplementedException('TODO: implement '.__CLASS__.'::'.__FUNCTION__);
    }

    #[OA\Post(path: '/products/{id}/on-shelf', summary: '商品上架', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'id', description: '商品ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK')]
    public function onShelf(int $id): JsonResponse
    {
        throw new NotImplementedException('TODO: implement '.__CLASS__.'::'.__FUNCTION__);
    }

    #[OA\Post(path: '/products/{id}/off-shelf', summary: '商品下架', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'id', description: '商品ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK')]
    public function offShelf(int $id): JsonResponse
    {
        throw new NotImplementedException('TODO: implement '.__CLASS__.'::'.__FUNCTION__);
    }

    #[OA\Post(path: '/products/batch/on-shelf', summary: '批量上架商品', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ProductBatchOnShelfRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function batchOnShelf(ProductBatchOnShelfRequest $request): JsonResponse
    {
        throw new NotImplementedException('TODO: implement '.__CLASS__.'::'.__FUNCTION__);
    }

    #[OA\Post(path: '/products/batch/off-shelf', summary: '批量下架商品', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ProductBatchOffShelfRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function batchOffShelf(ProductBatchOffShelfRequest $request): JsonResponse
    {
        throw new NotImplementedException('TODO: implement '.__CLASS__.'::'.__FUNCTION__);
    }

    #[OA\Post(path: '/products/batch/delete', summary: '批量删除商品', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ProductBatchDeleteRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function batchDelete(ProductBatchDeleteRequest $request): JsonResponse
    {
        throw new NotImplementedException('TODO: implement '.__CLASS__.'::'.__FUNCTION__);
    }
}
