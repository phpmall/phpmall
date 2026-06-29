<?php

declare(strict_types=1);

namespace App\Api\Supplier\Controllers;

use App\Api\Supplier\Requests\SupplyProduct\StoreRequest;
use App\Api\Supplier\Requests\SupplyProduct\SupplyProductIndexRequest;
use App\Api\Supplier\Requests\SupplyProduct\UpdateRequest;
use App\Api\Supplier\Responses\SupplyProduct\SupplyProductListResponse;
use App\Api\Supplier\Responses\SupplyProduct\SupplyProductResponse;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class SupplyProductController extends BaseController
{
    #[OA\Get(path: '/supply-products', summary: '供应商品列表', security: [['bearerAuth' => []]], tags: ['供应商中心'])]
    #[OA\Parameter(name: 'status', description: '商品状态', in: 'query', schema: new OA\Schema(type: 'integer', nullable: true))]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', schema: new OA\Schema(type: 'integer', example: 1))]
    #[OA\Parameter(name: 'per_page', description: '每页数量', in: 'query', schema: new OA\Schema(type: 'integer', example: 20))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: SupplyProductListResponse::class))]
    public function index(SupplyProductIndexRequest $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/supply-products', security: [['bearerAuth' => []]], summary: '创建供应商品', tags: ['供应商中心'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: StoreRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: SupplyProductResponse::class))]
    public function store(StoreRequest $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/supply-products/{id}', summary: '供应商品详情', tags: ['供应商中心'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: SupplyProductResponse::class))]
    public function show(int $id): JsonResponse
    {
        return $this->success();
    }

    #[OA\Put(path: '/supply-products/{id}', security: [['bearerAuth' => []]], summary: '更新供应商品', tags: ['供应商中心'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: SupplyProductResponse::class))]
    public function update(UpdateRequest $request, int $id): JsonResponse
    {
        return $this->success();
    }

    #[OA\Delete(path: '/supply-products/{id}', security: [['bearerAuth' => []]], summary: '删除供应商品', tags: ['供应商中心'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK')]
    public function destroy(int $id): JsonResponse
    {
        return $this->success();
    }
}
