<?php

declare(strict_types=1);

namespace App\Api\Seller\Controllers;

use App\Api\Seller\Requests\Shipment\ShipmentBatchShipRequest;
use App\Api\Seller\Requests\Shipment\ShipmentStoreRequest;
use App\Api\Seller\Responses\Shipment\ShipmentListResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class ShipmentController extends BaseController
{
    #[OA\Get(path: '/shipments', summary: '获取发货单列表', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShipmentListResponse::class))]
    public function index(Request $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/shipments', summary: '创建发货单', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ShipmentStoreRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function store(ShipmentStoreRequest $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/shipments/batch', summary: '批量发货', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ShipmentBatchShipRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function batchShip(ShipmentBatchShipRequest $request): JsonResponse
    {
        return $this->success();
    }
}
