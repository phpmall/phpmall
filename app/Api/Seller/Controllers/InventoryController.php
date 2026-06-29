<?php

declare(strict_types=1);

namespace App\Api\Seller\Controllers;

use App\Api\Seller\Requests\Inventory\InventoryBatchUpdateRequest;
use App\Api\Seller\Requests\Inventory\InventoryIndexRequest;
use App\Api\Seller\Requests\Inventory\InventoryUpdateRequest;
use App\Api\Seller\Responses\Inventory\InventoryListResponse;
use App\Api\Seller\Responses\Inventory\InventoryResponse;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class InventoryController extends BaseController
{
    #[OA\Get(path: '/inventory', summary: '获取库存列表', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'status', description: '库存状态', in: 'query', required: false, schema: new OA\Schema(type: 'integer', nullable: true))]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: false, schema: new OA\Schema(type: 'integer', example: 1))]
    #[OA\Parameter(name: 'per_page', description: '每页数量', in: 'query', required: false, schema: new OA\Schema(type: 'integer', example: 20))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: InventoryListResponse::class))]
    public function index(InventoryIndexRequest $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/inventory/{id}', summary: '获取库存详情', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'id', description: '库存ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: InventoryResponse::class))]
    public function show(int $id): JsonResponse
    {
        return $this->success();
    }

    #[OA\Put(path: '/inventory/{id}', summary: '更新库存', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'id', description: '库存ID', in: 'path', required: true)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: InventoryUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function update(InventoryUpdateRequest $request, int $id): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/inventory/batch', summary: '批量更新库存', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: InventoryBatchUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function batchUpdate(InventoryBatchUpdateRequest $request): JsonResponse
    {
        return $this->success();
    }
}
