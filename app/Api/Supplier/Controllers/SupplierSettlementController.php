<?php

declare(strict_types=1);

namespace App\Api\Supplier\Controllers;

use App\Api\Supplier\Requests\SupplierSettlement\SupplierSettlementIndexRequest;
use App\Api\Supplier\Responses\SupplierSettlement\SupplierSettlementListResponse;
use App\Api\Supplier\Responses\SupplierSettlement\SupplierSettlementResponse;
use App\Api\Supplier\Responses\SupplierSettlement\SupplierSettlementStatementResponse;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class SupplierSettlementController extends BaseController
{
    #[OA\Get(path: '/supplier-settlements', summary: '供应商结算列表', security: [['bearerAuth' => []]], tags: ['供应商中心'])]
    #[OA\Parameter(name: 'status', description: '结算状态', in: 'query', schema: new OA\Schema(type: 'integer', nullable: true))]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', schema: new OA\Schema(type: 'integer', example: 1))]
    #[OA\Parameter(name: 'per_page', description: '每页数量', in: 'query', schema: new OA\Schema(type: 'integer', example: 20))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: SupplierSettlementListResponse::class))]
    public function index(SupplierSettlementIndexRequest $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/supplier-settlements/{id}', summary: '供应商结算详情', tags: ['供应商中心'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: SupplierSettlementResponse::class))]
    public function show(int $id): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/supplier-settlements/{id}/statement', security: [['bearerAuth' => []]], summary: '供应商结算对账单', tags: ['供应商中心'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: SupplierSettlementStatementResponse::class))]
    public function statement(): JsonResponse
    {
        return $this->success();
    }
}
