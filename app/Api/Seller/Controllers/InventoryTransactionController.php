<?php

declare(strict_types=1);

namespace App\Api\Seller\Controllers;

use App\Api\Seller\Requests\InventoryTransaction\InventoryTransactionIndexRequest;
use App\Api\Seller\Responses\InventoryTransaction\InventoryTransactionListResponse;
use App\Api\Seller\Responses\InventoryTransaction\InventoryTransactionResponse;
use App\Exceptions\NotImplementedException;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class InventoryTransactionController extends BaseController
{
    #[OA\Get(path: '/inventory-transactions', summary: '获取库存流水列表', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: false, schema: new OA\Schema(type: 'integer', example: 1))]
    #[OA\Parameter(name: 'per_page', description: '每页数量', in: 'query', required: false, schema: new OA\Schema(type: 'integer', example: 20))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: InventoryTransactionListResponse::class))]
    public function index(InventoryTransactionIndexRequest $request): JsonResponse
    {
        throw new NotImplementedException('TODO: implement '.__CLASS__.'::'.__FUNCTION__);
    }

    #[OA\Get(path: '/inventory-transactions/{id}', summary: '获取库存流水详情', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'id', description: '流水ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: InventoryTransactionResponse::class))]
    public function show(int $id): JsonResponse
    {
        throw new NotImplementedException('TODO: implement '.__CLASS__.'::'.__FUNCTION__);
    }
}
