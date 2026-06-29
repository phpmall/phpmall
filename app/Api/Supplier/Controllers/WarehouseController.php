<?php

declare(strict_types=1);

namespace App\Api\Supplier\Controllers;

use App\Api\Supplier\Requests\Warehouse\StoreRequest;
use App\Api\Supplier\Requests\Warehouse\UpdateRequest;
use App\Api\Supplier\Requests\Warehouse\WarehouseIndexRequest;
use App\Api\Supplier\Responses\Warehouse\WarehouseListResponse;
use App\Api\Supplier\Responses\Warehouse\WarehouseResponse;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class WarehouseController extends BaseController
{
    #[OA\Get(path: '/warehouses', summary: '仓库列表', security: [['bearerAuth' => []]], tags: ['供应商中心'])]
    #[OA\Parameter(name: 'status', description: '仓库状态', in: 'query', schema: new OA\Schema(type: 'integer', nullable: true))]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', schema: new OA\Schema(type: 'integer', example: 1))]
    #[OA\Parameter(name: 'per_page', description: '每页数量', in: 'query', schema: new OA\Schema(type: 'integer', example: 20))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: WarehouseListResponse::class))]
    public function index(WarehouseIndexRequest $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/warehouses', security: [['bearerAuth' => []]], summary: '创建仓库', tags: ['供应商中心'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: StoreRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: WarehouseResponse::class))]
    public function store(StoreRequest $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/warehouses/{id}', summary: '仓库详情', security: [['bearerAuth' => []]], tags: ['供应商中心'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: WarehouseResponse::class))]
    public function show(int $id): JsonResponse
    {
        return $this->success();
    }

    #[OA\Put(path: '/warehouses/{id}', security: [['bearerAuth' => []]], summary: '更新仓库', tags: ['供应商中心'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: WarehouseResponse::class))]
    public function update(UpdateRequest $request, int $id): JsonResponse
    {
        return $this->success();
    }
}
