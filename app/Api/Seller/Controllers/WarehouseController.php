<?php

declare(strict_types=1);

namespace App\Api\Seller\Controllers;

use App\Api\Seller\Requests\Warehouse\WarehouseIndexRequest;
use App\Api\Seller\Requests\Warehouse\WarehouseStoreRequest;
use App\Api\Seller\Requests\Warehouse\WarehouseUpdateRequest;
use App\Api\Seller\Responses\Warehouse\WarehouseListResponse;
use App\Api\Seller\Responses\Warehouse\WarehouseResponse;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class WarehouseController extends BaseController
{
    #[OA\Get(path: '/warehouses', summary: '获取仓库列表', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'status', description: '状态', in: 'query', required: false, schema: new OA\Schema(type: 'integer', nullable: true))]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: false, schema: new OA\Schema(type: 'integer', example: 1))]
    #[OA\Parameter(name: 'per_page', description: '每页数量', in: 'query', required: false, schema: new OA\Schema(type: 'integer', example: 20))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: WarehouseListResponse::class))]
    public function index(WarehouseIndexRequest $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/warehouses', summary: '创建仓库', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: WarehouseStoreRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function store(WarehouseStoreRequest $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/warehouses/{id}', summary: '获取仓库详情', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'id', description: '仓库ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: WarehouseResponse::class))]
    public function show(int $id): JsonResponse
    {
        return $this->success();
    }

    #[OA\Put(path: '/warehouses/{id}', summary: '更新仓库', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'id', description: '仓库ID', in: 'path', required: true)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: WarehouseUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function update(WarehouseUpdateRequest $request, int $id): JsonResponse
    {
        return $this->success();
    }

    #[OA\Delete(path: '/warehouses/{id}', summary: '删除仓库', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'id', description: '仓库ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK')]
    public function destroy(int $id): JsonResponse
    {
        return $this->success();
    }
}
