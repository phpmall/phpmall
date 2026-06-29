<?php

declare(strict_types=1);

namespace App\Api\Supplier\Controllers;

use App\Api\Supplier\Requests\PurchaseOrder\ConfirmRequest;
use App\Api\Supplier\Requests\PurchaseOrder\PurchaseOrderIndexRequest;
use App\Api\Supplier\Requests\PurchaseOrder\ShipRequest;
use App\Api\Supplier\Responses\PurchaseOrder\PurchaseOrderListResponse;
use App\Api\Supplier\Responses\PurchaseOrder\PurchaseOrderResponse;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class PurchaseOrderController extends BaseController
{
    #[OA\Get(path: '/purchase-orders', summary: '采购订单列表', security: [['bearerAuth' => []]], tags: ['供应商中心'])]
    #[OA\Parameter(name: 'status', description: '订单状态', in: 'query', schema: new OA\Schema(type: 'integer', nullable: true))]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', schema: new OA\Schema(type: 'integer', example: 1))]
    #[OA\Parameter(name: 'per_page', description: '每页数量', in: 'query', schema: new OA\Schema(type: 'integer', example: 20))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: PurchaseOrderListResponse::class))]
    public function index(PurchaseOrderIndexRequest $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/purchase-orders/{id}', summary: '采购订单详情', tags: ['供应商中心'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: PurchaseOrderResponse::class))]
    public function show(int $id): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/purchase-orders/{id}/ship', security: [['bearerAuth' => []]], summary: '采购订单发货', tags: ['供应商中心'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ShipRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: PurchaseOrderResponse::class))]
    public function ship(ShipRequest $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/purchase-orders/{id}/confirm', security: [['bearerAuth' => []]], summary: '采购订单确认', tags: ['供应商中心'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ConfirmRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: PurchaseOrderResponse::class))]
    public function confirm(ConfirmRequest $request): JsonResponse
    {
        return $this->success();
    }
}
