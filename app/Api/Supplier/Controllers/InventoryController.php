<?php

declare(strict_types=1);

namespace App\Api\Supplier\Controllers;

use App\Api\Supplier\Requests\Inventory\BatchUpdateRequest;
use App\Api\Supplier\Requests\Inventory\InventoryIndexRequest;
use App\Api\Supplier\Requests\Inventory\UpdateRequest;
use App\Api\Supplier\Responses\Inventory\InventoryListResponse;
use App\Api\Supplier\Responses\Inventory\InventoryResponse;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class InventoryController extends BaseController
{
    #[OA\Get(path: '/inventory', summary: '库存列表', security: [['bearerAuth' => []]], tags: ['供应商中心'])]
    #[OA\Parameter(name: 'status', description: '库存状态', in: 'query', schema: new OA\Schema(type: 'integer', nullable: true))]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', schema: new OA\Schema(type: 'integer', example: 1))]
    #[OA\Parameter(name: 'per_page', description: '每页数量', in: 'query', schema: new OA\Schema(type: 'integer', example: 20))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: InventoryListResponse::class))]
    public function index(InventoryIndexRequest $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Put(path: '/inventory/{id}', security: [['bearerAuth' => []]], summary: '更新库存', tags: ['供应商中心'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: InventoryResponse::class))]
    public function update(UpdateRequest $request, int $id): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/inventory/batch', security: [['bearerAuth' => []]], summary: '批量更新库存', tags: ['供应商中心'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: BatchUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function batchUpdate(BatchUpdateRequest $request): JsonResponse
    {
        return $this->success();
    }
}
